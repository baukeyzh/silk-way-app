@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Заголовок -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Заявка на груз</h1>
                    <p class="text-gray-600">{{ $application->cargo->from_location }} → {{ $application->cargo->to_location }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    @if($application->isPending())
                        <span class="px-3 py-1 bg-yellow-200 text-yellow-800 text-sm font-medium rounded-full">
                            Ожидает рассмотрения
                        </span>
                    @elseif($application->isApproved())
                        <span class="px-3 py-1 bg-green-200 text-green-800 text-sm font-medium rounded-full">
                            Подтверждено
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-200 text-red-800 text-sm font-medium rounded-full">
                            Отклонено
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-2">
            <!-- Информация о грузе -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-box mr-2"></i>
                    Информация о грузе
                </h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Маршрут:</span>
                        <span class="font-medium">{{ $application->cargo->from_location }} → {{ $application->cargo->to_location }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Тип груза:</span>
                        <span class="font-medium">{{ $application->cargo->cargo_type }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Объем:</span>
                        <span class="font-medium">{{ $application->cargo->volume }} м³</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Вес:</span>
                        <span class="font-medium">{{ $application->cargo->weight }} кг</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Дата готовности:</span>
                        <span class="font-medium">{{ $application->cargo->ready_date->format('d.m.Y H:i') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Статус груза:</span>
                        <span class="font-medium">
                            @if($application->cargo->status === 'available')
                                <span class="text-green-600">Доступен</span>
                            @elseif($application->cargo->status === 'in_progress')
                                <span class="text-blue-600">В работе</span>
                            @else
                                <span class="text-gray-600">Доставлен</span>
                            @endif
                        </span>
                    </div>
                    
                    @if($application->cargo->comment)
                    <div class="pt-4 border-t border-gray-200">
                        <span class="text-gray-600 block mb-2">Комментарий:</span>
                        <p class="text-gray-800">{{ $application->cargo->comment }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Информация о заявке -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-file-alt mr-2"></i>
                    Информация о заявке
                </h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Водитель:</span>
                        <span class="font-medium">{{ $application->driver->name }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Дата подачи:</span>
                        <span class="font-medium">{{ $application->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    
                    @if($application->approved_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Дата подтверждения:</span>
                        <span class="font-medium">{{ $application->approved_at->format('d.m.Y H:i') }}</span>
                    </div>
                    @endif
                    
                    @if($application->driver_notes)
                    <div class="pt-4 border-t border-gray-200">
                        <span class="text-gray-600 block mb-2">Заметки водителя:</span>
                        <p class="text-gray-800">{{ $application->driver_notes }}</p>
                    </div>
                    @endif
                    
                    @if($application->warehouse_notes)
                    <div class="pt-4 border-t border-gray-200">
                        <span class="text-gray-600 block mb-2">Заметки склада:</span>
                        <p class="text-gray-800">{{ $application->warehouse_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Контактная информация (только для подтвержденных заявок) -->
        @if($application->isApproved() && ($application->pickup_contact || $application->pickup_address || $application->delivery_contact || $application->delivery_address))
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-phone mr-2"></i>
                Контактная информация
            </h2>
            
            <div class="grid gap-6 md:grid-cols-2">
                @if($application->pickup_contact || $application->pickup_address)
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Информация для забора</h3>
                    <div class="space-y-2">
                        @if($application->pickup_contact)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Контакт:</span>
                            <span class="font-medium">{{ $application->pickup_contact }}</span>
                        </div>
                        @endif
                        @if($application->pickup_address)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Адрес:</span>
                            <span class="font-medium">{{ $application->pickup_address }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                
                @if($application->delivery_contact || $application->delivery_address)
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Информация для доставки</h3>
                    <div class="space-y-2">
                        @if($application->delivery_contact)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Контакт:</span>
                            <span class="font-medium">{{ $application->delivery_contact }}</span>
                        </div>
                        @endif
                        @if($application->delivery_address)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Адрес:</span>
                            <span class="font-medium">{{ $application->delivery_address }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Действия -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Действия</h2>
            
            <div class="flex flex-wrap gap-4">
                @if(auth()->user()->isDriver() && $application->isApproved() && $application->cargo->status !== 'delivered')
                <form action="{{ route('applications.mark-delivered', $application) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                        <i class="fas fa-check mr-2"></i>
                        Отметить как доставленный
                    </button>
                </form>
                @endif
                
                @if(auth()->user()->isWarehouseEmployee() && $application->isPending())
                <form action="{{ route('applications.approve', $application) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                        <i class="fas fa-check mr-2"></i>
                        Подтвердить заявку
                    </button>
                </form>
                
                <form action="{{ route('applications.reject', $application) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Отклонить заявку
                    </button>
                </form>
                @endif
                
                <button type="button" 
                        onclick="history.back()"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Назад
                </button>
            </div>
        </div>
    </div>
</div>
@endsection 