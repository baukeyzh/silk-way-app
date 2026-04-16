<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AdminController extends Controller
{
    /**
     * @OA\Get(
     *     path="/admin/users",
     *     tags={"Admin"},
     *     summary="Список всех пользователей",
     *     description="Только для администраторов.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="status", in="query", description="pending | approved", @OA\Schema(type="string")),
     *     @OA\Parameter(name="page",   in="query", @OA\Schema(type="integer", default=1)),
     *     @OA\Response(response=200, description="Список пользователей",
     *         @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")))
     *     ),
     *     @OA\Response(response=403, description="Нет доступа")
     * )
     */
    public function users(Request $request): ResourceCollection
    {
        $status = $request->query('status');

        $query = User::query();

        if ($status === 'pending') {
            $query->pendingApproval();
        } elseif ($status === 'approved') {
            $query->approved();
        }

        $users = $query->latest()->paginate(20);

        return UserResource::collection($users);
    }

    /**
     * @OA\Get(
     *     path="/admin/users/{id}",
     *     tags={"Admin"},
     *     summary="Детали пользователя",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Данные пользователя",
     *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/User"))
     *     ),
     *     @OA\Response(response=404, description="Не найдено")
     * )
     */
    public function show(User $user): JsonResponse
    {
        return response()->json(['data' => new UserResource($user)]);
    }

    /**
     * @OA\Post(
     *     path="/admin/users/{id}/approve",
     *     tags={"Admin"},
     *     summary="Подтвердить аккаунт пользователя",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Аккаунт подтверждён",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Аккаунт подтверждён."),
     *             @OA\Property(property="data",    ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Не найдено")
     * )
     */
    public function approveUser(User $user): JsonResponse
    {
        $user->update(['approved' => true]);

        return response()->json([
            'message' => 'Аккаунт подтверждён.',
            'data'    => new UserResource($user),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/admin/users/{id}/toggle",
     *     tags={"Admin"},
     *     summary="Переключить статус подтверждения аккаунта",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Статус изменён",
     *         @OA\JsonContent(
     *             @OA\Property(property="message",     type="string"),
     *             @OA\Property(property="is_approved", type="boolean"),
     *             @OA\Property(property="data",        ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Не найдено")
     * )
     */
    public function toggleApproval(User $user): JsonResponse
    {
        $user->update(['approved' => !$user->approved]);

        $status = $user->approved ? 'подтверждён' : 'заблокирован';

        return response()->json([
            'message'     => "Аккаунт {$status}.",
            'is_approved' => $user->approved,
            'data'        => new UserResource($user),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/admin/users/{id}",
     *     tags={"Admin"},
     *     summary="Удалить пользователя",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Пользователь удалён",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Пользователь удалён."))
     *     ),
     *     @OA\Response(response=403, description="Нельзя удалить себя"),
     *     @OA\Response(response=404, description="Не найдено")
     * )
     */
    public function deleteUser(Request $request, User $user): JsonResponse
    {
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'Нельзя удалить свой аккаунт.'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Пользователь удалён.']);
    }
}
