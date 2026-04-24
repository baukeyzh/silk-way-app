<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Swagger-php 6 emits E_USER_WARNING which Laravel converts to ErrorException.
        // Suppress only swagger-php warnings so generation doesn't crash.
        if ($this->app->runningInConsole()) {
            $previous = set_error_handler(null);
            set_error_handler(
                function (int $errno, string $errstr, string $errfile = '') use ($previous): bool {
                    if ($errno === E_USER_WARNING && str_contains($errfile, 'swagger-php')) {
                        return true;
                    }
                    if ($previous) {
                        return (bool) $previous($errno, $errstr, $errfile);
                    }
                    return false;
                }
            );
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('cargo-guest', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });

        RateLimiter::for('cargo-auth', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?: $request->ip());
        });

        // Rate limiter for the WhatsApp driver registration API endpoints.
        // Caps total requests per IP to prevent automated abuse.
        // Per-phone 60s throttle is enforced separately in DriverRegistrationService
        // via the last_sent_at column — this limiter is IP-scoped only.
        RateLimiter::for('waha-driver-register', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        // Rate limiter for the WhatsApp driver login API endpoints.
        // Slightly more permissive than registration (20/min vs 10/min) — legitimate
        // users can retry on wrong codes without being locked out at the IP level.
        // Per-phone 60s throttle is still enforced in DriverLoginService via last_sent_at.
        RateLimiter::for('waha-driver-login', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });
    }
}
