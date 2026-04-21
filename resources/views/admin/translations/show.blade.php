@extends('layouts.app')

@section('title', translate('admin.translation_detail_title') . ' - Silk Way')

@section('content')
@php
    $locales = [
        'ru' => [
            'label'  => 'Русский',
            'code'   => 'RU',
            'pill'   => 'bg-slate-100 text-slate-700 border border-slate-200',
            'header' => 'bg-slate-50 border-b border-slate-100',
            'char'   => 'text-slate-400',
            'value'  => $translation->ru,
        ],
        'kz' => [
            'label'  => 'Қазақша',
            'code'   => 'KZ',
            'pill'   => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
            'header' => 'bg-emerald-50 border-b border-emerald-100',
            'char'   => 'text-emerald-400',
            'value'  => $translation->kz,
        ],
        'cn' => [
            'label'  => '中文',
            'code'   => 'CN',
            'pill'   => 'bg-amber-100 text-amber-700 border border-amber-200',
            'header' => 'bg-amber-50 border-b border-amber-100',
            'char'   => 'text-amber-400',
            'value'  => $translation->cn,
        ],
    ];

    $snippetBlade   = '{' . '{ __(' . "'" . $translation->key . "'" . ') }' . '}';
    $snippetPHP     = "__('" . $translation->key . "')";
    $snippetHelper  = "LocalizationHelper::t('" . $translation->key . "')";
@endphp

