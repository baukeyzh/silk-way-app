{{--
  Параметры:
    $name        - имя поля (from_city_id / to_city_id)
    $label       - текст метки
    $cities      - коллекция, сгруппированная по country
    $selected    - ID выбранного города (nullable)
--}}
<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 mb-1.5">
        {{ $label }} <span class="text-rose-500">*</span>
    </label>
    <div class="relative">
        <select name="{{ $name }}" id="{{ $name }}"
                class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error($name) border-rose-400 @enderror appearance-none pr-8"
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
                            <option value="{{ $city->id }}"
                                {{ (string) old($name, $selected) === (string) $city->id ? 'selected' : '' }}>
                                {{ $city->localized_name }}
                            </option>
                        @endforeach
                    </optgroup>
                @endif
            @endforeach
        </select>
        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
    </div>
    @error($name)
        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
    @enderror
</div>
