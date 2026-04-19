@extends('layouts.app')

@section('title', translate('cargo.public.title'))

@section('content')
@php
    $dateFmt = app()->getLocale() === 'cn' ? 'Y年n月j日' : 'd.m.Y';
    $hasFilters = request('from_city_id') || request('to_city_id')
               || request('ready_date_from') || request('ready_date_to')
               || request('search');
@endphp

{{-- ============================================================
     PUBLIC GUEST HEADER
     (only rendered for guests — auth users are redirected away)
     ============================================================ --}}
<div class="min-h-screen bg-slate-50">

    {{-- Top bar --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto flex items-center justify-between h-16 px-4 sm:px-6">
            {{-- Logo --}}
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center shrink-0">
                    <i class="fas fa-route text-white text-sm"></i>
                </div>
                <span class="font-bold text-slate-900 text-lg">Silk Way</span>
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

                {{-- Log in CTA --}}
                <a href="{{ route('login') }}"
                   class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                    <i class="fas fa-sign-in-alt mr-2"></i>{{ translate('nav.login') }}
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-6">

        {{-- Page title --}}
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ translate('cargo.public.title') }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ translate('cargo.available_cargo_desc') }}</p>
        </div>

        {{-- ========= FILTERS ========= --}}
        {{-- Mobile: toggled bottom sheet via Alpine; Desktop: inline row --}}
        <div x-data="{ filtersOpen: false }">

            {{-- Mobile filter pill --}}
            <div class="sm:hidden mb-3">
                <button @click="filtersOpen = !filtersOpen"
                        class="inline-flex items-center px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-xl transition-colors shadow-sm">
                    <i class="fas fa-sliders-h mr-2 text-slate-400"></i>
                    {{ translate('cargo.public.filters') }}
                    @if($hasFilters)
                    <span class="ml-2 w-2 h-2 rounded-full bg-indigo-500"></span>
                    @endif
                </button>
            </div>

            {{-- Desktop inline filters --}}
            <form method="GET" action="{{ route('cargo.index') }}"
                  class="hidden sm:flex flex-wrap gap-3 items-end bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                @include('cargo._public-filter-fields', ['cities' => $cities])
                <button type="submit"
                        class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition-colors shrink-0">
                    <i class="fas fa-search mr-1.5"></i>{{ translate('cargo.filter_button') }}
                </button>
                @if($hasFilters)
                <a href="{{ route('cargo.index') }}"
                   class="px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-600 text-sm font-medium rounded-lg transition-colors shrink-0">
                    <i class="fas fa-times mr-1.5"></i>{{ translate('cargo.reset_filters') }}
                </a>
                @endif
            </form>

            {{-- Mobile bottom sheet --}}
            <div x-show="filtersOpen" x-cloak
                 @click.self="filtersOpen = false"
                 class="sm:hidden fixed inset-0 bg-slate-900/40 z-40 flex items-end"
                 x-transition:enter="transition duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                <div class="w-full bg-white rounded-t-2xl p-5 space-y-4"
                     x-transition:enter="transition duration-200 transform"
                     x-transition:enter-start="translate-y-full"
                     x-transition:enter-end="translate-y-0"
                     x-transition:leave="transition duration-150 transform"
                     x-transition:leave-start="translate-y-0"
                     x-transition:leave-end="translate-y-full">
                    <div class="flex items-center justify-between mb-2">
                        <p class="font-semibold text-slate-900">{{ translate('cargo.public.filters') }}</p>
                        <button @click="filtersOpen = false" class="text-slate-400 hover:text-slate-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form method="GET" action="{{ route('cargo.index') }}" class="space-y-3">
                        @include('cargo._public-filter-fields', ['cities' => $cities])
                        <div class="flex gap-3 pt-2">
                            <button type="submit"
                                    class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors">
                                {{ translate('cargo.filter_button') }}
                            </button>
                            @if($hasFilters)
                            <a href="{{ route('cargo.index') }}"
                               class="flex-1 py-2.5 text-center bg-white border border-slate-300 text-slate-600 text-sm font-medium rounded-xl">
                                {{ translate('cargo.reset_filters') }}
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ========= RESULTS ========= --}}
        @if($cargo->count() > 0)

            {{-- Card grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($cargo as $item)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col">
                    {{-- Card header: from / to + status badge --}}
                    <div class="px-4 pt-4 pb-3 border-b border-slate-100">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate">{{ $item->localized_from_location }}</p>
                                <div class="flex items-center text-xs text-slate-500 mt-0.5">
                                    <i class="fas fa-arrow-right mr-1 text-slate-300 shrink-0"></i>
                                    <span class="truncate font-medium">{{ $item->localized_to_location }}</span>
                                </div>
                            </div>
                            {{-- Status badge --}}
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 shrink-0">
                                <i class="fas fa-check-circle mr-1"></i>{{ translate('cargo.status_available') }}
                            </span>
                        </div>
                    </div>

                    {{-- Card body --}}
                    <div class="px-4 py-3 flex-1 space-y-2">
                        {{-- Cargo type · weight · volume --}}
                        <p class="text-sm font-medium text-slate-800">{{ $item->localized_cargo_type ?: $item->cargo_type }}</p>
                        <p class="text-xs text-slate-500">
                            {{ $item->weight }} kg
                            <span class="mx-1 text-slate-300">·</span>
                            {{ $item->volume }} m³
                        </p>

                        {{-- Ready date --}}
                        <div class="text-xs text-slate-500">
                            <i class="fas fa-calendar-alt mr-1 text-slate-300"></i>
                            {{ translate('cargo.ready_label') }}: <span class="font-medium text-slate-700">{{ $item->ready_date->format($dateFmt) }}</span>
                        </div>

                        {{-- Amber price pill (price always hidden for guests) --}}
                        <div>
                            <span class="inline-flex items-center bg-amber-100 text-amber-700 rounded-full text-xs font-medium px-2.5 py-1">
                                <i class="fas fa-lock mr-1.5 text-amber-500 text-xs"></i>{{ translate('cargo.public.login_to_see_rate') }}
                            </span>
                        </div>
                    </div>

                    {{-- Card footer --}}
                    <div class="px-4 pb-4">
                        <a href="{{ route('cargo.show', $item) }}"
                           class="block w-full text-center py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors">
                            {{ translate('cargo.view_button') }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $cargo->links() }}
            </div>

        @else
            {{-- Empty state --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm py-16 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box-open text-slate-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-700 mb-1">{{ translate('cargo.public.no_results_title') }}</h3>
                <p class="text-sm text-slate-500 mb-6">{{ translate('cargo.no_cargo_desc') }}</p>
                @if($hasFilters)
                <a href="{{ route('cargo.index') }}"
                   class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors">
                    <i class="fas fa-times mr-2"></i>{{ translate('cargo.public.no_results_cta') }}
                </a>
                @endif
            </div>
        @endif

    </div>
</div>
@endsection
