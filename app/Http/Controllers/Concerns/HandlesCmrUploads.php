<?php

declare(strict_types=1);

namespace App\Http\Controllers\Concerns;

use App\Models\Cargo;
use App\Models\CargoApplication;
use App\Models\User;
use App\Services\FirebaseService;
use App\Services\WhatsAppService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        // Notify the cargo owner (WE) via WhatsApp and FCM after the DB update succeeds.
        // Fire-and-forget: a transport failure must never break the upload flow.
        $this->notifyCmrUploaded($application);
    }

    /**
     * Send a WhatsApp notification to the cargo owner when a CMR is uploaded.
     *
     * Skips silently if the cargo has no owner or the owner has no phone.
     * Admin fallback omitted — `created_by` is always set on cargo rows
     * created through this application (nullable only in legacy seeds).
     */
    private function notifyCmrUploaded(CargoApplication $application): void
    {
        // Reload with eager-loaded relations to avoid extra queries.
        $application->loadMissing(['cargo.createdBy', 'driver']);

        $owner = $application->cargo->createdBy ?? null;

        if (! $owner) {
            return;
        }

        $cargo      = $application->cargo;
        $cargoLabel = "{$cargo->from_location} → {$cargo->to_location}";
        $driverName = $application->driver->name ?? '—';
        $link       = rtrim((string) config('app.url'), '/') . '/applications/' . $application->id;

        // WhatsApp requires a phone — skip silently if owner has none.
        if (! empty($owner->phone)) {
            $template = translate('notifications.cmr_uploaded');
            $message  = str_replace(
                ['{cargo_title}', '{driver_name}', '{link}'],
                [$cargoLabel,    $driverName,      $link],
                $template
            );

            try {
                app(WhatsAppService::class)->sendNotification($owner->phone, $message);
            } catch (\Throwable $e) {
                Log::warning('CMR upload WhatsApp notification failed', [
                    'application_id' => $application->id,
                    'owner_id'       => $owner->id ?? null,
                    'error'          => $e->getMessage(),
                ]);
            }
        }

        // FCM: fire regardless of phone — recipient just needs registered tokens.
        try {
            app(FirebaseService::class)->sendToUser(
                $owner,
                translate('push.cmr_uploaded.title'),
                str_replace(':cargo_route', $cargoLabel, translate('push.cmr_uploaded.body')),
                ['type' => 'cmr_uploaded', 'cargo_id' => (string) $application->cargo_id],
            );
        } catch (\Throwable $e) {
            Log::warning('FCM cmr_uploaded notification failed', [
                'application_id' => $application->id,
                'owner_id'       => $owner->id ?? null,
                'error'          => $e->getMessage(),
            ]);
        }
    }

    /**
     * Confirm a CMR and atomically deliver the cargo.
     *
     * Wraps in a transaction. The cargo row is locked for update so that
     * concurrent confirm attempts on the same application are serialised.
     * FCM is fired AFTER the transaction commits to avoid notifying on rollback.
     */
    protected function performCmrConfirm(CargoApplication $application, User $reviewer): void
    {
        // Capture the application ID before entering the transaction so we can
        // reload the fresh model for notification after the transaction commits.
        $applicationId = $application->id;

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

        // Transaction is committed — safe to send notifications now.
        $fresh = CargoApplication::with(['cargo', 'driver'])->find($applicationId);
        if ($fresh) {
            $this->notifyCmrConfirmed($fresh);
        }
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

        // Notify driver after the DB write.
        $application->loadMissing(['cargo', 'driver']);
        $this->notifyCmrRejected($application);
    }

    /**
     * Notify the driver (via FCM) when a reviewer confirms their CMR.
     * WhatsApp is not sent here — the confirmation is a positive event surfaced
     * through the push channel only; add a WA call here if product changes that.
     */
    private function notifyCmrConfirmed(CargoApplication $application): void
    {
        $driver = $application->driver ?? null;

        if (! $driver) {
            return;
        }

        $cargo      = $application->cargo;
        $cargoLabel = $cargo ? "{$cargo->from_location} → {$cargo->to_location}" : '—';

        try {
            app(FirebaseService::class)->sendToUser(
                $driver,
                translate('push.cmr_confirmed.title'),
                str_replace(':cargo_route', $cargoLabel, translate('push.cmr_confirmed.body')),
                ['type' => 'cmr_confirmed', 'cargo_id' => (string) $application->cargo_id],
            );
        } catch (\Throwable $e) {
            Log::warning('FCM cmr_confirmed notification failed', [
                'application_id' => $application->id,
                'driver_id'      => $driver->id,
                'error'          => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notify the driver (via FCM) when a reviewer rejects their CMR.
     */
    private function notifyCmrRejected(CargoApplication $application): void
    {
        $driver = $application->driver ?? null;

        if (! $driver) {
            return;
        }

        $cargo      = $application->cargo;
        $cargoLabel = $cargo ? "{$cargo->from_location} → {$cargo->to_location}" : '—';

        try {
            app(FirebaseService::class)->sendToUser(
                $driver,
                translate('push.cmr_rejected.title'),
                str_replace(':cargo_route', $cargoLabel, translate('push.cmr_rejected.body')),
                ['type' => 'cmr_rejected', 'cargo_id' => (string) $application->cargo_id],
            );
        } catch (\Throwable $e) {
            Log::warning('FCM cmr_rejected notification failed', [
                'application_id' => $application->id,
                'driver_id'      => $driver->id,
                'error'          => $e->getMessage(),
            ]);
        }
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
