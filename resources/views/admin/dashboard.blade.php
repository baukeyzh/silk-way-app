@extends('layouts.app')

@section('title', \App\Helpers\LocalizationHelper::t('admin.dashboard_title') . ' - Silk Way')

@section('content')
<div class="space-y-8">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">{{ \App\Helpers\LocalizationHelper::t('admin.dashboard_title') }}</h1>
        <p class="mt-1 text-sm text-slate-500">{{ \App\Helpers\LocalizationHelper::t('admin.dashboard_desc') }}</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-box text-indigo-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('admin.total_cargo') }}</p>
                <p class="text-3xl font-bold text-slate-900 mt-0.5">{{ $cargoStats['total'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('admin.available_cargo') }}</p>
                <p class="text-3xl font-bold text-slate-900 mt-0.5">{{ $cargoStats['available'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-truck text-amber-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('admin.picked_up_cargo') }}</p>
                <p class="text-3xl font-bold text-slate-900 mt-0.5">{{ $cargoStats['picked_up'] }}</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('admin.users') }}"
           class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex flex-col items-center text-center hover:border-indigo-300 hover:shadow-md transition-all group">
            <div class="w-11 h-11 bg-emerald-100 rounded-xl flex items-center justify-center mb-3 group-hover:bg-emerald-200 transition-colors">
                <i class="fas fa-users text-emerald-600 text-lg"></i>
            </div>
            <span class="text-sm font-medium text-slate-700">Пользователи</span>
            @if($pendingUsers->count() > 0)
                <span class="mt-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">
                    {{ $pendingUsers->count() }} ожидают
                </span>
            @endif
        </a>

        <a href="{{ route('cargo.index') }}"
           class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex flex-col items-center text-center hover:border-indigo-300 hover:shadow-md transition-all group">
            <div class="w-11 h-11 bg-indigo-100 rounded-xl flex items-center justify-center mb-3 group-hover:bg-indigo-200 transition-colors">
                <i class="fas fa-box text-indigo-600 text-lg"></i>
            </div>
            <span class="text-sm font-medium text-slate-700">Грузы</span>
        </a>

        <a href="{{ route('applications.index') }}"
           class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex flex-col items-center text-center hover:border-indigo-300 hover:shadow-md transition-all group">
            <div class="w-11 h-11 bg-amber-100 rounded-xl flex items-center justify-center mb-3 group-hover:bg-amber-200 transition-colors">
                <i class="fas fa-clipboard-list text-amber-600 text-lg"></i>
            </div>
            <span class="text-sm font-medium text-slate-700">Заявки</span>
        </a>

        <a href="{{ route('cities.index') }}"
           class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex flex-col items-center text-center hover:border-indigo-300 hover:shadow-md transition-all group">
            <div class="w-11 h-11 bg-purple-100 rounded-xl flex items-center justify-center mb-3 group-hover:bg-purple-200 transition-colors">
                <i class="fas fa-city text-purple-600 text-lg"></i>
            </div>
            <span class="text-sm font-medium text-slate-700">Города</span>
            <span class="mt-1 text-xs text-slate-400">{{ \App\Models\City::count() }}</span>
        </a>
    </div>

    {{-- Pending Users --}}
    @if($pendingUsers->count() > 0)
        <div class="bg-white rounded-xl border border-amber-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-100 bg-amber-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock text-amber-500"></i>
                    <h2 class="text-sm font-semibold text-amber-800">
                        {{ translate('admin.pending_users') }}
                    </h2>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-200 text-amber-800">
                        {{ $pendingUsers->count() }}
                    </span>
                </div>
                <p class="text-xs text-amber-600 hidden sm:block">{{ translate('admin.pending_users_desc') }}</p>
            </div>
            <ul class="divide-y divide-slate-100">
                @foreach($pendingUsers as $user)
                    <li class="px-6 py-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 text-sm font-bold text-indigo-600">
                                {{ mb_strtoupper(mb_substr($user->name, 0, 1, 'UTF-8'), 'UTF-8') }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $user->name }}</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $user->role === 'warehouse_employee' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ $user->role === 'warehouse_employee' ? translate('auth.warehouse_employee') : translate('auth.driver_role') }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ translate('admin.registered_at') }}: {{ $user->created_at->format('d.m.Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2 flex-shrink-0">
                            <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">
                                    <i class="fas fa-check"></i>
                                    {{ \App\Helpers\LocalizationHelper::t('admin.approve') }}
                                </button>
                            </form>
                            <form action="{{ route('admin.users.reject', $user) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors"
                                        onclick="return confirm('{{ \App\Helpers\LocalizationHelper::t('admin.confirm_reject_user') }}')">
                                    <i class="fas fa-times"></i>
                                    {{ \App\Helpers\LocalizationHelper::t('admin.reject') }}
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Approved Users --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
            <i class="fas fa-users text-slate-500"></i>
            <h2 class="text-sm font-semibold text-slate-700">
                {{ translate('admin.approved_users') }}
                <span class="ml-1 text-slate-400 font-normal">({{ $approvedUsers->count() }})</span>
            </h2>
        </div>
        <ul class="divide-y divide-slate-100">
            @foreach($approvedUsers as $user)
                <li class="px-6 py-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center flex-shrink-0 text-sm font-bold text-slate-600">
                            {{ mb_strtoupper(mb_substr($user->name, 0, 1, 'UTF-8'), 'UTF-8') }}
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-sm font-semibold text-slate-900 truncate">{{ $user->name }}</p>
                                @if($user->isAdmin())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                        <i class="fas fa-crown mr-1 text-xs"></i>{{ translate('admin.administrator') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $user->role === 'warehouse_employee' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ $user->role === 'warehouse_employee' ? translate('auth.warehouse_employee') : translate('auth.driver_role') }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    @if(!$user->isAdmin())
                        <div class="flex gap-2 flex-shrink-0">
                            <form action="{{ route('admin.users.toggle-approval', $user) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition-colors">
                                    <i class="fas fa-ban"></i>
                                    {{ \App\Helpers\LocalizationHelper::t('admin.toggle_approval') }}
                                </button>
                            </form>
                            <form action="{{ route('admin.users.delete', $user) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors"
                                        onclick="return confirm('{{ \App\Helpers\LocalizationHelper::t('admin.confirm_delete_user') }}')">
                                    <i class="fas fa-trash"></i>
                                    {{ \App\Helpers\LocalizationHelper::t('admin.delete_user') }}
                                </button>
                            </form>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

</div>
@endsection
