<?php

namespace App\Providers;

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
        //
    }
}
