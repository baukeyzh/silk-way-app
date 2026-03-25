{{--
  Параметры:
    $name        - имя поля (from_city_id / to_city_id)
    $label       - текст метки
    $cities      - коллекция, сгруппированная по country
    $selected    - ID выбранного города (nullable)
--}}
<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}"
            class="mt-1 block w-full pl-3 pr-10 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error($name) border-red-500 @enderror"
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
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
