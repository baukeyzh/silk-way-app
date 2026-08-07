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

    /**
     * Fixed QA/review test account — bypasses WhatsApp delivery and always
     * uses a static code so app-store reviewers / QA can log in without a
     * live WAHA session. Normalized (digits-only) form of +7 700 12 000 13.
     */
    private const TEST_PHONE = '77001200013';
    private const TEST_PHONE_CODE = '123456';

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
        $isTestPhone = $phone === self::TEST_PHONE;

        // 1. Look up driver by phone. If none exists, auto-register a new
        //    unapproved account so the user can verify ownership in the same
        //    flow. The admin-approval gate still blocks access until approved
        //    (enforced at verifyCode), so this is not a security regression.
        $user = User::where('phone', $phone)
            ->where('role', User::ROLE_DRIVER)
            ->first();

        if (!$user) {
            // Guard against phone collision with non-driver accounts (admin / WE).
            // users.phone is UNIQUE — without this check the create() below would
            // throw a generic integrity constraint error.
            if (User::where('phone', $phone)->exists()) {
                abort(409, translate('driver_login.error_phone_conflict'));
            }

            $user = $this->autoRegisterDriver($phone, approved: $isTestPhone);
        } elseif ($isTestPhone && !$user->approved) {
            // Fixed QA/review test account — always auto-approved so it can
            // complete the login flow end-to-end without manual admin action.
            $user->update(['approved' => true]);
        }

        // 2. Approval gate moved to verifyCode so newly-created users can still
        //    verify their phone in this same flow. After verification they get
        //    a friendly "pending approval" message.

        // 3. WAHA session must be ready (skipped for the fixed QA test number)
        if (!$isTestPhone) {
            $this->requireSessionReady(translate('driver_login.error_service_unavailable'));
        }

        // 4. Per-phone resend throttle enforced at DB level
        $existing = PhoneVerification::where('phone', $phone)
            ->where('purpose', PhoneVerification::PURPOSE_DRIVER_LOGIN)
            ->first();

        if ($existing && !$existing->canResend()) {
            abort(429, translate('driver_login.error_rate_limit'));
        }

        // 5. Generate code, upsert row, send (test number gets a static code, no WhatsApp send)
        if ($isTestPhone) {
            $this->upsertVerification($phone, self::TEST_PHONE_CODE, $existing);
        } else {
            $code = $this->generateCode();
            $renderedMessage = str_replace(':code', $code, (string) translate('driver_login.wa_message'));

            $this->persistAndSend($phone, $code, $renderedMessage, $existing);
        }

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

        if ($phone === self::TEST_PHONE) {
            $this->upsertVerification($phone, self::TEST_PHONE_CODE, $verification);
        } else {
            $this->requireSessionReady(translate('driver_login.error_service_unavailable'));

            $code            = $this->generateCode();
            $renderedMessage = str_replace(':code', $code, (string) translate('driver_login.wa_message'));

            $this->persistAndSend($phone, $code, $renderedMessage, $verification);
        }

        return [
            'phone_masked'                => $this->maskPhone($phone),
            'expires_in_seconds'          => PhoneVerification::CODE_TTL_SECONDS,
            'resend_available_in_seconds' => PhoneVerification::RESEND_THROTTLE_SECONDS,
        ];
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Auto-register a driver whose phone is not yet in the system.
     * Placeholder name is "Водитель {last 4 digits}" — the driver can update it
     * later in their profile. Account is created unapproved (except the fixed
     * QA test number); admin must verify before they can pick up cargo.
     */
    private function autoRegisterDriver(string $phone, bool $approved = false): User
    {
        return User::create([
            'name'     => 'Водитель ' . substr($phone, -4),
            'phone'    => $phone,
            'email'    => null,
            'password' => null,
            'role'     => User::ROLE_DRIVER,
            'approved' => $approved,
        ]);
    }

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
        $this->upsertVerification($phone, $code, $existing);

        try {
            $this->whatsApp->sendOtp($phone, $code, $renderedMessage);
        } catch (\App\Exceptions\WhatsAppServiceException $e) {
            Log::warning('WAHA sendOtp failed during driver login', [
                'waha_status' => $e->getWahaStatusCode(),
            ]);
            abort(503, translate('driver_login.error_service_unavailable'));
        }
    }

    /**
     * Upsert the PhoneVerification row with a freshly hashed code, without sending anything.
     * Accepts an optional existing model to avoid a redundant query on resend.
     */
    private function upsertVerification(
        string $phone,
        string $code,
        ?PhoneVerification $existing = null,
    ): void {
        $now = now();

        $attributes = [
            'code_hash'    => Hash::make($code),
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
    }
}
