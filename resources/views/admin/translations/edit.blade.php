@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('edit_translation') }}</h1>
            <a href="{{ route('admin.translations.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('back') }}
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.translations.update', $translation) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Ключ перевода (только для чтения) -->
                    <div class="md:col-span-2">
                        <label for="key" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('translation_key') }}
                        </label>
                        <input type="text" 
                               id="key" 
                               value="{{ $translation->key }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100"
                               readonly>
                        <p class="mt-1 text-sm text-gray-500">Ключ перевода нельзя изменить</p>
                    </div>

                    <!-- Группа -->
                    <div class="md:col-span-2">
                        <label for="group" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('translation_group') }} *
                        </label>
                        <select id="group" 
                                name="group" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('group') border-red-500 @enderror">
                            @foreach($groups as $group)
                                <option value="{{ $group }}" {{ old('group', $translation->group) == $group ? 'selected' : '' }}>
                                    {{ ucfirst($group) }}
                                </option>
                            @endforeach
                            <option value="new_group" {{ old('group') == 'new_group' ? 'selected' : '' }}>
                                Новая группа
                            </option>
                        </select>
                        @error('group')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Новая группа (показывается только если выбрана опция "Новая группа") -->
                    <div class="md:col-span-2" id="new_group_field" style="display: none;">
                        <label for="new_group_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Название новой группы
                        </label>
                        <input type="text" 
                               id="new_group_name" 
                               name="new_group_name" 
                               value="{{ old('new_group_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Введите название новой группы">
                    </div>

                    <!-- Описание -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('translation_description') }}
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="Опишите, для чего используется этот перевод">{{ old('description', $translation->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Русский перевод -->
                    <div>
                        <label for="rus" class="block text-sm font-medium text-gray-700 mb-2">
                            Русский *
                        </label>
                        <textarea id="rus" 
                                  name="rus" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('rus') border-red-500 @enderror"
                                  placeholder="Введите перевод на русском языке">{{ old('rus', $translation->rus) }}</textarea>
                        @error('rus')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Казахский перевод -->
                    <div>
                        <label for="kaz" class="block text-sm font-medium text-gray-700 mb-2">
                            Қазақша *
                        </label>
                        <textarea id="kaz" 
                                  name="kaz" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kaz') border-red-500 @enderror"
                                  placeholder="Введите перевод на казахском языке">{{ old('kaz', $translation->kaz) }}</textarea>
                        @error('kaz')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Китайский перевод -->
                    <div class="md:col-span-2">
                        <label for="chn" class="block text-sm font-medium text-gray-700 mb-2">
                            中文 *
                        </label>
                        <textarea id="chn" 
                                  name="chn" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('chn') border-red-500 @enderror"
                                  placeholder="Введите перевод на китайском языке">{{ old('chn', $translation->chn) }}</textarea>
                        @error('chn')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('admin.translations.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('cancel') }}
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const groupSelect = document.getElementById('group');
    const newGroupField = document.getElementById('new_group_field');
    const newGroupNameInput = document.getElementById('new_group_name');
    
    function toggleNewGroupField() {
        if (groupSelect.value === 'new_group') {
            newGroupField.style.display = 'block';
            newGroupNameInput.required = true;
        } else {
            newGroupField.style.display = 'none';
            newGroupNameInput.required = false;
        }
    }
    
    groupSelect.addEventListener('change', toggleNewGroupField);
    toggleNewGroupField(); // Вызываем при загрузке страницы
});
</script>
@endsection
