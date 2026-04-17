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

    {{-- Status filter tabs --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 pt-4">
            <div class="flex gap-1 border-b border-slate-100">
                <a href="{{ route('applications.index', array_merge(request()->except('status'), [])) }}"
                   class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors whitespace-nowrap {{ !request('status') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    {{ translate('applications.all_statuses') }}
                </a>
                <a href="{{ route('applications.index', array_merge(request()->except('status'), ['status' => 'pending'])) }}"
                   class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors whitespace-nowrap {{ request('status') === 'pending' ? 'border-amber-500 text-amber-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    <i class="fas fa-clock mr-1.5"></i>{{ translate('applications.status_pending') }}
                </a>
                <a href="{{ route('applications.index', array_merge(request()->except('status'), ['status' => 'approved'])) }}"
                   class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors whitespace-nowrap {{ request('status') === 'approved' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    <i class="fas fa-check mr-1.5"></i>{{ translate('applications.status_approved') }}
                </a>
                <a href="{{ route('applications.index', array_merge(request()->except('status'), ['status' => 'rejected'])) }}"
                   class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors whitespace-nowrap {{ request('status') === 'rejected' ? 'border-rose-500 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    <i class="fas fa-times mr-1.5"></i>{{ translate('applications.status_rejected') }}
                </a>
            </div>
        </div>

        {{-- Search --}}
        <div class="px-6 py-4 border-b border-slate-100">
            <form method="GET" action="{{ route('applications.index') }}" class="flex gap-3">
                @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 text-sm"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="block w-full pl-10 pr-3 py-2.5 text-sm rounded-lg border border-slate-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="{{ translate('applications.search_placeholder') }}">
                </div>
                <button type="submit"
                        class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition-colors shrink-0">
                    <i class="fas fa-search mr-1.5"></i>{{ translate('applications.search_button') }}
                </button>
            </form>
        </div>

        {{-- Results --}}
        @if($applications->count() > 0)
        <div class="overflow-x-auto">
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
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-semibold text-slate-900">
                                {{ $application->cargo->localized_from_location }} → {{ $application->cargo->localized_to_location }}
                            </p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $application->cargo->cargo_type }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-semibold shrink-0">
                                    {{ strtoupper(substr($application->driver->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-800">{{ $application->driver->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $application->driver->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($application->isPending())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                    <i class="fas fa-clock mr-1"></i>{{ translate('applications.status_pending_short') }}
                                </span>
                            @elseif($application->isApproved())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    <i class="fas fa-check mr-1"></i>{{ translate('applications.status_approved_short') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                                    <i class="fas fa-times mr-1"></i>{{ translate('applications.status_rejected_short') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $application->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('cargo.applications.show-from-cargo', $application) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg transition-colors">
                                    <i class="fas fa-eye mr-1"></i>{{ translate('applications.view_details') }}
                                </a>
                                @if($application->isPending())
                                <form action="{{ route('applications.approve', $application) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-lg transition-colors"
                                            onclick="return confirm('{{ translate('applications.confirm_approve') }}')">
                                        <i class="fas fa-check mr-1"></i>{{ translate('applications.approve_button') }}
                                    </button>
                                </form>
                                <form action="{{ route('applications.reject', $application) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-medium rounded-lg transition-colors"
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

        {{-- Mobile cards --}}
        {{-- (The table above scrolls on mobile; alternatively show cards) --}}

        @if($applications->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $applications->links() }}
        </div>
        @endif

        @else
        <div class="py-16 text-center px-6">
            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-clipboard-list text-slate-400 text-xl"></i>
            </div>
            <h3 class="text-base font-semibold text-slate-700 mb-1">{{ translate('applications.no_applications') }}</h3>
            <p class="text-sm text-slate-500">{{ translate('applications.no_applications_desc') }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
