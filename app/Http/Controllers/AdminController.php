<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        // Проверяем права администратора
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }
        
        $pendingUsers = User::pendingApproval()->latest()->get();
        $approvedUsers = User::approved()->latest()->get();
        // Use inProgress() (status = 'in_progress') as the authoritative count rather than
        // pickedUp() (picked_by IS NOT NULL). Both return the same count today, but status is
        // the single source of truth — pickedUp() is a legacy proxy that would diverge if a
        // cargo is ever re-listed after the driver changes. The 'picked_up' key is kept for
        // backward compatibility with the dashboard Blade view until a view-layer pass is done.
        $inProgressCount = \App\Models\Cargo::inProgress()->count();
        $cargoStats = [
            'total'       => \App\Models\Cargo::count(),
            'available'   => \App\Models\Cargo::available()->count(),
            'in_progress' => $inProgressCount,
            'picked_up'   => $inProgressCount, // alias for Blade view (TODO: update view in next batch)
        ];

        return view('admin.dashboard', compact('pendingUsers', 'approvedUsers', 'cargoStats'));
    }

    public function users(): View
    {
        // Проверяем права администратора
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }
        
        $users = User::latest()->get();
        return view('admin.users', compact('users'));
    }

    public function approveUser(User $user): RedirectResponse
    {
        // Проверяем права администратора
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }

        $user->update(['approved' => true]);

        $this->notifyDriverApproved($user);

        return redirect()->back()->with('success', "Пользователь {$user->name} подтвержден!");
    }

    public function rejectUser(User $user): RedirectResponse
    {
        // Проверяем права администратора
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }
        
        $user->delete();
        
        return redirect()->back()->with('success', "Пользователь {$user->name} отклонен и удален!");
    }

    public function toggleUserApproval(User $user): RedirectResponse
    {
        // Проверяем права администратора
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }

        $wasApproved = $user->approved;
        $user->update(['approved' => ! $wasApproved]);

        // Only fire the notification on the false → true transition.
        if (! $wasApproved && $user->approved) {
            $this->notifyDriverApproved($user);
        }

        $status = $user->approved ? 'подтвержден' : 'отклонен';
        return redirect()->back()->with('success', "Пользователь {$user->name} {$status}!");
    }

    /**
     * Sends a WhatsApp approval notification to a driver.
     * Fires silently — if WAHA is down the admin action still succeeds.
     */
    private function notifyDriverApproved(User $user): void
    {
        if (! $user->isDriver() || empty($user->phone)) {
            return;
        }

        try {
            $message = translate('notifications.driver_approved');
            app(WhatsAppService::class)->sendNotification($user->phone, $message);
        } catch (\Throwable $e) {
            Log::warning('Driver approval WhatsApp notification failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    public function deleteUser(User $user): RedirectResponse
    {
        // Проверяем права администратора
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }
        
        if ($user->isAdmin() && $user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Нельзя удалить самого себя!');
        }

        $user->delete();
        
        return redirect()->back()->with('success', "Пользователь {$user->name} удален!");
    }
}
