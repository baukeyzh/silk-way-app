@extends('layouts.app')

@section('title', 'Города')

@section('content')
<div class="space-y-6">
    <!-- Заголовок -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Города</h1>
            <p class="mt-2 text-sm sm:text-base text-gray-700">Управление справочником городов</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('cities.create') }}"
               class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                <i class="fas fa-plus mr-2"></i>
                Добавить город
            </a>
        </div>
    </div>

    <!-- Фильтры -->
    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        <form method="GET" action="{{ route('cities.index') }}" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:space-x-4">
            <div class="flex-1 min-w-0">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Поиск по названию...">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                <select name="country" class="block w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Все страны</option>
                    <option value="kz" {{ request('country') === 'kz' ? 'selected' : '' }}>🇰🇿 Казахстан</option>
                    <option value="ru" {{ request('country') === 'ru' ? 'selected' : '' }}>🇷🇺 Россия</option>
                    <option value="cn" {{ request('country') === 'cn' ? 'selected' : '' }}>🇨🇳 Китай</option>
                </select>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <i class="fas fa-filter mr-2"></i>
                    Фильтр
                </button>
                @if(request('search') || request('country'))
                <a href="{{ route('cities.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Сбросить
                </a>
                @endif
            </div>
        </form>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    @if($cities->count() > 0)
        <!-- Десктопная таблица -->
        <div class="hidden lg:block bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Страна</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Рус</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Каз</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">中文</th>
                            <th class="relative px-6 py-3"><span class="sr-only">Действия</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($cities as $city)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($city->country === 'kz')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">🇰🇿 Казахстан</span>
                                @elseif($city->country === 'ru')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">🇷🇺 Россия</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">🇨🇳 Китай</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $city->name_rus }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $city->name_kaz }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $city->name_chn }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('cities.edit', $city) }}" class="text-indigo-600 hover:text-indigo-900 transition duration-200">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('cities.destroy', $city) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition duration-200"
                                                onclick="return confirm('Удалить город «{{ $city->name_rus }}»?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Мобильные карточки -->
        <div class="lg:hidden space-y-4">
            @foreach($cities as $city)
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-base font-semibold text-gray-900">{{ $city->name_rus }}</p>
                        <p class="text-sm text-gray-500">{{ $city->name_kaz }} · {{ $city->name_chn }}</p>
                    </div>
                    @if($city->country === 'kz')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">🇰🇿 Казахстан</span>
                    @elseif($city->country === 'ru')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">🇷🇺 Россия</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">🇨🇳 Китай</span>
                    @endif
                </div>
                <div class="flex space-x-2 mt-3">
                    <a href="{{ route('cities.edit', $city) }}"
                       class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-200">
                        <i class="fas fa-edit mr-1"></i> Редактировать
                    </a>
                    <form action="{{ route('cities.destroy', $city) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-3 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 transition duration-200"
                                onclick="return confirm('Удалить город «{{ $city->name_rus }}»?')">
                            <i class="fas fa-trash mr-1"></i> Удалить
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Пагинация -->
        <div class="mt-6">
            {{ $cities->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white shadow rounded-lg">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-city text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Города не найдены</h3>
            @if(request('search') || request('country'))
                <a href="{{ route('cities.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-times mr-2"></i> Сбросить фильтры
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
