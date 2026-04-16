<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): mixed  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        // Try each specified guard (e.g. auth:sanctum passes 'sanctum' as guard)
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::shouldUse($guard);
                return $next($request);
            }
        }

        // No guards specified — fall back to default guard
        if (empty($guards) && Auth::check()) {
            return $next($request);
        }

        Log::warning('User not authenticated', [
            'url'    => $request->url(),
            'guards' => $guards,
        ]);

        // API requests → 401 JSON
        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json(['message' => 'Не авторизован.'], 401);
        }

        return redirect()->route('login');
    }
}
