<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\DriverDocument;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DriverDocumentController extends Controller
{
    // -------------------------------------------------------------------------
    // Driver-facing routes
    // -------------------------------------------------------------------------

    /**
     * Show the authenticated driver their 6 documents.
     */
    public function index(): View
    {
        /** @var User $user */
        $user = auth()->user();

        abort_unless($user->isDriver(), 403);

        $documents = DriverDocument::getOrCreateForDriver($user->id);

        $verifiedCount = $documents->filter(fn (DriverDocument $d) => $d->isVerified())->count();

        return view('documents.index', compact('documents', 'verifiedCount'));
    }

    /**
     * Handle file upload for a single document.
     * Accepts only the document that belongs to the authenticated driver.
     */
    public function upload(Request $request, DriverDocument $document): RedirectResponse|JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        abort_unless($document->user_id === $user->id, 403);

        // Only allow re-upload when not_uploaded or rejected; pending/verified
        // documents must be deleted first or cannot be replaced arbitrarily.
        abort_unless(
            in_array($document->status, [
                DriverDocument::STATUS_NOT_UPLOADED,
                DriverDocument::STATUS_REJECTED,
            ], true),
            422
        );

        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        // Delete any previously stored file before overwriting
        if ($document->file_path && Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        $uploadedFile = $request->file('file');
        $directory    = 'driver-documents/' . $user->id;
        $storedPath   = $uploadedFile->store($directory, 'local');

        $document->update([
            'file_path'         => $storedPath,
            'original_filename' => $uploadedFile->getClientOriginalName(),
            'status'            => DriverDocument::STATUS_PENDING,
            'rejection_reason'  => null,
            'expires_at'        => $request->input('expires_at') ?: null,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'id'                => $document->id,
                'status'            => $document->status,
                'original_filename' => $document->original_filename,
                'expires_at'        => $document->expires_at?->format('Y-m-d'),
            ]);
        }

        return redirect()->route('documents.index')
            ->with('success', translate('docs.status_pending'));
    }

    /**
     * Delete a driver's uploaded document and reset it to not_uploaded.
     * Only permitted when the document is pending or rejected.
     */
    public function destroy(DriverDocument $document): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        abort_unless($document->user_id === $user->id, 403);
        abort_unless(
            in_array($document->status, [
                DriverDocument::STATUS_PENDING,
                DriverDocument::STATUS_REJECTED,
            ], true),
            422
        );

        if ($document->file_path && Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        $document->update([
            'file_path'         => null,
            'original_filename' => null,
            'status'            => DriverDocument::STATUS_NOT_UPLOADED,
            'rejection_reason'  => null,
            'expires_at'        => null,
            'verified_at'       => null,
            'verified_by'       => null,
        ]);

        return redirect()->route('documents.index')
            ->with('success', translate('docs.status_not_uploaded'));
    }

    /**
     * Handle simultaneous upload of multiple documents in a single request.
     *
     * Accepts parallel arrays: files[], document_ids[], expires_at[].
     * Processes each entry independently — a validation failure on one file
     * does not abort the others. Returns a JSON results array so the client
     * can update each card reactively without a page reload.
     */
    public function batchUpload(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        abort_unless($user->isDriver(), 403);

        $files       = $request->file('files', []);
        $documentIds = $request->input('document_ids', []);
        $expiresAt   = $request->input('expires_at', []);

        $results = [];

        foreach ($documentIds as $index => $documentId) {
            $result = [
                'id'                => (int) $documentId,
                'status'            => null,
                'original_filename' => null,
                'expires_at'        => null,
                'error'             => null,
            ];

            // --- locate the document and verify ownership ---
            $document = DriverDocument::find($documentId);

            if (! $document) {
                $result['error'] = 'Документ не найден.';
                $results[]       = $result;
                continue;
            }

            if ($document->user_id !== $user->id) {
                $result['error'] = 'Нет доступа к документу.';
                $results[]       = $result;
                continue;
            }

            if (! in_array($document->status, [
                DriverDocument::STATUS_NOT_UPLOADED,
                DriverDocument::STATUS_REJECTED,
            ], true)) {
                $result['error'] = 'Документ уже отправлен на проверку или подтверждён.';
                $results[]       = $result;
                continue;
            }

            // --- validate the uploaded file for this slot ---
            $uploadedFile = $files[$index] ?? null;

            if (! $uploadedFile) {
                $result['error'] = 'Файл не приложен.';
                $results[]       = $result;
                continue;
            }

            // Manual mime + size validation so one bad file does not throw a
            // ValidationException that would abort the entire request.
            $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png'];
            $maxBytes     = 5 * 1024 * 1024; // 5 MB

            if (! $uploadedFile->isValid()) {
                $result['error'] = 'Файл повреждён или не загружен полностью.';
                $results[]       = $result;
                continue;
            }

            if (! in_array($uploadedFile->getMimeType(), $allowedMimes, true)) {
                $result['error'] = 'Допустимые форматы: PDF, JPG, PNG.';
                $results[]       = $result;
                continue;
            }

            if ($uploadedFile->getSize() > $maxBytes) {
                $result['error'] = 'Файл превышает 5 МБ.';
                $results[]       = $result;
                continue;
            }

            // --- store the file ---
            if ($document->file_path && Storage::disk('local')->exists($document->file_path)) {
                Storage::disk('local')->delete($document->file_path);
            }

            $directory  = 'driver-documents/' . $user->id;
            $storedPath = $uploadedFile->store($directory, 'local');

            $expiry = ($expiresAt[$index] ?? null) ?: null;

            $document->update([
                'file_path'         => $storedPath,
                'original_filename' => $uploadedFile->getClientOriginalName(),
                'status'            => DriverDocument::STATUS_PENDING,
                'rejection_reason'  => null,
                'expires_at'        => $expiry,
            ]);

            $result['status']            = DriverDocument::STATUS_PENDING;
            $result['original_filename'] = $uploadedFile->getClientOriginalName();
            $result['expires_at']        = $expiry;

            $results[] = $result;
        }

        return response()->json(['results' => $results]);
    }

    // -------------------------------------------------------------------------
    // Admin-facing routes
    // -------------------------------------------------------------------------

    /**
     * List all drivers with their document completion state.
     */
    public function adminIndex(): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $drivers = User::where('role', 'driver')
            ->with(['driverDocuments'])
            ->orderBy('name')
            ->paginate(30);

        $documentTypes = DriverDocument::DOCUMENT_TYPES;

        return view('admin.documents.index', compact('drivers', 'documentTypes'));
    }

    /**
     * Admin approves or rejects a single document.
     */
    public function adminVerify(Request $request, DriverDocument $document): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $request->validate([
            'action'           => ['required', 'in:approve,reject'],
            'rejection_reason' => ['required_if:action,reject', 'nullable', 'string', 'max:1000'],
        ]);

        /** @var User $admin */
        $admin = auth()->user();

        if ($request->input('action') === 'approve') {
            $document->update([
                'status'           => DriverDocument::STATUS_VERIFIED,
                'rejection_reason' => null,
                'verified_at'      => now(),
                'verified_by'      => $admin->id,
            ]);
        } else {
            $document->update([
                'status'           => DriverDocument::STATUS_REJECTED,
                'rejection_reason' => $request->input('rejection_reason'),
                'verified_at'      => null,
                'verified_by'      => $admin->id,
            ]);
        }

        return back()->with('success', translate('docs.status_' . $document->fresh()->status));
    }
}