<div class="space-y-6">

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
            <h1 class="text-2xl font-bold text-slate-900">{{ translate('admin.translation_detail_heading') }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ translate('admin.translation_detail_desc') }}</p>
        </div>

        <a href="{{ route('admin.translations.edit', $translation) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shrink-0">
            <i class="fas fa-pencil-alt text-xs"></i>
            {{ translate('edit') }}
        </a>
    </div>

    {{-- Key hero card --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-6 py-5">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200">
                        <i class="fas fa-layer-group text-xs"></i>
                        {{ ucfirst((string) $translation->group) }}
                    </span>
                </div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('admin.translation_key_label') }}</p>
                <code class="block font-mono text-lg font-bold text-slate-900 break-all select-all">{{ $translation->key }}</code>
                @if($translation->description)
                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">{{ $translation->description }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Three-locale grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($locales as $locale => $cfg)
        @php
            $val      = $cfg['value'];
            $hasValue = !empty(trim((string) $val));
            $charCount = mb_strlen((string) $val, 'UTF-8');
        @endphp
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">

            {{-- Locale card header --}}
            <div class="{{ $cfg['header'] }} px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $cfg['pill'] }}">
                        {{ $cfg['code'] }}
                    </span>
                    <span class="text-sm font-semibold text-slate-800">{{ $cfg['label'] }}</span>
                </div>
                @if($hasValue)
                    <span class="text-xs {{ $cfg['char'] }} font-medium">{{ $charCount }} {{ translate('admin.translation_chars') }}</span>
                @endif
            </div>

            {{-- Locale card body --}}
            <div class="px-4 py-4 flex-1">
                @if($hasValue)
                    <p class="text-sm text-slate-800 leading-relaxed break-words">{{ $val }}</p>
                @else
                    <div class="flex flex-col items-center justify-center py-6 text-center">
                        <div class="w-9 h-9 bg-amber-100 rounded-full flex items-center justify-center mb-2">
                            <i class="fas fa-language text-amber-500 text-sm"></i>
                        </div>
                        <p class="text-xs font-semibold text-amber-700">{{ translate('admin.translation_not_translated') }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ translate('admin.translation_not_translated_hint') }}</p>
                    </div>
                @endif
            </div>

        </div>
        @endforeach
    </div>

    {{-- Metadata row --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-6 py-5">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">{{ translate('admin.translation_meta_heading') }}</p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-5">
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">{{ translate('admin.translation_key_label') }}</p>
                <p class="mt-1 text-sm font-semibold text-slate-900 font-mono truncate" title="{{ $translation->key }}">{{ $translation->key }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">{{ translate('admin.translation_group_label') }}</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">{{ ucfirst((string) $translation->group) }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">{{ translate('admin.translation_created_label') }}</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">{{ $translation->created_at->format('d.m.Y H:i') }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">{{ translate('admin.translation_updated_label') }}</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">{{ $translation->updated_at->format('d.m.Y H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Code snippets --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <p class="text-sm font-semibold text-slate-800">{{ translate('admin.translation_code_heading') }}</p>
            <p class="text-xs text-slate-400 mt-0.5">{{ translate('admin.translation_code_desc') }}</p>
        </div>

        <div class="bg-slate-900 px-6 py-5 space-y-5" x-data="translationSnippets()">

            {{-- Blade template --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ translate('admin.translation_code_blade') }}</span>
                    <button @click="copy('blade')"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium text-slate-300 hover:text-white hover:bg-slate-700 transition-colors">
                        <i class="fas" :class="copied.blade ? 'fa-check text-emerald-400' : 'fa-copy'"></i>
                        <span x-text="copied.blade ? '{{ translate('admin.translation_code_copied') }}' : '{{ translate('admin.translation_code_copy') }}'"></span>
                    </button>
                </div>
                <div class="bg-slate-800 rounded-lg px-4 py-3 font-mono text-sm leading-relaxed overflow-x-auto">
                    <span class="text-slate-500">{{-- {{ translate('admin.translation_code_in_blade') }} --}}</span><br>
                    <span class="text-indigo-400">{{ $snippetBlade }}</span>
                </div>
            </div>

            {{-- PHP controller --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ translate('admin.translation_code_php') }}</span>
                    <button @click="copy('php')"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium text-slate-300 hover:text-white hover:bg-slate-700 transition-colors">
                        <i class="fas" :class="copied.php ? 'fa-check text-emerald-400' : 'fa-copy'"></i>
                        <span x-text="copied.php ? '{{ translate('admin.translation_code_copied') }}' : '{{ translate('admin.translation_code_copy') }}'"></span>
                    </button>
                </div>
                <div class="bg-slate-800 rounded-lg px-4 py-3 font-mono text-sm leading-relaxed overflow-x-auto">
                    <span class="text-slate-500">{{-- {{ translate('admin.translation_code_in_controller') }} --}}</span><br>
                    <span class="text-indigo-400">__</span><span class="text-slate-100">(</span><span class="text-amber-300">'{{ $translation->key }}'</span><span class="text-slate-100">)</span>
                </div>
            </div>

            {{-- Helper --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ translate('admin.translation_code_helper') }}</span>
                    <button @click="copy('helper')"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium text-slate-300 hover:text-white hover:bg-slate-700 transition-colors">
                        <i class="fas" :class="copied.helper ? 'fa-check text-emerald-400' : 'fa-copy'"></i>
                        <span x-text="copied.helper ? '{{ translate('admin.translation_code_copied') }}' : '{{ translate('admin.translation_code_copy') }}'"></span>
                    </button>
                </div>
                <div class="bg-slate-800 rounded-lg px-4 py-3 font-mono text-sm leading-relaxed overflow-x-auto">
                    <span class="text-slate-500">{{-- {{ translate('admin.translation_code_in_helper') }} --}}</span><br>
                    <span class="text-indigo-400">LocalizationHelper</span><span class="text-slate-100">::</span><span class="text-indigo-400">t</span><span class="text-slate-100">(</span><span class="text-amber-300">'{{ $translation->key }}'</span><span class="text-slate-100">)</span>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
function translationSnippets() {
    return {
        copied: { blade: false, php: false, helper: false },
        snippets: {
            blade:  @json($snippetBlade),
            php:    @json($snippetPHP),
            helper: @json($snippetHelper),
        },
        copy(key) {
            if (!navigator.clipboard) return;
            navigator.clipboard.writeText(this.snippets[key]).then(() => {
                this.copied[key] = true;
                setTimeout(() => { this.copied[key] = false; }, 2000);
            });
        }
    };
}
</script>
@endsection
