<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): mixed  $next
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        // Поддерживаем несколько ролей через запятую
        $allowedRoles = array_map('trim', explode(',', $role));
        
        Log::info('CheckRole middleware called', [
            'user_id' => $request->user()?->id,
            'user_role' => $request->user()?->role,
            'required_roles' => $allowedRoles,
            'url' => $request->url()
        ]);

        if (!$request->user() || !$this->userHasAnyRole($request->user(), $allowedRoles)) {
            Log::warning('Access denied in CheckRole middleware', [
                'user_id' => $request->user()?->id,
                'user_role' => $request->user()?->role,
                'required_roles' => $allowedRoles,
                'url' => $request->url()
            ]);
            abort(403, 'Доступ запрещен');
        }

        Log::info('CheckRole middleware passed', [
            'user_id' => $request->user()->id,
            'user_role' => $request->user()->role,
            'required_roles' => $allowedRoles,
            'url' => $request->url()
        ]);

        return $next($request);
    }

    /**
     * Проверяет, есть ли у пользователя хотя бы одна из указанных ролей
     */
    private function userHasAnyRole($user, array $roles): bool
    {
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return true;
            }
        }
        return false;
    }
}
