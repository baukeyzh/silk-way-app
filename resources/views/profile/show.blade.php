@extends('layouts.app')

@section('title', \App\Helpers\LocalizationHelper::t('profile.title'))

@section('content')
@php
    $isDriver = $user->isDriver();
    $isAdmin  = $user->isAdmin();
    $isWE     = $user->isWarehouseEmployee();

    $initial = mb_strtoupper(mb_substr($user->name, 0, 1, 'UTF-8'), 'UTF-8');

    $isPlaceholderName = $isDriver && str_starts_with($user->name, 'Водитель ');

    $roleBadge = match(true) {
        $isAdmin => ['class' => 'bg-purple-100 text-purple-700', 'icon' => 'fa-user-shield', 'label' => \App\Helpers\LocalizationHelper::t('profile.role_admin')],
        $isWE    => ['class' => 'bg-indigo-100 text-indigo-700',  'icon' => 'fa-warehouse',   'label' => \App\Helpers\LocalizationHelper::t('profile.role_warehouse')],
        default  => ['class' => 'bg-emerald-100 text-emerald-700','icon' => 'fa-truck',        'label' => \App\Helpers\LocalizationHelper::t('profile.role_driver')],
    };

    $avatarRing = $isAdmin ? 'ring-purple-200' : ($isWE ? 'ring-indigo-200' : 'ring-emerald-200');
@endphp

{{-- Page heading --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">{{ \App\Helpers\LocalizationHelper::t('profile.heading') }}</h1>
    <p class="mt-1 text-sm text-slate-500">{{ \App\Helpers\LocalizationHelper::t('profile.subtitle') }}</p>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="lg:grid lg:grid-cols-[280px_1fr]">

        {{-- Identity panel --}}
        <div class="flex flex-col items-center gap-4 px-8 py-8 border-b border-slate-100 lg:border-b-0 lg:border-r lg:items-start">

            <div class="w-20 h-20 rounded-full bg-indigo-100 ring-4 {{ $avatarRing }} flex items-center justify-center shrink-0">
                <span class="text-3xl font-bold text-indigo-700 select-none leading-none">{{ $initial }}</span>
            </div>

            <div class="text-center lg:text-left">
                <p class="text-lg font-semibold text-slate-900 leading-tight">{{ $user->name }}</p>

                <span class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $roleBadge['class'] }}">
                    <i class="fas {{ $roleBadge['icon'] }} text-[11px]"></i>
                    {{ $roleBadge['label'] }}
                </span>

                @if($isDriver)
                <div class="mt-2">
                    @if($user->isApproved())
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <i class="fas fa-check-circle text-emerald-500 text-[11px]"></i>
                        {{ \App\Helpers\LocalizationHelper::t('profile.status_approved') }}
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                        <i class="fas fa-clock text-amber-500 text-[11px]"></i>
                        {{ \App\Helpers\LocalizationHelper::t('profile.status_pending') }}
                    </span>
                    @endif
                </div>
                @endif
            </div>

            @if($isDriver)
            <div class="w-full">
                <p class="text-xs font-medium text-slate-500 mb-1.5">
                    {{ \App\Helpers\LocalizationHelper::t('profile.field_phone_label') }}
                </p>
                <div class="flex items-center gap-2 px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg">
                    <i class="fas fa-phone text-slate-400 text-xs w-4 text-center"></i>
                    <span class="text-sm font-medium text-slate-700 tracking-wide">{{ $user->phone ?? '—' }}</span>
                </div>
                <p class="mt-1.5 text-xs text-slate-400 leading-snug">
                    {{ \App\Helpers\LocalizationHelper::t('profile.field_phone_hint') }}
                </p>
            </div>
            @endif

        </div>

        {{-- Form panel --}}
        <div class="px-8 py-8">

            @if($isPlaceholderName)
            <div class="mb-6 flex items-start gap-3 bg-indigo-50 border border-indigo-200 px-4 py-3.5 rounded-xl">
                <i class="fas fa-user-edit text-indigo-600 text-sm mt-0.5 shrink-0"></i>
                <p class="text-sm text-indigo-800">
                    {{ \App\Helpers\LocalizationHelper::t('profile.subtitle') }}
                </p>
            </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST"
                  x-data="{ submitting: false }" @submit="submitting = true"
                  class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">
                        {{ \App\Helpers\LocalizationHelper::t('profile.field_name') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-user text-slate-400 text-sm"></i>
                        </div>
                        <input id="name" name="name" type="text" required autocomplete="name"
                               maxlength="255"
                               class="block w-full pl-10 pr-3 py-2.5 rounded-lg border text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow {{ $errors->has('name') ? 'border-rose-400 bg-rose-50' : 'border-slate-300' }}"
                               value="{{ old('name', $user->name) }}">
                    </div>
                    @error('name')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                @if(! $isDriver)
                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">
                        {{ \App\Helpers\LocalizationHelper::t('profile.field_email') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-400 text-sm"></i>
                        </div>
                        <input id="email" name="email" type="email" required autocomplete="email"
                               maxlength="255"
                               class="block w-full pl-10 pr-3 py-2.5 rounded-lg border text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow {{ $errors->has('email') ? 'border-rose-400 bg-rose-50' : 'border-slate-300' }}"
                               value="{{ old('email', $user->email) }}">
                    </div>
                    @error('email')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password section --}}
                <div class="pt-1 border-t border-slate-100">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-4 mt-3">
                        {{ \App\Helpers\LocalizationHelper::t('profile.field_password') }}
                    </p>

                    <div class="space-y-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">
                                {{ \App\Helpers\LocalizationHelper::t('profile.field_password') }}
                            </label>
                            <div class="relative" x-data="{ show: false }">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-slate-400 text-sm"></i>
                                </div>
                                <input id="password" name="password" :type="show ? 'text' : 'password'" autocomplete="new-password"
                                       class="block w-full pl-10 pr-10 py-2.5 rounded-lg border text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow {{ $errors->has('password') ? 'border-rose-400 bg-rose-50' : 'border-slate-300' }}">
                                <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                                    <i class="fas text-sm" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            <p class="mt-1.5 text-xs text-slate-400">
                                {{ \App\Helpers\LocalizationHelper::t('profile.field_password_hint') }}
                            </p>
                            @error('password')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">
                                {{ \App\Helpers\LocalizationHelper::t('profile.field_password_confirmation') }}
                            </label>
                            <div class="relative" x-data="{ show: false }">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-slate-400 text-sm"></i>
                                </div>
                                <input id="password_confirmation" name="password_confirmation" :type="show ? 'text' : 'password'" autocomplete="new-password"
                                       class="block w-full pl-10 pr-10 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow">
                                <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                                    <i class="fas text-sm" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif {{-- !$isDriver --}}

                {{-- Submit --}}
                <div class="pt-2">
                    <button type="submit"
                            :disabled="submitting"
                            class="inline-flex items-center gap-2 py-2.5 px-6 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save" x-show="!submitting"></i>
                        <i class="fas fa-spinner fa-spin" x-show="submitting" x-cloak></i>
                        <span x-show="!submitting">{{ \App\Helpers\LocalizationHelper::t('profile.save_button') }}</span>
                        <span x-show="submitting" x-cloak>{{ \App\Helpers\LocalizationHelper::t('profile.save_button') }}...</span>
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

@endsection
