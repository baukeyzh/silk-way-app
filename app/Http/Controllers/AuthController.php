<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Проверяем, подтвержден ли аккаунт (кроме администраторов)
            if (!$user->isAdmin() && !$user->isApproved()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Ваш аккаунт еще не подтвержден администратором. Ожидайте подтверждения.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('cargo.index'));
        }

        return back()->withErrors([
            'email' => 'Неверные учетные данные.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:warehouse_employee,driver',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'approved' => false, // По умолчанию не подтвержден
        ]);

        return redirect()->route('login')->with('success', 
            'Регистрация успешна! Ваш аккаунт будет активирован администратором в ближайшее время.'
        );
    }
}
