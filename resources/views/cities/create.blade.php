@extends('layouts.app')

@section('title', 'Добавить город')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('cities.index') }}"
           class="p-2 rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 transition-colors">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Новый город</h1>
            <p class="text-sm text-slate-500 mt-0.5">Заполните название на всех трёх языках и укажите страну</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <form action="{{ route('cities.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-5">

                {{-- Country --}}
                <div>
                    <label for="country" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Страна <span class="text-rose-500">*</span>
                    </label>
                    <select name="country" id="country"
                            class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('country') border-rose-400 @enderror">
                        <option value="">— Выберите страну —</option>
                        <option value="kz" {{ old('country') === 'kz' ? 'selected' : '' }}>🇰🇿 Казахстан</option>
                        <option value="ru" {{ old('country') === 'ru' ? 'selected' : '' }}>🇷🇺 Россия</option>
                        <option value="cn" {{ old('country') === 'cn' ? 'selected' : '' }}>🇨🇳 Китай</option>
                    </select>
                    @error('country')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                {{-- Base name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Базовое название <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-rose-400 @enderror">
                    @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="border-t border-slate-100 pt-5">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Названия на языках</p>
                    <div class="space-y-4">
                        <div>
                            <label for="name_rus" class="block text-sm font-medium text-slate-700 mb-1.5">
                                <span class="mr-1.5">🇷🇺</span> Русский <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name_rus" id="name_rus" value="{{ old('name_rus') }}" required
                                   class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name_rus') border-rose-400 @enderror">
                            @error('name_rus')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="name_kaz" class="block text-sm font-medium text-slate-700 mb-1.5">
                                <span class="mr-1.5">🇰🇿</span> Қазақша <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name_kaz" id="name_kaz" value="{{ old('name_kaz') }}" required
                                   class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name_kaz') border-rose-400 @enderror">
                            @error('name_kaz')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="name_chn" class="block text-sm font-medium text-slate-700 mb-1.5">
                                <span class="mr-1.5">🇨🇳</span> 中文 <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name_chn" id="name_chn" value="{{ old('name_chn') }}" required
                                   class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name_chn') border-rose-400 @enderror">
                            @error('name_chn')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('cities.index') }}"
                   class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                    Отмена
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-save text-xs"></i>
                    Добавить город
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
