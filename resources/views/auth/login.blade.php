@extends('layouts.app')

@section('title', \App\Helpers\LocalizationHelper::t('auth.login') . ' — Silk Way')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">

        {{-- Logo + heading --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-indigo-600 rounded-2xl mb-5 shadow-lg shadow-indigo-500/30">
                <i class="fas fa-route text-white text-xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Silk Way</h1>
            <p class="mt-2 text-slate-500 text-sm">{{ \App\Helpers\LocalizationHelper::t('welcome.system_title') }}</p>

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
            <h2 class="text-xl font-semibold text-slate-900 mb-6">{{ \App\Helpers\LocalizationHelper::t('auth.login') }}</h2>

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

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
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
                        <input id="password" name="password" :type="show ? 'text' : 'password'" required autocomplete="current-password"
                               class="block w-full pl-10 pr-10 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow"
                               placeholder="{{ \App\Helpers\LocalizationHelper::t('auth.password_placeholder') }}">
                        <button type="button"
                                @click="show = !show"
                                :aria-label="show ? '{{ \App\Helpers\LocalizationHelper::t('auth.password_hide') }}' : '{{ \App\Helpers\LocalizationHelper::t('auth.password_show') }}'"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none focus:text-indigo-600 transition-colors">
                            <i class="fas text-sm" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember me --}}
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                           class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="remember" class="ml-2 text-sm text-slate-600">Запомнить меня</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full flex items-center justify-center py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    {{ \App\Helpers\LocalizationHelper::t('auth.login_button') }}
                </button>
            </form>
        </div>

        {{-- Register link --}}
        <p class="text-center mt-6 text-sm text-slate-500">
            {{ \App\Helpers\LocalizationHelper::t('auth.no_account') }}
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                {{ \App\Helpers\LocalizationHelper::t('auth.register_link') }}
            </a>
        </p>
    </div>
</div>
@endsection
