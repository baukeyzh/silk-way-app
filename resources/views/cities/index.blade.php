@extends('layouts.app')

@section('title', 'Города')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Города</h1>
            <p class="mt-1 text-sm text-slate-500">Управление справочником городов</p>
        </div>
        <a href="{{ route('cities.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
            <i class="fas fa-plus text-xs"></i>
            Добавить город
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex gap-3">
            <i class="fas fa-check-circle text-emerald-500 flex-shrink-0"></i>
            <p class="text-sm text-emerald-700">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <form method="GET" action="{{ route('cities.index') }}" class="flex flex-wrap gap-3 items-center">
            <div class="relative flex-1 min-w-48">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Поиск по названию..."
                       class="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <select name="country"
                    class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                <option value="">Все страны</option>
                <option value="kz" {{ request('country') === 'kz' ? 'selected' : '' }}>🇰🇿 Казахстан</option>
                <option value="ru" {{ request('country') === 'ru' ? 'selected' : '' }}>🇷🇺 Россия</option>
                <option value="cn" {{ request('country') === 'cn' ? 'selected' : '' }}>🇨🇳 Китай</option>
            </select>
            <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fas fa-filter mr-1.5"></i>Фильтр
            </button>
            @if(request('search') || request('country'))
                <a href="{{ route('cities.index') }}"
                   class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                    <i class="fas fa-times mr-1.5"></i>Сбросить
                </a>
            @endif
        </form>
    </div>

    @if($cities->count() > 0)
        {{-- Desktop Table --}}
        <div class="hidden lg:block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Страна</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Русский</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Қазақша</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">中文</th>
                        <th class="relative px-6 py-3.5"><span class="sr-only">Действия</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($cities as $city)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3.5">
                                @if($city->country === 'kz')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-sky-100 text-sky-700">🇰🇿 Казахстан</span>
                                @elseif($city->country === 'ru')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">🇷🇺 Россия</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">🇨🇳 Китай</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-sm font-medium text-slate-900">{{ $city->name_rus }}</td>
                            <td class="px-6 py-3.5 text-sm text-slate-700">{{ $city->name_kaz }}</td>
                            <td class="px-6 py-3.5 text-sm text-slate-700">{{ $city->name_chn }}</td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('cities.edit', $city) }}"
                                       class="p-1.5 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('cities.destroy', $city) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors"
                                                onclick="return confirm('Удалить город «{{ $city->name_rus }}»?')">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="lg:hidden space-y-3">
            @foreach($cities as $city)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $city->name_rus }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $city->name_kaz }} · {{ $city->name_chn }}</p>
                        </div>
                        @if($city->country === 'kz')
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-700">🇰🇿</span>
                        @elseif($city->country === 'ru')
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700">🇷🇺</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">🇨🇳</span>
                        @endif
                    </div>
                    <div class="flex gap-2 mt-3">
                        <a href="{{ route('cities.edit', $city) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                            <i class="fas fa-edit"></i> Редактировать
                        </a>
                        <form action="{{ route('cities.destroy', $city) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors"
                                    onclick="return confirm('Удалить город «{{ $city->name_rus }}»?')">
                                <i class="fas fa-trash"></i> Удалить
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $cities->links() }}</div>
    @else
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm py-16 text-center">
            <i class="fas fa-city text-5xl text-slate-300 mb-4"></i>
            <h3 class="text-base font-semibold text-slate-600 mb-1">Города не найдены</h3>
            @if(request('search') || request('country'))
                <a href="{{ route('cities.index') }}"
                   class="mt-3 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-times text-xs"></i> Сбросить фильтры
                </a>
            @endif
        </div>
    @endif

</div>
@endsection
