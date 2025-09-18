@extends('layouts.app')

@section('title', translate('cars.all_cars'))

@section('content')
<div class="space-y-6">
    <!-- Заголовок -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ translate('cars.all_cars') }}</h1>
            <p class="mt-2 text-sm sm:text-base text-gray-700">
                {{ translate('cars.all_cars_description') }}
            </p>
        </div>
        @if(auth()->user()->isAdmin() || auth()->user()->isWarehouseEmployee())
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('cars.create') }}" 
               class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto transition duration-200">
                <i class="fas fa-plus mr-2"></i>
                {{ translate('cars.add_car') }}
            </a>
        </div>
        @endif
    </div>

    <!-- Фильтры и поиск -->
    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        <form method="GET" action="{{ route('cars.all') }}" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:space-x-4">
            <div class="flex-1 min-w-0">
                <label for="search" class="sr-only">Поиск</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" id="search" 
                           value="{{ request('search') }}"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="{{ translate('search_placeholder') }}">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                <select name="status" class="block w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ translate('cars.all_statuses') }}</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ translate('cars.status_active') }}</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ translate('cars.status_inactive') }}</option>
                </select>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <i class="fas fa-filter mr-2"></i>
                    {{ translate('cars.filter') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Список машин -->
    @if($cars->count() > 0)
        <!-- Десктопная таблица -->
        <div class="hidden lg:block bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('cars.table_car') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('cars.table_driver') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('cars.table_trailer') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('cars.table_status') }}
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">{{ translate('cars.table_actions') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($cars as $car)
                        <tr class="hover:bg-gray-50 transition duration-200 cursor-pointer car-row" 
                            data-car-url="{{ route('cars.show', $car) }}"
                            onclick="handleRowClick(event, '{{ route('cars.show', $car) }}')">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $car->brand }} {{ $car->model }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $car->license_plate }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $car->user->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $car->user->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $car->trailer_type_ru }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $car->trailer_length }}×{{ $car->trailer_width }}×{{ $car->trailer_height }} м
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($car->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        {{ translate('cars.status_active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        {{ translate('cars.status_inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2" onclick="event.stopPropagation()">
                                    <a href="{{ route('cars.show', $car) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition duration-200">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isWarehouseEmployee())
                                    <a href="{{ route('cars.edit', $car) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 transition duration-200">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Мобильные карточки -->
        <div class="lg:hidden space-y-4">
            @foreach($cars as $car)
            <div class="bg-white shadow rounded-lg p-4 hover:shadow-md transition duration-200 cursor-pointer car-card" 
                 data-car-url="{{ route('cars.show', $car) }}"
                 onclick="handleCardClick(event, '{{ route('cars.show', $car) }}')">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $car->brand }} {{ $car->model }}
                        </h3>
                        <p class="text-sm text-gray-600">{{ $car->license_plate }}</p>
                    </div>
                    <div class="ml-3">
                        @if($car->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                {{ translate('cars.status_active') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                {{ translate('cars.status_inactive') }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="space-y-2 mb-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ translate('cars.driver') }}</span>
                        <span class="font-medium">{{ $car->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ translate('cars.email') }}</span>
                        <span class="font-medium">{{ $car->user->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ translate('cars.trailer') }}</span>
                        <span class="font-medium">{{ $car->trailer_type_ru }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ translate('cars.dimensions') }}</span>
                        <span class="font-medium">{{ $car->trailer_length }}×{{ $car->trailer_width }}×{{ $car->trailer_height }} м</span>
                    </div>
                </div>

                <div class="flex justify-between items-center" onclick="event.stopPropagation()">
                    <a href="{{ route('cars.show', $car) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        {{ translate('cars.view') }}
                    </a>
                    
                    @if(auth()->user()->isAdmin() || auth()->user()->isWarehouseEmployee())
                    <a href="{{ route('cars.edit', $car) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        {{ translate('cars.edit') }}
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Пагинация -->
        <div class="mt-6">
            {{ $cars->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-car text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Машины не найдены</h3>
            <p class="text-gray-500 mb-6">
                @if(request('search') || request('status'))
                    Попробуйте изменить параметры поиска
                @else
                    В данный момент нет зарегистрированных машин
                @endif
            </p>
            @if(request('search') || request('status'))
            <a href="{{ route('cars.all') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                <i class="fas fa-times mr-2"></i>
                Сбросить фильтры
            </a>
            @endif
        </div>
    @endif
</div>

<script>
// Функция для обработки клика по строке таблицы
function handleRowClick(event, url) {
    // Проверяем, что клик не по кнопкам действий
    if (event.target.closest('a, button') || event.target.closest('[onclick*="event.stopPropagation"]')) {
        return;
    }
    
    // Добавляем визуальный эффект при клике
    const row = event.currentTarget;
    row.style.backgroundColor = '#f3f4f6';
    
    // Переходим на страницу просмотра
    setTimeout(() => {
        window.location.href = url;
    }, 150);
}

// Функция для обработки клика по мобильной карточке
function handleCardClick(event, url) {
    // Проверяем, что клик не по кнопкам действий
    if (event.target.closest('a, button') || event.target.closest('[onclick*="event.stopPropagation"]')) {
        return;
    }
    
    // Добавляем визуальный эффект при клике
    const card = event.currentTarget;
    card.style.transform = 'scale(0.98)';
    card.style.transition = 'transform 0.1s ease';
    
    // Переходим на страницу просмотра
    setTimeout(() => {
        window.location.href = url;
    }, 100);
}

// Добавляем стили для интерактивности
document.addEventListener('DOMContentLoaded', function() {
    // Добавляем hover эффекты для строк таблицы
    const rows = document.querySelectorAll('.car-row');
    rows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f9fafb';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // Добавляем hover эффекты для мобильных карточек
    const cards = document.querySelectorAll('.car-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
});
</script>
@endsection
