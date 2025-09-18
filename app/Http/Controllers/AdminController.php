<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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
        $cargoStats = [
            'total' => \App\Models\Cargo::count(),
            'available' => \App\Models\Cargo::available()->count(),
            'picked_up' => \App\Models\Cargo::pickedUp()->count(),
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
        
        $user->update(['approved' => !$user->approved]);
        
        $status = $user->approved ? 'подтвержден' : 'отклонен';
        return redirect()->back()->with('success', "Пользователь {$user->name} {$status}!");
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
