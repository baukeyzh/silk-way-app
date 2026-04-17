@extends('layouts.app')

@section('title', translate('header.my_cars'))

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Мои машины</h1>
            <p class="mt-1 text-sm text-slate-500">Управляйте своими зарегистрированными машинами</p>
        </div>
        <a href="{{ route('cars.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm shrink-0">
            <i class="fas fa-plus mr-2"></i>Добавить машину
        </a>
    </div>

    {{-- Car card grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">

        {{-- Add new car card --}}
        <a href="{{ route('cars.create') }}"
           class="flex flex-col items-center justify-center bg-white rounded-xl border-2 border-dashed border-slate-300 hover:border-indigo-400 hover:bg-indigo-50/30 p-8 text-center transition-colors min-h-[200px]">
            <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center mb-3">
                <i class="fas fa-plus text-slate-400 text-xl"></i>
            </div>
            <p class="text-sm font-semibold text-slate-600">Добавить машину</p>
            <p class="text-xs text-slate-400 mt-1">Зарегистрировать новое ТС</p>
        </a>

        @foreach($cars as $car)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
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
                            <i class="fas fa-circle text-xs mr-1"></i>Активна
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-200 text-slate-600">
                            <i class="fas fa-circle text-xs mr-1"></i>Неактивна
                        </span>
                    @endif
                </div>
            </div>

            {{-- Card body --}}
            <div class="px-5 py-4">
                {{-- Trailer badge + dimensions --}}
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                        {{ $car->trailer_type_ru }}
                    </span>
                    <span class="text-xs text-slate-500 font-mono">{{ $car->trailer_length }}×{{ $car->trailer_width }}×{{ $car->trailer_height }} м</span>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <p class="text-slate-400">Макс. вес</p>
                        <p class="font-semibold text-slate-800 mt-0.5">{{ $car->max_weight }} т</p>
                    </div>
                    <div>
                        <p class="text-slate-400">Объём</p>
                        <p class="font-semibold text-slate-800 mt-0.5">{{ $car->trailer_volume }} м³</p>
                    </div>
                </div>
            </div>

            {{-- Card footer --}}
            <div class="px-5 pb-4 flex items-center justify-between gap-2" onclick="event.stopPropagation()">
                <a href="{{ route('cars.show', $car) }}"
                   class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition-colors">
                    <i class="fas fa-eye mr-1.5"></i>Просмотр
                </a>
                <a href="{{ route('cars.edit', $car) }}"
                   class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium rounded-lg transition-colors">
                    <i class="fas fa-pencil-alt mr-1.5"></i>Изменить
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @if($cars->count() === 0)
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm py-12 text-center">
        <p class="text-sm text-slate-500">Добавьте первую машину, нажав на карточку выше</p>
    </div>
    @endif

    {{-- Pagination --}}
    @if($cars->count() > 0)
    <div>{{ $cars->links() }}</div>
    @endif
</div>

<script>
function handleCardClick(event, url) {
    if (event.target.closest('a, button, form')) return;
    window.location.href = url;
}
</script>
@endsection
