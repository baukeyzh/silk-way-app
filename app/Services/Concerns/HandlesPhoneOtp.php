<?php

declare(strict_types=1);

namespace App\Services\Concerns;

use App\Exceptions\WhatsAppServiceException;
use App\Models\PhoneVerification;
use App\Services\WhatsAppService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Shared OTP primitives reused by DriverRegistrationService and DriverLoginService.
 *
 * Classes using this trait MUST declare:
 *   private readonly WhatsAppService $whatsApp;
 */
trait HandlesPhoneOtp
{
    /**
     * Strip all non-digit characters from a phone string.
     * "+7 (700) 120-00-13" → "77001200013"
     */
    public function normalizePhone(string $phone): string
    {
        return preg_replace('/\D/', '', $phone) ?? '';
    }

    /**
     * Return a zero-padded random 6-digit code.
     */
    private function generateCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Assert the WAHA session is WORKING; abort(503) otherwise.
     */
    private function requireSessionReady(string $unavailableMessage): void
    {
        try {
            $ready = $this->whatsApp->isSessionReady();
        } catch (WhatsAppServiceException $e) {
            Log::warning('WAHA session check threw exception', ['waha_status' => $e->getWahaStatusCode()]);
            abort(503, $unavailableMessage);
        }

        if (!$ready) {
            abort(503, $unavailableMessage);
        }
    }

    /**
     * Upsert a PhoneVerification row and send the OTP via WhatsApp.
     *
     * @return array{phone: string, expires_in_seconds: int, resend_available_in_seconds: int}
     */
    private function upsertAndSend(
        string $phone,
        string $purpose,
        string $unavailableMessage,
        string $waMessage,
    ): array {
        $code = $this->generateCode();
        $now  = Carbon::now();

        PhoneVerification::updateOrCreate(
            ['phone' => $phone, 'purpose' => $purpose],
            [
                'code_hash'    => Hash::make($code),
                'attempts'     => 0,
                'expires_at'   => $now->copy()->addSeconds(PhoneVerification::CODE_TTL_SECONDS),
                'last_sent_at' => $now,
            ]
        );

        try {
            $this->whatsApp->sendOtp($phone, $code, $waMessage);
        } catch (WhatsAppServiceException $e) {
            Log::warning('WAHA sendOtp failed', [
                'purpose'     => $purpose,
                'waha_status' => $e->getWahaStatusCode(),
            ]);
            abort(503, $unavailableMessage);
        }

        return [
            'phone'                       => $phone,
            'expires_in_seconds'          => PhoneVerification::CODE_TTL_SECONDS,
            'resend_available_in_seconds' => PhoneVerification::RESEND_THROTTLE_SECONDS,
        ];
    }

    /**
     * Produce a masked phone string for display, e.g. "+7 ••• ••• **-67".
     * Works for 11-digit KZ/RU numbers (7XXXXXXXXXX).
     * Falls back to partial masking for other lengths.
     */
    public function maskPhone(string $phone): string
    {
        $digits = $this->normalizePhone($phone);
        $len    = strlen($digits);

        if ($len === 11) {
            // "77001234567" → "+7 ••• ••• **-67"
            return '+' . $digits[0] . ' ••• ••• **-' . substr($digits, -2);
        }

        // Generic fallback: show only last 2 digits
        return str_repeat('•', max(0, $len - 2)) . substr($digits, -2);
    }
}
