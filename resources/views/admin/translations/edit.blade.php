@extends('layouts.app')

@section('title', 'Редактировать перевод')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.translations.index') }}"
           class="p-2 rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 transition-colors">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Редактировать перевод</h1>
            <p class="text-xs font-mono text-slate-500 mt-0.5">{{ $translation->key }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <form action="{{ route('admin.translations.update', $translation) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-5">

                {{-- Key (readonly) --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Ключ перевода</label>
                    <input type="text" value="{{ $translation->key }}" readonly
                           class="w-full rounded-lg border-slate-200 bg-slate-50 text-slate-500 text-sm font-mono cursor-not-allowed">
                    <p class="mt-1 text-xs text-slate-400">Ключ перевода нельзя изменить</p>
                </div>

                {{-- Group --}}
                <div>
                    <label for="group" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Группа <span class="text-rose-500">*</span>
                    </label>
                    <select id="group" name="group"
                            class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('group') border-rose-400 @enderror"
                            onchange="toggleNewGroup(this)">
                        @foreach($groups as $group)
                            <option value="{{ $group }}" {{ old('group', $translation->group) == $group ? 'selected' : '' }}>
                                {{ ucfirst($group) }}
                            </option>
                        @endforeach
                        <option value="new_group" {{ old('group') == 'new_group' ? 'selected' : '' }}>
                            + Новая группа
                        </option>
                    </select>
                    @error('group')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                {{-- New group name (hidden by default) --}}
                <div id="new_group_field" class="hidden">
                    <label for="new_group_name" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Название новой группы <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="new_group_name" name="new_group_name" value="{{ old('new_group_name') }}"
                           placeholder="например: notifications"
                           class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Описание
                    </label>
                    <textarea id="description" name="description" rows="2"
                              placeholder="Для чего используется этот перевод..."
                              class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-rose-400 @enderror">{{ old('description', $translation->description) }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="border-t border-slate-100 pt-5">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Переводы</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="ru" class="block text-sm font-medium text-slate-700 mb-1.5">
                                <span class="mr-1">🇷🇺</span> Русский <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="ru" name="ru" rows="4"
                                      placeholder="Перевод на русском..."
                                      class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('ru') border-rose-400 @enderror">{{ old('ru', $translation->ru) }}</textarea>
                            @error('ru')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="kz" class="block text-sm font-medium text-slate-700 mb-1.5">
                                <span class="mr-1">🇰🇿</span> Қазақша <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="kz" name="kz" rows="4"
                                      placeholder="Қазақша аударма..."
                                      class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('kz') border-rose-400 @enderror">{{ old('kz', $translation->kz) }}</textarea>
                            @error('kz')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="cn" class="block text-sm font-medium text-slate-700 mb-1.5">
                                <span class="mr-1">🇨🇳</span> 中文 <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="cn" name="cn" rows="4"
                                      placeholder="中文翻译..."
                                      class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('cn') border-rose-400 @enderror">{{ old('cn', $translation->cn) }}</textarea>
                            @error('cn')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.translations.index') }}"
                   class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                    Отмена
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-save text-xs"></i>
                    Сохранить
                </button>
            </div>
        </form>
    </div>

</div>

<script>
function toggleNewGroup(select) {
    const field = document.getElementById('new_group_field');
    const input = document.getElementById('new_group_name');
    if (select.value === 'new_group') {
        field.classList.remove('hidden');
        input.required = true;
    } else {
        field.classList.add('hidden');
        input.required = false;
    }
}
document.addEventListener('DOMContentLoaded', function() {
    toggleNewGroup(document.getElementById('group'));
});
</script>
@endsection
