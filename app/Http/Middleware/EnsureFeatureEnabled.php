<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Generic feature-flag gate. Use as `feature:{flag_name}` on routes to block
 * access with 404 when the flag at `config/features.php:{flag_name}` is false.
 *
 * Example:
 *   Route::middleware('feature:web_driver_login')->group(...)
 */
class EnsureFeatureEnabled
{
    public function handle(Request $request, Closure $next, string $flag): Response
    {
        abort_unless((bool) config("features.{$flag}", true), 404);
        return $next($request);
    }
}
