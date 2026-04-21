@extends('layouts.app')

@section('title', $cargo->localized_from_location . ' → ' . $cargo->localized_to_location . ' — Silk Way')

@section('content')
@php
    $dateFmt   = app()->getLocale() === 'cn' ? 'Y年n月j日' : 'd.m.Y';
    $loginUrl  = route('login')    . '?redirect=' . urlencode(request()->fullUrl());
    $registerUrl = route('register') . '?redirect=' . urlencode(request()->fullUrl());
@endphp

<div class="min-h-screen bg-slate-50">

    {{-- ================================================================
         CONSOLIDATED TOP BAR — compact single row
         ================================================================ --}}
    <header class="bg-white/95 backdrop-blur border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-4xl mx-auto flex items-center justify-between h-14 px-4 sm:px-6">

            {{-- Left: logo + back --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('cargo.index') }}" class="flex items-center gap-2 shrink-0">
                    <div class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-route text-white" style="font-size: 11px;"></i>
                    </div>
                    <span class="font-bold text-slate-900 text-sm hidden sm:block">Silk Way</span>
                </a>
                <span class="text-slate-200 hidden sm:block">|</span>
                <a href="{{ route('cargo.index') }}"
                   class="flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors">
                    <i class="fas fa-arrow-left text-xs"></i>
                    <span>{{ translate('public.detail_back') }}</span>
                </a>
            </div>

            {{-- Right: language (globe on mobile, pills on sm+) + login --}}
            <div class="flex items-center gap-2">

                <div x-data="{ langOpen: false }" class="relative">
                    {{-- Mobile globe --}}
                    <button @click="langOpen = !langOpen"
                            class="sm:hidden w-8 h-8 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 transition-colors"
                            aria-label="Language">
                        <i class="fas fa-globe text-sm"></i>
                    </button>
                    <div x-show="langOpen" @click.outside="langOpen = false" x-cloak
                         class="sm:hidden absolute right-0 top-10 bg-white border border-slate-200 rounded-xl shadow-lg py-1 w-28 z-50"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95">
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'ru']) }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm {{ app()->getLocale() === 'ru' ? 'text-indigo-600 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                            @if(app()->getLocale() === 'ru')<i class="fas fa-check text-indigo-600 text-xs w-3"></i>@else<span class="w-3"></span>@endif Русский
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'kz']) }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm {{ app()->getLocale() === 'kz' ? 'text-indigo-600 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                            @if(app()->getLocale() === 'kz')<i class="fas fa-check text-indigo-600 text-xs w-3"></i>@else<span class="w-3"></span>@endif Қазақша
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'cn']) }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm {{ app()->getLocale() === 'cn' ? 'text-indigo-600 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                            @if(app()->getLocale() === 'cn')<i class="fas fa-check text-indigo-600 text-xs w-3"></i>@else<span class="w-3"></span>@endif 中文
                        </a>
                    </div>

                    {{-- Desktop pills --}}
                    <div class="hidden sm:flex items-center space-x-1 bg-slate-100 rounded-lg p-1">
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'ru']) }}"
                           class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'ru' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">RU</a>
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'kz']) }}"
                           class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'kz' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">KK</a>
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'cn']) }}"
                           class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'cn' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">中</a>
                    </div>
                </div>

                <a href="{{ $loginUrl }}"
                   class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold rounded-xl transition-colors shadow-sm">
                    <i class="fas fa-sign-in-alt mr-1.5"></i>{{ translate('nav.login') }}
                </a>
            </div>

        </div>
    </header>

    {{-- Main: bottom padding to clear sticky CTA --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6 sm:py-8 pb-28 space-y-4">

        {{-- ============================================================
             HERO CARD: FROM → TO
             ============================================================ --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Top accent bar --}}
            <div class="h-1 bg-gradient-to-r from-indigo-500 to-violet-500"></div>

            <div class="p-5 sm:p-6">
                {{-- Status + amber pill --}}
                <div class="flex items-center justify-between gap-2 mb-5">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                        <i class="fas fa-circle text-emerald-500 mr-1.5" style="font-size: 6px;"></i>{{ translate('cargo.status_available') }}
                    </span>
                    <span class="inline-flex items-center bg-amber-50 border border-amber-200 text-amber-700 rounded-full text-xs font-medium px-2.5 py-1 shrink-0">
                        <i class="fas fa-lock mr-1.5 text-amber-500 text-xs"></i>{{ translate('cargo.public.login_to_see_rate') }}
                    </span>
                </div>

                {{-- Route: FROM → TO --}}
                <div class="flex items-center gap-3 sm:gap-5">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.public.from') }}</p>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900 truncate">{{ $cargo->localized_from_location }}</p>
                    </div>

                    <div class="shrink-0 flex flex-col items-center gap-1.5">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 border border-indigo-200 flex items-center justify-center">
                            <i class="fas fa-arrow-right text-indigo-500 text-sm"></i>
                        </div>
                        <div class="w-px h-0 sm:h-0"></div>
                    </div>

                    <div class="flex-1 min-w-0 text-right">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.public.to') }}</p>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900 truncate">{{ $cargo->localized_to_location }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================
             DETAIL GRID: cargo type / weight+vol / rate / ready date
             ============================================================ --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

            {{-- Cargo type --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center mb-2">
                    <i class="fas fa-box text-slate-400 text-sm"></i>
                </div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">{{ translate('cargo.cargo_type') }}</p>
                <p class="text-sm font-semibold text-slate-900 leading-snug">{{ $cargo->localized_cargo_type ?: $cargo->cargo_type }}</p>
            </div>

            {{-- Weight + volume --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center mb-2">
                    <i class="fas fa-weight-hanging text-slate-400 text-sm"></i>
                </div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">{{ translate('cargo.volume_weight_label') }}</p>
                <p class="text-sm font-semibold text-slate-900 leading-snug">{{ $cargo->volume }} m³ · {{ $cargo->weight }} kg</p>
            </div>

            {{-- Rate (hidden) --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center mb-2">
                    <i class="fas fa-lock text-amber-500 text-sm"></i>
                </div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">{{ translate('cargo.price_usd') }}</p>
                <span class="inline-flex items-center bg-amber-100 text-amber-700 rounded-full text-xs font-medium px-2 py-0.5">
                    {{ translate('cargo.public.login_to_see_rate') }}
                </span>
            </div>

            {{-- Ready date --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center mb-2">
                    <i class="fas fa-calendar-alt text-indigo-500 text-sm"></i>
                </div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">{{ translate('cargo.ready_date_label') }}</p>
                <p class="text-sm font-semibold text-slate-900">{{ $cargo->ready_date->format($dateFmt) }}</p>
            </div>

        </div>

        {{-- ============================================================
             COMMENT (conditional)
             ============================================================ --}}
        @if($cargo->localized_comment)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 sm:p-6">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">{{ translate('cargo.comment_label') }}</h2>
            <p class="text-sm text-slate-700 leading-relaxed">{{ $cargo->localized_comment }}</p>
        </div>
        @endif

        {{-- ============================================================
             REGISTER NUDGE (replaces in-flow CTA banner — non-redundant)
             Explains the value prop, not just "log in". No CTA button here
             since the sticky bottom handles the primary action.
             ============================================================ --}}
        <div class="bg-gradient-to-br from-indigo-50 to-violet-50 border border-indigo-200 rounded-xl p-5 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-white border border-indigo-200 flex items-center justify-center shrink-0">
                <i class="fas fa-info-circle text-indigo-500"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-indigo-900 mb-0.5">{{ translate('public.hero_cta_register') }}</p>
                <p class="text-xs text-indigo-700 leading-relaxed">{{ translate('public.hero_subtitle') }}</p>
            </div>
        </div>

    </div>

    {{-- ================================================================
         STICKY BOTTOM CTA — single, consolidated (anti-pattern fixed)
         ================================================================ --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur border-t border-slate-200 px-4 py-3 z-20 shadow-lg">
        <div class="max-w-4xl mx-auto flex gap-3">
            <a href="{{ $registerUrl }}"
               class="flex-1 flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl py-3 font-semibold text-sm transition-colors">
                <i class="fas fa-user-plus text-xs"></i>{{ translate('public.hero_cta_register') }}
            </a>
            <a href="{{ $loginUrl }}"
               class="flex items-center justify-center gap-2 px-4 py-3 border border-slate-300 hover:border-indigo-400 hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 rounded-xl font-medium text-sm transition-colors">
                <i class="fas fa-sign-in-alt text-xs"></i>
                <span class="hidden sm:inline">{{ translate('public.detail_cta_label') }}</span>
                <span class="sm:hidden">{{ translate('nav.login') }}</span>
            </a>
        </div>
    </div>

</div>
@endsection
