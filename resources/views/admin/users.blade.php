@extends('layouts.app')

@section('title', \App\Helpers\LocalizationHelper::t('admin.users_management_title') . ' - Silk Way')

@section('content')
<div class="space-y-6">
    <!-- Заголовок -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ \App\Helpers\LocalizationHelper::t('admin.users_management_heading') }}</h1>
            <p class="mt-2 text-sm sm:text-base text-gray-700">
                {{ \App\Helpers\LocalizationHelper::t('admin.users_management_desc') }}
            </p>
        </div>
    </div>
    
    <!-- Таблица пользователей -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ \App\Helpers\LocalizationHelper::t('admin.table_user') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ \App\Helpers\LocalizationHelper::t('admin.table_role') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ \App\Helpers\LocalizationHelper::t('admin.table_status') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ \App\Helpers\LocalizationHelper::t('admin.table_registration_date') }}
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">{{ \App\Helpers\LocalizationHelper::t('admin.table_actions') }}</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->isAdmin())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-crown mr-1"></i>
                                    {{ \App\Helpers\LocalizationHelper::t('admin.administrator') }}
                                </span>
                            @elseif($user->isWarehouseEmployee())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-warehouse mr-1"></i>
                                    {{ \App\Helpers\LocalizationHelper::t('auth.warehouse_employee') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-truck mr-1"></i>
                                    {{ \App\Helpers\LocalizationHelper::t('auth.driver_role') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->isApproved())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    {{ \App\Helpers\LocalizationHelper::t('admin.status_approved') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ \App\Helpers\LocalizationHelper::t('admin.status_pending') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2">
                                @if(!$user->isApproved())
                                    <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200">
                                            <i class="fas fa-check mr-1"></i>
                                            {{ \App\Helpers\LocalizationHelper::t('admin.approve') }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.users.reject', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200"
                                                onclick="return confirm('{{ \App\Helpers\LocalizationHelper::t('admin.confirm_reject_user') }}')">
                                            <i class="fas fa-times mr-1"></i>
                                            {{ \App\Helpers\LocalizationHelper::t('admin.reject') }}
                                        </button>
                                    </form>
                                @else
                                    @if(!$user->isAdmin())
                                    <form action="{{ route('admin.users.toggle-approval', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition duration-200"
                                                title="{{ \App\Helpers\LocalizationHelper::t('admin.toggle_access_title') }}">
                                            <i class="fas fa-ban mr-1"></i>
                                            {{ \App\Helpers\LocalizationHelper::t('admin.toggle_approval') }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200"
                                                onclick="return confirm('{{ \App\Helpers\LocalizationHelper::t('admin.confirm_delete_user') }}')"
                                                title="{{ \App\Helpers\LocalizationHelper::t('admin.delete_user_title') }}">
                                            <i class="fas fa-trash mr-1"></i>
                                            {{ \App\Helpers\LocalizationHelper::t('admin.delete_user') }}
                                        </button>
                                    </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection