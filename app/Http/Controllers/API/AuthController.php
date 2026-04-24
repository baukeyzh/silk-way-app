<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Info(
 *     title="Silk Way API",
 *     version="1.8.0",
 *     description="REST API для мобильного приложения Silk Way. Авторизация через Bearer токен (Sanctum).",
 *     @OA\Contact(email="admin@silkway.kz")
 * )
 *
 * @OA\Server(
 *     url="/api/v1",
 *     description="API сервер"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Token",
 *     description="Введите токен (без слова Bearer — оно добавляется автоматически)"
 * )
 *
 * @OA\Tag(name="Auth",         description="Авторизация")
 * @OA\Tag(name="Cargo",        description="Грузы")
 * @OA\Tag(name="Cars",         description="Транспортные средства")
 * @OA\Tag(name="Applications", description="Заявки на грузы")
 * @OA\Tag(name="Admin",        description="Администрирование")
 * @OA\Tag(name="Documents",            description="Документы водителя")
 * @OA\Tag(name="DriverRegistration",  description="Регистрация водителя через WhatsApp OTP")
 * @OA\Tag(name="DriverLogin",         description="Вход водителя через WhatsApp OTP")
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/register",
     *     tags={"Auth"},
     *     summary="Регистрация нового пользователя",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation","role"},
     *             @OA\Property(property="name",                  type="string",  example="Иван Иванов"),
     *             @OA\Property(property="email",                 type="string",  format="email", example="ivan@example.com"),
     *             @OA\Property(property="password",              type="string",  format="password", example="secret123"),
     *             @OA\Property(property="password_confirmation", type="string",  format="password", example="secret123"),
     *             @OA\Property(property="role",                  type="string",  enum={"warehouse_employee"}, example="warehouse_employee")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Регистрация успешна, ожидайте подтверждения администратора",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Регистрация успешна. Ожидайте подтверждения администратора.")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function register(Request $request): JsonResponse
    {
        // Drivers must register via /api/v1/auth/driver/register — never via this endpoint.
        if ($request->input('role') === User::ROLE_DRIVER) {
            return response()->json([
                'message' => translate('auth.driver_use_whatsapp_register'),
                'redirect' => '/api/v1/auth/driver/register/request',
            ], 422);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => ['required', Rule::in([User::ROLE_WAREHOUSE_EMPLOYEE])],
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'approved' => false,
        ]);

        return response()->json([
            'message' => 'Регистрация успешна. Ожидайте подтверждения администратора.',
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"Auth"},
     *     summary="Вход в систему",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email",    type="string", format="email", example="ivan@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный вход",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|abc123..."),
     *             @OA\Property(property="user",  ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Неверные учётные данные"),
     *     @OA\Response(response=403, description="Аккаунт не подтверждён")
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Detect driver accounts before Auth::attempt() — consistent rejection
        // regardless of password match, eliminating any timing oracle.
        // Drivers authenticate exclusively via /api/v1/auth/driver/login.
        $targetUser = User::where('email', $request->input('email'))->first();
        if ($targetUser && $targetUser->isDriver()) {
            return response()->json([
                'message'  => translate('auth.driver_use_whatsapp_login'),
                'redirect' => '/api/v1/auth/driver/login/request',
            ], 403);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Неверные учётные данные.'], 401);
        }

        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isApproved()) {
            Auth::logout();
            return response()->json(['message' => 'Аккаунт ещё не подтверждён администратором.'], 403);
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => new UserResource($user),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     tags={"Auth"},
     *     summary="Выход из системы (удаляет текущий токен)",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Выход выполнен",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Выход выполнен."))
     *     ),
     *     @OA\Response(response=401, description="Не авторизован")
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Выход выполнен.']);
    }

    /**
     * @OA\Get(
     *     path="/auth/me",
     *     tags={"Auth"},
     *     summary="Данные текущего пользователя",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Данные пользователя",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=401, description="Не авторизован")
     * )
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json(new UserResource($request->user()));
    }
}
