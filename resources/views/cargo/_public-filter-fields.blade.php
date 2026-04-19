{{-- Shared filter fields used by both desktop inline and mobile bottom sheet --}}

{{-- From city --}}
<div class="flex-1 min-w-[160px]">
    <label class="block text-xs font-medium text-slate-500 mb-1">{{ translate('cargo.public.from') }}</label>
    <select name="from_city_id"
            class="block w-full px-3 py-2.5 text-sm rounded-lg border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        <option value="">{{ translate('cargo.all_statuses') }}</option>
        @foreach($cities as $city)
        <option value="{{ $city->id }}" {{ request('from_city_id') == $city->id ? 'selected' : '' }}>
            {{ $city->localized_name }}
        </option>
        @endforeach
    </select>
</div>

{{-- To city --}}
<div class="flex-1 min-w-[160px]">
    <label class="block text-xs font-medium text-slate-500 mb-1">{{ translate('cargo.public.to') }}</label>
    <select name="to_city_id"
            class="block w-full px-3 py-2.5 text-sm rounded-lg border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        <option value="">{{ translate('cargo.all_statuses') }}</option>
        @foreach($cities as $city)
        <option value="{{ $city->id }}" {{ request('to_city_id') == $city->id ? 'selected' : '' }}>
            {{ $city->localized_name }}
        </option>
        @endforeach
    </select>
</div>

{{-- Ready date from --}}
<div class="min-w-[140px]">
    <label class="block text-xs font-medium text-slate-500 mb-1">{{ translate('cargo.ready_label') }} {{ translate('cargo.public.from') }}</label>
    <input type="date" name="ready_date_from" value="{{ request('ready_date_from') }}"
           class="block w-full px-3 py-2.5 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
</div>

{{-- Ready date to --}}
<div class="min-w-[140px]">
    <label class="block text-xs font-medium text-slate-500 mb-1">{{ translate('cargo.ready_label') }} {{ translate('cargo.public.to') }}</label>
    <input type="date" name="ready_date_to" value="{{ request('ready_date_to') }}"
           class="block w-full px-3 py-2.5 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
</div>

{{-- Cargo type text search --}}
<div class="flex-1 min-w-[180px]">
    <label class="block text-xs font-medium text-slate-500 mb-1">{{ translate('cargo.table_cargo') }}</label>
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
            <i class="fas fa-search text-slate-400 text-xs"></i>
        </div>
        <input type="text" name="search" value="{{ request('search') }}"
               class="block w-full pl-9 pr-3 py-2.5 text-sm rounded-lg border border-slate-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
               placeholder="{{ translate('cargo.search_placeholder') }}">
    </div>
</div>
