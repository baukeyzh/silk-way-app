<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // WAHA — self-hosted WhatsApp HTTP API
    // Required env vars: WAHA_URL, WAHA_API_KEY, WA_SESSION (optional, defaults to "default")
    'waha' => [
        'url'     => env('WAHA_URL', 'https://wa.fruck.kz'),
        'api_key' => env('WAHA_API_KEY'),
        'session' => env('WA_SESSION', 'default'),
    ],

    // Firebase web client config — values are intentionally public (Firebase web SDK
    // ships them in the JS bundle). vapid_key comes from Firebase Console →
    // Project Settings → Cloud Messaging → Web Push certificates.
    'firebase_web' => [
        'vapid_key' => env('FIREBASE_WEB_VAPID_KEY'),
    ],

];
