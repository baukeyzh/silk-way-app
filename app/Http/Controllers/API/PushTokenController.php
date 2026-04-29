<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePushTokenRequest;
use App\Models\FcmToken;
use App\Models\User;
use App\Services\FirebaseService;
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

    /**
     * @OA\Post(
     *     path="/admin/push/test",
     *     tags={"Admin"},
     *     summary="Отправить тестовый пуш указанному пользователю",
     *     description="Только для администраторов. Шлёт пуш на все FCM-токены пользователя через FirebaseService::sendToUser. Возвращает количество устройств, до которых сообщение реально дошло.",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","title","body"},
     *             @OA\Property(property="user_id", type="integer", example=5),
     *             @OA\Property(property="title",   type="string",  example="Тест"),
     *             @OA\Property(property="body",    type="string",  example="Привет от Silk Way"),
     *             @OA\Property(property="data",    type="object",  nullable=true, description="Доп. payload (значения приводятся к строкам)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Отправлено",
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id",              type="integer", example=5),
     *             @OA\Property(property="token_count",          type="integer", example=2, description="Сколько токенов было у юзера на момент отправки"),
     *             @OA\Property(property="delivered_to_devices", type="integer", example=2, description="Сколько устройств приняли пуш")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Нет доступа"),
     *     @OA\Response(response=404, description="Пользователь не найден"),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function sendTest(Request $request, FirebaseService $firebase): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'title'   => 'required|string|max:120',
            'body'    => 'required|string|max:500',
            'data'    => 'sometimes|array',
        ]);

        $user = User::findOrFail($validated['user_id']);

        $delivered = $firebase->sendToUser(
            $user,
            $validated['title'],
            $validated['body'],
            $validated['data'] ?? [],
        );

        return response()->json([
            'user_id'              => $user->id,
            'token_count'          => $user->fcmTokens()->count(),
            'delivered_to_devices' => $delivered,
        ]);
    }
}
