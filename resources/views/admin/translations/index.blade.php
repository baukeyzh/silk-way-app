@extends('layouts.app')

@section('title', \App\Helpers\LocalizationHelper::t('admin.translations') . ' - Silk Way')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ \App\Helpers\LocalizationHelper::t('admin.translations') }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ \App\Helpers\LocalizationHelper::t('admin.translations_desc') }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.translations.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                <i class="fas fa-plus text-xs"></i>
                {{ \App\Helpers\LocalizationHelper::t('admin.add_translation') }}
            </a>
            <a href="{{ route('admin.translations.export') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors">
                <i class="fas fa-download text-xs"></i>
                {{ \App\Helpers\LocalizationHelper::t('admin.export') }}
            </a>
            <form action="{{ route('admin.translations.clear-cache') }}" method="POST">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition-colors">
                    <i class="fas fa-broom text-xs"></i>
                    {{ \App\Helpers\LocalizationHelper::t('admin.clear_cache') }}
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex gap-3">
            <i class="fas fa-check-circle text-emerald-500 flex-shrink-0"></i>
            <p class="text-sm text-emerald-700">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 flex gap-3">
            <i class="fas fa-exclamation-circle text-rose-500 flex-shrink-0"></i>
            <p class="text-sm text-rose-700">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <form method="GET" action="{{ route('admin.translations.index') }}" class="flex flex-wrap gap-3 items-center">
            <div class="relative flex-1 min-w-48">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="{{ \App\Helpers\LocalizationHelper::t('admin.search_placeholder') }}"
                       class="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <select name="group"
                    class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                <option value="">{{ \App\Helpers\LocalizationHelper::t('admin.all_groups') }}</option>
                @foreach($groups as $group)
                    <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>
                        {{ ucfirst($group) }}
                    </option>
                @endforeach
            </select>
            <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fas fa-filter mr-1.5"></i>{{ \App\Helpers\LocalizationHelper::t('admin.filter') }}
            </button>
            <a href="{{ route('admin.translations.index') }}"
               class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                <i class="fas fa-times mr-1.5"></i>{{ \App\Helpers\LocalizationHelper::t('admin.clear_filters') }}
            </a>
        </form>
    </div>

    {{-- Table --}}
    @if($translations->count() > 0)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                {{ \App\Helpers\LocalizationHelper::t('admin.table_key') }}
                            </th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                🇷🇺 {{ \App\Helpers\LocalizationHelper::t('admin.table_russian') }}
                            </th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden xl:table-cell">
                                🇰🇿 {{ \App\Helpers\LocalizationHelper::t('admin.table_kazakh') }}
                            </th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden xl:table-cell">
                                🇨🇳 {{ \App\Helpers\LocalizationHelper::t('admin.table_chinese') }}
                            </th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                {{ \App\Helpers\LocalizationHelper::t('admin.table_group') }}
                            </th>
                            <th class="relative px-6 py-3.5">
                                <span class="sr-only">{{ \App\Helpers\LocalizationHelper::t('admin.table_actions') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($translations as $translation)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-3.5">
                                    <p class="text-xs font-mono font-medium text-slate-900">{{ $translation->key }}</p>
                                    @if($translation->description)
                                        <p class="text-xs text-slate-400 mt-0.5">{{ Str::limit($translation->description, 40) }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 max-w-xs">
                                    <p class="text-sm text-slate-700 truncate">{{ Str::limit($translation->ru, 50) }}</p>
                                </td>
                                <td class="px-6 py-3.5 max-w-xs hidden xl:table-cell">
                                    <p class="text-sm text-slate-700 truncate">{{ Str::limit($translation->kz, 50) }}</p>
                                </td>
                                <td class="px-6 py-3.5 max-w-xs hidden xl:table-cell">
                                    <p class="text-sm text-slate-700 truncate">{{ Str::limit($translation->cn, 50) }}</p>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                        {{ $translation->group }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('admin.translations.show', $translation) }}"
                                           class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                           title="Просмотр">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        <a href="{{ route('admin.translations.edit', $translation) }}"
                                           class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                           title="Редактировать">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($translations->hasPages())
                <div class="px-6 py-3 border-t border-slate-200 bg-slate-50">
                    {{ $translations->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm py-16 text-center">
            <i class="fas fa-language text-5xl text-slate-300 mb-4"></i>
            <h3 class="text-base font-semibold text-slate-600 mb-1">{{ \App\Helpers\LocalizationHelper::t('admin.no_translations_found') }}</h3>
            <p class="text-sm text-slate-400 mb-4">
                @if(request('search') || request('group'))
                    {{ \App\Helpers\LocalizationHelper::t('admin.try_change_search') }}
                @else
                    {{ \App\Helpers\LocalizationHelper::t('admin.no_translations_desc') }}
                @endif
            </p>
            @if(request('search') || request('group'))
                <a href="{{ route('admin.translations.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-times text-xs"></i>
                    {{ \App\Helpers\LocalizationHelper::t('admin.reset_filters') }}
                </a>
            @endif
        </div>
    @endif

</div>
@endsection
