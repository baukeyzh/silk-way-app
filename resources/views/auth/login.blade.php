@extends('layouts.app')

@section('title', \App\Helpers\LocalizationHelper::t('auth.login') . ' — Silk Way')

@section('content')
@php
    $driverLoginEnabled = (bool) config('features.web_driver_login', true);

    $activeTab = request()->query('type', $driverLoginEnabled ? 'driver' : 'warehouse');
    $activeTab = in_array($activeTab, ['driver', 'warehouse']) ? $activeTab : 'driver';

    // If driver login is disabled, force warehouse tab even if ?type=driver.
    if (!$driverLoginEnabled && $activeTab === 'driver') {
        $activeTab = 'warehouse';
    }

    $isDriver   = $activeTab === 'driver';
    $driverStep = session('driver_login_phone') ? 2 : 1;

    // Determine error banner variant
    $errorHeading  = null;
    $isExpiredCode = false;

    if ($errors->any()) {
        if ($errors->has('code')) {
            $errorHeading  = \App\Helpers\LocalizationHelper::t('auth.login_error_heading_wa_verify');
            $isExpiredCode = collect($errors->get('code'))->contains(fn($m) =>
                str_contains($m, 'истёк') || str_contains($m, 'expired')
            );
        } elseif ($isDriver && $driverStep === 1) {
            $errorHeading = \App\Helpers\LocalizationHelper::t('auth.login_error_heading_wa_send');
        } else {
            $errorHeading = \App\Helpers\LocalizationHelper::t('auth.login_error_heading_generic');
        }
    }

    $driverRegEnabled = (bool) config('features.web_driver_registration', true);
@endphp

