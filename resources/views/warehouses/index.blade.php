@extends('layouts.app')

@section('title', translate('warehouse.title'))

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ translate('warehouse.title') }}</h1>
        </div>
        <a href="{{ route('warehouses.create') }}"
           class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
            <i class="fas fa-plus mr-2"></i>{{ translate('warehouse.create') }}
        </a>
    </div>

    {{-- Admin filters --}}
    @if(auth()->user()->isAdmin())
    <form method="GET" action="{{ route('warehouses.index') }}"
          class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-medium text-slate-500 mb-1">Город</label>
                <div class="relative">
                    <select name="city_id"
                            class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 appearance-none pr-8">
                        <option value="">Все города</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->localized_name }}
                            </option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-medium text-slate-500 mb-1">Сотрудник</label>
                <div class="relative">
                    <select name="creator_id"
                            class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 appearance-none pr-8">
                        <option value="">Все сотрудники</option>
                        @foreach($creators as $creator)
                            <option value="{{ $creator->id }}" {{ request('creator_id') == $creator->id ? 'selected' : '' }}>
                                {{ $creator->name }}
                            </option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>
            <button type="submit"
                    class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-filter mr-1.5"></i>Применить
            </button>
            @if(request('city_id') || request('creator_id'))
            <a href="{{ route('warehouses.index') }}"
               class="px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-600 text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-times mr-1.5"></i>Сбросить
            </a>
            @endif
        </div>
    </form>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden"
         x-data="{ confirmingDelete: null }">

        @if($warehouses->isEmpty())
        <div class="px-6 py-16 text-center">
            <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-warehouse text-2xl text-slate-400"></i>
            </div>
            @if(auth()->user()->isAdmin() && (request('city_id') || request('creator_id')))
                <p class="text-slate-600 font-medium">{{ translate('warehouse.empty_filtered') }}</p>
            @else
                <p class="text-slate-600 font-medium">{{ translate('warehouse.empty_own') }}</p>
                <p class="text-slate-400 text-sm mt-1">Создайте первый склад, чтобы начать оформлять грузы.</p>
                <a href="{{ route('warehouses.create') }}"
                   class="mt-4 inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <i class="fas fa-plus mr-2"></i>{{ translate('warehouse.create') }}
                </a>
            @endif
        </div>

        @else

        {{-- Desktop table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Название</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('warehouse.city') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('warehouse.address') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('warehouse.phone') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Создан</th>
                        <th class="relative px-6 py-3"><span class="sr-only">{{ translate('common.actions') }}</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($warehouses as $warehouse)
                    @php $cargoCount = $warehouse->cargoCount(); @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                    <i class="fas fa-warehouse text-indigo-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-800">{{ $warehouse->name_rus }}</p>
                                    @if($warehouse->name_kaz)
                                    <p class="text-xs text-slate-400">{{ $warehouse->name_kaz }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $warehouse->city?->localized_name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600 max-w-xs">
                            <span class="truncate block" title="{{ $warehouse->address }}">{{ $warehouse->address }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $warehouse->phone ?: '—' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">
                            {{ $warehouse->created_at->format('d.m.Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            {{-- Default actions --}}
                            <div x-show="confirmingDelete !== {{ $warehouse->id }}" class="flex items-center justify-end gap-2">
                                <a href="{{ route('warehouses.edit', $warehouse) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg transition-colors">
                                    <i class="fas fa-pencil-alt mr-1.5"></i>Изменить
                                </a>
                                @if($cargoCount === 0)
                                <button type="button"
                                        @click="confirmingDelete = {{ $warehouse->id }}"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 border border-rose-200 hover:bg-rose-100 rounded-lg transition-colors">
                                    <i class="fas fa-trash-alt mr-1.5"></i>Удалить
                                </button>
                                @else
                                <span class="inline-flex items-center px-3 py-1.5 text-xs text-slate-400 bg-slate-50 border border-slate-200 rounded-lg">
                                    <i class="fas fa-lock mr-1.5"></i>{{ $cargoCount }} груз.
                                </span>
                                @endif
                            </div>

                            {{-- Delete confirmation inline --}}
                            <div x-show="confirmingDelete === {{ $warehouse->id }}" x-cloak
                                 class="flex flex-col items-end gap-2">
                                @if($cargoCount === 0)
                                <p class="text-xs text-slate-700 text-right max-w-xs">
                                    {!! translate('warehouse.delete_confirm', ['name' => '<strong>' . e($warehouse->name_rus) . '</strong>']) !!}
                                </p>
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            @click="confirmingDelete = null"
                                            class="px-3 py-1.5 text-xs font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg transition-colors">
                                        Отмена
                                    </button>
                                    <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 text-xs font-medium text-white bg-rose-600 hover:bg-rose-700 rounded-lg transition-colors">
                                            Удалить
                                        </button>
                                    </form>
                                </div>
                                @else
                                <p class="text-xs text-amber-800 text-right max-w-xs">
                                    {!! translate('warehouse.delete_blocked', ['count' => '<strong>' . $cargoCount . '</strong>']) !!}
                                    Чтобы удалить склад, сначала измените склад во всех связанных грузах.
                                </p>
                                <button type="button"
                                        @click="confirmingDelete = null"
                                        class="px-3 py-1.5 text-xs font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg transition-colors">
                                    Закрыть
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile stacked cards --}}
        <div class="md:hidden divide-y divide-slate-100">
            @foreach($warehouses as $warehouse)
            @php $cargoCount = $warehouse->cargoCount(); @endphp
            <div class="p-4" x-data="{ confirmingDelete: false }">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                            <i class="fas fa-warehouse text-indigo-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $warehouse->name_rus }}</p>
                            <p class="text-xs text-slate-500">{{ $warehouse->city?->localized_name ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('warehouses.edit', $warehouse) }}"
                           class="p-2 text-slate-500 hover:text-indigo-600 transition-colors">
                            <i class="fas fa-pencil-alt text-sm"></i>
                        </a>
                        @if($cargoCount === 0)
                        <button type="button" @click="confirmingDelete = !confirmingDelete"
                                class="p-2 text-slate-500 hover:text-rose-600 transition-colors">
                            <i class="fas fa-trash-alt text-sm"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <div class="mt-2 ml-13 space-y-1">
                    <p class="text-xs text-slate-500"><i class="fas fa-map-marker-alt mr-1.5 w-3 text-center"></i>{{ $warehouse->address }}</p>
                    @if($warehouse->phone)
                    <p class="text-xs text-slate-500"><i class="fas fa-phone mr-1.5 w-3 text-center"></i>{{ $warehouse->phone }}</p>
                    @endif
                </div>

                {{-- Mobile inline confirm --}}
                <div x-show="confirmingDelete" x-cloak class="mt-3 pt-3 border-t border-slate-100">
                    @if($cargoCount === 0)
                    <p class="text-xs text-slate-700 mb-2">
                        {!! translate('warehouse.delete_confirm', ['name' => '<strong>' . e($warehouse->name_rus) . '</strong>']) !!}
                    </p>
                    <div class="flex gap-2">
                        <button type="button" @click="confirmingDelete = false"
                                class="flex-1 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-300 rounded-lg">
                            Отмена
                        </button>
                        <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full py-2 text-xs font-medium text-white bg-rose-600 rounded-lg">
                                Удалить
                            </button>
                        </form>
                    </div>
                    @else
                    <p class="text-xs text-amber-800 mb-2">
                        {!! translate('warehouse.delete_blocked', ['count' => '<strong>' . $cargoCount . '</strong>']) !!}
                    </p>
                    <button type="button" @click="confirmingDelete = false"
                            class="w-full py-2 text-xs font-medium text-slate-700 bg-white border border-slate-300 rounded-lg">
                        Закрыть
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($warehouses->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $warehouses->links() }}
        </div>
        @endif

        @endif
    </div>
</div>
@endsection
