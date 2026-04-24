<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(Request $request): View|RedirectResponse
    {
        // ?reset=1 from the "Изменить номер" link — clear OTP-flow session keys
        // and bounce to a clean URL so a refresh doesn't re-trigger the reset.
        if ($request->query('reset') === '1') {
            session()->forget([
                'driver_login_phone',
                'driver_login_phone_masked',
                'driver_reg_phone',
                'driver_reg_name',
            ]);
            return redirect()->route('login', array_filter([
                'type' => $request->query('type'),
            ]));
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Detect driver accounts before Auth::attempt() to avoid timing oracles
        // and to surface a helpful redirect regardless of whether a password
        // happens to match. Drivers always authenticate via WhatsApp OTP only.
        $targetUser = \App\Models\User::where('email', $credentials['email'])->first();
        if ($targetUser && $targetUser->isDriver()) {
            return back()->withErrors([
                'email' => translate('auth.driver_use_whatsapp_login'),
            ])->onlyInput('email');
        }

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

            $redirect = $request->input('redirect');
            if (is_string($redirect) && str_starts_with($redirect, '/') && !str_starts_with($redirect, '//')) {
                return redirect($redirect);
            }

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

    public function showRegister(Request $request): View|RedirectResponse
    {
        // ?reset=1 from the "Изменить номер" link — clear OTP-flow session keys.
        if ($request->query('reset') === '1') {
            session()->forget([
                'driver_reg_phone',
                'driver_reg_name',
                'driver_login_phone',
                'driver_login_phone_masked',
            ]);
            return redirect()->route('register', array_filter([
                'type' => $request->query('type'),
            ]));
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        // Reject driver role submissions before running full validation — drivers
        // must use the WhatsApp OTP path. Check raw input to avoid leaking timing
        // information from the validator on an otherwise-valid payload.
        if ($request->input('role') === User::ROLE_DRIVER) {
            return back()
                ->withErrors(['role' => translate('auth.driver_use_whatsapp_register')])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => ['required', Rule::in([User::ROLE_WAREHOUSE_EMPLOYEE])],
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'approved' => false,
        ]);

        return redirect()->route('login')->with('success',
            'Регистрация успешна! Ваш аккаунт будет активирован администратором в ближайшее время.'
        );
    }
}
