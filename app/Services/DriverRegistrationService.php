<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\WhatsAppServiceException;
use App\Models\PhoneVerification;
use App\Models\User;
use App\Services\Concerns\HandlesPhoneOtp;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // retained for OTP code_hash — NOT used for user password
use Illuminate\Support\Facades\Log;

/**
 * Encapsulates the complete WhatsApp OTP driver registration flow.
 * Both the web and API controllers delegate to this service so the
 * business logic lives in exactly one place.
 */
class DriverRegistrationService
{
    use HandlesPhoneOtp;

    public function __construct(
        private readonly WhatsAppService $whatsApp,
    ) {}

    // ─── Result DTOs ──────────────────────────────────────────────────────────

    public readonly string $normalizedPhone;

    // ─── Step 1: Request code ─────────────────────────────────────────────────

    /**
     * Validate preconditions, generate an OTP and send it via WhatsApp.
     *
     * @return array{phone: string, expires_in_seconds: int, resend_available_in_seconds: int}
     *
     * @throws \Illuminate\Validation\ValidationException  – phone already registered, not on WA
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException – 429 rate-limit, 503 service down
     */
    public function requestCode(string $name, string $phone): array
    {
        $phone = $this->normalizePhone($phone);

        // 1. Phone already registered
        if (User::where('phone', $phone)->exists()) {
            abort(409, translate('driver_reg.error_phone_taken'));
        }

        // 2. Check WhatsApp session before burning a check-exists call
        try {
            $ready = $this->whatsApp->isSessionReady();
        } catch (WhatsAppServiceException $e) {
            Log::warning('WAHA session check threw exception during driver registration', [
                'code' => $e->getWahaStatusCode(),
            ]);
            abort(503, translate('driver_reg.error_service_unavailable'));
        }

        if (!$ready) {
            abort(503, translate('driver_reg.error_service_unavailable'));
        }

        // 3. Check number exists on WhatsApp.
        // NOWEB engine requires the contact store to be enabled for this call.
        // If the store is disabled (400 from WAHA), skip the pre-flight check and
        // let sendOtp fail later if the number really isn't on WhatsApp — don't
        // block registration on an optional diagnostic.
        try {
            $exists = $this->whatsApp->checkNumberExists($phone);
            if (!$exists) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'phone' => translate('driver_reg.error_phone_not_whatsapp'),
                ]);
            }
        } catch (WhatsAppServiceException $e) {
            Log::info('WAHA check-exists unavailable — proceeding without pre-flight check', [
                'code' => $e->getWahaStatusCode(),
            ]);
        }

        // 4. Per-phone resend throttle — enforced at DB level (not IP-scoped)
        $existing = PhoneVerification::where('phone', $phone)
            ->where('purpose', PhoneVerification::PURPOSE_DRIVER_REGISTRATION)
            ->first();

        if ($existing && !$existing->canResend()) {
            abort(429, translate('driver_reg.error_rate_limit'));
        }

        // 5. Generate code and persist
        $code = $this->generateCode();
        $now  = Carbon::now();

        PhoneVerification::updateOrCreate(
            [
                'phone'   => $phone,
                'purpose' => PhoneVerification::PURPOSE_DRIVER_REGISTRATION,
            ],
            [
                'code_hash'    => Hash::make($code),
                'attempts'     => 0,
                'expires_at'   => $now->copy()->addSeconds(PhoneVerification::CODE_TTL_SECONDS),
                'last_sent_at' => $now,
            ]
        );

        // 6. Send via WhatsApp — abort with 503 on failure, row already saved so
        //    we can retry without creating a duplicate DB row.
        try {
            $this->whatsApp->sendOtp($phone, $code);
        } catch (WhatsAppServiceException $e) {
            Log::warning('WAHA sendOtp failed during driver registration request', [
                'code' => $e->getWahaStatusCode(),
            ]);
            abort(503, translate('driver_reg.error_service_unavailable'));
        }

        return [
            'phone'                    => $phone,
            'expires_in_seconds'       => PhoneVerification::CODE_TTL_SECONDS,
            'resend_available_in_seconds' => PhoneVerification::RESEND_THROTTLE_SECONDS,
        ];
    }

    // ─── Step 2: Verify code and create user ──────────────────────────────────

    /**
     * Verify the OTP and, on success, atomically create the driver User.
     *
     * @return User the newly created (unapproved) driver
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException – 409, 410, 429
     */
    public function verifyAndRegister(
        string $phone,
        string $code,
        string $name,
    ): User {
        $phone = $this->normalizePhone($phone);

        $verification = PhoneVerification::where('phone', $phone)
            ->where('purpose', PhoneVerification::PURPOSE_DRIVER_REGISTRATION)
            ->first();

        if (!$verification) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'phone' => 'Сначала запросите код.',
            ]);
        }

        if ($verification->isExpired()) {
            abort(410, translate('driver_reg.error_code_expired'));
        }

        if ($verification->isMaxAttemptsReached()) {
            abort(429, translate('driver_reg.error_too_many_attempts'));
        }

        // Compare using hash_equals internally via Hash::check which is constant-time
        if (!Hash::check($code, $verification->code_hash)) {
            $verification->increment('attempts');
            throw \Illuminate\Validation\ValidationException::withMessages([
                'code' => translate('driver_reg.error_code_wrong'),
            ]);
        }

        return DB::transaction(function () use ($phone, $name, $verification) {
            // Race-condition guard: re-check phone uniqueness inside the transaction
            if (User::where('phone', $phone)->lockForUpdate()->exists()) {
                abort(409, translate('driver_reg.error_phone_taken'));
            }

            $user = User::create([
                'name'     => $name,
                // Email is NULL for driver accounts registered via WhatsApp OTP.
                // The login flow uses phone; email auth remains available for legacy accounts.
                'email'    => null,
                'phone'    => $phone,
                // Drivers authenticate exclusively via WhatsApp OTP — no password is stored.
                'password' => null,
                'role'     => User::ROLE_DRIVER,
                'approved' => false,
            ]);

            $verification->delete();

            return $user;
        });
    }

    // ─── Step 3: Resend code ──────────────────────────────────────────────────

    /**
     * Regenerate and re-send the OTP for an existing pending verification.
     *
     * @return array{expires_in_seconds: int, resend_available_in_seconds: int}
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException – 429, 503
     */
    public function resendCode(string $phone): array
    {
        $phone = $this->normalizePhone($phone);

        $verification = PhoneVerification::where('phone', $phone)
            ->where('purpose', PhoneVerification::PURPOSE_DRIVER_REGISTRATION)
            ->first();

        if (!$verification) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'phone' => 'Сначала запросите код.',
            ]);
        }

        if (!$verification->canResend()) {
            abort(429, translate('driver_reg.error_rate_limit'));
        }

        // Check session is still alive before generating a new code
        try {
            $ready = $this->whatsApp->isSessionReady();
        } catch (WhatsAppServiceException $e) {
            abort(503, translate('driver_reg.error_service_unavailable'));
        }

        if (!$ready) {
            abort(503, translate('driver_reg.error_service_unavailable'));
        }

        $code = $this->generateCode();
        $now  = Carbon::now();

        $verification->update([
            'code_hash'    => Hash::make($code),
            'attempts'     => 0,
            'expires_at'   => $now->copy()->addSeconds(PhoneVerification::CODE_TTL_SECONDS),
            'last_sent_at' => $now,
        ]);

        try {
            $this->whatsApp->sendOtp($phone, $code);
        } catch (WhatsAppServiceException $e) {
            Log::warning('WAHA sendOtp failed during resend', ['code' => $e->getWahaStatusCode()]);
            abort(503, translate('driver_reg.error_service_unavailable'));
        }

        return [
            'expires_in_seconds'          => PhoneVerification::CODE_TTL_SECONDS,
            'resend_available_in_seconds'  => PhoneVerification::RESEND_THROTTLE_SECONDS,
        ];
    }

    // normalizePhone() and generateCode() are provided by HandlesPhoneOtp trait.
}

