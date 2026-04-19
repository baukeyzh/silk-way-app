@extends('layouts.app')

@section('title', $cargo->localized_from_location . ' → ' . $cargo->localized_to_location . ' — Silk Way')

@section('content')
@php
    $dateFmt   = app()->getLocale() === 'cn' ? 'Y年n月j日' : 'd.m.Y';
    $loginUrl  = route('login') . '?redirect=' . urlencode(request()->fullUrl());
@endphp

<div class="min-h-screen bg-slate-50">

    {{-- Top bar --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-4xl mx-auto flex items-center justify-between h-16 px-4 sm:px-6">
            {{-- Logo + back --}}
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fas fa-route text-white text-sm"></i>
                    </div>
                    <span class="font-bold text-slate-900 text-lg hidden sm:block">Silk Way</span>
                </div>
                <a href="{{ route('cargo.index') }}"
                   class="flex items-center text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors">
                    <i class="fas fa-arrow-left mr-1.5"></i>{{ translate('common.back') }}
                </a>
            </div>

            {{-- Right side --}}
            <div class="flex items-center space-x-3">
                {{-- Language switcher --}}
                <div class="flex items-center space-x-1 bg-slate-100 rounded-lg p-1">
                    <a href="{{ request()->fullUrlWithQuery(['lang' => 'ru']) }}"
                       class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'ru' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">RU</a>
                    <a href="{{ request()->fullUrlWithQuery(['lang' => 'kz']) }}"
                       class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'kz' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">KK</a>
                    <a href="{{ request()->fullUrlWithQuery(['lang' => 'cn']) }}"
                       class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'cn' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">中</a>
                </div>

                <a href="{{ route('login') }}"
                   class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                    <i class="fas fa-sign-in-alt mr-2"></i>{{ translate('nav.login') }}
                </a>
            </div>
        </div>
    </header>

    {{-- Main content with bottom padding to clear sticky CTA --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 pb-32 space-y-5">

        {{-- Hero FROM → TO card --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-4 flex-wrap">
                    <div class="text-center">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.public.from') }}</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $cargo->localized_from_location }}</p>
                    </div>
                    <div class="flex items-center gap-2 text-slate-300">
                        <div class="h-px w-8 bg-slate-200"></div>
                        <div class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-200 flex items-center justify-center">
                            <i class="fas fa-arrow-right text-indigo-500 text-sm"></i>
                        </div>
                        <div class="h-px w-8 bg-slate-200"></div>
                    </div>
                    <div class="text-center">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.public.to') }}</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $cargo->localized_to_location }}</p>
                    </div>
                </div>
                {{-- Status badge --}}
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-700">
                    <i class="fas fa-check-circle mr-1.5"></i>{{ translate('cargo.status_available') }}
                </span>
            </div>
        </div>

        {{-- 4-tile info grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.cargo_type') }}</p>
                <p class="text-sm font-semibold text-slate-900">{{ $cargo->localized_cargo_type ?: $cargo->cargo_type }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.volume_weight_label') }}</p>
                <p class="text-sm font-semibold text-slate-900">{{ $cargo->volume }} m³ · {{ $cargo->weight }} kg</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.price_usd') }}</p>
                {{-- Price always hidden for guests --}}
                <span class="inline-flex items-center bg-amber-100 text-amber-700 rounded-full text-xs font-medium px-2.5 py-1">
                    <i class="fas fa-lock mr-1.5 text-amber-500 text-xs"></i>{{ translate('cargo.public.login_to_see_rate') }}
                </span>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.ready_date_label') }}</p>
                <p class="text-sm font-semibold text-slate-900">{{ $cargo->ready_date->format($dateFmt) }}</p>
            </div>
        </div>

        {{-- Comments section (visible if comment exists; no author/timestamp) --}}
        @if($cargo->localized_comment)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-3">{{ translate('cargo.comment_label') }}</h2>
            <p class="text-sm text-slate-700 leading-relaxed">{{ $cargo->localized_comment }}</p>
        </div>
        @endif

        {{-- CTA banner (above the sticky button — visible on scroll) --}}
        <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-5 text-center">
            <p class="text-sm text-indigo-700 font-medium mb-3">
                <i class="fas fa-info-circle mr-1.5"></i>{{ translate('cargo.public.login_to_see_rate') }}
            </p>
            <a href="{{ $loginUrl }}"
               class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors">
                <i class="fas fa-paper-plane mr-2"></i>{{ translate('cargo.public.apply_cta') }}
            </a>
        </div>

    </div>

    {{-- Sticky bottom CTA --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 px-4 py-4 z-20">
        <div class="max-w-4xl mx-auto">
            <a href="{{ $loginUrl }}"
               class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl py-3 font-medium text-sm transition-colors">
                <i class="fas fa-paper-plane mr-2"></i>{{ translate('cargo.public.apply_cta') }}
            </a>
        </div>
    </div>

</div>
@endsection
