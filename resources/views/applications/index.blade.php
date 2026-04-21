@extends('layouts.app')

@section('title', translate('applications.title'))

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">{{ translate('applications.heading') }}</h1>
        <p class="mt-1 text-sm text-slate-500">
            @if(auth()->user()->isAdmin())
                {{ translate('applications.admin_desc') }}
            @else
                {{ translate('applications.driver_desc') }}
            @endif
        </p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

        {{-- Status filter tabs with counts --}}
        <div class="px-6 pt-4">
            <div class="flex gap-1 border-b border-slate-100 overflow-x-auto">

                {{-- All --}}
                <a href="{{ route('applications.index', array_filter(['search' => $search ?: null])) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors whitespace-nowrap
                          {{ $status === null ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    {{ translate('applications.all_statuses') }}
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-xs rounded-full
                                 {{ $status === null ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $counts['all'] }}
                    </span>
                </a>

                {{-- Pending --}}
                <a href="{{ route('applications.index', array_filter(['status' => 'pending', 'search' => $search ?: null])) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors whitespace-nowrap
                          {{ $status === 'pending' ? 'border-amber-500 text-amber-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    <i class="fas fa-clock text-xs"></i>
                    {{ translate('applications.status_pending') }}
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-xs rounded-full
                                 {{ $status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $counts['pending'] }}
                    </span>
                </a>

                {{-- Approved --}}
                <a href="{{ route('applications.index', array_filter(['status' => 'approved', 'search' => $search ?: null])) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors whitespace-nowrap
                          {{ $status === 'approved' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    <i class="fas fa-check text-xs"></i>
                    {{ translate('applications.status_approved') }}
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-xs rounded-full
                                 {{ $status === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $counts['approved'] }}
                    </span>
                </a>

                {{-- Rejected --}}
                <a href="{{ route('applications.index', array_filter(['status' => 'rejected', 'search' => $search ?: null])) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors whitespace-nowrap
                          {{ $status === 'rejected' ? 'border-rose-500 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    <i class="fas fa-times text-xs"></i>
                    {{ translate('applications.status_rejected') }}
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-xs rounded-full
                                 {{ $status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $counts['rejected'] }}
                    </span>
                </a>

            </div>
        </div>

        {{-- Search bar --}}
        <div class="px-6 py-4 border-b border-slate-100">
            <form method="GET" action="{{ route('applications.index') }}" class="flex gap-3">
                @if($status)
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 text-sm"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search }}"
                           class="block w-full pl-10 pr-3 py-2.5 text-sm rounded-lg border border-slate-300 placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="{{ translate('applications.search_placeholder') }}">
                </div>
                <button type="submit"
                        class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition-colors shrink-0">
                    <i class="fas fa-search mr-1.5"></i>{{ translate('applications.search_button') }}
                </button>
                @if($search)
                    <a href="{{ route('applications.index', array_filter(['status' => $status])) }}"
                       class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-lg transition-colors shrink-0">
                        <i class="fas fa-times mr-1"></i>{{ translate('applications.clear_search') }}
                    </a>
                @endif
            </form>
        </div>

        {{-- Results --}}
        @if($applications->count() > 0)

            {{-- Desktop table (hidden on xs) --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('applications.table_route') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('applications.table_driver') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('applications.table_status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('applications.table_submitted') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('applications.table_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($applications as $application)
                        <tr class="hover:bg-slate-50 transition-colors">

                            {{-- Route + cargo type --}}
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $application->cargo->localized_from_location }} → {{ $application->cargo->localized_to_location }}
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $application->cargo->cargo_type }}</p>
                            </td>

                            {{-- Driver --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-semibold shrink-0">
                                        {{ mb_strtoupper(mb_substr($application->driver->name, 0, 1, 'UTF-8'), 'UTF-8') }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-800">{{ $application->driver->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $application->driver->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Status pill --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($application->isPending())
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        <i class="fas fa-clock mr-1"></i>{{ translate('applications.status_pending_short') }}
                                    </span>
                                @elseif($application->isApproved())
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                            <i class="fas fa-check mr-1"></i>{{ translate('applications.status_approved_short') }}
                                        </span>
                                        @if($application->approvedBy && $application->approved_at)
                                            <p class="text-xs text-slate-400 mt-1">
                                                {{ translate('applications.approved_by_label') }} {{ $application->approvedBy->name }}
                                                &middot; {{ $application->approved_at->diffForHumans() }}
                                            </p>
                                        @endif
                                    </div>
                                @else
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                                            <i class="fas fa-times mr-1"></i>{{ translate('applications.status_rejected_short') }}
                                        </span>
                                        @if($application->warehouse_notes)
                                            <p class="text-xs text-slate-500 mt-1 max-w-xs truncate" title="{{ $application->warehouse_notes }}">
                                                {{ $application->warehouse_notes }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            {{-- Submitted at --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-slate-700">{{ $application->created_at->format('d.m.Y') }}</p>
                                <p class="text-xs text-slate-400">{{ $application->created_at->diffForHumans() }}</p>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <a href="{{ route('cargo.applications.show-from-cargo', $application) }}"
                                       class="inline-flex items-center px-3 py-1.5 min-h-[36px] bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg transition-colors">
                                        <i class="fas fa-eye mr-1"></i>{{ translate('applications.view_details') }}
                                    </a>
                                    @if($application->isPending())
                                        <form action="{{ route('applications.approve', $application) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 min-h-[36px] bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-lg transition-colors"
                                                    onclick="return confirm('{{ translate('applications.confirm_approve') }}')">
                                                <i class="fas fa-check mr-1"></i>{{ translate('applications.approve_button') }}
                                            </button>
                                        </form>
                                        <form action="{{ route('applications.reject', $application) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 min-h-[36px] bg-rose-600 hover:bg-rose-700 text-white text-xs font-medium rounded-lg transition-colors"
                                                    onclick="return confirm('{{ translate('applications.confirm_reject') }}')">
                                                <i class="fas fa-times mr-1"></i>{{ translate('applications.reject_button') }}
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

            {{-- Mobile card stack (visible on xs only) --}}
            <div class="sm:hidden divide-y divide-slate-100">
                @foreach($applications as $application)
                <div class="p-4 space-y-3">

                    {{-- Route --}}
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">
                                {{ $application->cargo->localized_from_location }} → {{ $application->cargo->localized_to_location }}
                            </p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $application->cargo->cargo_type }}</p>
                        </div>
                        {{-- Status pill --}}
                        @if($application->isPending())
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 shrink-0">
                                <i class="fas fa-clock mr-1"></i>{{ translate('applications.status_pending_short') }}
                            </span>
                        @elseif($application->isApproved())
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 shrink-0">
                                <i class="fas fa-check mr-1"></i>{{ translate('applications.status_approved_short') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700 shrink-0">
                                <i class="fas fa-times mr-1"></i>{{ translate('applications.status_rejected_short') }}
                            </span>
                        @endif
                    </div>

                    {{-- Driver --}}
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-semibold shrink-0">
                            {{ mb_strtoupper(mb_substr($application->driver->name, 0, 1, 'UTF-8'), 'UTF-8') }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-800">{{ $application->driver->name }}</p>
                            <p class="text-xs text-slate-400">{{ $application->driver->email }}</p>
                        </div>
                    </div>

                    {{-- Rejection note or approval meta --}}
                    @if($application->isRejected() && $application->warehouse_notes)
                        <p class="text-xs text-rose-600 bg-rose-50 rounded-lg px-3 py-2">{{ $application->warehouse_notes }}</p>
                    @elseif($application->isApproved() && $application->approvedBy)
                        <p class="text-xs text-slate-500">
                            {{ translate('applications.approved_by_label') }} {{ $application->approvedBy->name }}
                            @if($application->approved_at)
                                &middot; {{ $application->approved_at->diffForHumans() }}
                            @endif
                        </p>
                    @endif

                    {{-- Submitted timestamp --}}
                    <p class="text-xs text-slate-400">
                        {{ translate('applications.submitted_label') }} {{ $application->created_at->diffForHumans() }}
                    </p>

                    {{-- Actions --}}
                    <div class="flex flex-wrap gap-2 pt-1">
                        <a href="{{ route('cargo.applications.show-from-cargo', $application) }}"
                           class="inline-flex items-center px-3 py-2 min-h-[44px] bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg transition-colors">
                            <i class="fas fa-eye mr-1.5"></i>{{ translate('applications.view_details') }}
                        </a>
                        @if($application->isPending())
                            <form action="{{ route('applications.approve', $application) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-2 min-h-[44px] bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-lg transition-colors"
                                        onclick="return confirm('{{ translate('applications.confirm_approve') }}')">
                                    <i class="fas fa-check mr-1.5"></i>{{ translate('applications.approve_button') }}
                                </button>
                            </form>
                            <form action="{{ route('applications.reject', $application) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-2 min-h-[44px] bg-rose-600 hover:bg-rose-700 text-white text-xs font-medium rounded-lg transition-colors"
                                        onclick="return confirm('{{ translate('applications.confirm_reject') }}')">
                                    <i class="fas fa-times mr-1.5"></i>{{ translate('applications.reject_button') }}
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($applications->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $applications->links() }}
            </div>
            @endif

        @else

            {{-- Empty state — context-aware per active filter --}}
            <div class="py-16 text-center px-6">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4
                            @if($status === 'pending') bg-amber-50
                            @elseif($status === 'approved') bg-emerald-50
                            @elseif($status === 'rejected') bg-rose-50
                            @else bg-slate-100
                            @endif">
                    @if($status === 'pending')
                        <i class="fas fa-clock text-amber-400 text-xl"></i>
                    @elseif($status === 'approved')
                        <i class="fas fa-check-circle text-emerald-400 text-xl"></i>
                    @elseif($status === 'rejected')
                        <i class="fas fa-times-circle text-rose-400 text-xl"></i>
                    @else
                        <i class="fas fa-clipboard-list text-slate-400 text-xl"></i>
                    @endif
                </div>
                <h3 class="text-base font-semibold text-slate-700 mb-1">
                    @if($status === 'pending')
                        {{ translate('applications.no_pending') }}
                    @elseif($status === 'approved')
                        {{ translate('applications.no_approved') }}
                    @elseif($status === 'rejected')
                        {{ translate('applications.no_rejected') }}
                    @elseif($search)
                        {{ translate('applications.no_search_results') }}
                    @else
                        {{ translate('applications.no_applications') }}
                    @endif
                </h3>
                <p class="text-sm text-slate-500">
                    @if($status === 'pending')
                        {{ translate('applications.no_pending_desc') }}
                    @elseif($status === 'approved')
                        {{ translate('applications.no_approved_desc') }}
                    @elseif($status === 'rejected')
                        {{ translate('applications.no_rejected_desc') }}
                    @elseif($search)
                        {{ translate('applications.no_search_results_desc') }}
                    @else
                        {{ translate('applications.no_applications_desc') }}
                    @endif
                </p>
                @if($status || $search)
                    <a href="{{ route('applications.index') }}"
                       class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                        <i class="fas fa-arrow-left text-xs"></i>{{ translate('applications.show_all_link') }}
                    </a>
                @endif
            </div>

        @endif

    </div>
</div>
@endsection
