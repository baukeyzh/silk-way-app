<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\WhatsAppServiceException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $baseUrl;
    private string $apiKey;
    private string $session;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.waha.url', ''), '/');
        $this->apiKey  = (string) config('services.waha.api_key', '');
        $this->session = (string) config('services.waha.session', 'default');
    }

    /**
     * Returns true only when the WAHA session status is exactly "WORKING".
     *
     * @throws WhatsAppServiceException on transport or server error
     */
    public function isSessionReady(): bool
    {
        $response = Http::withHeaders(['X-Api-Key' => $this->apiKey])
            ->timeout(15)
            ->get("{$this->baseUrl}/api/sessions/{$this->session}");

        if ($response->failed()) {
            Log::warning('WhatsApp session status check failed', [
                'status' => $response->status(),
                // Never log the body verbatim — it may contain sensitive WAHA internals.
                'url'    => "{$this->baseUrl}/api/sessions/{$this->session}",
            ]);
            throw new WhatsAppServiceException(
                'WAHA session check failed',
                $response->status(),
                $response->body(),
            );
        }

        return ($response->json('status') ?? '') === 'WORKING';
    }

    /**
     * Returns true when the phone number is registered on WhatsApp.
     *
     * @param string $phone digits only, e.g. "77001234567"
     * @throws WhatsAppServiceException on transport or server error
     */
    public function checkNumberExists(string $phone): bool
    {
        $response = Http::withHeaders(['X-Api-Key' => $this->apiKey])
            ->timeout(15)
            ->get("{$this->baseUrl}/api/{$this->session}/contacts/check-exists", [
                'phone' => $phone,
            ]);

        if ($response->failed()) {
            Log::warning('WhatsApp check-exists failed', [
                'status' => $response->status(),
            ]);
            throw new WhatsAppServiceException(
                'WAHA check-exists failed',
                $response->status(),
                $response->body(),
            );
        }

        return (bool) ($response->json('numberExists') ?? false);
    }

    /**
     * Sends a free-form notification text to the given phone via WhatsApp.
     *
     * Unlike sendOtp(), this method has no OTP-specific concerns (no code parameter,
     * no OTP template lookup). Use it for transactional messages such as account
     * approval confirmations.
     *
     * Callers MUST wrap this in try/catch — a WhatsAppServiceException here should
     * never block the primary business action (e.g. user approval).
     *
     * @param string $phone   digits only, e.g. "77001234567"
     * @param string $message fully-rendered message text
     * @throws WhatsAppServiceException on transport or server error
     */
    public function sendNotification(string $phone, string $message): bool
    {
        $chatId = "{$phone}@c.us";

        $response = Http::withHeaders(['X-Api-Key' => $this->apiKey])
            ->timeout(15)
            ->post("{$this->baseUrl}/api/sendText", [
                'session' => $this->session,
                'chatId'  => $chatId,
                'text'    => $message,
            ]);

        if ($response->failed()) {
            Log::warning('WhatsApp sendNotification failed', [
                'status' => $response->status(),
                'phone'  => substr($phone, 0, 4) . '****',
            ]);
            throw new WhatsAppServiceException(
                'WAHA sendText (notification) failed',
                $response->status(),
                $response->body(),
            );
        }

        return true;
    }

    /**
     * Sends a 6-digit OTP to the given phone via WhatsApp.
     *
     * @param string      $phone          digits only, e.g. "77001234567"
     * @param string      $code           6-digit numeric code
     * @param string|null $messageOverride pre-rendered message; if null uses driver_reg.wa_message template
     * @throws WhatsAppServiceException on transport or server error
     */
    public function sendOtp(string $phone, string $code, ?string $messageOverride = null): bool
    {
        $chatId  = "{$phone}@c.us";
        $message = $messageOverride ?? str_replace(':code', $code, (string) translate('driver_reg.wa_message'));

        $response = Http::withHeaders(['X-Api-Key' => $this->apiKey])
            ->timeout(15)
            ->post("{$this->baseUrl}/api/sendText", [
                'session' => $this->session,
                'chatId'  => $chatId,
                'text'    => $message,
            ]);

        if ($response->failed()) {
            // Log without the code value — codes are sensitive
            Log::warning('WhatsApp sendText failed', [
                'status' => $response->status(),
                'phone'  => substr($phone, 0, 4) . '****', // partial for debugging
            ]);
            throw new WhatsAppServiceException(
                'WAHA sendText failed',
                $response->status(),
                $response->body(),
            );
        }

        return true;
    }
}
