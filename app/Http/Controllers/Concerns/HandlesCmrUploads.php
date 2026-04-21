<?php

declare(strict_types=1);

namespace App\Http\Controllers\Concerns;

use App\Models\Cargo;
use App\Models\CargoApplication;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Shared CMR upload / confirm / reject / destroy logic.
 *
 * Used by both the web CargoApplicationController and the API
 * CargoApplicationController so that all state transitions are
 * identical regardless of the transport layer.
 *
 * Callers are responsible for:
 *  - Authorization checks before calling any method.
 *  - Validating request inputs before passing them here.
 */
trait HandlesCmrUploads
{
    /**
     * Persist the uploaded CMR file and flip the application to pending_review.
     *
     * Replaces any previously uploaded file (re-upload after rejection).
     *
     * @throws \RuntimeException if the file cannot be stored.
     */
    protected function performCmrUpload(
        CargoApplication $application,
        UploadedFile     $file
    ): void {
        // Remove the old file when the driver re-uploads after rejection.
        if ($application->cmr_file_path
            && Storage::disk('local')->exists($application->cmr_file_path)
        ) {
            Storage::disk('local')->delete($application->cmr_file_path);
        }

        $directory  = 'cmr/' . $application->id;
        $hash       = hash('sha256', $file->getClientOriginalName() . microtime());
        $extension  = $file->getClientOriginalExtension();
        $storedPath = $file->storeAs($directory, "{$hash}.{$extension}", 'local');

        if ($storedPath === false) {
            throw new \RuntimeException('Не удалось сохранить CMR-файл на диск.');
        }

        $application->update([
            'cmr_status'            => CargoApplication::CMR_STATUS_PENDING_REVIEW,
            'cmr_file_path'         => $storedPath,
            'cmr_original_filename' => $file->getClientOriginalName(),
            'cmr_uploaded_at'       => now(),
            // Clear any prior rejection data so the review slate is clean.
            'cmr_rejection_reason'  => null,
            'cmr_rejected_at'       => null,
        ]);
    }

    /**
     * Confirm a CMR and atomically deliver the cargo.
     *
     * Wraps in a transaction. The cargo row is locked for update so that
     * concurrent confirm attempts on the same application are serialised.
     */
    protected function performCmrConfirm(CargoApplication $application, User $reviewer): void
    {
        DB::transaction(function () use ($application, $reviewer): void {
            // Lock the application row to prevent race conditions.
            $application = CargoApplication::where('id', $application->id)
                ->lockForUpdate()
                ->firstOrFail();

            $application->update([
                'cmr_status'       => CargoApplication::CMR_STATUS_CONFIRMED,
                'cmr_confirmed_by' => $reviewer->id,
                'cmr_confirmed_at' => now(),
                'status'           => CargoApplication::STATUS_DELIVERED,
            ]);

            $application->cargo()->update([
                'status' => Cargo::STATUS_DELIVERED,
            ]);
        });
    }

    /**
     * Reject a CMR with a mandatory reason.
     *
     * Does NOT flip cargo.status — the cargo stays in_progress until
     * the driver re-uploads and a reviewer confirms.
     */
    protected function performCmrReject(
        CargoApplication $application,
        string           $reason
    ): void {
        $application->update([
            'cmr_status'           => CargoApplication::CMR_STATUS_REJECTED,
            'cmr_rejection_reason' => $reason,
            'cmr_rejected_at'      => now(),
        ]);
    }

    /**
     * Delete the uploaded CMR file and reset all CMR columns to their
     * initial state.  Only callable before confirmation.
     */
    protected function performCmrDestroy(CargoApplication $application): void
    {
        if ($application->cmr_file_path
            && Storage::disk('local')->exists($application->cmr_file_path)
        ) {
            Storage::disk('local')->delete($application->cmr_file_path);
        }

        $application->update([
            'cmr_status'            => CargoApplication::CMR_STATUS_NOT_UPLOADED,
            'cmr_file_path'         => null,
            'cmr_original_filename' => null,
            'cmr_uploaded_at'       => null,
            'cmr_rejection_reason'  => null,
            'cmr_rejected_at'       => null,
        ]);
    }
}
