<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\FirebaseServiceException;
use App\Models\FcmToken;
use App\Models\User;
use Kreait\Firebase\Exception\Messaging\InvalidArgument;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MessageData;
use Kreait\Firebase\Messaging\MulticastSendReport;
use Kreait\Firebase\Messaging\Notification;
use Throwable;

class FirebaseService
{
    /**
     * Send a push notification to a single FCM registration token.
     *
     * UNREGISTERED (NotFound) and INVALID_ARGUMENT (InvalidArgument) failures
     * indicate the token is no longer valid — we purge it and swallow silently
     * so the caller is not forced to handle routine token churn.
     *
     * @param array<string, mixed> $data
     * @throws FirebaseServiceException on transport or server errors
     */
    public function sendToToken(string $token, string $title, string $body, array $data = []): void
    {
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body))
            ->withData(MessageData::fromArray($this->stringifyData($data)));

        try {
            app('firebase.messaging')->send($message);
        } catch (NotFound | InvalidArgument) {
            // Token is no longer registered or was never valid — remove it.
            FcmToken::where('token', $token)->delete();
        } catch (MessagingException $e) {
            throw new FirebaseServiceException(
                'FCM send failed: ' . $e->getMessage(),
                (string) ($e->errors()[0] ?? ''),
            );
        } catch (Throwable $e) {
            throw new FirebaseServiceException('FCM transport error: ' . $e->getMessage());
        }
    }

    /**
     * Send a push notification to all registered devices of a user.
     *
     * Returns the number of devices the message was successfully delivered to.
     *
     * @param array<string, mixed> $data
     * @throws FirebaseServiceException on transport or server errors
     */
    public function sendToUser(User $user, string $title, string $body, array $data = []): int
    {
        $tokens = $user->fcmTokens()->pluck('token');

        $delivered = 0;

        foreach ($tokens as $token) {
            try {
                $this->sendToToken($token, $title, $body, $data);
                $delivered++;
            } catch (FirebaseServiceException) {
                // Transport errors on individual tokens are collected; caller
                // can check the return count to detect partial delivery.
            }
        }

        return $delivered;
    }

    /**
     * Send a push notification to many tokens in a single multicast request.
     *
     * Automatically removes tokens that FCM reports as unknown or invalid.
     *
     * @param list<string>         $tokens
     * @param array<string, mixed> $data
     * @throws FirebaseServiceException on transport or server errors
     */
    public function sendToTokens(array $tokens, string $title, string $body, array $data = []): MulticastSendReport
    {
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData(MessageData::fromArray($this->stringifyData($data)));

        try {
            $report = app('firebase.messaging')->sendMulticast($message, $tokens);
        } catch (MessagingException $e) {
            throw new FirebaseServiceException(
                'FCM multicast failed: ' . $e->getMessage(),
                (string) ($e->errors()[0] ?? ''),
            );
        } catch (Throwable $e) {
            throw new FirebaseServiceException('FCM transport error: ' . $e->getMessage());
        }

        // Purge tokens FCM tells us are dead — unknownTokens covers UNREGISTERED,
        // invalidTokens covers malformed tokens (INVALID_ARGUMENT).
        $staleTokens = array_merge($report->unknownTokens(), $report->invalidTokens());

        if ($staleTokens !== []) {
            FcmToken::whereIn('token', $staleTokens)->delete();
        }

        return $report;
    }

    /**
     * FCM data payloads require all values to be strings.
     *
     * @param array<string, mixed> $data
     * @return array<string, string>
     */
    private function stringifyData(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $result[(string) $key] = (string) $value;
        }

        return $result;
    }
}
