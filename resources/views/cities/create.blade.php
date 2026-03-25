@extends('layouts.app')

@section('title', 'Добавить город')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Новый город</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Заполните название на всех трёх языках и укажите страну.
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="{{ route('cities.store') }}" method="POST">
                @csrf
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700">Страна</label>
                            <select name="country" id="country"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('country') border-red-500 @enderror">
                                <option value="">— Выберите страну —</option>
                                <option value="kz" {{ old('country') === 'kz' ? 'selected' : '' }}>🇰🇿 Казахстан</option>
                                <option value="ru" {{ old('country') === 'ru' ? 'selected' : '' }}>🇷🇺 Россия</option>
                                <option value="cn" {{ old('country') === 'cn' ? 'selected' : '' }}>🇨🇳 Китай</option>
                            </select>
                            @error('country')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Базовое название</label>
                            <input type="text" name="name" id="name"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('name') border-red-500 @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="name_rus" class="block text-sm font-medium text-gray-700">Название на русском</label>
                            <input type="text" name="name_rus" id="name_rus"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('name_rus') border-red-500 @enderror"
                                   value="{{ old('name_rus') }}" required>
                            @error('name_rus')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="name_kaz" class="block text-sm font-medium text-gray-700">Название на казахском</label>
                            <input type="text" name="name_kaz" id="name_kaz"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('name_kaz') border-red-500 @enderror"
                                   value="{{ old('name_kaz') }}" required>
                            @error('name_kaz')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="name_chn" class="block text-sm font-medium text-gray-700">Название на китайском</label>
                            <input type="text" name="name_chn" id="name_chn"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('name_chn') border-red-500 @enderror"
                                   value="{{ old('name_chn') }}" required>
                            @error('name_chn')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 flex justify-end space-x-3">
                        <a href="{{ route('cities.index') }}"
                           class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Отмена
                        </a>
                        <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>
                            Добавить город
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
