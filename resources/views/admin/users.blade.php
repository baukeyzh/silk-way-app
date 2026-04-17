@extends('layouts.app')

@section('title', \App\Helpers\LocalizationHelper::t('admin.users_management_title') . ' - Silk Way')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">{{ \App\Helpers\LocalizationHelper::t('admin.users_management_heading') }}</h1>
        <p class="mt-1 text-sm text-slate-500">{{ \App\Helpers\LocalizationHelper::t('admin.users_management_desc') }}</p>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            {{ \App\Helpers\LocalizationHelper::t('admin.table_user') }}
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            {{ \App\Helpers\LocalizationHelper::t('admin.table_role') }}
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            {{ \App\Helpers\LocalizationHelper::t('admin.table_status') }}
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden sm:table-cell">
                            {{ \App\Helpers\LocalizationHelper::t('admin.table_registration_date') }}
                        </th>
                        <th class="relative px-6 py-3.5">
                            <span class="sr-only">{{ \App\Helpers\LocalizationHelper::t('admin.table_actions') }}</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 text-sm font-bold
                                        {{ $user->isAdmin() ? 'bg-purple-100 text-purple-700' : ($user->isWarehouseEmployee() ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700') }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->isAdmin())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                        <i class="fas fa-crown text-xs"></i>
                                        {{ \App\Helpers\LocalizationHelper::t('admin.administrator') }}
                                    </span>
                                @elseif($user->isWarehouseEmployee())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                        <i class="fas fa-warehouse text-xs"></i>
                                        {{ \App\Helpers\LocalizationHelper::t('auth.warehouse_employee') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                        <i class="fas fa-truck text-xs"></i>
                                        {{ \App\Helpers\LocalizationHelper::t('auth.driver_role') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->isApproved())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ \App\Helpers\LocalizationHelper::t('admin.status_approved') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        {{ \App\Helpers\LocalizationHelper::t('admin.status_pending') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 hidden sm:table-cell">
                                {{ $user->created_at->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex justify-end gap-2">
                                    @if(!$user->isApproved())
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
                                    @elseif(!$user->isAdmin())
                                        <form action="{{ route('admin.users.toggle-approval', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition-colors"
                                                    title="{{ \App\Helpers\LocalizationHelper::t('admin.toggle_access_title') }}">
                                                <i class="fas fa-ban"></i>
                                                {{ \App\Helpers\LocalizationHelper::t('admin.toggle_approval') }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.users.delete', $user) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors"
                                                    onclick="return confirm('{{ \App\Helpers\LocalizationHelper::t('admin.confirm_delete_user') }}')"
                                                    title="{{ \App\Helpers\LocalizationHelper::t('admin.delete_user_title') }}">
                                                <i class="fas fa-trash"></i>
                                                {{ \App\Helpers\LocalizationHelper::t('admin.delete_user') }}
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

</div>
@endsection
