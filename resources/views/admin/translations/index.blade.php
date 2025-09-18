@extends('layouts.app')

@section('title', \App\Helpers\LocalizationHelper::t('admin.translations') . ' - Silk Way')

@section('content')
<div class="space-y-6">
    <!-- Заголовок -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ \App\Helpers\LocalizationHelper::t('admin.translations') }}</h1>
            <p class="mt-2 text-sm sm:text-base text-gray-700">
                {{ \App\Helpers\LocalizationHelper::t('admin.translations_desc') }}
            </p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('admin.translations.create') }}" 
               class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                <i class="fas fa-plus mr-2"></i>
                {{ \App\Helpers\LocalizationHelper::t('admin.add_translation') }}
            </a>
            <a href="{{ route('admin.translations.export') }}" 
               class="inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200">
                <i class="fas fa-download mr-2"></i>
                {{ \App\Helpers\LocalizationHelper::t('admin.export') }}
            </a>
            <form action="{{ route('admin.translations.clear-cache') }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-yellow-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition duration-200">
                    <i class="fas fa-broom mr-2"></i>
                    {{ \App\Helpers\LocalizationHelper::t('admin.clear_cache') }}
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Фильтры и поиск -->
    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        <form method="GET" action="{{ route('admin.translations.index') }}" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:space-x-4">
            <div class="flex-1 min-w-0">
                <label for="search" class="sr-only">{{ \App\Helpers\LocalizationHelper::t('admin.search_by_key') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="{{ \App\Helpers\LocalizationHelper::t('admin.search_placeholder') }}">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                <select name="group" class="block w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ \App\Helpers\LocalizationHelper::t('admin.all_groups') }}</option>
                    @foreach($groups as $group)
                        <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>
                            {{ ucfirst($group) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <i class="fas fa-filter mr-2"></i>
                    {{ \App\Helpers\LocalizationHelper::t('admin.filter') }}
                </button>
                <a href="{{ route('admin.translations.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <i class="fas fa-times mr-2"></i>
                    {{ \App\Helpers\LocalizationHelper::t('admin.clear_filters') }}
                </a>
            </div>
        </form>
    </div>

    <!-- Таблица переводов -->
    @if($translations->count() > 0)
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ \App\Helpers\LocalizationHelper::t('admin.table_key') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ \App\Helpers\LocalizationHelper::t('admin.table_russian') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ \App\Helpers\LocalizationHelper::t('admin.table_kazakh') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ \App\Helpers\LocalizationHelper::t('admin.table_chinese') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ \App\Helpers\LocalizationHelper::t('admin.table_group') }}
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">{{ \App\Helpers\LocalizationHelper::t('admin.table_actions') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($translations as $translation)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $translation->key }}</div>
                                @if($translation->description)
                                    <div class="text-sm text-gray-500">{{ Str::limit($translation->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ Str::limit($translation->rus, 50) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ Str::limit($translation->kaz, 50) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ Str::limit($translation->chn, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $translation->group }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.translations.show', $translation) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition duration-200">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.translations.edit', $translation) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 transition duration-200">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($translations->hasPages())
                <div class="px-6 py-3 border-t border-gray-200">
                    {{ $translations->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-language text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">{{ \App\Helpers\LocalizationHelper::t('admin.no_translations_found') }}</h3>
            <p class="text-gray-500 mb-6">
                @if(request('search') || request('group'))
                    {{ \App\Helpers\LocalizationHelper::t('admin.try_change_search') }}
                @else
                    {{ \App\Helpers\LocalizationHelper::t('admin.no_translations_desc') }}
                @endif
            </p>
            @if(request('search') || request('group'))
            <a href="{{ route('admin.translations.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                <i class="fas fa-times mr-2"></i>
                {{ \App\Helpers\LocalizationHelper::t('admin.reset_filters') }}
            </a>
            @endif
        </div>
    @endif
</div>
@endsection