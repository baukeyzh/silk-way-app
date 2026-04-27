<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePushTokenRequest;
use App\Models\FcmToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PushTokenController extends Controller
{
    /**
     * @OA\Post(
     *     path="/push-tokens",
     *     tags={"PushTokens"},
     *     summary="Зарегистрировать FCM-токен устройства",
     *     description="Сохраняет или обновляет FCM-токен для текущего пользователя. Безопасно вызывать при каждом запуске приложения.",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"token"},
     *             @OA\Property(property="token",    type="string", minLength=32, maxLength=512, example="fGh3k..."),
     *             @OA\Property(property="platform", type="string", nullable=true, enum={"android","ios","web"}, example="android")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Токен сохранён",
     *         @OA\JsonContent(
     *             @OA\Property(property="id",    type="integer", example=42),
     *             @OA\Property(property="token", type="string",  example="fGh3k...")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function store(StorePushTokenRequest $request): JsonResponse
    {
        $user = $request->user();

        $fcmToken = FcmToken::updateOrCreate(
            [
                'user_id' => $user->id,
                'token'   => $request->validated('token'),
            ],
            [
                'platform'     => $request->validated('platform'),
                'last_used_at' => now(),
            ],
        );

        return response()->json([
            'id'    => $fcmToken->id,
            'token' => $fcmToken->token,
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/push-tokens/{token}",
     *     tags={"PushTokens"},
     *     summary="Удалить FCM-токен устройства",
     *     description="Удаляет FCM-токен для текущего пользователя. Идемпотентен — возвращает 204 даже если токен не найден.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="token",
     *         in="path",
     *         required=true,
     *         description="FCM registration token",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=204, description="Токен удалён (или не существовал)"),
     *     @OA\Response(response=401, description="Не авторизован")
     * )
     */
    public function destroy(Request $request, string $token): Response
    {
        FcmToken::where('user_id', $request->user()->id)
            ->where('token', $token)
            ->delete();

        return response()->noContent();
    }
}
