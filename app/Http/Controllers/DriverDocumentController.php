<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\UploadsDriverDocuments;
use App\Models\DriverDocument;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DriverDocumentController extends Controller
{
    use UploadsDriverDocuments;
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
     * Handle file upload for a single document slot.
     * Accepts only documents that belong to the authenticated driver.
     */
    public function upload(Request $request, DriverDocument $document): RedirectResponse
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

        $this->uploadFileToSlot(
            $document,
            $request->file('file'),
            $request->input('expires_at') ?: null
        );

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
     * Handle simultaneous upload of multiple document slots in one request.
     *
     * Body: files[{documentId}] = file, expires_at[{documentId}] = date (optional).
     * Every documentId must belong to the authenticated driver; any mismatch aborts
     * the whole request (403) before any file is written — no partial writes.
     * Slots whose status is pending or verified are silently skipped (they are
     * locked), so the driver does not get a confusing error if the form rendered
     * stale state.
     */
    public function batchUpload(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        abort_unless($user->isDriver(), 403);

        $request->validate([
            'files'      => ['required', 'array', 'min:1'],
            'files.*'    => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'expires_at' => ['sometimes', 'array'],
            'expires_at.*' => ['sometimes', 'nullable', 'date'],
        ]);

        $uploadedFiles = $request->file('files', []);

        if (empty($uploadedFiles)) {
            return redirect()->route('documents.index')
                ->with('flash.errors', [translate('docs.batch_no_files')]);
        }

        // Authorization pass — verify ALL document IDs belong to this driver
        // before touching the filesystem. Fail fast with 403 on any mismatch.
        $documentIds = array_keys($uploadedFiles);
        $documents   = DriverDocument::whereIn('id', $documentIds)->get()->keyBy('id');

        foreach ($documentIds as $docId) {
            $doc = $documents->get($docId);
            abort_unless($doc !== null && $doc->user_id === $user->id, 403);
        }

        $slotErrors  = [];
        $slotNames   = [];
        $expiresAt   = $request->input('expires_at', []);

        foreach ($uploadedFiles as $docId => $uploadedFile) {
            /** @var DriverDocument $doc */
            $doc = $documents->get($docId);

            // Skip locked slots silently — pending/verified cannot be overwritten.
            if (! in_array($doc->status, [
                DriverDocument::STATUS_NOT_UPLOADED,
                DriverDocument::STATUS_REJECTED,
            ], true)) {
                continue;
            }

            try {
                $this->uploadFileToSlot(
                    $doc,
                    $uploadedFile,
                    ($expiresAt[$docId] ?? null) ?: null
                );
                $slotNames[] = $doc->document_type_label;
            } catch (\Throwable $e) {
                $slotErrors[$docId] = $e->getMessage();
            }
        }

        if (! empty($slotErrors) && empty($slotNames)) {
            // Every slot failed.
            return redirect()->route('documents.index')
                ->with('flash.errors', $slotErrors);
        }

        if (! empty($slotErrors)) {
            // Partial failure — some slots uploaded, some did not.
            return redirect()->route('documents.index')
                ->with('flash.uploaded', $slotNames)
                ->with('flash.errors', $slotErrors)
                ->with('warning', translate('docs.batch_partial_error'));
        }

        return redirect()->route('documents.index')
            ->with('flash.uploaded', $slotNames)
            ->with('success', translate('docs.batch_success'));
    }

    // -------------------------------------------------------------------------
    // Admin-facing routes
    // -------------------------------------------------------------------------

    /**
     * List all drivers with their document completion state.
     * Supports ?filter= (all|pending|rejected|verified) and ?search= (name/email).
     */
    public function adminIndex(Request $request): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $filter = $request->input('filter', 'pending');
        $search = trim((string) $request->input('search', ''));

        $query = User::where('role', 'driver')
            ->with(['driverDocuments.verifiedBy'])
            ->orderBy('name');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter by document status: only keep drivers that have at least one
        // document in the requested state (or show all when filter === 'all').
        if ($filter !== 'all') {
            $query->whereHas('driverDocuments', fn ($q) => $q->where('status', $filter));
        }

        $drivers       = $query->paginate(30)->withQueryString();
        $documentTypes = DriverDocument::DOCUMENT_TYPES;

        return view('admin.documents.index', compact('drivers', 'documentTypes', 'filter', 'search'));
    }

    /**
     * Stream a driver's own uploaded file so they can preview it.
     * Only the owning driver may access their documents this way.
     */
    public function driverFile(DriverDocument $document): StreamedResponse
    {
        /** @var User $user */
        $user = auth()->user();

        abort_unless($document->user_id === $user->id, 403);
        abort_unless($document->file_path !== null, 404);
        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        return Storage::disk('local')->response($document->file_path, $document->original_filename);
    }

    /**
     * Stream a driver's uploaded file for admin review.
     * Authorized to admins only; any admin may view any document.
     */
    public function adminFile(DriverDocument $document): StreamedResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        abort_unless($document->file_path !== null, 404);
        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        return Storage::disk('local')->response($document->file_path, $document->original_filename);
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
