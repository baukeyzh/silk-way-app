@extends('layouts.app')

@section('title', translate('admin.translation_edit_title') . ' - Silk Way')

@section('content')
@php
    $localeConfig = [
        'ru' => [
            'label'    => translate('admin.translation_ru_label'),
            'code'     => 'RU',
            'pill'     => 'bg-slate-100 text-slate-700 border border-slate-200',
            'header'   => 'bg-slate-50 border-b border-slate-100',
            'char'     => 'text-slate-400',
            'required' => false,
            'placeholder' => translate('admin.translation_ru_placeholder'),
            'value'    => old('ru', $translation->ru),
        ],
        'kz' => [
            'label'    => translate('admin.translation_kz_label'),
            'code'     => 'KZ',
            'pill'     => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
            'header'   => 'bg-emerald-50 border-b border-emerald-100',
            'char'     => 'text-emerald-400',
            'required' => false,
            'placeholder' => translate('admin.translation_kz_placeholder'),
            'value'    => old('kz', $translation->kz),
        ],
        'cn' => [
            'label'    => translate('admin.translation_cn_label'),
            'code'     => 'CN',
            'pill'     => 'bg-amber-100 text-amber-700 border border-amber-200',
            'header'   => 'bg-amber-50 border-b border-amber-100',
            'char'     => 'text-amber-400',
            'required' => false,
            'placeholder' => translate('admin.translation_cn_placeholder'),
            'value'    => old('cn', $translation->cn),
        ],
    ];

    $errorCount = count($errors->all());
    $existingGroupsJson = json_encode($groups->values()->toArray(), JSON_UNESCAPED_UNICODE);
@endphp