<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">

        {{-- ── Logo + heading ───────────────────────────────────────────────── --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-indigo-600 rounded-2xl mb-5 shadow-lg shadow-indigo-500/30">
                <i class="fas fa-route text-white text-xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Silk Way</h1>
            <p class="mt-2 text-slate-500 text-sm">
                @if($isDriver && $driverStep === 2)
                    {{ \App\Helpers\LocalizationHelper::t('auth.login_card_heading_driver_step2') }}
                @elseif($isDriver)
                    {{ \App\Helpers\LocalizationHelper::t('driver_login.subtitle_step1') }}
                @else
                    {{ \App\Helpers\LocalizationHelper::t('welcome.system_title') }}
                @endif
            </p>

            {{-- Language switcher --}}
            <div class="mt-4 inline-flex items-center bg-slate-100 rounded-lg p-1 space-x-1">
                <a href="{{ request()->fullUrlWithQuery(['lang' => 'ru']) }}"
                   class="px-3 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'ru' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">RU</a>
                <a href="{{ request()->fullUrlWithQuery(['lang' => 'kz']) }}"
                   class="px-3 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'kz' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">KK</a>
                <a href="{{ request()->fullUrlWithQuery(['lang' => 'cn']) }}"
                   class="px-3 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'cn' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">中</a>
            </div>
        </div>

        {{-- ── Tab selector (hidden during driver step 2, or when driver login is disabled) --}}
        @if(!($isDriver && $driverStep === 2) && $driverLoginEnabled)
        <div class="grid grid-cols-2 gap-3 mb-6" role="tablist" aria-label="Тип входа">

            {{-- Driver tab --}}
            <a href="{{ route('login', ['type' => 'driver']) }}"
               role="tab"
               aria-selected="{{ $isDriver ? 'true' : 'false' }}"
               class="flex flex-col items-start gap-1 px-4 py-3.5 rounded-xl border-2 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                      {{ $isDriver
                           ? 'border-emerald-400 bg-emerald-50 shadow-sm'
                           : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50' }}">
                <div class="flex items-center gap-2">
                    <div class="flex items-center justify-center w-7 h-7 rounded-lg {{ $isDriver ? 'bg-emerald-500' : 'bg-slate-100' }}">
                        <i class="fab fa-whatsapp text-sm {{ $isDriver ? 'text-white' : 'text-slate-500' }}"></i>
                    </div>
                    <span class="text-sm font-semibold {{ $isDriver ? 'text-emerald-900' : 'text-slate-700' }}">
                        {{ \App\Helpers\LocalizationHelper::t('auth.login_tab_driver_label') }}
                    </span>
                </div>
                <p class="text-xs {{ $isDriver ? 'text-emerald-700' : 'text-slate-400' }} leading-tight pl-0.5">
                    {{ \App\Helpers\LocalizationHelper::t('auth.login_tab_driver_sub') }}
                </p>
            </a>

            {{-- Warehouse / Admin tab --}}
            <a href="{{ route('login', ['type' => 'warehouse']) }}"
               role="tab"
               aria-selected="{{ !$isDriver ? 'true' : 'false' }}"
               class="flex flex-col items-start gap-1 px-4 py-3.5 rounded-xl border-2 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                      {{ !$isDriver
                           ? 'border-indigo-400 bg-indigo-50 shadow-sm'
                           : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50' }}">
                <div class="flex items-center gap-2">
                    <div class="flex items-center justify-center w-7 h-7 rounded-lg {{ !$isDriver ? 'bg-indigo-600' : 'bg-slate-100' }}">
                        <i class="fas fa-user-tie text-sm {{ !$isDriver ? 'text-white' : 'text-slate-500' }}"></i>
                    </div>
                    <span class="text-sm font-semibold {{ !$isDriver ? 'text-indigo-900' : 'text-slate-700' }}">
                        {{ \App\Helpers\LocalizationHelper::t('auth.login_tab_warehouse_label') }}
                    </span>
                </div>
                <p class="text-xs {{ !$isDriver ? 'text-indigo-600' : 'text-slate-400' }} leading-tight pl-0.5">
                    {{ \App\Helpers\LocalizationHelper::t('auth.login_tab_warehouse_sub') }}
                </p>
            </a>

        </div>
        @endif

        {{-- ── Card ──────────────────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">

            {{-- Card heading --}}
            <h2 class="text-xl font-semibold text-slate-900 mb-6">
                @if($isDriver && $driverStep === 2)
                    {{ \App\Helpers\LocalizationHelper::t('auth.login_card_heading_driver_step2') }}
                @elseif($isDriver)
                    {{ \App\Helpers\LocalizationHelper::t('auth.login_card_heading_driver') }}
                @else
                    {{ \App\Helpers\LocalizationHelper::t('auth.login_card_heading') }}
                @endif
            </h2>

            {{-- Error banner --}}
            @if($errors->any())
            <div class="mb-6 rounded-xl border bg-rose-50 border-rose-200 px-4 py-4">
                <div class="flex items-start gap-3">
                    <div class="shrink-0 mt-0.5">
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-rose-100 rounded-full">
                            <i class="fas fa-exclamation-circle text-rose-600 text-xs"></i>
                        </span>
                    </div>
                    <div class="min-w-0 flex-1">
                        @if($errorHeading)
                        <p class="text-sm font-semibold text-rose-800 mb-1.5">{{ $errorHeading }}</p>
                        @endif

                        @if($isExpiredCode)
                        <p class="text-sm text-rose-700 mb-3">
                            {{ $errors->first('code') }}
                        </p>
                        <form action="{{ route('driver.login.resend') }}" method="POST">
                            @csrf
                            <input type="hidden" name="phone" value="{{ session('driver_login_phone') }}">
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                                <i class="fab fa-whatsapp"></i>
                                {{ \App\Helpers\LocalizationHelper::t('auth.expired_code_cta') }}
                            </button>
                        </form>
                        @else
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                            <li class="flex items-start gap-1.5 text-sm text-rose-700">
                                <i class="fas fa-times-circle text-rose-400 mt-0.5 shrink-0 text-xs"></i>
                                <span>{{ $error }}</span>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Code sent flash (driver step 1 success) --}}
            @if(session('driver_login_code_sent'))
            <div class="mb-5 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl">
                <i class="fab fa-whatsapp text-emerald-500 mt-0.5 shrink-0 text-base"></i>
                <p class="text-sm font-medium">{{ \App\Helpers\LocalizationHelper::t('driver_login.code_sent') }}</p>
            </div>
            @endif

            {{-- Code resent flash --}}
            @if(session('driver_login_code_resent'))
            <div class="mb-5 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl">
                <i class="fab fa-whatsapp text-emerald-500 mt-0.5 shrink-0 text-base"></i>
                <p class="text-sm font-medium">{{ \App\Helpers\LocalizationHelper::t('driver_login.code_resent') }}</p>
            </div>
            @endif

            {{-- ================================================================
                 DRIVER FLOW
                 ================================================================ --}}
            @if($isDriver)

            @if($driverStep === 1)
            {{-- ── Driver Step 1: Phone input ──────────────────────────────── --}}
            <form action="{{ route('driver.login.request') }}" method="POST" class="space-y-5"
                  x-data="{ submitting: false }" @submit="submitting = true">
                @csrf

                <div>
                    <label for="driver_phone" class="block text-sm font-medium text-slate-700 mb-1.5">
                        {{ \App\Helpers\LocalizationHelper::t('auth.phone') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-slate-400 text-sm"></i>
                        </div>
                        <input id="driver_phone" name="phone" type="tel" required autocomplete="tel"
                               class="block w-full pl-10 pr-3 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow @error('phone') border-rose-400 ring-1 ring-rose-400 @enderror"
                               placeholder="+7 700 123 4567"
                               value="{{ old('phone') }}">
                    </div>
                    <p class="mt-1.5 text-xs text-slate-400">
                        {{ \App\Helpers\LocalizationHelper::t('driver_login.phone_hint') }}
                    </p>
                    @error('phone')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        :disabled="submitting"
                        class="w-full flex items-center justify-center py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    <i class="fab fa-whatsapp mr-2" x-show="!submitting"></i>
                    <i class="fas fa-spinner fa-spin mr-2" x-show="submitting" x-cloak></i>
                    <span x-show="!submitting">{{ \App\Helpers\LocalizationHelper::t('driver_login.request_button') }}</span>
                    <span x-show="submitting" x-cloak>{{ \App\Helpers\LocalizationHelper::t('auth.sending_code') }}</span>
                </button>
            </form>

            @else
            {{-- ── Driver Step 2: OTP verification ────────────────────────── --}}

            {{-- Masked phone info banner with change-number link --}}
            <div class="mb-6 flex items-center justify-between gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl">
                <div class="flex items-center gap-2 min-w-0">
                    <i class="fab fa-whatsapp text-emerald-500 shrink-0"></i>
                    <span class="text-sm font-medium truncate">
                        {{ \App\Helpers\LocalizationHelper::t('driver_login.code_sent_to') }}
                        <span class="font-bold">{{ session('driver_login_phone_masked') ?: session('driver_login_phone') }}</span>
                    </span>
                </div>
                <a href="{{ route('login', ['type' => 'driver', 'reset' => 1]) }}"
                   class="text-xs font-semibold text-emerald-700 hover:text-emerald-900 transition-colors whitespace-nowrap shrink-0">
                    {{ \App\Helpers\LocalizationHelper::t('driver_login.change_number') }}
                </a>
            </div>

            <form action="{{ route('driver.login.verify') }}" method="POST" class="space-y-5"
                  x-data="{ submitting: false }" @submit="submitting = true">
                @csrf
                <input type="hidden" name="phone" value="{{ session('driver_login_phone') }}">

                <div>
                    <label for="code" class="block text-sm font-medium text-slate-700 mb-1.5">
                        {{ \App\Helpers\LocalizationHelper::t('driver_login.code_label') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-hashtag text-slate-400 text-sm"></i>
                        </div>
                        <input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code"
                               maxlength="6" pattern="\d{6}" required
                               class="block w-full pl-10 pr-3 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow tracking-widest font-mono @error('code') border-rose-400 ring-1 ring-rose-400 @enderror"
                               placeholder="000000"
                               value="{{ old('code') }}">
                    </div>
                    <p class="mt-1.5 text-xs text-slate-400">
                        {{ \App\Helpers\LocalizationHelper::t('driver_login.code_hint') }}
                    </p>
                    @error('code')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        :disabled="submitting"
                        class="w-full flex items-center justify-center py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    <i class="fas fa-sign-in-alt mr-2" x-show="!submitting"></i>
                    <i class="fas fa-spinner fa-spin mr-2" x-show="submitting" x-cloak></i>
                    <span x-show="!submitting">{{ \App\Helpers\LocalizationHelper::t('driver_login.verify_button') }}</span>
                    <span x-show="submitting" x-cloak>{{ \App\Helpers\LocalizationHelper::t('auth.verifying') }}</span>
                </button>
            </form>

            {{-- Resend block with 60s Alpine countdown --}}
            <div class="mt-6 pt-6 border-t border-slate-100" x-data="{
                countdown: 60,
                init() {
                    let i = setInterval(() => {
                        if (this.countdown <= 1) { clearInterval(i); }
                        this.countdown--;
                    }, 1000);
                }
            }">
                <p class="text-sm text-slate-500 text-center mb-3">
                    {{ \App\Helpers\LocalizationHelper::t('driver_login.resend_prompt') }}
                </p>

                <p x-show="countdown > 0" class="text-center text-xs text-slate-400">
                    {{ \App\Helpers\LocalizationHelper::t('driver_login.resend_available_in') }}
                    <span class="font-semibold text-slate-600" x-text="countdown"></span>
                    сек.
                </p>

                <form x-show="countdown <= 0"
                      action="{{ route('driver.login.resend') }}"
                      method="POST"
                      x-data="{ submitting: false }" @submit="submitting = true">
                    @csrf
                    <input type="hidden" name="phone" value="{{ session('driver_login_phone') }}">
                    <button type="submit"
                            :disabled="submitting"
                            class="w-full flex items-center justify-center py-2.5 px-4 bg-white border border-slate-300 hover:border-emerald-400 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 disabled:opacity-60 disabled:cursor-not-allowed text-sm font-semibold rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        <i class="fab fa-whatsapp mr-2 text-emerald-500" x-show="!submitting"></i>
                        <i class="fas fa-spinner fa-spin mr-2 text-slate-400" x-show="submitting" x-cloak></i>
                        <span x-show="!submitting">{{ \App\Helpers\LocalizationHelper::t('driver_login.resend_button') }}</span>
                        <span x-show="submitting" x-cloak>{{ \App\Helpers\LocalizationHelper::t('auth.sending_code') }}</span>
                    </button>
                </form>
            </div>

            @endif {{-- driverStep --}}

            {{-- ================================================================
                 WAREHOUSE / ADMIN FLOW
                 ================================================================ --}}
            @else

            <form action="{{ route('login') }}" method="POST" class="space-y-5"
                  x-data="{ submitting: false }" @submit="submitting = true">
                @csrf

                @if(request('redirect'))
                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                @endif

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">
                        {{ \App\Helpers\LocalizationHelper::t('auth.email') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-400 text-sm"></i>
                        </div>
                        <input id="email" name="email" type="email" required autocomplete="email"
                               class="block w-full pl-10 pr-3 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow @error('email') border-rose-400 ring-1 ring-rose-400 @enderror"
                               placeholder="{{ \App\Helpers\LocalizationHelper::t('auth.email_placeholder') }}"
                               value="{{ old('email') }}">
                    </div>
                    @error('email')
                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password with eye toggle --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">
                        {{ \App\Helpers\LocalizationHelper::t('auth.password') }}
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-slate-400 text-sm"></i>
                        </div>
                        <input id="password" name="password" :type="show ? 'text' : 'password'" required autocomplete="current-password"
                               class="block w-full pl-10 pr-10 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow @error('password') border-rose-400 ring-1 ring-rose-400 @enderror"
                               placeholder="{{ \App\Helpers\LocalizationHelper::t('auth.password_placeholder') }}">
                        <button type="button"
                                @click="show = !show"
                                :aria-label="show ? '{{ \App\Helpers\LocalizationHelper::t('auth.password_hide') }}' : '{{ \App\Helpers\LocalizationHelper::t('auth.password_show') }}'"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none focus:text-indigo-600 transition-colors">
                            <i class="fas text-sm" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password')
                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                           class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="remember" class="ml-2 text-sm text-slate-600">Запомнить меня</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        :disabled="submitting"
                        class="w-full flex items-center justify-center py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-sign-in-alt mr-2" x-show="!submitting"></i>
                    <i class="fas fa-spinner fa-spin mr-2" x-show="submitting" x-cloak></i>
                    <span x-show="!submitting">{{ \App\Helpers\LocalizationHelper::t('auth.login_button') }}</span>
                    <span x-show="submitting" x-cloak>{{ \App\Helpers\LocalizationHelper::t('auth.login_submitting') }}</span>
                </button>
            </form>

            @endif {{-- isDriver --}}

        </div>

        {{-- ── Footer link — matches the active tab ────────────────────────── --}}
        <div class="mt-6 text-center">
            @if($isDriver)
                <p class="text-sm text-slate-500">
                    {{ \App\Helpers\LocalizationHelper::t('driver_login.no_account') }}
                    @if($driverRegEnabled)
                        <a href="{{ route('register', ['type' => 'driver']) }}" class="font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                            {{ \App\Helpers\LocalizationHelper::t('driver_login.register_link') }}
                        </a>
                    @else
                        <a href="{{ route('register', ['type' => 'warehouse']) }}" class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                            {{ \App\Helpers\LocalizationHelper::t('auth.register_link') }}
                        </a>
                    @endif
                </p>
            @else
                <p class="text-sm text-slate-500">
                    {{ \App\Helpers\LocalizationHelper::t('auth.no_account') }}
                    <a href="{{ route('register', ['type' => 'warehouse']) }}" class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                        {{ \App\Helpers\LocalizationHelper::t('auth.register_link') }}
                    </a>
                </p>
            @endif
        </div>

    </div>
</div>
@endsection
