@extends('layouts.app')

@section('title', \App\Helpers\LocalizationHelper::t('auth.register_title') . ' — Silk Way')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">

        {{-- Logo + heading --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-indigo-600 rounded-2xl mb-5 shadow-lg shadow-indigo-500/30">
                <i class="fas fa-route text-white text-xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Silk Way</h1>
            <p class="mt-2 text-slate-500 text-sm">{{ \App\Helpers\LocalizationHelper::t('auth.register_desc') }}</p>

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

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">{{ \App\Helpers\LocalizationHelper::t('auth.register_heading') }}</h2>

            @if($errors->any())
            <div class="mb-5 flex items-start gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl">
                <i class="fas fa-exclamation-circle text-rose-500 mt-0.5 shrink-0"></i>
                <ul class="text-sm space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-5" x-data="{ role: '{{ old('role', '') }}' }">
                @csrf

                {{-- Full name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">
                        {{ \App\Helpers\LocalizationHelper::t('auth.full_name') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-user text-slate-400 text-sm"></i>
                        </div>
                        <input id="name" name="name" type="text" required autocomplete="name"
                               class="block w-full pl-10 pr-3 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-rose-400 ring-1 ring-rose-400 @enderror"
                               placeholder="{{ \App\Helpers\LocalizationHelper::t('auth.full_name_placeholder') }}"
                               value="{{ old('name') }}">
                    </div>
                </div>

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
                               class="block w-full pl-10 pr-3 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') border-rose-400 ring-1 ring-rose-400 @enderror"
                               placeholder="{{ \App\Helpers\LocalizationHelper::t('auth.email_placeholder') }}"
                               value="{{ old('email') }}">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">
                        {{ \App\Helpers\LocalizationHelper::t('auth.password') }}
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-slate-400 text-sm"></i>
                        </div>
                        <input id="password" name="password" :type="show ? 'text' : 'password'" required
                               class="block w-full pl-10 pr-10 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('password') border-rose-400 ring-1 ring-rose-400 @enderror"
                               placeholder="{{ \App\Helpers\LocalizationHelper::t('auth.password_placeholder') }}">
                        <button type="button"
                                @click="show = !show"
                                :aria-label="show ? '{{ \App\Helpers\LocalizationHelper::t('auth.password_hide') }}' : '{{ \App\Helpers\LocalizationHelper::t('auth.password_show') }}'"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none focus:text-indigo-600 transition-colors">
                            <i class="fas text-sm" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    <p class="mt-1.5 text-xs text-slate-400">Минимум 8 символов — используйте буквы и цифры</p>
                </div>

                {{-- Password confirm --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">
                        {{ \App\Helpers\LocalizationHelper::t('auth.password_confirmation') }}
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-slate-400 text-sm"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" :type="show ? 'text' : 'password'" required
                               class="block w-full pl-10 pr-10 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="{{ \App\Helpers\LocalizationHelper::t('auth.password_confirmation_placeholder') }}">
                        <button type="button"
                                @click="show = !show"
                                :aria-label="show ? '{{ \App\Helpers\LocalizationHelper::t('auth.password_hide') }}' : '{{ \App\Helpers\LocalizationHelper::t('auth.password_show') }}'"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none focus:text-indigo-600 transition-colors">
                            <i class="fas text-sm" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Role selector --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-3">
                        {{ \App\Helpers\LocalizationHelper::t('auth.role') }}
                    </label>
                    <input type="hidden" name="role" :value="role">
                    <div class="grid grid-cols-2 gap-3">
                        {{-- Warehouse card --}}
                        <button type="button"
                                @click="role = 'warehouse_employee'"
                                :class="role === 'warehouse_employee'
                                    ? 'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-500'
                                    : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="relative flex flex-col items-center p-4 rounded-xl border-2 transition-all cursor-pointer text-left">
                            <div :class="role === 'warehouse_employee' ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-500'"
                                 class="w-10 h-10 rounded-xl flex items-center justify-center mb-3 transition-colors">
                                <i class="fas fa-warehouse text-lg"></i>
                            </div>
                            <p :class="role === 'warehouse_employee' ? 'text-indigo-900' : 'text-slate-700'"
                               class="text-sm font-semibold text-center transition-colors">
                                {{ \App\Helpers\LocalizationHelper::t('auth.warehouse_employee') }}
                            </p>
                            <p class="text-xs text-slate-400 text-center mt-1">Создание грузов</p>
                            <div x-show="role === 'warehouse_employee'"
                                 class="absolute top-2 right-2 w-5 h-5 bg-indigo-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </button>

                        {{-- Driver card --}}
                        <button type="button"
                                @click="role = 'driver'"
                                :class="role === 'driver'
                                    ? 'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-500'
                                    : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="relative flex flex-col items-center p-4 rounded-xl border-2 transition-all cursor-pointer text-left">
                            <div :class="role === 'driver' ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-500'"
                                 class="w-10 h-10 rounded-xl flex items-center justify-center mb-3 transition-colors">
                                <i class="fas fa-truck text-lg"></i>
                            </div>
                            <p :class="role === 'driver' ? 'text-indigo-900' : 'text-slate-700'"
                               class="text-sm font-semibold text-center transition-colors">
                                {{ \App\Helpers\LocalizationHelper::t('auth.driver_role') }}
                            </p>
                            <p class="text-xs text-slate-400 text-center mt-1">Доставка грузов</p>
                            <div x-show="role === 'driver'"
                                 class="absolute top-2 right-2 w-5 h-5 bg-indigo-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </button>
                    </div>
                    @error('role')
                    <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full flex items-center justify-center py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-user-plus mr-2"></i>
                    {{ \App\Helpers\LocalizationHelper::t('auth.register_button') }}
                </button>
            </form>
        </div>

        {{-- Login link --}}
        <p class="text-center mt-6 text-sm text-slate-500">
            {{ \App\Helpers\LocalizationHelper::t('auth.have_account') }}
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                {{ \App\Helpers\LocalizationHelper::t('auth.login_link') }}
            </a>
        </p>
    </div>
</div>
@endsection