<div class="space-y-6" x-data="translationEditForm({{ $existingGroupsJson }})">

    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.translations.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-indigo-600 transition-colors">
                    <i class="fas fa-arrow-left text-xs"></i>
                    {{ translate('admin.translation_back_to_list') }}
                </a>
            </div>
            <h1 class="text-2xl font-bold text-slate-900">{{ translate('admin.translation_edit_title') }}</h1>
            <p class="mt-1 text-sm text-slate-500 font-mono">{{ $translation->key }}</p>
        </div>

        <a href="{{ route('admin.translations.show', $translation) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors shrink-0">
            <i class="fas fa-eye text-xs"></i>
            {{ translate('admin.translation_detail_heading') }}
        </a>
    </div>

    {{-- Validation error summary banner --}}
    @if($errors->any())
    <div class="flex items-start gap-3 bg-rose-50 border border-rose-200 rounded-xl px-5 py-4">
        <div class="shrink-0 w-8 h-8 bg-rose-100 rounded-full flex items-center justify-center mt-0.5">
            <i class="fas fa-exclamation-triangle text-rose-500 text-xs"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-rose-700">{{ translate('admin.translation_error_summary') }}</p>
            <p class="text-xs text-rose-500 mt-0.5">{{ str_replace(':count', $errorCount, translate('admin.translation_error_count')) }}</p>
            <ul class="mt-2 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li class="text-xs text-rose-600 flex items-center gap-1.5">
                        <span class="w-1 h-1 bg-rose-400 rounded-full shrink-0"></span>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Main form card --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <form action="{{ route('admin.translations.update', $translation) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            {{-- ── Section 1: Meta ─────────────────────────────────────────────── --}}
            <div class="px-6 pt-6 pb-5 space-y-5">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-5 h-5 bg-indigo-100 rounded flex items-center justify-center">
                        <i class="fas fa-tag text-indigo-500 text-xs"></i>
                    </div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ translate('admin.translation_section_meta') }}</p>
                </div>

                {{-- Key (readonly on edit) --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        {{ translate('admin.translation_key_label') }}
                    </label>
                    <input type="text"
                           value="{{ $translation->key }}"
                           readonly
                           class="w-full rounded-lg border-slate-200 bg-slate-50 text-slate-500 text-sm font-mono shadow-sm cursor-not-allowed">
                    <p class="mt-1.5 text-xs text-slate-400">{{ translate('admin.translation_key_readonly_hint') }}</p>
                </div>

                {{-- Group (Alpine autocomplete) --}}
                <div class="relative" x-data="{ open: false, query: '{{ old('group', $translation->group) }}' }">
                    <label for="group" class="block text-sm font-medium text-slate-700 mb-1.5">
                        {{ translate('admin.translation_group_label') }}
                    </label>
                    <div class="relative">
                        <input type="text"
                               id="group"
                               name="group"
                               x-model="query"
                               @input="open = query.length > 0"
                               @focus="open = filteredGroups().length > 0"
                               @click.away="open = false"
                               @keydown.escape="open = false"
                               @keydown.tab="open = false"
                               placeholder="{{ translate('admin.translation_group_placeholder') }}"
                               autocomplete="off"
                               spellcheck="false"
                               class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('group') border-rose-400 bg-rose-50 @enderror pr-8">
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                        </div>
                    </div>

                    {{-- Dropdown suggestions --}}
                    <div x-show="open && filteredGroups().length > 0"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute z-10 mt-1 w-full bg-white border border-slate-200 rounded-lg shadow-lg overflow-hidden"
                         style="display: none;">
                        <ul class="py-1 max-h-48 overflow-y-auto">
                            <template x-for="grp in filteredGroups()" :key="grp">
                                <li>
                                    <button type="button"
                                            @click="query = grp; open = false"
                                            class="w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 flex items-center gap-2 transition-colors">
                                        <i class="fas fa-layer-group text-slate-300 text-xs"></i>
                                        <span x-text="grp" class="font-medium"></span>
                                    </button>
                                </li>
                            </template>
                        </ul>
                    </div>

                    @error('group')
                        <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                            <i class="fas fa-circle-exclamation text-xs"></i>
                            {{ $message }}
                        </p>
                    @else
                        <p class="mt-1.5 text-xs text-slate-400">{{ translate('admin.translation_group_hint') }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">
                        {{ translate('admin.translation_description_label') }}
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="2"
                              placeholder="{{ translate('admin.translation_description_placeholder') }}"
                              class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-rose-400 bg-rose-50 @enderror">{{ old('description', $translation->description) }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                            <i class="fas fa-circle-exclamation text-xs"></i>
                            {{ $message }}
                        </p>
                    @else
                        <p class="mt-1.5 text-xs text-slate-400">{{ translate('admin.translation_description_hint') }}</p>
                    @enderror
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-slate-100"></div>

            {{-- ── Section 2: Locales ───────────────────────────────────────────── --}}
            <div class="px-6 pt-5 pb-6 space-y-4">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-5 h-5 bg-indigo-100 rounded flex items-center justify-center">
                        <i class="fas fa-language text-indigo-500 text-xs"></i>
                    </div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ translate('admin.translation_section_locales') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($localeConfig as $locale => $cfg)
                    @php $initialCount = mb_strlen((string) $cfg['value'], 'UTF-8'); @endphp
                    <div class="rounded-xl border border-slate-200 overflow-hidden flex flex-col"
                         x-data="{ count: {{ $initialCount }} }">

                        {{-- Locale card header --}}
                        <div class="{{ $cfg['header'] }} px-4 py-3 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $cfg['pill'] }}">
                                    {{ $cfg['code'] }}
                                </span>
                                <span class="text-sm font-semibold text-slate-800">{{ $cfg['label'] }}</span>
                                <span class="text-xs text-slate-400 font-normal italic">{{ translate('admin.translation_optional_badge') }}</span>
                            </div>
                            <span class="text-xs {{ $cfg['char'] }} font-medium tabular-nums"
                                  x-text="count + '\u00a0{{ translate('admin.translation_chars_suffix') }}'"></span>
                        </div>

                        {{-- Textarea --}}
                        <div class="flex-1 flex flex-col px-4 py-3">
                            <textarea id="{{ $locale }}"
                                      name="{{ $locale }}"
                                      rows="5"
                                      @input="count = $el.value.replace(/\r/g, '').length"
                                      placeholder="{{ $cfg['placeholder'] }}"
                                      class="flex-1 w-full resize-none rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error($locale) border-rose-400 bg-rose-50 @enderror">{{ $cfg['value'] }}</textarea>
                            @error($locale)
                                <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation text-xs"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Sticky footer action bar ─────────────────────────────────────── --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.translations.index') }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 min-h-[44px] text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                    {{ translate('admin.translation_btn_cancel') }}
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 min-h-[44px] text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    <i class="fas fa-save text-xs"></i>
                    {{ translate('admin.translation_btn_save') }}
                </button>
            </div>

        </form>
    </div>

</div>

<script>
function translationEditForm(existingGroups) {
    return {
        existingGroups: existingGroups || [],
        filteredGroups() {
            const q = (this.query || '').toLowerCase().trim();
            if (!q) return this.existingGroups.slice(0, 12);
            return this.existingGroups.filter(g => g.toLowerCase().includes(g)).slice(0, 12);
        },
        query: '{{ old('group', $translation->group) }}',
        open: false,
    };
}
</script>
@endsection
