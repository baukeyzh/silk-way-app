<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverLogin\RequestCodeRequest;
use App\Http\Requests\DriverLogin\ResendCodeRequest;
use App\Http\Requests\DriverLogin\VerifyCodeRequest;
use App\Http\Resources\UserResource;
use App\Services\DriverLoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(name="DriverLogin", description="Вход водителя через WhatsApp OTP")
 */
class DriverLoginController extends Controller
{
    public function __construct(
        private readonly DriverLoginService $service,
    ) {}

    /**
     * @OA\Post(
     *     path="/auth/driver/login/request",
     *     tags={"DriverLogin"},
     *     summary="Шаг 1 — Запрос OTP-кода для входа через WhatsApp",
     *     description="Находит водителя по номеру телефона, проверяет статус аккаунта и отправляет 6-значный код в WhatsApp. Повторная отправка — не чаще 1 раза в 60 секунд.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone"},
     *             @OA\Property(property="phone", type="string", example="+7 700 120 00 13")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Код отправлен в WhatsApp",
     *         @OA\JsonContent(
     *             @OA\Property(property="message",                     type="string",  example="Код для входа отправлен"),
     *             @OA\Property(property="phone",                       type="string",  example="77001200013"),
     *             @OA\Property(property="phone_masked",                type="string",  example="+7 ••• ••• **-13"),
     *             @OA\Property(property="expires_in_seconds",          type="integer", example=600),
     *             @OA\Property(property="resend_available_in_seconds", type="integer", example=60),
     *             @OA\Property(property="register_url",                type="string",  example="https://example.com/register/driver")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Аккаунт ещё не подтверждён администратором"),
     *     @OA\Response(response=404, description="Водитель с таким номером не найден"),
     *     @OA\Response(response=422, description="Ошибка валидации"),
     *     @OA\Response(response=429, description="Слишком часто — подождите 60 секунд"),
     *     @OA\Response(response=503, description="WAHA-сессия не готова")
     * )
     */
    public function requestCode(RequestCodeRequest $request): JsonResponse
    {
        try {
            $result = $this->service->requestCode(
                $request->string('phone')->toString(),
            );
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Ошибка валидации.',
                'errors'  => $e->errors(),
            ], 422);
        }

        return response()->json([
            'message'                     => translate('driver_login.code_sent'),
            'phone'                       => $result['phone'],
            'phone_masked'                => $result['phone_masked'],
            'expires_in_seconds'          => $result['expires_in_seconds'],
            'resend_available_in_seconds' => $result['resend_available_in_seconds'],
            'register_url'                => $result['register_url'],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/auth/driver/login/verify",
     *     tags={"DriverLogin"},
     *     summary="Шаг 2 — Верификация кода и вход водителя",
     *     description="Проверяет OTP-код. При успехе удаляет верификационную строку и возвращает Sanctum-токен. Пароль не требуется — OTP является фактором аутентификации.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone","code"},
     *             @OA\Property(property="phone", type="string",  example="77001200013"),
     *             @OA\Property(property="code",  type="string",  example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Вход выполнен успешно",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string",  example="Вход выполнен успешно."),
     *             @OA\Property(property="token",   type="string",  example="1|abc123..."),
     *             @OA\Property(property="user",    ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Аккаунт не подтверждён (статус изменён между шагами)"),
     *     @OA\Response(response=404, description="Водитель не найден (аккаунт удалён между шагами)"),
     *     @OA\Response(response=410, description="Код истёк — запросите новый"),
     *     @OA\Response(response=422, description="Неверный код или ошибка валидации"),
     *     @OA\Response(response=429, description="Превышен лимит попыток (5)")
     * )
     */
    public function verifyCode(VerifyCodeRequest $request): JsonResponse
    {
        try {
            $user = $this->service->verifyCode(
                phone: $request->string('phone')->toString(),
                code:  $request->string('code')->toString(),
            );
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Ошибка валидации.',
                'errors'  => $e->errors(),
            ], 422);
        }

        $token = $user->createToken('mobile-wa')->plainTextToken;

        return response()->json([
            'message' => translate('driver_login.login_success'),
            'token'   => $token,
            'user'    => new UserResource($user),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/auth/driver/login/resend",
     *     tags={"DriverLogin"},
     *     summary="Шаг 3 — Повторная отправка кода для входа",
     *     description="Генерирует новый код и отправляет повторно. Не чаще 1 раза в 60 секунд на номер.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone"},
     *             @OA\Property(property="phone", type="string", example="77001200013")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Код отправлен повторно",
     *         @OA\JsonContent(
     *             @OA\Property(property="message",                     type="string",  example="Новый код для входа отправлен"),
     *             @OA\Property(property="phone_masked",                type="string",  example="+7 ••• ••• **-13"),
     *             @OA\Property(property="expires_in_seconds",          type="integer", example=600),
     *             @OA\Property(property="resend_available_in_seconds", type="integer", example=60)
     *         )
     *     ),
     *     @OA\Response(response=422, description="Нет активной верификации для этого номера"),
     *     @OA\Response(response=429, description="Слишком рано — подождите 60 секунд"),
     *     @OA\Response(response=503, description="WAHA-сессия не готова")
     * )
     */
    public function resendCode(ResendCodeRequest $request): JsonResponse
    {
        try {
            $result = $this->service->resendCode(
                $request->string('phone')->toString(),
            );
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Ошибка валидации.',
                'errors'  => $e->errors(),
            ], 422);
        }

        return response()->json([
            'message'                     => translate('driver_login.code_resent'),
            'phone_masked'                => $result['phone_masked'],
            'expires_in_seconds'          => $result['expires_in_seconds'],
            'resend_available_in_seconds' => $result['resend_available_in_seconds'],
        ]);
    }
}
