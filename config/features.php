<?php

/*
|--------------------------------------------------------------------------
| Feature Flags
|--------------------------------------------------------------------------
|
| Toggle optional features at the deployment level. All flags default to
| safe values so the app works out-of-the-box; set env vars to override.
|
*/

return [

    /*
     * WhatsApp OTP driver registration via the web UI.
     *
     * When false:
     *   - The /register page hides the "driver" tab entirely
     *   - Direct POSTs to /register/driver/* return 404
     *
     * The API endpoints (/api/v1/auth/driver/register/*) are NOT gated by
     * this flag — mobile clients continue to work regardless. This flag
     * only controls the web self-service path.
     */
    'web_driver_registration' => env('WEB_DRIVER_REGISTRATION_ENABLED', true),

    /*
     * WhatsApp OTP driver login via the web UI.
     *
     * When false:
     *   - The /login page hides the "driver" tab
     *   - Direct POSTs to /login/driver/* return 404
     *
     * The API endpoints (/api/v1/auth/driver/login/*) are NOT gated.
     */
    'web_driver_login' => env('WEB_DRIVER_LOGIN_ENABLED', true),

];
