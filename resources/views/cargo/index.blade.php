@extends('layouts.app')

@section('title', translate('cargo.available_cargo'))

@section('content')
@php $dateFmt = app()->getLocale() === 'cn' ? 'Y年n月j日' : 'd.m.Y'; @endphp
<div class="space-y-6">

    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ translate('cargo.available_cargo') }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ translate('cargo.available_cargo_desc') }}</p>
        </div>
        @if(auth()->user()->isWarehouseEmployee() || auth()->user()->isAdmin())
        <a href="{{ route('cargo.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm shrink-0">
            <i class="fas fa-plus mr-2"></i>{{ translate('cargo.add_cargo_button') }}
        </a>
        @endif
    </div>

    {{-- Stats bar --}}
    @php
        $total = $cargo->total() ?? $cargo->count();
        $available = $cargo->where('status','available')->count();
        $pickedUp = $cargo->where('status','picked_up')->count();
    @endphp
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('cargo.total') }}</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $cargo->total() ?? $cargo->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('cargo.status_available') }}</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $cargo->where('status','available')->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('cargo.status_in_transit') }}</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ $cargo->where('status','picked_up')->count() }}</p>
        </div>
    </div>

    {{-- Search + filters --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <form method="GET" action="{{ route('cargo.index') }}" class="flex flex-col sm:flex-row gap-3">
            {{-- Search --}}
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400 text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="block w-full pl-10 pr-3 py-2.5 text-sm rounded-lg border border-slate-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       placeholder="{{ translate('cargo.search_placeholder') }}">
            </div>

            {{-- Status pills --}}
            <div class="flex flex-wrap gap-2 items-center">
                <a href="{{ route('cargo.index', array_merge(request()->except('status'), ['search' => request('search')])) }}"
                   class="px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ translate('cargo.all_statuses') }}
                </a>
                <a href="{{ route('cargo.index', array_merge(request()->except('status'), ['status' => 'available', 'search' => request('search')])) }}"
                   class="px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request('status') === 'available' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <i class="fas fa-check-circle mr-1"></i>{{ translate('cargo.status_available') }}
                </a>
                <a href="{{ route('cargo.index', array_merge(request()->except('status'), ['status' => 'picked_up', 'search' => request('search')])) }}"
                   class="px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request('status') === 'picked_up' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <i class="fas fa-truck mr-1"></i>{{ translate('cargo.status_picked_up') }}
                </a>
                <a href="{{ route('cargo.index', array_merge(request()->except('status'), ['status' => 'delivered', 'search' => request('search')])) }}"
                   class="px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request('status') === 'delivered' ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <i class="fas fa-check-double mr-1"></i>{{ translate('cargo.status_delivered') }}
                </a>
            </div>

            <button type="submit"
                    class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition-colors shrink-0">
                <i class="fas fa-filter mr-1.5"></i>{{ translate('cargo.filter_button') }}
            </button>
        </form>
    </div>

    {{-- Results --}}
    @if($cargo->count() > 0)

        {{-- Desktop table --}}
        <div class="hidden lg:block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('cargo.table_route') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('cargo.table_cargo') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('cargo.table_readiness') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('cargo.table_status') }}</th>
                        @if(auth()->user()->isDriver())
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('cargo.my_application_col') }}</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('cargo.table_created') }}</th>
                        <th class="relative px-6 py-3"><span class="sr-only">{{ translate('common.actions') }}</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($cargo as $item)
                    <tr class="hover:bg-slate-50 transition-colors cursor-pointer cargo-row"
                        onclick="handleCargoRowClick(event, '{{ route('cargo.show', $item) }}')">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-slate-900">{{ $item->localized_from_location }}</div>
                            <div class="flex items-center text-xs text-slate-500 mt-0.5">
                                <i class="fas fa-arrow-right mr-1 text-slate-400"></i>{{ $item->localized_to_location }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-slate-800">{{ $item->cargo_type }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $item->volume }} m³ · {{ $item->weight }} kg</div>
                            @if($item->price_usd)
                            <div class="text-xs font-semibold text-emerald-600 mt-0.5">${{ number_format($item->price_usd, 2) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ $item->ready_date->format($dateFmt) }}<br>
                            <span class="text-xs text-slate-400">{{ $item->ready_date->format('H:i') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->status === 'available')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    <i class="fas fa-check-circle mr-1"></i>{{ translate('cargo.status_available') }}
                                </span>
                            @elseif($item->status === 'picked_up')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                    <i class="fas fa-truck mr-1"></i>{{ translate('cargo.status_picked_up') }}
                                </span>
                            @elseif($item->status === 'delivered')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                    <i class="fas fa-check-double mr-1"></i>{{ translate('cargo.status_delivered') }}
                                </span>
                            @endif
                        </td>
                        @if(auth()->user()->isDriver())
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php $myApp = $myApplications->get($item->id) @endphp
                            @if($myApp)
                                @if($myApp->isPending())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        <i class="fas fa-clock mr-1"></i>{{ translate('cargo.app_pending') }}
                                    </span>
                                @elseif($myApp->isApproved())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                        <i class="fas fa-check mr-1"></i>{{ translate('cargo.app_approved') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                                        <i class="fas fa-times mr-1"></i>{{ translate('cargo.app_rejected') }}
                                    </span>
                                @endif
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400">
                            {{ $item->created_at->format($dateFmt) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <div class="flex items-center justify-end space-x-3" onclick="event.stopPropagation()">
                                <a href="{{ route('cargo.show', $item) }}" class="text-indigo-600 hover:text-indigo-800 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->isWarehouseEmployee() && $item->status === 'available')
                                <a href="{{ route('cargo.edit', $item) }}" class="text-slate-500 hover:text-slate-700 transition-colors">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('cargo.destroy', $item) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 transition-colors"
                                            onclick="return confirm('{{ translate('cargo.confirm_delete') }}')">
                                        <i class="fas fa-trash-alt"></i>
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

        {{-- Mobile card grid --}}
        <div class="lg:hidden grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($cargo as $item)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden cursor-pointer cargo-card hover:shadow-md transition-shadow"
                 onclick="handleCargoCardClick(event, '{{ route('cargo.show', $item) }}')">
                {{-- Card header with status --}}
                <div class="px-4 pt-4 pb-3 border-b border-slate-100">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-900 truncate">{{ $item->localized_from_location }}</p>
                            <div class="flex items-center text-xs text-slate-500 mt-0.5">
                                <i class="fas fa-arrow-right mr-1 text-slate-300"></i>
                                <span class="truncate">{{ $item->localized_to_location }}</span>
                            </div>
                        </div>
                        @if($item->status === 'available')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 shrink-0">
                                <i class="fas fa-check-circle mr-1"></i>{{ translate('cargo.status_available') }}
                            </span>
                        @elseif($item->status === 'picked_up')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700 shrink-0">
                                <i class="fas fa-truck mr-1"></i>{{ translate('cargo.status_in_transit') }}
                            </span>
                        @elseif($item->status === 'delivered')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 shrink-0">
                                <i class="fas fa-check-double mr-1"></i>{{ translate('cargo.status_delivered') }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Card body --}}
                <div class="px-4 py-3">
                    <p class="text-sm font-medium text-slate-800 mb-2">{{ $item->cargo_type }}</p>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 text-xs">
                        <div class="text-slate-500">{{ translate('cargo.volume_label') }} <span class="font-medium text-slate-700">{{ $item->volume }} m³</span></div>
                        <div class="text-slate-500">{{ translate('cargo.weight_label') }} <span class="font-medium text-slate-700">{{ $item->weight }} kg</span></div>
                        @if($item->price_usd)
                        <div class="text-slate-500">{{ translate('cargo.price_label') }} <span class="font-semibold text-emerald-600">${{ number_format($item->price_usd, 2) }}</span></div>
                        @endif
                        <div class="text-slate-500">{{ translate('cargo.ready_label') }} <span class="font-medium text-slate-700">{{ $item->ready_date->format($dateFmt) }}</span></div>
                    </div>

                    @if(auth()->user()->isDriver())
                    @php $myApp = $myApplications->get($item->id) @endphp
                    @if($myApp)
                    <div class="mt-3">
                        @if($myApp->isPending())
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                <i class="fas fa-clock mr-1"></i>{{ translate('cargo.app_pending_full') }}
                            </span>
                        @elseif($myApp->isApproved())
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                <i class="fas fa-check mr-1"></i>{{ translate('cargo.app_approved_full') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                                <i class="fas fa-times mr-1"></i>{{ translate('cargo.app_rejected_full') }}
                            </span>
                        @endif
                    </div>
                    @endif
                    @endif
                </div>

                {{-- Card footer actions --}}
                <div class="px-4 pb-4 flex items-center justify-between gap-2" onclick="event.stopPropagation()">
                    <a href="{{ route('cargo.show', $item) }}"
                       class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition-colors">
                        <i class="fas fa-eye mr-1.5"></i>{{ translate('cargo.view_button') }}
                    </a>
                    @if(auth()->user()->isWarehouseEmployee() && $item->status === 'available')
                    <div class="flex gap-2">
                        <a href="{{ route('cargo.edit', $item) }}"
                           class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium rounded-lg transition-colors">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form action="{{ route('cargo.destroy', $item) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-300 hover:bg-rose-50 hover:border-rose-300 text-rose-600 text-xs font-medium rounded-lg transition-colors"
                                    onclick="return confirm('{{ translate('cargo.confirm_delete') }}')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $cargo->links() }}
        </div>

    @else
        {{-- Empty state --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm py-16 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-box text-slate-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-700 mb-1">{{ translate('cargo.no_cargo_found') }}</h3>
            <p class="text-sm text-slate-500 mb-6">
                @if(request('search') || request('status'))
                    {{ translate('cargo.try_change_search') }}
                @else
                    {{ translate('cargo.no_cargo_desc') }}
                @endif
            </p>
            @if(request('search') || request('status'))
            <a href="{{ route('cargo.index') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-times mr-2"></i>{{ translate('cargo.reset_filters') }}
            </a>
            @endif
        </div>
    @endif
</div>

<script>
function handleCargoRowClick(event, url) {
    if (event.target.closest('a, button, form')) return;
    window.location.href = url;
}
function handleCargoCardClick(event, url) {
    if (event.target.closest('a, button, form')) return;
    window.location.href = url;
}
</script>
@endsection
