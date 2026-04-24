<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PhoneVerification;
use App\Models\User;
use App\Services\Concerns\HandlesPhoneOtp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Encapsulates the complete WhatsApp OTP driver login flow.
 *
 * Step 1 — requestCode:  find driver, send OTP via WhatsApp
 * Step 2 — verifyCode:   validate OTP, return User (caller handles session/token)
 * Step 3 — resendCode:   regenerate and re-send OTP
 *
 * Web and API controllers both delegate here — business logic in one place.
 */
class DriverLoginService
{
    use HandlesPhoneOtp;

    public function __construct(
        private readonly WhatsAppService $whatsApp,
    ) {}

    // ─── Step 1: Request login code ───────────────────────────────────────────

    /**
     * Validate preconditions, generate an OTP and dispatch it via WhatsApp.
     *
     * @return array{phone: string, phone_masked: string, expires_in_seconds: int, resend_available_in_seconds: int, register_url: string}
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException – 403 not approved, 404 not found, 429 rate-limit, 503 service down
     */
    public function requestCode(string $phone): array
    {
        $phone = $this->normalizePhone($phone);

        // 1. Driver must exist
        $user = User::where('phone', $phone)
            ->where('role', User::ROLE_DRIVER)
            ->first();

        if (!$user) {
            abort(404, translate('driver_login.error_not_found'));
        }

        // 2. Driver must be approved
        if (!$user->approved) {
            abort(403, translate('driver_login.error_not_approved'));
        }

        // 3. WAHA session must be ready
        $this->requireSessionReady(translate('driver_login.error_service_unavailable'));

        // 4. Per-phone resend throttle enforced at DB level
        $existing = PhoneVerification::where('phone', $phone)
            ->where('purpose', PhoneVerification::PURPOSE_DRIVER_LOGIN)
            ->first();

        if ($existing && !$existing->canResend()) {
            abort(429, translate('driver_login.error_rate_limit'));
        }

        // 5. Generate code, upsert row, send
        $waMessage = str_replace(':code', '__CODE__', (string) translate('driver_login.wa_message'));
        // upsertAndSend generates its own code internally and substitutes it;
        // we pass the pre-rendered template with a placeholder so sendOtp can inject.
        // Actually we generate the code here to have it for the message, then persist.
        $code = $this->generateCode();
        $renderedMessage = str_replace(':code', $code, (string) translate('driver_login.wa_message'));

        $this->persistAndSend($phone, $code, $renderedMessage);

        return [
            'phone'                       => $phone,
            'phone_masked'                => $this->maskPhone($phone),
            'expires_in_seconds'          => PhoneVerification::CODE_TTL_SECONDS,
            'resend_available_in_seconds' => PhoneVerification::RESEND_THROTTLE_SECONDS,
            'register_url'                => route('driver.register.show'),
        ];
    }

    // ─── Step 2: Verify code ──────────────────────────────────────────────────

    /**
     * Verify the OTP. On success, atomically deletes the verification row
     * and returns the authenticated User. The caller handles session/token creation.
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException – 403, 410, 429
     */
    public function verifyCode(string $phone, string $code): User
    {
        $phone = $this->normalizePhone($phone);

        $verification = PhoneVerification::where('phone', $phone)
            ->where('purpose', PhoneVerification::PURPOSE_DRIVER_LOGIN)
            ->first();

        if (!$verification) {
            throw ValidationException::withMessages([
                'phone' => translate('driver_login.error_no_pending_code'),
            ]);
        }

        if ($verification->isExpired()) {
            abort(410, translate('driver_login.error_code_expired'));
        }

        if ($verification->isMaxAttemptsReached()) {
            abort(429, translate('driver_login.error_too_many_attempts'));
        }

        // Constant-time compare via Hash::check (bcrypt)
        if (!Hash::check($code, $verification->code_hash)) {
            $verification->increment('attempts');
            throw ValidationException::withMessages([
                'code' => translate('driver_login.error_code_wrong'),
            ]);
        }

        return DB::transaction(function () use ($phone, $verification) {
            // Re-fetch user inside transaction — guard against approval revocation between steps
            $user = User::where('phone', $phone)
                ->where('role', User::ROLE_DRIVER)
                ->lockForUpdate()
                ->first();

            if (!$user) {
                abort(404, translate('driver_login.error_not_found'));
            }

            if (!$user->approved) {
                abort(403, translate('driver_login.error_not_approved'));
            }

            $verification->delete();

            return $user;
        });
    }

    // ─── Step 3: Resend code ──────────────────────────────────────────────────

    /**
     * Regenerate and re-send the OTP for an existing pending login verification.
     *
     * @return array{phone_masked: string, expires_in_seconds: int, resend_available_in_seconds: int}
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException – 429, 503
     */
    public function resendCode(string $phone): array
    {
        $phone = $this->normalizePhone($phone);

        $verification = PhoneVerification::where('phone', $phone)
            ->where('purpose', PhoneVerification::PURPOSE_DRIVER_LOGIN)
            ->first();

        if (!$verification) {
            throw ValidationException::withMessages([
                'phone' => translate('driver_login.error_no_pending_code'),
            ]);
        }

        if (!$verification->canResend()) {
            abort(429, translate('driver_login.error_rate_limit'));
        }

        $this->requireSessionReady(translate('driver_login.error_service_unavailable'));

        $code            = $this->generateCode();
        $renderedMessage = str_replace(':code', $code, (string) translate('driver_login.wa_message'));

        $this->persistAndSend($phone, $code, $renderedMessage, $verification);

        return [
            'phone_masked'                => $this->maskPhone($phone),
            'expires_in_seconds'          => PhoneVerification::CODE_TTL_SECONDS,
            'resend_available_in_seconds' => PhoneVerification::RESEND_THROTTLE_SECONDS,
        ];
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Upsert the PhoneVerification row and send the OTP.
     * Accepts an optional existing model to avoid a redundant query on resend.
     */
    private function persistAndSend(
        string $phone,
        string $code,
        string $renderedMessage,
        ?PhoneVerification $existing = null,
    ): void {
        $now = now();

        $attributes = [
            'code_hash'    => \Illuminate\Support\Facades\Hash::make($code),
            'attempts'     => 0,
            'expires_at'   => $now->copy()->addSeconds(PhoneVerification::CODE_TTL_SECONDS),
            'last_sent_at' => $now,
        ];

        if ($existing) {
            $existing->update($attributes);
        } else {
            PhoneVerification::updateOrCreate(
                ['phone' => $phone, 'purpose' => PhoneVerification::PURPOSE_DRIVER_LOGIN],
                $attributes
            );
        }

        try {
            $this->whatsApp->sendOtp($phone, $code, $renderedMessage);
        } catch (\App\Exceptions\WhatsAppServiceException $e) {
            Log::warning('WAHA sendOtp failed during driver login', [
                'waha_status' => $e->getWahaStatusCode(),
            ]);
            abort(503, translate('driver_login.error_service_unavailable'));
        }
    }
}
