@extends('layouts.app')

@section('title', translate('cargo.create_title'))

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ translate('cargo.new_cargo') }}</h3>
                <p class="mt-1 text-sm text-gray-600">{{ translate('cargo.create_desc') }}</p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="{{ route('cargo.store') }}" method="POST">
                @csrf
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            @include('partials.city-select', [
                                'name'     => 'from_city_id',
                                'label'    => translate('cargo.from_location'),
                                'cities'   => $cities,
                                'selected' => null,
                            ])
                            @include('partials.city-select', [
                                'name'     => 'to_city_id',
                                'label'    => translate('cargo.to_location'),
                                'cities'   => $cities,
                                'selected' => null,
                            ])
                        </div>

                        <div>
                            <label for="cargo_type" class="block text-sm font-medium text-gray-700">
                                {{ translate('cargo.cargo_type') }}
                            </label>
                            <input type="text" name="cargo_type" id="cargo_type"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                   value="{{ old('cargo_type') }}" required>
                        </div>

                        <div>
                            <label for="price_usd" class="block text-sm font-medium text-gray-700">
                                Цена (USD)
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" name="price_usd" id="price_usd"
                                       class="pl-7 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('price_usd') }}"
                                       placeholder="0.00">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="volume" class="block text-sm font-medium text-gray-700">
                                    {{ translate('cargo.volume') }}
                                </label>
                                <input type="number" step="0.01" name="volume" id="volume"
                                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('volume') }}" required>
                            </div>

                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700">
                                    {{ translate('cargo.weight') }}
                                </label>
                                <input type="number" step="0.01" name="weight" id="weight"
                                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('weight') }}" required>
                            </div>
                        </div>

                        <div>
                            <label for="ready_date" class="block text-sm font-medium text-gray-700">
                                {{ translate('cargo.ready_date') }}
                            </label>
                            <input type="datetime-local" name="ready_date" id="ready_date"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                   value="{{ old('ready_date') }}" required>
                        </div>

                        <div>
                            <label for="comment" class="block text-sm font-medium text-gray-700">
                                {{ translate('cargo.comment') }}
                            </label>
                            <textarea name="comment" id="comment" rows="3"
                                      class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                      placeholder="{{ translate('cargo.comment_placeholder') }}">{{ old('comment') }}</textarea>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="button"
                                onclick="history.back()"
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ translate('cargo.cancel') }}
                        </button>
                        <button type="submit"
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>
                            {{ translate('cargo.create_cargo') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
