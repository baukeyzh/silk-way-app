@extends('layouts.app')

@section('title', \App\Helpers\LocalizationHelper::t('admin.dashboard_title') . ' - Silk Way')

@section('content')
<div class="space-y-6">
    <!-- Заголовок -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ \App\Helpers\LocalizationHelper::t('admin.dashboard_title') }}</h1>
            <p class="mt-2 text-sm sm:text-base text-gray-700">
                {{ \App\Helpers\LocalizationHelper::t('admin.dashboard_desc') }}
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.translations.index') }}" 
               class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                <i class="fas fa-language mr-2"></i>
                {{ \App\Helpers\LocalizationHelper::t('admin.translations') }}
            </a>
        </div>
    </div>

    <!-- Статистика -->
    <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-box text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                {{ translate('admin.total_cargo') }}
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $cargoStats['total'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                {{ translate('admin.available_cargo') }}
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $cargoStats['available'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-truck text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                {{ translate('admin.picked_up_cargo') }}
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $cargoStats['picked_up'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Пользователи на подтверждение -->
    @if($pendingUsers->count() > 0)
    <div class="mt-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ translate('admin.pending_users') }} ({{ $pendingUsers->count() }})
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ translate('admin.pending_users_desc') }}
                </p>
            </div>
            <ul class="divide-y divide-gray-200">
                @foreach($pendingUsers as $user)
                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $user->name }}
                                        </p>
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $user->role === 'warehouse_employee' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $user->role === 'warehouse_employee' ? translate('auth.warehouse_employee') : translate('auth.driver_role') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    <p class="text-xs text-gray-400">{{ translate('admin.registered_at') }}: {{ $user->created_at->format('d.m.Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
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
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Подтвержденные пользователи -->
    <div class="mt-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ translate('admin.approved_users') }} ({{ $approvedUsers->count() }})
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ translate('admin.approved_users_desc') }}
                </p>
            </div>
            <ul class="divide-y divide-gray-200">
                @foreach($approvedUsers as $user)
                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $user->name }}
                                            @if($user->isAdmin())
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    {{ translate('admin.administrator') }}
                                                </span>
                                            @endif
                                        </p>
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $user->role === 'warehouse_employee' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $user->role === 'warehouse_employee' ? translate('auth.warehouse_employee') : translate('auth.driver_role') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    <p class="text-xs text-gray-400">{{ translate('admin.approved_at') }}: {{ $user->updated_at->format('d.m.Y H:i') }}</p>
                                </div>
                            </div>
                            @if(!$user->isAdmin())
                            <div class="flex space-x-2">
                                <form action="{{ route('admin.users.toggle-approval', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition duration-200">
                                        <i class="fas fa-ban mr-1"></i>
                                        {{ \App\Helpers\LocalizationHelper::t('admin.toggle_approval') }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200"
                                            onclick="return confirm('{{ \App\Helpers\LocalizationHelper::t('admin.confirm_delete_user') }}')">
                                        <i class="fas fa-trash mr-1"></i>
                                        {{ \App\Helpers\LocalizationHelper::t('admin.delete_user') }}
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection 