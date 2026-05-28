{{--
  Shared form partial for warehouse create and edit.
  Parameters:
    $warehouse   - Warehouse model (null on create)
    $cities      - Collection grouped by country
    $preselectedCity - city_id to pre-select (nullable, create only via ?city_id=)
--}}

{{-- ОСНОВНАЯ ИНФОРМАЦИЯ --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">
        <i class="fas fa-info-circle mr-2 text-indigo-500"></i>Основная информация
    </h2>
    <div class="space-y-4">

        {{-- City --}}
        <div>
            <label for="city_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                {{ translate('warehouse.city') }} <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <select name="city_id" id="city_id"
                        class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 appearance-none pr-8 @error('city_id') border-rose-400 @enderror"
                        required>
                    <option value="">{{ translate('cargo.select_city') }}</option>
                    @php
                        $countryLabels = [
                            'kz' => '🇰🇿 ' . translate('country.kz'),
                            'ru' => '🇷🇺 ' . translate('country.ru'),
                            'cn' => '🇨🇳 ' . translate('country.cn'),
                        ];
                        $selectedCityId = old('city_id', $warehouse?->city_id ?? $preselectedCity);
                    @endphp
                    @foreach($countryLabels as $code => $countryLabel)
                        @if($cities->has($code))
                            <optgroup label="{{ $countryLabel }}">
                                @foreach($cities[$code] as $city)
                                    <option value="{{ $city->id }}"
                                        {{ (string) $selectedCityId === (string) $city->id ? 'selected' : '' }}>
                                        {{ $city->localized_name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
            </div>
            @error('city_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>

        {{-- Address --}}
        <div>
            <label for="address" class="block text-sm font-medium text-slate-700 mb-1.5">
                {{ translate('warehouse.address') }} <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="address" id="address"
                   class="block w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('address') border-rose-400 @enderror"
                   value="{{ old('address', $warehouse?->address) }}" required
                   placeholder="ул. Панфилова, 14">
            @error('address')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>

        {{-- Phone --}}
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">
                {{ translate('warehouse.phone') }}
                <span class="text-slate-400 font-normal ml-1">({{ translate('common.optional') ?? 'необязательно' }})</span>
            </label>
            <input type="tel" name="phone" id="phone"
                   class="block w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('phone') border-rose-400 @enderror"
                   value="{{ old('phone', $warehouse?->phone) }}"
                   placeholder="+7 727 000 00 00">
            @error('phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>

    </div>
</div>

{{-- НАЗВАНИЕ СКЛАДА --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">
        <i class="fas fa-warehouse mr-2 text-indigo-500"></i>{{ translate('warehouse.title') }}
    </h2>
    <div class="space-y-4">

        {{-- Name RU (required) --}}
        <div>
            <label for="name_rus" class="block text-sm font-medium text-slate-700 mb-1.5">
                {{ translate('warehouse.name_ru') }}
                <span class="text-rose-500">*</span>
                <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-indigo-100 text-indigo-700">[RU]</span>
            </label>
            <input type="text" name="name_rus" id="name_rus"
                   class="block w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name_rus') border-rose-400 @enderror"
                   value="{{ old('name_rus', $warehouse?->name_rus) }}" required
                   placeholder="Склад Алматы-Север">
            @error('name_rus')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>

        {{-- Name KZ (optional) --}}
        <div>
            <label for="name_kaz" class="block text-sm font-medium text-slate-700 mb-1.5">
                {{ translate('warehouse.name_kz') }}
                <span class="text-slate-400 font-normal ml-1">({{ translate('common.optional') ?? 'необязательно' }})</span>
                <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700">[KK]</span>
            </label>
            <input type="text" name="name_kaz" id="name_kaz"
                   class="block w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name_kaz') border-rose-400 @enderror"
                   value="{{ old('name_kaz', $warehouse?->name_kaz) }}"
                   placeholder="Алматы-Солтүстік қоймасы">
            @error('name_kaz')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>

        {{-- Name CN (optional) --}}
        <div>
            <label for="name_chn" class="block text-sm font-medium text-slate-700 mb-1.5">
                {{ translate('warehouse.name_cn') }}
                <span class="text-slate-400 font-normal ml-1">({{ translate('common.optional') ?? 'необязательно' }})</span>
                <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-rose-100 text-rose-700">[CN]</span>
            </label>
            <input type="text" name="name_chn" id="name_chn"
                   class="block w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name_chn') border-rose-400 @enderror"
                   value="{{ old('name_chn', $warehouse?->name_chn) }}"
                   placeholder="阿拉木图北仓库">
            @error('name_chn')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>

    </div>
</div>
