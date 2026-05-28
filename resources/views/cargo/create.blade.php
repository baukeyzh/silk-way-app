@extends('layouts.app')

@section('title', translate('cargo.create_title'))

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <button type="button" onclick="history.back()"
                class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-600 transition-colors shrink-0">
            <i class="fas fa-arrow-left text-sm"></i>
        </button>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ translate('cargo.new_cargo') }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">{{ translate('cargo.create_desc') }}</p>
        </div>
    </div>

    <form action="{{ route('cargo.store') }}" method="POST">
        @csrf

        <div class="space-y-5">

            {{-- Route section with warehouse picker --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6"
                 x-data="{
                     warehousesByCity: {{ $warehousesByCity }},
                     fromCityId: '{{ old('from_city_id', '') }}',
                     toCityId: '{{ old('to_city_id', '') }}',
                     fromWarehouseId: '{{ old('from_warehouse_id', '') }}',
                     toWarehouseId: '{{ old('to_warehouse_id', '') }}',
                     get fromWarehouses() {
                         return this.fromCityId ? (this.warehousesByCity[this.fromCityId] || []) : [];
                     },
                     get toWarehouses() {
                         return this.toCityId ? (this.warehousesByCity[this.toCityId] || []) : [];
                     },
                 }">
                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">
                    <i class="fas fa-route mr-2 text-indigo-500"></i>Маршрут
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-[1fr_auto_1fr] gap-4 items-start">

                    {{-- FROM side --}}
                    <div class="space-y-3">
                        {{-- City --}}
                        <div>
                            <label for="from_city_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                                {{ translate('cargo.from_location') }} <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="from_city_id" id="from_city_id"
                                        x-model="fromCityId"
                                        @change="fromWarehouseId = ''"
                                        class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('from_city_id') border-rose-400 @enderror appearance-none pr-8"
                                        required>
                                    <option value="">{{ translate('cargo.select_city') }}</option>
                                    @php
                                        $countryLabels = [
                                            'kz' => '🇰🇿 ' . translate('country.kz'),
                                            'ru' => '🇷🇺 ' . translate('country.ru'),
                                            'cn' => '🇨🇳 ' . translate('country.cn'),
                                        ];
                                    @endphp
                                    @foreach($countryLabels as $code => $countryLabel)
                                        @if($cities->has($code))
                                            <optgroup label="{{ $countryLabel }}">
                                                @foreach($cities[$code] as $city)
                                                    <option value="{{ $city->id }}">{{ $city->localized_name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                            @error('from_city_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- FROM Warehouse sub-block --}}
                        <div x-show="fromCityId !== ''" x-cloak>
                            {{-- Multiple warehouses: dropdown --}}
                            <template x-if="fromWarehouses.length > 1">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                        {{ translate('cargo.from_warehouse') }} <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select name="from_warehouse_id"
                                                x-model="fromWarehouseId"
                                                class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('from_warehouse_id') border-rose-400 @enderror appearance-none pr-8"
                                                required>
                                            <option value="" disabled>Выберите склад</option>
                                            <template x-for="wh in fromWarehouses" :key="wh.id">
                                                <option :value="wh.id"
                                                        :selected="String(wh.id) === String(fromWarehouseId)"
                                                        x-text="wh.name_rus.length > 30 ? wh.name_rus.substring(0, 27) + '...' : wh.name_rus + ' (' + (wh.address.length > 30 ? wh.address.substring(0, 27) + '...' : wh.address) + ')'">
                                                </option>
                                            </template>
                                        </select>
                                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                    </div>
                                    @error('from_warehouse_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                                </div>
                            </template>

                            {{-- Exactly one warehouse: readonly badge + hidden input --}}
                            <template x-if="fromWarehouses.length === 1">
                                <div>
                                    <input type="hidden" name="from_warehouse_id" :value="fromWarehouses[0].id">
                                    <div class="bg-slate-100 text-slate-700 rounded-lg px-3 py-2 text-sm flex items-center gap-2">
                                        <i class="fas fa-warehouse text-slate-400"></i>
                                        <span>{{ translate('cargo.warehouse_label', ['name' => '']) }}</span>
                                        <span class="font-medium" x-text="fromWarehouses[0].name_rus"></span>
                                    </div>
                                </div>
                            </template>

                            {{-- Zero warehouses: amber banner --}}
                            <template x-if="fromWarehouses.length === 0">
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-sm text-amber-800">
                                    <p class="font-medium">{{ translate('warehouse.no_warehouses_city') }}. {{ translate('warehouse.create_prompt') }}.</p>
                                    <a :href="'/warehouses/create?city_id=' + fromCityId"
                                       target="_blank"
                                       class="inline-flex items-center mt-2 px-3 py-1.5 bg-amber-100 hover:bg-amber-200 border border-amber-300 text-amber-800 text-xs font-medium rounded-lg transition-colors">
                                        <i class="fas fa-plus mr-1.5"></i>{{ translate('warehouse.create_link') }}
                                    </a>
                                    <p class="mt-2 text-xs text-amber-700">{{ translate('warehouse.autocreate_note') }}</p>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Arrow separator --}}
                    <div class="hidden sm:flex items-center justify-center pt-7">
                        <div class="w-9 h-9 rounded-full bg-indigo-50 border border-indigo-200 flex items-center justify-center">
                            <i class="fas fa-arrow-right text-indigo-500 text-sm"></i>
                        </div>
                    </div>

                    {{-- TO side --}}
                    <div class="space-y-3">
                        {{-- City --}}
                        <div>
                            <label for="to_city_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                                {{ translate('cargo.to_location') }} <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="to_city_id" id="to_city_id"
                                        x-model="toCityId"
                                        @change="toWarehouseId = ''"
                                        class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('to_city_id') border-rose-400 @enderror appearance-none pr-8"
                                        required>
                                    <option value="">{{ translate('cargo.select_city') }}</option>
                                    @foreach($countryLabels as $code => $countryLabel)
                                        @if($cities->has($code))
                                            <optgroup label="{{ $countryLabel }}">
                                                @foreach($cities[$code] as $city)
                                                    <option value="{{ $city->id }}">{{ $city->localized_name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                            @error('to_city_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- TO Warehouse sub-block --}}
                        <div x-show="toCityId !== ''" x-cloak>
                            {{-- Multiple warehouses: dropdown --}}
                            <template x-if="toWarehouses.length > 1">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                        {{ translate('cargo.to_warehouse') }} <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select name="to_warehouse_id"
                                                x-model="toWarehouseId"
                                                class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('to_warehouse_id') border-rose-400 @enderror appearance-none pr-8"
                                                required>
                                            <option value="" disabled>Выберите склад</option>
                                            <template x-for="wh in toWarehouses" :key="wh.id">
                                                <option :value="wh.id"
                                                        :selected="String(wh.id) === String(toWarehouseId)"
                                                        x-text="wh.name_rus.length > 30 ? wh.name_rus.substring(0, 27) + '...' : wh.name_rus + ' (' + (wh.address.length > 30 ? wh.address.substring(0, 27) + '...' : wh.address) + ')'">
                                                </option>
                                            </template>
                                        </select>
                                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                    </div>
                                    @error('to_warehouse_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                                </div>
                            </template>

                            {{-- Exactly one warehouse: readonly badge + hidden input --}}
                            <template x-if="toWarehouses.length === 1">
                                <div>
                                    <input type="hidden" name="to_warehouse_id" :value="toWarehouses[0].id">
                                    <div class="bg-slate-100 text-slate-700 rounded-lg px-3 py-2 text-sm flex items-center gap-2">
                                        <i class="fas fa-warehouse text-slate-400"></i>
                                        <span>{{ translate('cargo.warehouse_label', ['name' => '']) }}</span>
                                        <span class="font-medium" x-text="toWarehouses[0].name_rus"></span>
                                    </div>
                                </div>
                            </template>

                            {{-- Zero warehouses: amber banner --}}
                            <template x-if="toWarehouses.length === 0">
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-sm text-amber-800">
                                    <p class="font-medium">{{ translate('warehouse.no_warehouses_city') }}. {{ translate('warehouse.create_prompt') }}.</p>
                                    <a :href="'/warehouses/create?city_id=' + toCityId"
                                       target="_blank"
                                       class="inline-flex items-center mt-2 px-3 py-1.5 bg-amber-100 hover:bg-amber-200 border border-amber-300 text-amber-800 text-xs font-medium rounded-lg transition-colors">
                                        <i class="fas fa-plus mr-1.5"></i>{{ translate('warehouse.create_link') }}
                                    </a>
                                    <p class="mt-2 text-xs text-amber-700">{{ translate('warehouse.autocreate_note') }}</p>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Cargo details --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">
                    <i class="fas fa-box mr-2 text-indigo-500"></i>Параметры груза
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Cargo type --}}
                    <div class="sm:col-span-2">
                        <label for="cargo_type" class="block text-sm font-medium text-slate-700 mb-1.5">
                            {{ translate('cargo.cargo_type') }}
                        </label>
                        <input type="text" name="cargo_type" id="cargo_type"
                               class="block w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('cargo_type') border-rose-400 @enderror"
                               value="{{ old('cargo_type') }}" required
                               placeholder="Например: промышленное оборудование">
                        @error('cargo_type')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Volume --}}
                    <div>
                        <label for="volume" class="block text-sm font-medium text-slate-700 mb-1.5">
                            {{ translate('cargo.volume') }}
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" name="volume" id="volume"
                                   class="block w-full px-3 py-2.5 pr-12 rounded-lg border border-slate-300 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('volume') border-rose-400 @enderror"
                                   value="{{ old('volume') }}" required placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-slate-400 text-sm">м³</span>
                            </div>
                        </div>
                        @error('volume')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Weight --}}
                    <div>
                        <label for="weight" class="block text-sm font-medium text-slate-700 mb-1.5">
                            {{ translate('cargo.weight') }}
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" name="weight" id="weight"
                                   class="block w-full px-3 py-2.5 pr-10 rounded-lg border border-slate-300 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('weight') border-rose-400 @enderror"
                                   value="{{ old('weight') }}" required placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-slate-400 text-sm">кг</span>
                            </div>
                        </div>
                        @error('weight')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Price --}}
                    <div>
                        <label for="price_usd" class="block text-sm font-medium text-slate-700 mb-1.5">
                            {{ translate('cargo.price_usd') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <span class="text-slate-400 text-sm font-medium">$</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="price_usd" id="price_usd"
                                   class="block w-full pl-8 pr-3 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('price_usd') border-rose-400 @enderror"
                                   value="{{ old('price_usd') }}" placeholder="0.00">
                        </div>
                        @error('price_usd')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Ready date --}}
                    <div>
                        <label for="ready_date" class="block text-sm font-medium text-slate-700 mb-1.5">
                            {{ translate('cargo.ready_date') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-calendar text-slate-400 text-sm"></i>
                            </div>
                            <input type="datetime-local" name="ready_date" id="ready_date"
                                   class="block w-full pl-10 pr-3 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('ready_date') border-rose-400 @enderror"
                                   value="{{ old('ready_date') }}" required>
                        </div>
                        @error('ready_date')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Comment --}}
                    <div class="sm:col-span-2">
                        <label for="comment" class="block text-sm font-medium text-slate-700 mb-1.5">
                            {{ translate('cargo.comment') }}
                            <span class="text-slate-400 font-normal ml-1">(необязательно)</span>
                        </label>
                        <textarea name="comment" id="comment" rows="3"
                                  class="block w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                                  placeholder="{{ translate('cargo.comment_placeholder') }}">{{ old('comment') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="history.back()"
                        class="px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors">
                    {{ translate('cargo.cancel') }}
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <i class="fas fa-save mr-2"></i>{{ translate('cargo.create_cargo') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
