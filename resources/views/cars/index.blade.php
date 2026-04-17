@extends('layouts.app')

@section('title', translate('cars.all_cars'))

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ translate('cars.all_cars') }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ translate('cars.all_cars_description') }}</p>
        </div>
        @if(auth()->user()->isAdmin() || auth()->user()->isWarehouseEmployee())
        <a href="{{ route('cars.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm shrink-0">
            <i class="fas fa-plus mr-2"></i>{{ translate('cars.add_car') }}
        </a>
        @endif
    </div>

    {{-- Search + filter --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <form method="GET" action="{{ route('cars.all') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400 text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="block w-full pl-10 pr-3 py-2.5 text-sm rounded-lg border border-slate-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       placeholder="{{ translate('search_placeholder') }}">
            </div>
            <select name="status"
                    class="block px-3 py-2.5 text-sm rounded-lg border border-slate-300 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="">{{ translate('cars.all_statuses') }}</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ translate('cars.status_active') }}</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ translate('cars.status_inactive') }}</option>
            </select>
            <button type="submit"
                    class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition-colors shrink-0">
                <i class="fas fa-filter mr-1.5"></i>{{ translate('cars.filter') }}
            </button>
        </form>
    </div>

    {{-- Car card grid --}}
    @if($cars->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($cars as $car)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow cursor-pointer car-card"
             onclick="handleCardClick(event, '{{ route('cars.show', $car) }}')">

            {{-- Card header --}}
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 px-5 pt-5 pb-4 border-b border-slate-200">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl border border-slate-200 flex items-center justify-center shadow-sm">
                            <i class="fas fa-truck text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">{{ $car->brand }} {{ $car->model }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5 font-mono">{{ $car->license_plate }}</p>
                        </div>
                    </div>
                    @if($car->is_active)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                            <i class="fas fa-circle text-xs mr-1"></i>{{ translate('cars.status_active') }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-200 text-slate-600">
                            <i class="fas fa-circle text-xs mr-1"></i>{{ translate('cars.status_inactive') }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Card body --}}
            <div class="px-5 py-4">
                {{-- Driver --}}
                <div class="flex items-center gap-2.5 mb-3 pb-3 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-semibold shrink-0">
                        {{ strtoupper(substr($car->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-800">{{ $car->user->name }}</p>
                        <p class="text-xs text-slate-400">{{ $car->user->email }}</p>
                    </div>
                </div>

                {{-- Trailer info --}}
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                        {{ $car->trailer_type_ru }}
                    </span>
                    <span class="text-xs text-slate-500 font-mono">{{ $car->trailer_length }}×{{ $car->trailer_width }}×{{ $car->trailer_height }} м</span>
                </div>

                {{-- Max weight --}}
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-500">{{ translate('cars.dimensions') }}</span>
                    <span class="font-medium text-slate-700">{{ $car->max_weight ?? '—' }} т</span>
                </div>
            </div>

            {{-- Card footer --}}
            <div class="px-5 pb-4 flex items-center justify-between gap-2" onclick="event.stopPropagation()">
                <a href="{{ route('cars.show', $car) }}"
                   class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition-colors">
                    <i class="fas fa-eye mr-1.5"></i>{{ translate('cars.view') }}
                </a>
                @if(auth()->user()->isAdmin() || auth()->user()->isWarehouseEmployee())
                <a href="{{ route('cars.edit', $car) }}"
                   class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium rounded-lg transition-colors">
                    <i class="fas fa-pencil-alt mr-1.5"></i>{{ translate('cars.edit') }}
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $cars->links() }}
    </div>

    @else
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm py-16 text-center">
        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-truck text-slate-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-slate-700 mb-1">Машины не найдены</h3>
        <p class="text-sm text-slate-500 mb-6">
            @if(request('search') || request('status'))
                Попробуйте изменить параметры поиска
            @else
                Нет зарегистрированных машин
            @endif
        </p>
        @if(request('search') || request('status'))
        <a href="{{ route('cars.all') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-times mr-2"></i>Сбросить фильтры
        </a>
        @endif
    </div>
    @endif
</div>

<script>
function handleCardClick(event, url) {
    if (event.target.closest('a, button, form')) return;
    window.location.href = url;
}
</script>
@endsection
