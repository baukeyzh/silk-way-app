<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Concerns\UploadsDriverDocuments;
use App\Http\Controllers\Controller;
use App\Http\Resources\DriverDocumentResource;
use App\Models\DriverDocument;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DriverDocumentController extends Controller
{
    use UploadsDriverDocuments;
    // -------------------------------------------------------------------------
    // Driver endpoints
    // -------------------------------------------------------------------------

    /**
     * @OA\Get(
     *     path="/documents",
     *     tags={"Documents"},
     *     summary="Get the authenticated driver's document slots",
     *     description="Returns all 6 document slots for the authenticated driver, creating placeholder rows for any that do not yet exist. Each slot includes stable type codes, localized labels, upload constraints, and current file state. Locale is resolved from Accept-Language header (ru/kk/zh) or ?lang= query param.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="lang", in="query", required=false, description="Override locale: ru | kz | cn", @OA\Schema(type="string", enum={"ru","kz","cn"})),
     *     @OA\Response(
     *         response=200,
     *         description="List of driver document slots",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/DriverDocument"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — driver role required")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $documents = DriverDocument::getOrCreateForDriver($request->user()->id);

        return response()->json([
            'data' => DriverDocumentResource::collection($documents)->resolve(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/documents/types",
     *     tags={"Documents"},
     *     summary="Catalog of all document type definitions",
     *     description="Returns the static catalog of all document slot types with localized labels, descriptions, and upload constraints. Useful for onboarding screens that need to show the required document list before the user registers. Requires driver authentication for consistency with the rest of the documents surface.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="lang", in="query", required=false, description="Override locale: ru | kz | cn", @OA\Schema(type="string", enum={"ru","kz","cn"})),
     *     @OA\Response(
     *         response=200,
     *         description="Document type catalog",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/DocumentTypeDefinition"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — driver role required")
     * )
     *
     * @OA\Schema(
     *     schema="DocumentTypeDefinition",
     *     type="object",
     *     description="Static definition of a document slot type. Not tied to a specific driver.",
     *     required={"type","label","description","required","accepted_mime_types","max_file_size_bytes"},
     *     @OA\Property(property="type",                type="string",  example="driver_license",    description="Stable machine-readable code. Use this in POST /documents/by-type/{type}/upload."),
     *     @OA\Property(property="label",               type="string",  example="Водительское удостоверение", description="Localized name."),
     *     @OA\Property(property="description",         type="string",  example="Лицевая и обратная стороны удостоверения", description="Localized upload hint."),
     *     @OA\Property(property="required",            type="boolean", example=true),
     *     @OA\Property(property="accepted_mime_types", type="array",   @OA\Items(type="string"), example={"image/jpeg","image/png","application/pdf"}),
     *     @OA\Property(property="max_file_size_bytes", type="integer", example=5242880)
     * )
     */
    public function types(): JsonResponse
    {
        return response()->json([
            'data' => DriverDocument::typeCatalog(),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/documents/by-type/{type}/upload",
     *     tags={"Documents"},
     *     summary="Upload a file to a document slot by type code",
     *     description="Semantic alternative to POST /documents/{id}/upload. The driver does not need to first GET /documents to discover the numeric ID — they can upload directly using the stable type code (e.g. 'driver_license'). The server resolves the driver's slot by (authenticated user, type code). Behaves identically to the ID-based upload endpoint.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="type", in="path", required=true, description="Stable document type code", @OA\Schema(type="string", enum={"driver_license","vehicle_passport","trailer_passport","category_cert","green_card","insurance"})),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file"},
     *                 @OA\Property(property="file",       type="string", format="binary", description="PDF, JPG, JPEG or PNG, max 5 MB"),
     *                 @OA\Property(property="expires_at", type="string", format="date",   description="Optional expiry date (YYYY-MM-DD)", example="2027-08-15")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Document updated, status set to pending",
     *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/DriverDocument"))
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Unknown type code"),
     *     @OA\Response(response=422, description="Validation error or slot status does not allow upload")
     * )
     */
    public function uploadByType(Request $request, string $type): JsonResponse
    {
        // Reject unknown type codes before hitting the DB.
        abort_unless(
            in_array($type, DriverDocument::DOCUMENT_TYPES, true),
            404,
            'Unknown document type: ' . $type
        );

        // getOrCreateForDriver ensures the slot exists; then we scope to this type.
        $document = DriverDocument::where('user_id', $request->user()->id)
            ->where('document_type', $type)
            ->first();

        // Slot may not exist yet if getOrCreateForDriver was never called — create it.
        if ($document === null) {
            $document = DriverDocument::create([
                'user_id'       => $request->user()->id,
                'document_type' => $type,
                'status'        => DriverDocument::STATUS_NOT_UPLOADED,
            ]);
        }

        abort_unless(
            in_array($document->status, [
                DriverDocument::STATUS_NOT_UPLOADED,
                DriverDocument::STATUS_REJECTED,
            ], true),
            422,
            'Document cannot be replaced in its current status. Delete it first.'
        );

        $request->validate([
            'file'       => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'expires_at' => ['nullable', 'date', 'after:today'],
        ]);

        $this->uploadFileToSlot(
            $document,
            $request->file('file'),
            $request->input('expires_at') ?: null
        );

        return response()->json(['data' => DriverDocumentResource::make($document->fresh())->resolve()]);
    }

    /**
     * @OA\Post(
     *     path="/documents/{document}/upload",
     *     tags={"Documents"},
     *     summary="Upload a file for a driver document",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="document", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file"},
     *                 @OA\Property(property="file",       type="string", format="binary", description="PDF, JPG, JPEG or PNG, max 5 MB"),
     *                 @OA\Property(property="expires_at", type="string", format="date",   description="Optional expiry date (YYYY-MM-DD)", example="2027-08-15")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Document updated, status set to pending",
     *         @OA\JsonContent(@OA\Property(property="data", type="object"))
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validation error or document status does not allow upload")
     * )
     */
    public function upload(Request $request, DriverDocument $document): JsonResponse
    {
        abort_unless($document->user_id === $request->user()->id, 403);

        // Pending/verified documents must be deleted before re-uploading.
        abort_unless(
            in_array($document->status, [
                DriverDocument::STATUS_NOT_UPLOADED,
                DriverDocument::STATUS_REJECTED,
            ], true),
            422,
            'Document cannot be replaced in its current status. Delete it first.'
        );

        $request->validate([
            'file'       => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'expires_at' => ['nullable', 'date', 'after:today'],
        ]);

        // Remove any previously stored file before overwriting.
        if ($document->file_path && Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        $uploadedFile = $request->file('file');
        $directory    = 'driver-documents/' . $request->user()->id;
        $storedPath   = $uploadedFile->store($directory, 'local');

        $document->update([
            'file_path'         => $storedPath,
            'original_filename' => $uploadedFile->getClientOriginalName(),
            'status'            => DriverDocument::STATUS_PENDING,
            'rejection_reason'  => null,
            'expires_at'        => $request->input('expires_at') ?: null,
        ]);

        return response()->json(['data' => DriverDocumentResource::make($document->fresh())->resolve()]);
    }

    /**
     * @OA\Post(
     *     path="/documents/batch",
     *     tags={"Documents"},
     *     summary="Пакетная загрузка документов водителя",
     *     description="Загружает файлы сразу для нескольких слотов документов в одном запросе. Каждый ключ в `files` и `expires_at` — это ID документа. Все ID должны принадлежать авторизованному водителю (иначе 403, без побочных эффектов). Слоты со статусом `verified` или `pending` молча пропускаются — они не завершают запрос ошибкой. При частичных ошибках возвращается 207 с полями `uploaded` и `errors`.",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"files"},
     *                 @OA\Property(
     *                     property="files",
     *                     type="object",
     *                     description="Ключ — ID документа (integer), значение — файл (PDF, JPG, JPEG, PNG, max 5 MB)",
     *                     @OA\AdditionalProperties(type="string", format="binary")
     *                 ),
     *                 @OA\Property(
     *                     property="expires_at",
     *                     type="object",
     *                     description="Ключ — ID документа, значение — дата истечения (YYYY-MM-DD), необязательно",
     *                     @OA\AdditionalProperties(type="string", format="date", example="2027-08-15")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Все файлы успешно загружены",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Документы успешно загружены."),
     *             @OA\Property(property="uploaded", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="document_id", type="integer", example=42),
     *                     @OA\Property(property="filename",    type="string",  example="passport.pdf"),
     *                     @OA\Property(property="status",      type="string",  example="pending")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=207,
     *         description="Частичный успех — часть файлов загружена, часть не удалась",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Часть документов загружена с ошибками."),
     *             @OA\Property(property="uploaded", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="document_id", type="integer", example=42),
     *                     @OA\Property(property="filename",    type="string",  example="passport.pdf"),
     *                     @OA\Property(property="status",      type="string",  example="pending")
     *                 )
     *             ),
     *             @OA\Property(property="errors", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="document_id", type="integer", example=47),
     *                     @OA\Property(property="reason",      type="string",  example="Не удалось сохранить файл на диск.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Не авторизован",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Unauthenticated."))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Запрещено — один или несколько document_id не принадлежат водителю",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Forbidden."))
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации — файлы не переданы или не прошли проверку",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The files field is required."),
     *             @OA\Property(property="errors",  type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Слишком много запросов",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Too Many Attempts."))
     *     )
     * )
     */
    public function batchUpload(Request $request): JsonResponse
    {
        $request->validate([
            'files'        => ['required', 'array', 'min:1'],
            'files.*'      => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'expires_at'   => ['sometimes', 'array'],
            'expires_at.*' => ['sometimes', 'nullable', 'date'],
        ]);

        $uploadedFiles = $request->file('files', []);

        // Authorization pre-flight: every document ID must belong to the
        // authenticated driver. Fail with 403 before touching the filesystem
        // — no partial writes on ownership mismatch.
        $documentIds = array_keys($uploadedFiles);
        $documents   = DriverDocument::whereIn('id', $documentIds)->get()->keyBy('id');

        foreach ($documentIds as $docId) {
            $doc = $documents->get($docId);
            abort_unless($doc !== null && $doc->user_id === $request->user()->id, 403);
        }

        $expiresAt    = $request->input('expires_at', []);
        $uploadedRows = [];
        $errorRows    = [];

        foreach ($uploadedFiles as $docId => $file) {
            /** @var DriverDocument $doc */
            $doc = $documents->get($docId);

            // Silently skip locked slots — pending/verified cannot be overwritten.
            if (! in_array($doc->status, [
                DriverDocument::STATUS_NOT_UPLOADED,
                DriverDocument::STATUS_REJECTED,
            ], true)) {
                continue;
            }

            try {
                $this->uploadFileToSlot(
                    $doc,
                    $file,
                    ($expiresAt[$docId] ?? null) ?: null
                );

                $uploadedRows[] = [
                    'document_id' => $doc->id,
                    'filename'    => $file->getClientOriginalName(),
                    'status'      => DriverDocument::STATUS_PENDING,
                ];
            } catch (\Throwable $e) {
                $errorRows[] = [
                    'document_id' => $doc->id,
                    'reason'      => $e->getMessage(),
                ];
            }
        }

        // All slots failed (or were all locked/skipped with nothing uploaded).
        if (empty($uploadedRows) && ! empty($errorRows)) {
            return response()->json([
                'message' => 'Не удалось загрузить ни один документ.',
                'errors'  => collect($errorRows)->mapWithKeys(
                    fn (array $e) => ["files.{$e['document_id']}" => [$e['reason']]]
                )->all(),
            ], 422);
        }

        // Partial success — at least one slot succeeded, at least one failed.
        if (! empty($errorRows)) {
            return response()->json([
                'message'  => 'Часть документов загружена с ошибками.',
                'uploaded' => $uploadedRows,
                'errors'   => $errorRows,
            ], 207);
        }

        return response()->json([
            'message'  => 'Документы успешно загружены.',
            'uploaded' => $uploadedRows,
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/documents/{document}",
     *     tags={"Documents"},
     *     summary="Delete a driver's uploaded document and reset to not_uploaded",
     *     description="Permitted only when the document status is pending or rejected.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="document", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Document deleted",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Document deleted."))
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Document status does not allow deletion")
     * )
     */
    public function destroy(Request $request, DriverDocument $document): JsonResponse
    {
        abort_unless($document->user_id === $request->user()->id, 403);

        abort_unless(
            in_array($document->status, [
                DriverDocument::STATUS_PENDING,
                DriverDocument::STATUS_REJECTED,
            ], true),
            422,
            'Only pending or rejected documents can be deleted.'
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

        return response()->json(['message' => 'Document deleted.']);
    }

    // -------------------------------------------------------------------------
    // Admin endpoints
    // -------------------------------------------------------------------------

    /**
     * @OA\Get(
     *     path="/admin/documents",
     *     tags={"Documents"},
     *     summary="Admin — paginated list of drivers with document statuses",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", default=1)),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated driver list with document status map",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id",             type="integer", example=5),
     *                     @OA\Property(property="name",           type="string",  example="Ivan Ivanov"),
     *                     @OA\Property(property="email",          type="string",  example="ivan@example.com"),
     *                     @OA\Property(property="documents",      type="object",
     *                         @OA\Property(property="driver_license",   type="string", example="verified"),
     *                         @OA\Property(property="vehicle_passport", type="string", example="pending"),
     *                         @OA\Property(property="trailer_passport", type="string", example="not_uploaded"),
     *                         @OA\Property(property="category_cert",    type="string", example="not_uploaded"),
     *                         @OA\Property(property="green_card",       type="string", example="not_uploaded"),
     *                         @OA\Property(property="insurance",        type="string", example="not_uploaded")
     *                     ),
     *                     @OA\Property(property="verified_count", type="integer", example=1),
     *                     @OA\Property(property="total_count",    type="integer", example=6)
     *                 )
     *             ),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — admin role required")
     * )
     */
    public function adminIndex(): JsonResponse
    {
        $drivers = User::where('role', 'driver')
            ->with(['driverDocuments'])
            ->orderBy('name')
            ->paginate(30);

        $result = $drivers->through(function (User $driver): array {
            // Build a status map keyed by document type, defaulting to not_uploaded
            // for any type that has no DB row yet (avoids N+1 — documents are eager-loaded).
            $byType = $driver->driverDocuments->keyBy('document_type');

            $statusMap = collect(DriverDocument::DOCUMENT_TYPES)
                ->mapWithKeys(fn (string $type) => [
                    $type => $byType->get($type)?->status ?? DriverDocument::STATUS_NOT_UPLOADED,
                ])
                ->all();

            $verifiedCount = collect($statusMap)
                ->filter(fn (string $s) => $s === DriverDocument::STATUS_VERIFIED)
                ->count();

            return [
                'id'             => $driver->id,
                'name'           => $driver->name,
                'email'          => $driver->email,
                'documents'      => $statusMap,
                'verified_count' => $verifiedCount,
                'total_count'    => count(DriverDocument::DOCUMENT_TYPES),
            ];
        });

        return response()->json([
            'data' => $result->items(),
            'meta' => [
                'current_page' => $drivers->currentPage(),
                'last_page'    => $drivers->lastPage(),
                'per_page'     => $drivers->perPage(),
                'total'        => $drivers->total(),
                'from'         => $drivers->firstItem(),
                'to'           => $drivers->lastItem(),
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/admin/documents/{document}/verify",
     *     tags={"Documents"},
     *     summary="Admin — approve or reject a driver document",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="document", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"action"},
     *                 @OA\Property(property="action",           type="string", enum={"approve","reject"}, example="approve"),
     *                 @OA\Property(property="rejection_reason", type="string", example="Document is expired.", description="Required when action is reject")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Document verification result",
     *         @OA\JsonContent(@OA\Property(property="data", type="object"))
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — admin role required"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function adminVerify(Request $request, DriverDocument $document): JsonResponse
    {
        $request->validate([
            'action'           => ['required', 'in:approve,reject'],
            'rejection_reason' => ['required_if:action,reject', 'nullable', 'string', 'max:1000'],
        ]);

        /** @var User $admin */
        $admin = $request->user();

        if ($request->input('action') === 'approve') {
            $document->update([
                'status'           => DriverDocument::STATUS_VERIFIED,
                'rejection_reason' => null,
                'verified_at'      => now(),
                'verified_by'      => $admin->id,
            ]);

            // Notify the driver their document was verified.
            $driver = $document->user;
            if ($driver !== null) {
                try {
                    app(FirebaseService::class)->sendToUser(
                        $driver,
                        translate('push.document_verified.title'),
                        translate('push.document_verified.body'),
                        ['type' => 'document_verified', 'document_id' => (string) $document->id],
                    );
                } catch (\Throwable $e) {
                    Log::warning('FCM document_verified notification failed', [
                        'document_id' => $document->id,
                        'driver_id'   => $driver->id,
                        'error'       => $e->getMessage(),
                    ]);
                }
            }
        } else {
            // On rejection: do NOT write verified_by — that column is semantically
            // "who approved this document". Leaving it null on rejection preserves
            // the invariant that verified_by is only populated for verified documents.
            $document->update([
                'status'           => DriverDocument::STATUS_REJECTED,
                'rejection_reason' => $request->input('rejection_reason'),
                'verified_at'      => null,
                'verified_by'      => null,
            ]);

            // Notify the driver their document was rejected.
            $driver = $document->user;
            if ($driver !== null) {
                try {
                    app(FirebaseService::class)->sendToUser(
                        $driver,
                        translate('push.document_rejected.title'),
                        translate('push.document_rejected.body'),
                        ['type' => 'document_rejected', 'document_id' => (string) $document->id],
                    );
                } catch (\Throwable $e) {
                    Log::warning('FCM document_rejected notification failed', [
                        'document_id' => $document->id,
                        'driver_id'   => $driver->id,
                        'error'       => $e->getMessage(),
                    ]);
                }
            }
        }

        return response()->json(['data' => DriverDocumentResource::make($document->fresh())->resolve()]);
    }
}
