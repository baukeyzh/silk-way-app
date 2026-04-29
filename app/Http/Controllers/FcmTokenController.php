<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\FcmToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FcmTokenController extends Controller
{
    /**
     * Register (or transfer ownership of) an FCM token for the authenticated
     * web user. Called silently on page load when Notification.permission is
     * already 'granted' — no user gesture required at this point.
     *
     * Upsert keyed on `token` alone: the same browser always produces the same
     * FCM registration token, so if user A logs out and user B logs in we just
     * flip user_id over to B rather than creating a duplicate row.
     */
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'token'    => 'required|string|min:32|max:512',
            'platform' => 'sometimes|string|in:android,ios,web',
        ]);

        $fcmToken = FcmToken::updateOrCreate(
            ['token' => $data['token']],
            [
                'user_id'      => $request->user()->id,
                'platform'     => $data['platform'] ?? 'web',
                'last_used_at' => now(),
            ],
        );

        return response()->json(['ok' => true, 'id' => $fcmToken->id]);
    }

    /**
     * Unregister an FCM token for the authenticated web user.
     *
     * Scoped by (user_id, token) so users cannot delete each other's tokens.
     * Idempotent — returns ok:true even if the token was never registered.
     */
    public function unregister(Request $request): JsonResponse
    {
        $data = $request->validate([
            'token' => 'required|string|min:32|max:512',
        ]);

        FcmToken::where('user_id', $request->user()->id)
            ->where('token', $data['token'])
            ->delete();

        return response()->json(['ok' => true]);
    }
}
