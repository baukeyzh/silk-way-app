@extends('layouts.app')

@section('title', $car->brand . ' ' . $car->model)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <button type="button" onclick="history.back()"
                    class="p-2 rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left text-sm"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $car->brand }} {{ $car->model }}</h1>
                <p class="text-sm text-slate-500 mt-0.5 font-mono">{{ $car->license_plate }}</p>
            </div>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
            {{ $car->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $car->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
            {{ $car->is_active ? 'Активна' : 'Неактивна' }}
        </span>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex gap-3">
            <i class="fas fa-check-circle text-emerald-500 flex-shrink-0"></i>
            <p class="text-sm text-emerald-700">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Main Info --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h2 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                    <i class="fas fa-truck text-indigo-500"></i>
                    Основная информация
                </h2>
            </div>
            <dl class="divide-y divide-slate-100">
                <div class="px-6 py-3.5 flex justify-between items-center">
                    <dt class="text-sm text-slate-500">Водитель</dt>
                    <dd class="text-sm font-medium text-slate-900">{{ $car->user->name }}</dd>
                </div>
                <div class="px-6 py-3.5 flex justify-between items-center">
                    <dt class="text-sm text-slate-500">Email</dt>
                    <dd class="text-sm text-slate-700">{{ $car->user->email }}</dd>
                </div>
                <div class="px-6 py-3.5 flex justify-between items-center">
                    <dt class="text-sm text-slate-500">Марка</dt>
                    <dd class="text-sm font-medium text-slate-900">{{ $car->brand }}</dd>
                </div>
                <div class="px-6 py-3.5 flex justify-between items-center">
                    <dt class="text-sm text-slate-500">Модель</dt>
                    <dd class="text-sm font-medium text-slate-900">{{ $car->model }}</dd>
                </div>
                <div class="px-6 py-3.5 flex justify-between items-center">
                    <dt class="text-sm text-slate-500">Гос. номер</dt>
                    <dd class="text-sm font-mono font-semibold text-slate-900 tracking-wider">{{ $car->license_plate }}</dd>
                </div>
                <div class="px-6 py-3.5 flex justify-between items-center">
                    <dt class="text-sm text-slate-500">Макс. вес</dt>
                    <dd class="text-sm font-semibold text-slate-900">{{ $car->max_weight }} т</dd>
                </div>
            </dl>
        </div>

        {{-- Trailer Info --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h2 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                    <i class="fas fa-trailer text-indigo-500"></i>
                    Прицеп
                </h2>
            </div>
            <dl class="divide-y divide-slate-100">
                <div class="px-6 py-3.5 flex justify-between items-center">
                    <dt class="text-sm text-slate-500">Тип</dt>
                    <dd class="text-sm font-medium text-slate-900">{{ $car->trailer_type_ru }}</dd>
                </div>
                <div class="px-6 py-3.5">
                    <dt class="text-sm text-slate-500 mb-2">Габариты</dt>
                    <dd class="grid grid-cols-3 gap-3">
                        <div class="bg-slate-50 rounded-lg p-2.5 text-center">
                            <p class="text-xs text-slate-400 mb-0.5">Длина</p>
                            <p class="text-sm font-semibold text-slate-900">{{ $car->trailer_length }} м</p>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-2.5 text-center">
                            <p class="text-xs text-slate-400 mb-0.5">Ширина</p>
                            <p class="text-sm font-semibold text-slate-900">{{ $car->trailer_width }} м</p>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-2.5 text-center">
                            <p class="text-xs text-slate-400 mb-0.5">Высота</p>
                            <p class="text-sm font-semibold text-slate-900">{{ $car->trailer_height }} м</p>
                        </div>
                    </dd>
                </div>
                <div class="px-6 py-3.5 flex justify-between items-center">
                    <dt class="text-sm text-slate-500">Объём</dt>
                    <dd class="text-sm font-semibold text-slate-900">{{ $car->trailer_volume }} м³</dd>
                </div>
            </dl>
        </div>
    </div>


    {{-- Actions --}}
    @if(auth()->user()->id === $car->user_id)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-wrap gap-3">
            <a href="{{ route('cars.edit', $car) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fas fa-edit text-xs"></i>
                Редактировать
            </a>

            <form action="{{ route('cars.toggle-status', $car) }}" method="POST">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                               {{ $car->is_active
                                  ? 'text-amber-700 bg-amber-50 border border-amber-200 hover:bg-amber-100'
                                  : 'text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100' }}">
                    <i class="fas {{ $car->is_active ? 'fa-pause' : 'fa-play' }} text-xs"></i>
                    {{ $car->is_active ? 'Деактивировать' : 'Активировать' }}
                </button>
            </form>

            <form action="{{ route('cars.destroy', $car) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-rose-700 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors"
                        onclick="return confirm('Удалить эту машину?')">
                    <i class="fas fa-trash text-xs"></i>
                    Удалить
                </button>
            </form>
        </div>
    @endif

</div>
@endsection
