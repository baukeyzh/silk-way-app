@extends('layouts.app')

@section('title', translate('public.listings_title') . ' — Silk Way')

@section('content')
@php
    $dateFmt   = app()->getLocale() === 'cn' ? 'Y年n月j日' : 'd.m.Y';
    $hasFilters = request('from_city_id') || request('to_city_id')
               || request('ready_date_from') || request('ready_date_to')
               || request('search');
    $registerUrl = route('register') . '?redirect=' . urlencode(request()->fullUrl());
    $loginUrl    = route('login')    . '?redirect=' . urlencode(request()->fullUrl());
    $totalCount  = $cargo->total();
@endphp

<div class="min-h-screen bg-slate-50">

    {{-- ================================================================
         STICKY TOP BAR
         ================================================================ --}}
    <header class="bg-white/95 backdrop-blur border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto flex items-center justify-between h-14 sm:h-16 px-4 sm:px-6">

            {{-- Logo --}}
            <a href="{{ route('cargo.index') }}" class="flex items-center space-x-2.5 shrink-0">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-route text-white text-sm"></i>
                </div>
                <span class="font-bold text-slate-900 text-base sm:text-lg">Silk Way</span>
            </a>

            {{-- Right: language + CTAs --}}
            <div class="flex items-center gap-2 sm:gap-3">

                {{-- Language switcher — globe icon on mobile, pills on sm+ --}}
                <div x-data="{ langOpen: false }" class="relative">
                    {{-- Mobile: globe button --}}
                    <button @click="langOpen = !langOpen"
                            class="sm:hidden w-9 h-9 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 transition-colors"
                            aria-label="Language">
                        <i class="fas fa-globe text-sm"></i>
                    </button>
                    {{-- Mobile dropdown --}}
                    <div x-show="langOpen" @click.outside="langOpen = false" x-cloak
                         class="sm:hidden absolute right-0 top-11 bg-white border border-slate-200 rounded-xl shadow-lg py-1 w-28 z-50"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95">
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'ru']) }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm {{ app()->getLocale() === 'ru' ? 'text-indigo-600 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                            @if(app()->getLocale() === 'ru')<i class="fas fa-check text-indigo-600 text-xs w-3"></i>@else<span class="w-3"></span>@endif
                            Русский
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'kz']) }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm {{ app()->getLocale() === 'kz' ? 'text-indigo-600 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                            @if(app()->getLocale() === 'kz')<i class="fas fa-check text-indigo-600 text-xs w-3"></i>@else<span class="w-3"></span>@endif
                            Қазақша
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'cn']) }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm {{ app()->getLocale() === 'cn' ? 'text-indigo-600 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                            @if(app()->getLocale() === 'cn')<i class="fas fa-check text-indigo-600 text-xs w-3"></i>@else<span class="w-3"></span>@endif
                            中文
                        </a>
                    </div>

                    {{-- Desktop: pill switcher --}}
                    <div class="hidden sm:flex items-center space-x-1 bg-slate-100 rounded-lg p-1">
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'ru']) }}"
                           class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'ru' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">RU</a>
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'kz']) }}"
                           class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'kz' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">KK</a>
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'cn']) }}"
                           class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'cn' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">中</a>
                    </div>
                </div>

                {{-- Login (ghost) --}}
                <a href="{{ $loginUrl }}"
                   class="hidden sm:inline-flex items-center px-3.5 py-2 border border-slate-300 hover:border-slate-400 text-slate-700 hover:text-slate-900 text-sm font-medium rounded-xl transition-colors">
                    {{ translate('public.hero_cta_login') }}
                </a>

                {{-- Register (primary) --}}
                <a href="{{ $registerUrl }}"
                   class="inline-flex items-center px-3.5 py-2 sm:px-4 sm:py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                    <i class="fas fa-user-plus mr-1.5 sm:mr-2 text-xs sm:text-sm"></i>
                    <span class="hidden sm:inline">{{ translate('public.hero_cta_register') }}</span>
                    <span class="sm:hidden">{{ translate('public.hero_cta_login') }}</span>
                </a>
            </div>
        </div>
    </header>

    {{-- ================================================================
         HERO SECTION
         ================================================================ --}}
    <section class="relative overflow-hidden bg-white border-b border-slate-100">
        {{-- Gradient blob background --}}
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-100 rounded-full opacity-60 blur-3xl"></div>
            <div class="absolute top-16 -left-16 w-72 h-72 bg-violet-100 rounded-full opacity-40 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-indigo-200 to-transparent"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 pt-14 pb-12 sm:pt-20 sm:pb-16 text-center">
            {{-- Eyebrow --}}
            <div class="inline-flex items-center gap-2 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                {{ $totalCount }} {{ translate('public.listings_count_badge') }}
            </div>

            {{-- Headline --}}
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-slate-900 leading-tight max-w-3xl mx-auto">
                {{ translate('public.hero_headline') }}
            </h1>

            {{-- Subtitle --}}
            <p class="mt-4 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
                {{ translate('public.hero_subtitle') }}
            </p>

            {{-- CTAs --}}
            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ $registerUrl }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors shadow-sm text-sm sm:text-base">
                    <i class="fas fa-user-plus mr-2"></i>{{ translate('public.hero_cta_register') }}
                </a>
                <a href="{{ $loginUrl }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-white hover:bg-slate-50 border border-slate-300 hover:border-slate-400 text-slate-700 font-semibold rounded-xl transition-colors text-sm sm:text-base">
                    <i class="fas fa-sign-in-alt mr-2 text-slate-400"></i>{{ translate('public.hero_cta_login') }}
                </a>
            </div>

            {{-- Trust bullets --}}
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-6 text-sm text-slate-500">
                <span class="flex items-center gap-1.5">
                    <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                    {{ translate('public.hero_trust_1') }}
                </span>
                <span class="hidden sm:block w-1 h-1 rounded-full bg-slate-300"></span>
                <span class="flex items-center gap-1.5">
                    <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                    {{ translate('public.hero_trust_2') }}
                </span>
                <span class="hidden sm:block w-1 h-1 rounded-full bg-slate-300"></span>
                <span class="flex items-center gap-1.5">
                    <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                    {{ translate('public.hero_trust_3') }}
                </span>
            </div>
        </div>
    </section>

    {{-- ================================================================
         HOW IT WORKS
         ================================================================ --}}
    <section id="how-it-works" class="bg-slate-50 border-b border-slate-200 py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 text-center mb-8 sm:mb-10">
                {{ translate('public.how_title') }}
            </h2>

            {{-- Steps — horizontal scroll on mobile, 3-col on desktop --}}
            <div class="flex gap-4 overflow-x-auto pb-2 sm:pb-0 sm:grid sm:grid-cols-3 sm:gap-6 -mx-4 px-4 sm:mx-0 sm:px-0 snap-x snap-mandatory">

                {{-- Step 1 --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 shrink-0 w-72 sm:w-auto snap-start">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0">
                            <i class="fas fa-search text-indigo-600"></i>
                        </div>
                        <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">{{ translate('public.how_step1_label') }}</span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base mb-1.5">{{ translate('public.how_step1_title') }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ translate('public.how_step1_body') }}</p>
                </div>

                {{-- Step 2 --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 shrink-0 w-72 sm:w-auto snap-start">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0">
                            <i class="fas fa-user-check text-indigo-600"></i>
                        </div>
                        <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">{{ translate('public.how_step2_label') }}</span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base mb-1.5">{{ translate('public.how_step2_title') }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ translate('public.how_step2_body') }}</p>
                </div>

                {{-- Step 3 --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 shrink-0 w-72 sm:w-auto snap-start">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0">
                            <i class="fas fa-truck text-indigo-600"></i>
                        </div>
                        <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">{{ translate('public.how_step3_label') }}</span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base mb-1.5">{{ translate('public.how_step3_title') }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ translate('public.how_step3_body') }}</p>
                </div>

            </div>
        </div>
    </section>

    {{-- ================================================================
         LISTINGS SECTION
         ================================================================ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-14 space-y-7">

        {{-- Section header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900">{{ translate('public.listings_title') }}</h2>
                    @if($totalCount > 0)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                        {{ $totalCount }} {{ translate('public.listings_count_badge') }}
                    </span>
                    @endif
                </div>
                <p class="mt-1 text-sm text-slate-500">{{ translate('public.listings_subtitle') }}</p>
            </div>
        </div>

        {{-- ======= FILTERS ======= --}}
        <div x-data="{ filtersOpen: false }">

            {{-- Filter bar --}}
            <div class="flex items-center gap-2 flex-wrap">
                {{-- Active filter chips --}}
                @if($hasFilters)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-medium rounded-full">
                        <i class="fas fa-filter text-indigo-400 text-xs"></i>
                        {{ translate('cargo.public.filters') }}
                    </span>
                    @if(request('from_city_id'))
                        @php $fromCity = $cities->firstWhere('id', request('from_city_id')); @endphp
                        @if($fromCity)
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-medium rounded-full">
                            <i class="fas fa-arrow-right-from-bracket text-slate-400 text-xs"></i>
                            {{ $fromCity->localized_name }}
                            <a href="{{ request()->fullUrlWithQuery(['from_city_id' => null]) }}" class="ml-1 text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xs"></i></a>
                        </span>
                        @endif
                    @endif
                    @if(request('to_city_id'))
                        @php $toCity = $cities->firstWhere('id', request('to_city_id')); @endphp
                        @if($toCity)
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-medium rounded-full">
                            <i class="fas fa-arrow-right-to-bracket text-slate-400 text-xs"></i>
                            {{ $toCity->localized_name }}
                            <a href="{{ request()->fullUrlWithQuery(['to_city_id' => null]) }}" class="ml-1 text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xs"></i></a>
                        </span>
                        @endif
                    @endif
                    @if(request('ready_date_from') || request('ready_date_to'))
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-medium rounded-full">
                        <i class="fas fa-calendar-alt text-slate-400 text-xs"></i>
                        {{ request('ready_date_from') }}@if(request('ready_date_from') && request('ready_date_to')) – @endif{{ request('ready_date_to') }}
                        <a href="{{ request()->fullUrlWithQuery(['ready_date_from' => null, 'ready_date_to' => null]) }}" class="ml-1 text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xs"></i></a>
                    </span>
                    @endif
                    @if(request('search'))
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-medium rounded-full">
                        <i class="fas fa-search text-slate-400 text-xs"></i>
                        {{ request('search') }}
                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-1 text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xs"></i></a>
                    </span>
                    @endif
                    <a href="{{ route('cargo.index') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-rose-600 hover:text-rose-700 text-xs font-medium transition-colors">
                        <i class="fas fa-times"></i>{{ translate('cargo.reset_filters') }}
                    </a>
                @endif

                {{-- More filters pill --}}
                <button @click="filtersOpen = !filtersOpen"
                        class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-white border border-slate-300 hover:border-indigo-400 hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 text-xs font-semibold rounded-full transition-colors shadow-sm">
                    <i class="fas fa-sliders-h text-xs"></i>
                    {{ translate('cargo.public.filters') }}
                    @if($hasFilters)
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    @endif
                </button>
            </div>

            {{-- Filter drawer / bottom sheet (shared mobile + desktop) --}}
            <div x-show="filtersOpen" x-cloak
                 @click.self="filtersOpen = false"
                 class="fixed inset-0 bg-slate-900/40 z-40 flex items-end sm:items-start sm:justify-center sm:pt-24"
                 x-transition:enter="transition duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">

                <div class="w-full sm:max-w-xl bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl"
                     x-transition:enter="transition duration-200 transform"
                     x-transition:enter-start="translate-y-4 sm:scale-95 opacity-0"
                     x-transition:enter-end="translate-y-0 sm:scale-100 opacity-100"
                     x-transition:leave="transition duration-150 transform"
                     x-transition:leave-start="translate-y-0 sm:scale-100 opacity-100"
                     x-transition:leave-end="translate-y-4 sm:scale-95 opacity-0">

                    {{-- Drawer header --}}
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-sliders-h text-indigo-600 text-sm"></i>
                            <p class="font-semibold text-slate-900 text-sm">{{ translate('cargo.public.filters') }}</p>
                        </div>
                        <button @click="filtersOpen = false"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>

                    {{-- Filter fields --}}
                    <form method="GET" action="{{ route('cargo.index') }}" class="p-5 space-y-4">
                        @include('cargo._public-filter-fields', ['cities' => $cities])
                        <div class="flex gap-3 pt-1">
                            <button type="submit"
                                    class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors">
                                <i class="fas fa-search mr-1.5"></i>{{ translate('cargo.filter_button') }}
                            </button>
                            @if($hasFilters)
                            <a href="{{ route('cargo.index') }}"
                               class="flex-1 py-2.5 text-center bg-white border border-slate-300 hover:bg-slate-50 text-slate-600 text-sm font-medium rounded-xl transition-colors">
                                {{ translate('cargo.reset_filters') }}
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

        </div>

        {{-- ======= RESULTS ======= --}}
        @if($cargo->count() > 0)

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($cargo as $item)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col group">

                    {{-- Card header: route --}}
                    <div class="px-4 pt-4 pb-4 border-b border-slate-100">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            {{-- Status badge --}}
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 shrink-0">
                                <i class="fas fa-circle text-emerald-500 mr-1" style="font-size: 6px;"></i>{{ translate('cargo.status_available') }}
                            </span>
                            {{-- Amber rate pill anchored to top-right --}}
                            <span class="inline-flex items-center bg-amber-50 border border-amber-200 text-amber-700 rounded-full text-xs font-medium px-2.5 py-1 shrink-0">
                                <i class="fas fa-lock mr-1 text-amber-500" style="font-size: 10px;"></i>{{ translate('cargo.public.login_to_see_rate') }}
                            </span>
                        </div>

                        {{-- FROM → TO (dominant) --}}
                        <div class="flex items-center gap-2">
                            <div class="flex-1 min-w-0">
                                <p class="text-base font-bold text-slate-900 truncate leading-snug">{{ $item->localized_from_location }}</p>
                            </div>
                            <div class="shrink-0 flex items-center gap-1 text-slate-300">
                                <div class="w-4 h-px bg-slate-200"></div>
                                <div class="w-6 h-6 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center">
                                    <i class="fas fa-arrow-right text-indigo-400" style="font-size: 9px;"></i>
                                </div>
                                <div class="w-4 h-px bg-slate-200"></div>
                            </div>
                            <div class="flex-1 min-w-0 text-right">
                                <p class="text-base font-bold text-slate-900 truncate leading-snug">{{ $item->localized_to_location }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card body: metadata --}}
                    <div class="px-4 py-3 flex-1 space-y-2">
                        {{-- Cargo type --}}
                        <p class="text-sm font-medium text-slate-700 truncate">
                            <i class="fas fa-box text-slate-300 mr-1.5 text-xs"></i>{{ $item->localized_cargo_type ?: $item->cargo_type }}
                        </p>

                        {{-- Weight + volume --}}
                        <p class="text-xs text-slate-500">
                            <i class="fas fa-weight-hanging text-slate-300 mr-1 text-xs"></i>{{ $item->weight }} kg
                            <span class="mx-1.5 text-slate-200">|</span>
                            <i class="fas fa-cube text-slate-300 mr-1 text-xs"></i>{{ $item->volume }} m³
                        </p>

                        {{-- Ready date --}}
                        <div class="inline-flex items-center gap-1.5 bg-slate-50 border border-slate-100 rounded-lg px-2.5 py-1.5 text-xs font-medium text-slate-600">
                            <i class="fas fa-calendar-alt text-slate-400 text-xs"></i>
                            {{ translate('cargo.ready_label') }}: <span class="font-semibold text-slate-800 ml-0.5">{{ $item->ready_date->format($dateFmt) }}</span>
                        </div>
                    </div>

                    {{-- Card footer: CTA --}}
                    <div class="px-4 pb-4 pt-1">
                        <a href="{{ route('cargo.show', $item) }}"
                           class="block w-full text-center py-2.5 bg-slate-900 hover:bg-indigo-600 text-white text-sm font-semibold rounded-xl transition-colors group-hover:bg-indigo-600">
                            {{ translate('cargo.view_button') }}
                        </a>
                    </div>

                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $cargo->links() }}
            </div>

        @else
            {{-- ======= IMPROVED EMPTY STATE ======= --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm py-16 px-6 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-route text-slate-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-800 mb-2">{{ translate('public.empty_title') }}</h3>
                <p class="text-sm text-slate-500 max-w-sm mx-auto leading-relaxed mb-6">
                    @if($hasFilters)
                        {{ translate('public.empty_body_filtered') }}
                    @else
                        {{ translate('public.empty_body_no_cargo') }}
                    @endif
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    @if($hasFilters)
                    <a href="{{ route('cargo.index') }}"
                       class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors">
                        <i class="fas fa-times mr-2"></i>{{ translate('cargo.public.no_results_cta') }}
                    </a>
                    @endif
                    <a href="{{ $registerUrl }}"
                       class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl transition-colors">
                        <i class="fas fa-user-plus mr-2 text-slate-400"></i>{{ translate('public.hero_cta_register') }}
                    </a>
                </div>
            </div>
        @endif

    </section>

    {{-- ================================================================
         FOOTER
         ================================================================ --}}
    <footer class="bg-white border-t border-slate-200 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 sm:py-14">

            {{-- 3-column grid on desktop --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10">

                {{-- Brand --}}
                <div class="lg:col-span-1">
                    <a href="{{ route('cargo.index') }}" class="flex items-center gap-2.5 mb-3">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fas fa-route text-white text-sm"></i>
                        </div>
                        <span class="font-bold text-slate-900 text-base">{{ translate('public.footer_company_name') }}</span>
                    </a>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ translate('public.footer_company_tagline') }}</p>
                </div>

                {{-- Product --}}
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">{{ translate('public.footer_col_product') }}</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('cargo.index') }}" class="text-sm text-slate-600 hover:text-indigo-600 transition-colors">{{ translate('public.footer_link_browse') }}</a></li>
                        <li><a href="#how-it-works" class="text-sm text-slate-600 hover:text-indigo-600 transition-colors">{{ translate('public.footer_link_how') }}</a></li>
                    </ul>
                </div>

                {{-- Drivers --}}
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">{{ translate('public.footer_col_drivers') }}</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ $registerUrl }}" class="text-sm text-slate-600 hover:text-indigo-600 transition-colors">{{ translate('public.footer_link_register') }}</a></li>
                        <li><a href="{{ $loginUrl }}" class="text-sm text-slate-600 hover:text-indigo-600 transition-colors">{{ translate('public.footer_link_login') }}</a></li>
                    </ul>
                </div>

                {{-- Legal --}}
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">{{ translate('public.footer_col_legal') }}</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="text-sm text-slate-600 hover:text-indigo-600 transition-colors">{{ translate('public.footer_link_privacy') }}</a></li>
                        <li><a href="#" class="text-sm text-slate-600 hover:text-indigo-600 transition-colors">{{ translate('public.footer_link_terms') }}</a></li>
                    </ul>
                </div>

            </div>

            {{-- Bottom bar --}}
            <div class="mt-10 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-400">
                    {{ str_replace(':year', date('Y'), translate('public.footer_copyright')) }}
                </p>
                {{-- Language switcher in footer --}}
                <div class="flex items-center space-x-1 bg-slate-100 rounded-lg p-1">
                    <a href="{{ request()->fullUrlWithQuery(['lang' => 'ru']) }}"
                       class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'ru' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">RU</a>
                    <a href="{{ request()->fullUrlWithQuery(['lang' => 'kz']) }}"
                       class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'kz' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">KK</a>
                    <a href="{{ request()->fullUrlWithQuery(['lang' => 'cn']) }}"
                       class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'cn' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">中</a>
                </div>
            </div>

        </div>
    </footer>

</div>
@endsection
