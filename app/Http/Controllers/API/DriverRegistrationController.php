<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverRegistration\RequestCodeRequest;
use App\Http\Requests\DriverRegistration\ResendCodeRequest;
use App\Http\Requests\DriverRegistration\VerifyCodeRequest;
use App\Http\Resources\UserResource;
use App\Services\DriverRegistrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(name="DriverRegistration", description="Регистрация водителя через WhatsApp OTP")
 */
class DriverRegistrationController extends Controller
{
    public function __construct(
        private readonly DriverRegistrationService $service,
    ) {}

    /**
     * @OA\Post(
     *     path="/auth/driver/register/request",
     *     tags={"DriverRegistration"},
     *     summary="Шаг 1 — Запрос OTP-кода через WhatsApp",
     *     description="Проверяет номер телефона, отправляет 6-значный код в WhatsApp. Повторная отправка — не чаще 1 раза в 60 секунд.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","phone"},
     *             @OA\Property(property="name",  type="string",  maxLength=255, example="Иван Иванов"),
     *             @OA\Property(property="phone", type="string",  example="+7 700 120 00 13")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Код отправлен в WhatsApp",
     *         @OA\JsonContent(
     *             @OA\Property(property="message",                    type="string",  example="Код отправлен"),
     *             @OA\Property(property="phone",                      type="string",  example="77001200013"),
     *             @OA\Property(property="expires_in_seconds",         type="integer", example=600),
     *             @OA\Property(property="resend_available_in_seconds",type="integer", example=60)
     *         )
     *     ),
     *     @OA\Response(response=409, description="Номер уже зарегистрирован"),
     *     @OA\Response(response=422, description="Ошибка валидации / номер не найден в WhatsApp"),
     *     @OA\Response(response=429, description="Слишком часто — подождите 60 секунд"),
     *     @OA\Response(response=503, description="WAHA-сессия не готова")
     * )
     */
    public function requestCode(RequestCodeRequest $request): JsonResponse
    {
        try {
            $result = $this->service->requestCode(
                $request->string('name')->toString(),
                $request->string('phone')->toString(),
            );
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Ошибка валидации.',
                'errors'  => $e->errors(),
            ], 422);
        }

        return response()->json([
            'message'                    => translate('driver_reg.code_sent'),
            'phone'                      => $result['phone'],
            'expires_in_seconds'         => $result['expires_in_seconds'],
            'resend_available_in_seconds' => $result['resend_available_in_seconds'],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/auth/driver/register/verify",
     *     tags={"DriverRegistration"},
     *     summary="Шаг 2 — Верификация кода и создание аккаунта водителя",
     *     description="Проверяет OTP, создаёт пользователя с role=driver, approved=false, password=null. Токен НЕ выдаётся — требуется подтверждение администратора. Пароль не нужен — вход только через WhatsApp OTP.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone","code","name"},
     *             @OA\Property(property="phone", type="string",  example="77001200013"),
     *             @OA\Property(property="code",  type="string",  example="123456"),
     *             @OA\Property(property="name",  type="string",  maxLength=255, example="Иван Иванов")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Аккаунт создан, ожидает подтверждения администратора",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Регистрация успешна. Ожидайте подтверждения администратора."),
     *             @OA\Property(property="user",    ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(response=409, description="Номер уже зарегистрирован (race condition)"),
     *     @OA\Response(response=410, description="Код истёк — запросите новый"),
     *     @OA\Response(response=422, description="Неверный код или ошибка валидации"),
     *     @OA\Response(response=429, description="Превышен лимит попыток")
     * )
     */
    public function verifyCode(VerifyCodeRequest $request): JsonResponse
    {
        try {
            $user = $this->service->verifyAndRegister(
                phone: $request->string('phone')->toString(),
                code:  $request->string('code')->toString(),
                name:  $request->string('name')->toString(),
            );
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Ошибка валидации.',
                'errors'  => $e->errors(),
            ], 422);
        }

        return response()->json([
            'message' => translate('driver_reg.success'),
            'user'    => new UserResource($user),
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/auth/driver/register/resend",
     *     tags={"DriverRegistration"},
     *     summary="Шаг 3 — Повторная отправка кода",
     *     description="Генерирует новый код и отправляет его повторно. Не чаще 1 раза в 60 секунд на номер.",
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
     *             @OA\Property(property="message",                    type="string",  example="Код повторно отправлен"),
     *             @OA\Property(property="expires_in_seconds",         type="integer", example=600),
     *             @OA\Property(property="resend_available_in_seconds",type="integer", example=60)
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
            'message'                    => translate('driver_reg.code_resent'),
            'expires_in_seconds'         => $result['expires_in_seconds'],
            'resend_available_in_seconds' => $result['resend_available_in_seconds'],
        ]);
    }
}
