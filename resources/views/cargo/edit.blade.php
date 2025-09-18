@extends('layouts.app')

@section('title', 'Редактировать груз - Silk Way')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Редактировать груз</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Измените информацию о грузе
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="{{ route('cargo.update', $cargo) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="from_location" class="block text-sm font-medium text-gray-700">
                                    Откуда
                                </label>
                                <input type="text" name="from_location" id="from_location" 
                                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('from_location', $cargo->from_location) }}" required>
                            </div>

                            <div>
                                <label for="to_location" class="block text-sm font-medium text-gray-700">
                                    Куда
                                </label>
                                <input type="text" name="to_location" id="to_location" 
                                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('to_location', $cargo->to_location) }}" required>
                            </div>
                        </div>

                        <div>
                            <label for="cargo_type" class="block text-sm font-medium text-gray-700">
                                Тип груза
                            </label>
                            <input type="text" name="cargo_type" id="cargo_type" 
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                   value="{{ old('cargo_type', $cargo->cargo_type) }}" required>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="volume" class="block text-sm font-medium text-gray-700">
                                    Объем (м³)
                                </label>
                                <input type="number" step="0.01" name="volume" id="volume" 
                                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('volume', $cargo->volume) }}" required>
                            </div>

                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700">
                                    Вес (кг)
                                </label>
                                <input type="number" step="0.01" name="weight" id="weight" 
                                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('weight', $cargo->weight) }}" required>
                            </div>
                        </div>

                        <div>
                            <label for="ready_date" class="block text-sm font-medium text-gray-700">
                                Дата и время готовности
                            </label>
                            <input type="datetime-local" name="ready_date" id="ready_date" 
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                   value="{{ old('ready_date', $cargo->ready_date->format('Y-m-d\TH:i')) }}" required>
                        </div>

                        <div>
                            <label for="comment" class="block text-sm font-medium text-gray-700">
                                Комментарий / контакт
                            </label>
                            <textarea name="comment" id="comment" rows="3" 
                                      class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                      placeholder="Дополнительная информация, контактные данные...">{{ old('comment', $cargo->comment) }}</textarea>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="button" 
                                onclick="history.back()"
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Отмена
                        </button>
                        <button type="submit" 
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>
                            Обновить груз
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 