<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Concerns\HandlesCmrUploads;
use App\Http\Controllers\Controller;
use App\Http\Resources\CargoApplicationResource;
use App\Models\Cargo;
use App\Models\CargoApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CargoApplicationController extends Controller
{
    use HandlesCmrUploads;
    /**
     * @OA\Get(
     *     path="/applications",
     *     tags={"Applications"},
     *     summary="Список заявок",
     *     description="Администратор видит все заявки. Сотрудник склада — только заявки на свои грузы.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", default=1)),
     *     @OA\Response(response=200, description="Список заявок",
     *         @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CargoApplication")))
     *     ),
     *     @OA\Response(response=403, description="Нет доступа")
     * )
     */
    public function index(Request $request): ResourceCollection
    {
        $user = $request->user();

        $query = $user->isAdmin()
            ? CargoApplication::with(['cargo', 'driver'])
            : CargoApplication::with(['cargo', 'driver'])->whereHas('cargo', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            });

        $applications = $query->latest()->paginate(20);

        return CargoApplicationResource::collection($applications);
    }

    /**
     * @OA\Get(
     *     path="/applications/my",
     *     tags={"Applications"},
     *     summary="Мои заявки (для водителя)",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Заявки водителя по статусам",
     *         @OA\JsonContent(
     *             @OA\Property(property="pending",  type="array", @OA\Items(ref="#/components/schemas/CargoApplication")),
     *             @OA\Property(property="approved", type="array", @OA\Items(ref="#/components/schemas/CargoApplication")),
     *             @OA\Property(property="rejected", type="array", @OA\Items(ref="#/components/schemas/CargoApplication"))
     *         )
     *     ),
     *     @OA\Response(response=403, description="Доступ только для водителей")
     * )
     */
    public function myApplications(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->isDriver()) {
            return response()->json(['message' => 'Доступ только для водителей.'], 403);
        }

        return response()->json([
            'pending'  => CargoApplicationResource::collection($user->getPendingApplications()),
            'approved' => CargoApplicationResource::collection($user->getApprovedApplications()),
            'rejected' => CargoApplicationResource::collection($user->getRejectedApplications()),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/applications/{id}",
     *     tags={"Applications"},
     *     summary="Детали заявки",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Детали заявки",
     *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/CargoApplication"))
     *     ),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=404, description="Не найдено")
     * )
     */
    public function show(Request $request, CargoApplication $application): JsonResponse
    {
        $user = $request->user();

        if ($user->isDriver() && $application->driver_id !== $user->id) {
            return response()->json(['message' => 'Вы можете просматривать только свои заявки.'], 403);
        }

        if ($user->isWarehouseEmployee() && $application->cargo->created_by !== $user->id) {
            return response()->json(['message' => 'Вы можете просматривать заявки только на свои грузы.'], 403);
        }

        $application->load(['cargo', 'driver', 'car']);

        return response()->json(['data' => new CargoApplicationResource($application)]);
    }

    /**
     * @OA\Post(
     *     path="/cargo/{cargo}/apply",
     *     tags={"Applications"},
     *     summary="Подать заявку на груз",
     *     description="Только для водителей.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="cargo", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="driver_notes", type="string", nullable=true, example="Готов забрать завтра")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Заявка подана",
     *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/CargoApplication"))
     *     ),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=409, description="Груз уже взят другим водителем или повторная заявка"),
     *     @OA\Response(
     *         response=422,
     *         description="Не пройдены предусловия: нет автомобиля или не подтверждены обязательные документы. На случай missing-docs тело содержит missing_document_types: string[].",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="missing_document_types", type="array", @OA\Items(type="string"), example={"driver_license","vehicle_passport"})
     *         )
     *     )
     * )
     */
    public function apply(Request $request, Cargo $cargo): JsonResponse
    {
        $user = $request->user();

        if (!$user->isDriver()) {
            return response()->json(['message' => 'Только водители могут подавать заявки.'], 403);
        }

        // Driver must have at least one car — cargo_applications.car_id is NOT NULL.
        $car = $user->cars()->first();
        if (!$car) {
            return response()->json([
                'message' => translate('applications.no_car_first'),
            ], 422);
        }

        // Driver must have all REQUIRED documents admin-verified before applying.
        $missingDocs = $user->unverifiedRequiredDocumentTypes();
        if (!empty($missingDocs)) {
            return response()->json([
                'message'                 => translate('applications.documents_required'),
                'missing_document_types'  => $missingDocs,
            ], 422);
        }

        $validated = $request->validate([
            'driver_notes' => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($cargo, $user, $car, $validated): JsonResponse {
            // Блокируем строку груза для предотвращения гонки потоков
            $cargo = Cargo::where('id', $cargo->id)->lockForUpdate()->firstOrFail();

            // Проверяем статус груза
            if ($cargo->status !== Cargo::STATUS_AVAILABLE) {
                return response()->json(['message' => 'Cargo is no longer available.'], 409);
            }

            // Защитная проверка: груз не должен иметь одобренную заявку
            if ($cargo->hasApprovedApplication()) {
                return response()->json(['message' => 'Cargo is no longer available.'], 409);
            }

            // Проверяем дублирующую заявку от того же водителя
            if ($cargo->applications()->where('driver_id', $user->id)->exists()) {
                return response()->json(['message' => 'You have already applied for this cargo.'], 409);
            }

            $application = CargoApplication::create([
                'cargo_id'     => $cargo->id,
                'driver_id'    => $user->id,
                'car_id'       => $car->id,
                'status'       => CargoApplication::STATUS_PENDING,
                'driver_notes' => $validated['driver_notes'] ?? null,
            ]);

            return response()->json(['data' => new CargoApplicationResource($application)], 201);
        });
    }

    /**
     * @OA\Post(
     *     path="/applications/{id}/approve",
     *     tags={"Applications"},
     *     summary="Подтвердить заявку водителя",
     *     description="Только для сотрудников склада и администраторов.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="warehouse_notes",  type="string",  nullable=true),
     *             @OA\Property(property="contact_whatsapp",type="string",  nullable=true, example="+77001234567"),
     *             @OA\Property(property="contact_wechat",  type="string",  nullable=true),
     *             @OA\Property(property="pickup_contact",  type="string",  nullable=true),
     *             @OA\Property(property="pickup_address",  type="string",  nullable=true),
     *             @OA\Property(property="delivery_contact",type="string",  nullable=true),
     *             @OA\Property(property="delivery_address",type="string",  nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Заявка подтверждена",
     *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/CargoApplication"))
     *     ),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=409, description="Заявка уже обработана")
     * )
     */
    public function approve(Request $request, CargoApplication $application): JsonResponse
    {
        $user = $request->user();

        if (!$user->isAdmin() && $application->cargo->created_by !== $user->id) {
            return response()->json(['message' => 'Вы можете подтверждать заявки только на свои грузы.'], 403);
        }

        if (!$application->isPending()) {
            return response()->json(['message' => 'Эта заявка уже обработана.'], 409);
        }

        $validated = $request->validate([
            'warehouse_notes'  => 'nullable|string|max:1000',
            'contact_whatsapp' => 'nullable|string|max:255',
            'contact_wechat'   => 'nullable|string|max:255',
            'pickup_contact'   => 'nullable|string|max:255',
            'pickup_address'   => 'nullable|string|max:500',
            'delivery_contact' => 'nullable|string|max:255',
            'delivery_address' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($application, $validated, $user): JsonResponse {
            // Re-fetch with a row-level lock to prevent two concurrent approvals
            // from landing on the same cargo (e.g. two admins acting simultaneously).
            $application = CargoApplication::where('id', $application->id)->lockForUpdate()->firstOrFail();

            // Defensive re-check: the application may have been acted on between
            // the initial isPending() check above and acquiring the lock here.
            if (!$application->isPending()) {
                return response()->json(['message' => 'Эта заявка уже обработана другим пользователем.'], 409);
            }

            // Also lock the cargo to prevent the same cargo being approved via
            // a different application concurrently.
            $cargo = Cargo::where('id', $application->cargo_id)->lockForUpdate()->firstOrFail();

            if ($cargo->status !== Cargo::STATUS_AVAILABLE) {
                return response()->json(['message' => 'Груз уже не доступен — другая заявка была подтверждена первой.'], 409);
            }

            $application->update(array_merge($validated, [
                'status'      => CargoApplication::STATUS_APPROVED,
                'approved_at' => now(),
                'approved_by' => $user->id,
            ]));

            $cargo->update([
                'status'    => Cargo::STATUS_IN_PROGRESS,
                'picked_by' => $application->driver_id,
                'picked_at' => now(),
            ]);

            $cargo->applications()
                ->where('id', '!=', $application->id)
                ->where('status', CargoApplication::STATUS_PENDING)
                ->update(['status' => CargoApplication::STATUS_REJECTED]);

            $application->load(['cargo', 'driver']);

            return response()->json(['data' => new CargoApplicationResource($application)]);
        });
    }

    /**
     * @OA\Post(
     *     path="/applications/{id}/reject",
     *     tags={"Applications"},
     *     summary="Отклонить заявку водителя",
     *     description="Только для сотрудников склада и администраторов.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Заявка отклонена",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Заявка отклонена."))
     *     ),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=409, description="Заявка уже обработана")
     * )
     */
    public function reject(Request $request, CargoApplication $application): JsonResponse
    {
        $user = $request->user();

        if (!$user->isAdmin() && $application->cargo->created_by !== $user->id) {
            return response()->json(['message' => 'Вы можете отклонять заявки только на свои грузы.'], 403);
        }

        if (!$application->isPending()) {
            return response()->json(['message' => 'Эта заявка уже обработана.'], 409);
        }

        $application->update(['status' => CargoApplication::STATUS_REJECTED]);

        return response()->json(['message' => 'Заявка отклонена.']);
    }

    /**
     * @OA\Post(
     *     path="/applications/{id}/deliver",
     *     tags={"Applications"},
     *     summary="[DEPRECATED] Отметить груз как доставленный",
     *     description="DEPRECATED: This endpoint is no longer operational. The delivery status is now set exclusively via the CMR confirmation flow. Upload a CMR document via POST /api/v1/applications/{id}/cmr/upload, then have the warehouse employee or admin confirm it via POST /api/v1/applications/{id}/cmr/confirm. The route is kept alive so existing clients receive an actionable error instead of 404.",
     *     deprecated=true,
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=410, description="Endpoint deprecated — use CMR upload flow",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This endpoint is deprecated."),
     *             @OA\Property(property="use_instead", type="string", example="POST /api/v1/applications/{id}/cmr/upload")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Нет доступа или заявка не подтверждена")
     * )
     */
    public function markAsDelivered(Request $request, CargoApplication $application): JsonResponse
    {
        // This endpoint is superseded by the CMR confirmation flow introduced in API v1.5.
        // Cargo transitions to 'delivered' only when a WE or admin confirms the uploaded CMR
        // via POST /api/v1/applications/{id}/cmr/confirm. Keeping the route alive so mobile
        // clients on older builds receive a clear, actionable error rather than a 404.
        return response()->json([
            'message'     => 'This endpoint is deprecated and no longer performs any action.',
            'use_instead' => 'POST /api/v1/applications/{id}/cmr/upload',
            'details'     => 'Upload a CMR document first, then ask a warehouse employee or admin to confirm it via POST /api/v1/applications/{id}/cmr/confirm. Confirmation moves the cargo to delivered status.',
        ], 410);
    }

    // ── CMR flow ──────────────────────────────────────────────────────────────

    /**
     * @OA\Post(
     *     path="/applications/{id}/cmr/upload",
     *     tags={"CMR"},
     *     summary="Загрузить CMR-документ",
     *     description="Только для водителя-владельца заявки. CMR должен быть в статусе not_uploaded или rejected.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"cmr_file"},
     *                 @OA\Property(property="cmr_file", type="string", format="binary", description="JPG, PNG или PDF, макс. 10 МБ")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="CMR загружен",
     *         @OA\JsonContent(
     *             @OA\Property(property="message",     type="string", example="CMR успешно загружен."),
     *             @OA\Property(property="cmr_status",  type="string", example="pending_review"),
     *             @OA\Property(property="uploaded_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=409, description="Неверное состояние CMR"),
     *     @OA\Response(response=422, description="Ошибка валидации файла")
     * )
     */
    public function uploadCmr(Request $request, CargoApplication $application): JsonResponse
    {
        $user = $request->user();

        if (!$user->isDriver()) {
            return response()->json(['message' => translate('cmr.access_denied')], 403);
        }
        if ($application->driver_id !== $user->id) {
            return response()->json(['message' => translate('cmr.access_denied')], 403);
        }
        if (!$application->canDriverUploadCmr()) {
            return response()->json(['message' => translate('cmr.invalid_state')], 409);
        }

        $request->validate([
            'cmr_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $this->performCmrUpload($application, $request->file('cmr_file'));

        $application->refresh();

        return response()->json([
            'message'     => translate('cmr.upload_success'),
            'cmr_status'  => $application->cmr_status,
            'uploaded_at' => $application->cmr_uploaded_at?->toISOString(),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/applications/{id}/cmr/confirm",
     *     tags={"CMR"},
     *     summary="Подтвердить CMR",
     *     description="WE-владелец груза или администратор. CMR должен быть в статусе pending_review. Груз переходит в delivered.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="CMR подтверждён, груз доставлен",
     *         @OA\JsonContent(
     *             @OA\Property(property="message",     type="string", example="CMR подтверждён."),
     *             @OA\Property(property="application", ref="#/components/schemas/CargoApplication")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=409, description="CMR не в статусе pending_review")
     * )
     */
    public function confirmCmr(Request $request, CargoApplication $application): JsonResponse
    {
        $user = $request->user();

        if (!$user->isAdmin() && $application->cargo->created_by !== $user->id) {
            return response()->json(['message' => translate('cmr.access_denied')], 403);
        }
        if (!$application->canReviewerActOnCmr()) {
            return response()->json(['message' => translate('cmr.invalid_state')], 409);
        }

        $this->performCmrConfirm($application, $user);

        $application->load(['cargo', 'driver']);

        return response()->json([
            'message'     => translate('cmr.confirmed_success'),
            'application' => new CargoApplicationResource($application),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/applications/{id}/cmr/reject",
     *     tags={"CMR"},
     *     summary="Отклонить CMR",
     *     description="WE-владелец груза или администратор. CMR должен быть в статусе pending_review.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"rejection_reason"},
     *             @OA\Property(property="rejection_reason", type="string", maxLength=1000, example="Документ нечитаем, переснимите.")
     *         )
     *     ),
     *     @OA\Response(response=200, description="CMR отклонён",
     *         @OA\JsonContent(
     *             @OA\Property(property="message",          type="string"),
     *             @OA\Property(property="rejection_reason", type="string")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=409, description="CMR не в статусе pending_review"),
     *     @OA\Response(response=422, description="rejection_reason обязателен")
     * )
     */
    public function rejectCmr(Request $request, CargoApplication $application): JsonResponse
    {
        $user = $request->user();

        if (!$user->isAdmin() && $application->cargo->created_by !== $user->id) {
            return response()->json(['message' => translate('cmr.access_denied')], 403);
        }
        if (!$application->canReviewerActOnCmr()) {
            return response()->json(['message' => translate('cmr.invalid_state')], 409);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $reason = $request->input('rejection_reason');

        $this->performCmrReject($application, $reason);

        return response()->json([
            'message'          => translate('cmr.rejected_success'),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/applications/{id}/cmr",
     *     tags={"CMR"},
     *     summary="Удалить загруженный CMR",
     *     description="Только водитель-владелец. Нельзя удалить подтверждённый CMR.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="CMR удалён",
     *         @OA\JsonContent(@OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=409, description="CMR уже подтверждён или нет файла для удаления")
     * )
     */
    public function destroyCmr(Request $request, CargoApplication $application): JsonResponse
    {
        $user = $request->user();

        if (!$user->isDriver()) {
            return response()->json(['message' => translate('cmr.access_denied')], 403);
        }
        if ($application->driver_id !== $user->id) {
            return response()->json(['message' => translate('cmr.access_denied')], 403);
        }
        if ($application->isCmrLocked()) {
            return response()->json(['message' => translate('cmr.already_confirmed')], 409);
        }
        if (!in_array($application->cmr_status, [
            CargoApplication::CMR_STATUS_PENDING_REVIEW,
            CargoApplication::CMR_STATUS_REJECTED,
        ], true)) {
            return response()->json(['message' => translate('cmr.invalid_state')], 409);
        }

        $this->performCmrDestroy($application);

        return response()->json(['message' => translate('cmr.deleted_success')]);
    }

    /**
     * @OA\Get(
     *     path="/applications/{id}/cmr/file",
     *     tags={"CMR"},
     *     summary="Скачать CMR-файл",
     *     description="Доступен водителю-владельцу, WE-владельцу груза и администратору.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Бинарный файл CMR", @OA\MediaType(mediaType="application/octet-stream")),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=404, description="Файл не найден")
     * )
     */
    public function cmrFile(Request $request, CargoApplication $application): StreamedResponse|JsonResponse
    {
        $user = $request->user();

        $isDriverOwner = $user->isDriver() && $application->driver_id === $user->id;
        $isCargoOwner  = $user->isWarehouseEmployee() && $application->cargo->created_by === $user->id;
        $isAdmin       = $user->isAdmin();

        if (!$isDriverOwner && !$isCargoOwner && !$isAdmin) {
            return response()->json(['message' => translate('cmr.access_denied')], 403);
        }

        if (!$application->cmr_file_path || !Storage::disk('local')->exists($application->cmr_file_path)) {
            return response()->json(['message' => 'Файл не найден.'], 404);
        }

        return Storage::disk('local')->response(
            $application->cmr_file_path,
            $application->cmr_original_filename ?? 'cmr_document'
        );
    }
}
