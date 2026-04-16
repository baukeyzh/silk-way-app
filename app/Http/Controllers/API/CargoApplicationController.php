<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CargoApplicationResource;
use App\Models\Cargo;
use App\Models\CargoApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CargoApplicationController extends Controller
{
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
     *     @OA\Response(response=403, description="Нет доступа или груз недоступен"),
     *     @OA\Response(response=409, description="Заявка уже существует")
     * )
     */
    public function apply(Request $request, Cargo $cargo): JsonResponse
    {
        $user = $request->user();

        if (!$user->isDriver()) {
            return response()->json(['message' => 'Только водители могут подавать заявки.'], 403);
        }

        if ($cargo->status !== 'available') {
            return response()->json(['message' => 'Этот груз больше не доступен.'], 403);
        }

        if ($cargo->applications()->where('driver_id', $user->id)->exists()) {
            return response()->json(['message' => 'Вы уже подавали заявку на этот груз.'], 409);
        }

        $validated = $request->validate([
            'driver_notes' => 'nullable|string|max:1000',
        ]);

        $application = CargoApplication::create([
            'cargo_id'     => $cargo->id,
            'driver_id'    => $user->id,
            'car_id'       => $user->cars->first()?->id,
            'status'       => 'pending',
            'driver_notes' => $validated['driver_notes'] ?? null,
        ]);

        return response()->json(['data' => new CargoApplicationResource($application)], 201);
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

        $application->update(array_merge($validated, [
            'status'      => 'approved',
            'approved_at' => now(),
            'approved_by' => $user->id,
        ]));

        $application->cargo->update([
            'status'    => 'in_progress',
            'picked_by' => $application->driver_id,
            'picked_at' => now(),
        ]);

        $application->cargo->applications()
            ->where('id', '!=', $application->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        $application->load(['cargo', 'driver']);

        return response()->json(['data' => new CargoApplicationResource($application)]);
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

        $application->update(['status' => 'rejected']);

        return response()->json(['message' => 'Заявка отклонена.']);
    }

    /**
     * @OA\Post(
     *     path="/applications/{id}/deliver",
     *     tags={"Applications"},
     *     summary="Отметить груз как доставленный",
     *     description="Только для водителя-владельца заявки.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Груз отмечен как доставленный",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Груз доставлен."))
     *     ),
     *     @OA\Response(response=403, description="Нет доступа или заявка не подтверждена")
     * )
     */
    public function markAsDelivered(Request $request, CargoApplication $application): JsonResponse
    {
        $user = $request->user();

        if (!$user->isDriver()) {
            return response()->json(['message' => 'Доступ только для водителей.'], 403);
        }

        if ($application->driver_id !== $user->id) {
            return response()->json(['message' => 'Вы можете отмечать только свои грузы как доставленные.'], 403);
        }

        if (!$application->isApproved()) {
            return response()->json(['message' => 'Эта заявка ещё не подтверждена.'], 403);
        }

        $application->cargo->update(['status' => 'delivered']);

        return response()->json(['message' => 'Груз доставлен.']);
    }
}
