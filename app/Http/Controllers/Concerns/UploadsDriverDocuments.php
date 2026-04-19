<?php

declare(strict_types=1);

namespace App\Http\Controllers\Concerns;

use App\Models\DriverDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Shared file-upload logic for DriverDocument slots.
 *
 * Used by both the web DriverDocumentController and the API DriverDocumentController
 * so that single-slot and batch-upload paths share identical storage semantics.
 */
trait UploadsDriverDocuments
{
    /**
     * Write an uploaded file to local storage and update the DriverDocument record.
     *
     * Deletes any previously stored file for the slot before writing the new one.
     * Sets status to STATUS_PENDING regardless of prior state — callers are
     * responsible for checking that the slot is in an uploadable state before
     * calling this method.
     *
     * @throws \RuntimeException if the file cannot be persisted to disk.
     */
    protected function uploadFileToSlot(
        DriverDocument $document,
        UploadedFile   $file,
        ?string        $expiresAt
    ): void {
        // Remove any previously stored file first.
        if ($document->file_path && Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        $directory  = 'driver-documents/' . $document->user_id;
        $storedPath = $file->store($directory, 'local');

        if ($storedPath === false) {
            throw new \RuntimeException('Не удалось сохранить файл на диск.');
        }

        $document->update([
            'file_path'         => $storedPath,
            'original_filename' => $file->getClientOriginalName(),
            'status'            => DriverDocument::STATUS_PENDING,
            'rejection_reason'  => null,
            'expires_at'        => $expiresAt,
        ]);
    }
}
