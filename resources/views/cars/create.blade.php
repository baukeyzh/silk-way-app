@extends('layouts.app')

@section('title', 'Добавить машину')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <button type="button" onclick="history.back()"
                class="p-2 rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 transition-colors">
            <i class="fas fa-arrow-left text-sm"></i>
        </button>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Добавить машину</h1>
            <p class="text-sm text-slate-500 mt-0.5">Заполните данные о транспортном средстве</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 flex gap-3">
            <i class="fas fa-exclamation-circle text-rose-500 mt-0.5 flex-shrink-0"></i>
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-sm text-rose-700">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cars.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Basic Info --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h2 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                    <i class="fas fa-truck text-indigo-500"></i>
                    Основная информация
                </h2>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="brand" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Марка <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="brand" id="brand" value="{{ old('brand') }}" required
                           placeholder="Volvo, MAN, Scania..."
                           class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('brand') border-rose-400 @enderror">
                    @error('brand')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="model" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Модель <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="model" id="model" value="{{ old('model') }}" required
                           placeholder="FH16, TGX..."
                           class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('model') border-rose-400 @enderror">
                    @error('model')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="license_plate" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Гос. номер <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="license_plate" id="license_plate" value="{{ old('license_plate') }}" required
                           placeholder="А123БВ77"
                           class="w-full rounded-lg border-slate-300 shadow-sm text-sm font-mono tracking-wider uppercase focus:border-indigo-500 focus:ring-indigo-500 @error('license_plate') border-rose-400 @enderror">
                    @error('license_plate')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="max_weight" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Максимальный вес (т) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" name="max_weight" id="max_weight" value="{{ old('max_weight') }}" required
                               step="0.1" min="0.1" max="100"
                               class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10 @error('max_weight') border-rose-400 @enderror">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400">тонн</span>
                    </div>
                    @error('max_weight')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Trailer Info --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h2 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                    <i class="fas fa-trailer text-indigo-500"></i>
                    Прицеп
                </h2>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label for="trailer_type" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Тип прицепа <span class="text-rose-500">*</span>
                    </label>
                    <select name="trailer_type" id="trailer_type" required
                            class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('trailer_type') border-rose-400 @enderror">
                        <option value="">— Выберите тип —</option>
                        @foreach($trailerTypes as $key => $value)
                            <option value="{{ $key }}" {{ old('trailer_type') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('trailer_type')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-700 mb-2">Габариты (метры) <span class="text-rose-500">*</span></p>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label for="trailer_length" class="block text-xs text-slate-500 mb-1">Длина</label>
                            <input type="number" name="trailer_length" id="trailer_length" value="{{ old('trailer_length') }}" required
                                   step="0.1" min="0.1" max="50" placeholder="13.6"
                                   class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('trailer_length') border-rose-400 @enderror">
                        </div>
                        <div>
                            <label for="trailer_width" class="block text-xs text-slate-500 mb-1">Ширина</label>
                            <input type="number" name="trailer_width" id="trailer_width" value="{{ old('trailer_width') }}" required
                                   step="0.1" min="0.1" max="10" placeholder="2.4"
                                   class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('trailer_width') border-rose-400 @enderror">
                        </div>
                        <div>
                            <label for="trailer_height" class="block text-xs text-slate-500 mb-1">Высота</label>
                            <input type="number" name="trailer_height" id="trailer_height" value="{{ old('trailer_height') }}" required
                                   step="0.1" min="0.1" max="10" placeholder="2.7"
                                   class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('trailer_height') border-rose-400 @enderror">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Document Upload --}}
        {{-- Actions --}}
        <div class="flex justify-end gap-3 pb-6">
            <button type="button" onclick="history.back()"
                    class="px-5 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                Отмена
            </button>
            <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2">
                <i class="fas fa-plus text-xs"></i>
                Добавить машину
            </button>
        </div>
    </form>
</div>
@endsection
