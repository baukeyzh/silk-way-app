@extends('layouts.app')

@section('title', translate('cargo.available_cargo'))

@section('content')
<div class="space-y-6">
    <!-- Заголовок -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ translate('cargo.available_cargo') }}</h1>
            <p class="mt-2 text-sm sm:text-base text-gray-700">
                {{ translate('cargo.available_cargo_desc') }}
            </p>
        </div>
        @if(auth()->user()->isWarehouseEmployee())
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('cargo.create') }}" 
               class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto transition duration-200">
                <i class="fas fa-plus mr-2"></i>
                {{ translate('cargo.add_cargo_button') }}
            </a>
        </div>
        @endif
    </div>

    <!-- Фильтры и поиск -->
    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        <form method="GET" action="{{ route('cargo.index') }}" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:space-x-4">
            <div class="flex-1 min-w-0">
                <label for="search" class="sr-only">Поиск</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" id="search" 
                           value="{{ request('search') }}"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="{{ translate('cargo.search_placeholder') }}">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                <select name="status" class="block w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ translate('cargo.all_statuses') }}</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>{{ translate('cargo.status_available') }}</option>
                    <option value="picked_up" {{ request('status') == 'picked_up' ? 'selected' : '' }}>{{ translate('cargo.status_picked_up') }}</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>{{ translate('cargo.status_delivered') }}</option>
                </select>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <i class="fas fa-filter mr-2"></i>
                    {{ translate('cargo.filter_button') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Список грузов -->
    @if($cargo->count() > 0)
        <!-- Десктопная таблица -->
        <div class="hidden lg:block bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('cargo.table_route') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('cargo.table_cargo') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('cargo.table_readiness') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('cargo.table_status') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('cargo.table_created') }}
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Действия</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($cargo as $item)
                        <tr class="hover:bg-gray-50 transition duration-200 cursor-pointer cargo-row" 
                            data-cargo-url="{{ route('cargo.show', $item) }}"
                            onclick="handleCargoRowClick(event, '{{ route('cargo.show', $item) }}')">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $item->from_location }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-arrow-right mr-1"></i>
                                    {{ $item->to_location }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $item->cargo_type }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $item->volume }} м³, {{ $item->weight }} кг
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->ready_date->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status === 'available')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        {{ translate('cargo.status_available') }}
                                    </span>
                                @elseif($item->status === 'picked_up')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-truck mr-1"></i>
                                        {{ translate('cargo.status_picked_up') }}
                                    </span>
                                @elseif($item->status === 'delivered')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-check-double mr-1"></i>
                                        {{ translate('cargo.status_delivered') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2" onclick="event.stopPropagation()">
                                    <a href="{{ route('cargo.show', $item) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition duration-200">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user()->isWarehouseEmployee() && $item->status === 'available')
                                    <a href="{{ route('cargo.edit', $item) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 transition duration-200">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('cargo.destroy', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition duration-200"
                                                onclick="return confirm('{{ translate('cargo.confirm_delete') }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
            @foreach($cargo as $item)
            <div class="bg-white shadow rounded-lg p-4 hover:shadow-md transition duration-200 cursor-pointer cargo-card" 
                 data-cargo-url="{{ route('cargo.show', $item) }}"
                 onclick="handleCargoCardClick(event, '{{ route('cargo.show', $item) }}')">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $item->from_location }} → {{ $item->to_location }}
                        </h3>
                        <p class="text-sm text-gray-600">{{ $item->cargo_type }}</p>
                    </div>
                    <div class="ml-3">
                        @if($item->status === 'available')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                {{ translate('cargo.status_available') }}
                            </span>
                        @elseif($item->status === 'picked_up')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-truck mr-1"></i>
                                {{ translate('cargo.status_picked_up') }}
                            </span>
                        @elseif($item->status === 'delivered')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-check-double mr-1"></i>
                                {{ translate('cargo.status_delivered') }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                    <div>
                        <span class="text-gray-500">{{ translate('cargo.volume_label') }}</span>
                        <span class="font-medium">{{ $item->volume }} м³</span>
                    </div>
                    <div>
                        <span class="text-gray-500">{{ translate('cargo.weight_label') }}</span>
                        <span class="font-medium">{{ $item->weight }} кг</span>
                    </div>
                    <div>
                        <span class="text-gray-500">{{ translate('cargo.readiness_label') }}</span>
                        <span class="font-medium">{{ $item->ready_date->format('d.m.Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">{{ translate('cargo.created_label') }}</span>
                        <span class="font-medium">{{ $item->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                </div>

                <div class="flex justify-between items-center" onclick="event.stopPropagation()">
                    <a href="{{ route('cargo.show', $item) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        {{ translate('cargo.view_button') }}
                    </a>
                    
                    @if(auth()->user()->isWarehouseEmployee() && $item->status === 'available')
                    <div class="flex space-x-2">
                        <a href="{{ route('cargo.edit', $item) }}" 
                           class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('cargo.destroy', $item) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200"
                                    onclick="return confirm('{{ translate('cargo.confirm_delete') }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Пагинация -->
        <div class="mt-6">
            {{ $cargo->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-box text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">{{ translate('cargo.no_cargo_found') }}</h3>
            <p class="text-gray-500 mb-6">
                @if(request('search') || request('status'))
                    {{ translate('cargo.try_change_search') }}
                @else
                    {{ translate('cargo.no_cargo_desc') }}
                @endif
            </p>
            @if(request('search') || request('status'))
            <a href="{{ route('cargo.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                <i class="fas fa-times mr-2"></i>
                {{ translate('cargo.reset_filters') }}
            </a>
            @endif
        </div>
    @endif
</div>

<script>
// Функция для обработки клика по строке таблицы грузов
function handleCargoRowClick(event, url) {
    // Проверяем, что клик не по кнопкам действий
    if (event.target.closest('a, button, form') || event.target.closest('[onclick*="event.stopPropagation"]')) {
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

// Функция для обработки клика по мобильной карточке грузов
function handleCargoCardClick(event, url) {
    // Проверяем, что клик не по кнопкам действий
    if (event.target.closest('a, button, form') || event.target.closest('[onclick*="event.stopPropagation"]')) {
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
    // Добавляем hover эффекты для строк таблицы грузов
    const rows = document.querySelectorAll('.cargo-row');
    rows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f9fafb';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // Добавляем hover эффекты для мобильных карточек грузов
    const cards = document.querySelectorAll('.cargo-card');
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