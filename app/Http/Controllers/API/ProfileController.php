<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/profile",
     *     tags={"Profile"},
     *     summary="Получить профиль текущего пользователя",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Данные профиля",
     *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/User"))
     *     ),
     *     @OA\Response(response=401, description="Не авторизован")
     * )
     */
    public function show(Request $request): JsonResponse
    {
        return response()->json(['data' => new UserResource($request->user())]);
    }

    /**
     * @OA\Put(
     *     path="/profile",
     *     tags={"Profile"},
     *     summary="Обновить профиль текущего пользователя",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name",                  type="string",  example="Иван Иванов"),
     *             @OA\Property(property="email",                 type="string",  format="email",    example="ivan@example.com", description="Только для warehouse_employee / admin"),
     *             @OA\Property(property="password",              type="string",  format="password", example="newSecret123",      description="Только для warehouse_employee / admin; не заполнять — пароль не меняется"),
     *             @OA\Property(property="password_confirmation", type="string",  format="password", example="newSecret123",      description="Только для warehouse_employee / admin")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Профиль обновлён",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Профиль обновлён."),
     *             @OA\Property(property="data",    ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user      = $request->user();
        $validated = $request->validated();

        $payload = ['name' => $validated['name']];

        if (! $user->isDriver()) {
            $payload['email'] = $validated['email'];

            if (! empty($validated['password'])) {
                $payload['password'] = Hash::make($validated['password']);
            }
        }

        $user->update($payload);

        return response()->json([
            'message' => 'Профиль обновлён.',
            'data'    => new UserResource($user->fresh()),
        ]);
    }
}
