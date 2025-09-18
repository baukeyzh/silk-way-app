@extends('layouts.app')

@section('title', translate('applications.title'))

@section('content')
<div class="space-y-6">
    <!-- Заголовок -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-clipboard-list mr-3 text-blue-600"></i>
                    {{ translate('applications.heading') }}
                </h1>
                <p class="mt-2 text-gray-600">
                    @if(auth()->user()->isAdmin())
                        {{ translate('applications.admin_desc') }}
                    @else
                        {{ translate('applications.driver_desc') }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Фильтры и поиск -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" action="{{ route('applications.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">{{ translate('applications.status_label') }}</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ translate('applications.all_statuses') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ translate('applications.status_pending') }}</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ translate('applications.status_approved') }}</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ translate('applications.status_rejected') }}</option>
                    </select>
                </div>
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">{{ translate('applications.search_label') }}</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="{{ translate('applications.search_placeholder') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-search mr-2"></i>{{ translate('applications.search_button') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Список заявок -->
    @if($applications->count() > 0)
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Десктопная таблица -->
        <div class="hidden lg:block">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('applications.table_route') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('applications.table_driver') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('applications.table_status') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('applications.table_submitted') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('applications.table_actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($applications as $application)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $application->cargo->from_location }} → {{ $application->cargo->to_location }}
                                </div>
                                <div class="text-sm text-gray-500">{{ $application->cargo->cargo_type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $application->driver->name }}</div>
                                <div class="text-sm text-gray-500">{{ $application->driver->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($application->isPending())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ translate('applications.status_pending_short') }}
                                    </span>
                                @elseif($application->isApproved())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        {{ translate('applications.status_approved_short') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>
                                        {{ translate('applications.status_rejected_short') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $application->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('cargo.applications.show-from-cargo', $application) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition duration-200">
                                        <i class="fas fa-eye mr-1"></i>
                                        {{ translate('applications.view_details') }}
                                    </a>
                                    @if($application->isPending())
                                    <form action="{{ route('applications.approve', $application) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900 transition duration-200"
                                                onclick="return confirm('{{ translate('applications.confirm_approve') }}')">
                                            <i class="fas fa-check mr-1"></i>
                                            {{ translate('applications.approve_button') }}
                                        </button>
                                    </form>
                                    <form action="{{ route('applications.reject', $application) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition duration-200"
                                                onclick="return confirm('{{ translate('applications.confirm_reject') }}')">
                                            <i class="fas fa-times mr-1"></i>
                                            {{ translate('applications.reject_button') }}
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
        <div class="lg:hidden divide-y divide-gray-200">
            @foreach($applications as $application)
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $application->cargo->from_location }} → {{ $application->cargo->to_location }}
                        </h3>
                        <p class="text-sm text-gray-600">{{ $application->cargo->cargo_type }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ translate('applications.driver_label') }} {{ $application->driver->name }}</p>
                    </div>
                    @if($application->isPending())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>
                            {{ translate('applications.status_pending_short') }}
                        </span>
                    @elseif($application->isApproved())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>
                            {{ translate('applications.status_approved_short') }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times mr-1"></i>
                            {{ translate('applications.status_rejected_short') }}
                        </span>
                    @endif
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                    <div>
                        <span class="text-gray-500">{{ translate('applications.submitted_label') }}</span>
                        <span class="font-medium">{{ $application->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                </div>

                <div class="flex justify-center space-x-2">
                    <a href="{{ route('cargo.applications.show-from-cargo', $application) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        {{ translate('applications.view_details') }}
                    </a>
                    
                    @if($application->isPending())
                    <form action="{{ route('applications.approve', $application) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200"
                                onclick="return confirm('{{ translate('applications.confirm_approve') }}')">
                            <i class="fas fa-check mr-2"></i>
                            {{ translate('applications.approve_button') }}
                        </button>
                    </form>
                    
                    <form action="{{ route('applications.reject', $application) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200"
                                onclick="return confirm('{{ translate('applications.confirm_reject') }}')">
                            <i class="fas fa-times mr-2"></i>
                            {{ translate('applications.reject_button') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Пагинация -->
    @if($applications->hasPages())
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-center">
            {{ $applications->links() }}
        </div>
    </div>
    @endif

    @else
    <!-- Если нет заявок -->
    <div class="bg-white shadow rounded-lg p-12 text-center">
        <div class="text-gray-400 mb-4">
            <i class="fas fa-clipboard-list text-6xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">{{ translate('applications.no_applications') }}</h3>
        <p class="text-gray-500">{{ translate('applications.no_applications_desc') }}</p>
    </div>
    @endif

    <!-- Навигация -->
    <div class="mt-8 pt-6 border-t border-gray-200">
        <button type="button" 
                onclick="history.back()"
                class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            {{ translate('applications.back_button') }}
        </button>
    </div>
</div>
@endsection
