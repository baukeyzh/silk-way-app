@extends('layouts.app')

@section('title', translate('my_cargo.title'))

@section('content')
@php $dateFmt = app()->getLocale() === 'cn' ? 'Y年n月j日' : 'd.m.Y'; @endphp
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ translate('my_cargo.heading') }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ translate('my_cargo.description') }}</p>
        </div>
        <a href="{{ route('cargo.index') }}"
           class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors shrink-0">
            <i class="fas fa-box mr-2"></i>{{ translate('cargo.available_cargo') }}
        </a>
    </div>

    {{-- Stats cards --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fas fa-truck text-indigo-600"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500">{{ translate('my_cargo.stats_total_picked') }}</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $cargo->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fas fa-route text-amber-600"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500">{{ translate('my_cargo.stats_in_delivery') }}</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $cargo->where('status', 'picked_up')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500">{{ translate('my_cargo.stats_delivered') }}</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $cargo->where('status', 'delivered')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    @if($cargo->count() > 0)

        {{-- Desktop table --}}
        <div class="hidden lg:block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('my_cargo.table_route') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('my_cargo.table_cargo') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('my_cargo.table_picked') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('my_cargo.table_status') }}</th>
                        <th class="relative px-6 py-3"><span class="sr-only">{{ translate('common.actions') }}</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($cargo as $item)
                    <tr class="hover:bg-slate-50 transition-colors cursor-pointer"
                        onclick="handleCargoRowClick(event, '{{ route('cargo.show', $item) }}')">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-slate-900">{{ $item->localized_from_location }}</div>
                            <div class="flex items-center text-xs text-slate-500 mt-0.5">
                                <i class="fas fa-arrow-right mr-1 text-slate-300"></i>{{ $item->localized_to_location }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-medium text-slate-800">{{ $item->cargo_type }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $item->volume }} m³ · {{ $item->weight }} kg</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ $item->picked_at?->format($dateFmt.' H:i') ?? '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                {{-- Timeline dots --}}
                                <div class="flex items-center gap-1">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                    <div class="w-6 h-px {{ in_array($item->status, ['picked_up','delivered']) ? 'bg-amber-400' : 'bg-slate-200' }}"></div>
                                    <div class="w-2 h-2 rounded-full {{ in_array($item->status, ['picked_up','delivered']) ? 'bg-amber-500' : 'bg-slate-200' }}"></div>
                                    <div class="w-6 h-px {{ $item->status === 'delivered' ? 'bg-emerald-400' : 'bg-slate-200' }}"></div>
                                    <div class="w-2 h-2 rounded-full {{ $item->status === 'delivered' ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>
                                </div>
                                @if($item->status === 'picked_up')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        {{ translate('my_cargo.status_in_delivery') }}
                                    </span>
                                @elseif($item->status === 'delivered')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                        {{ translate('my_cargo.status_delivered') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                        {{ translate('cargo.status_available') }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('cargo.show', $item) }}" class="text-indigo-600 hover:text-indigo-800 transition-colors text-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($item->status === 'in_progress' || $item->status === 'picked_up')
                                @php $app = $applications->get($item->id); @endphp
                                @if($app)
                                @php $cmrStatus = $app->cmr_status ?? 'not_uploaded'; @endphp
                                <a href="{{ route('my-cargo.applications.show-from-my-cargo', $app) }}"
                                   class="inline-flex items-center px-3 py-1 min-h-[36px] text-xs font-medium rounded-lg transition-colors
                                          @if($cmrStatus === 'pending_review') bg-amber-100 hover:bg-amber-200 text-amber-700
                                          @elseif($cmrStatus === 'confirmed') bg-emerald-100 hover:bg-emerald-200 text-emerald-700
                                          @elseif($cmrStatus === 'rejected') bg-rose-100 hover:bg-rose-200 text-rose-700
                                          @else bg-indigo-600 hover:bg-indigo-700 text-white
                                          @endif">
                                    @if($cmrStatus === 'pending_review')
                                        <i class="fas fa-clock mr-1"></i>{{ translate('cmr.badge_pending') }}
                                    @elseif($cmrStatus === 'confirmed')
                                        <i class="fas fa-check-circle mr-1"></i>{{ translate('cmr.banner_confirmed_title') }}
                                    @elseif($cmrStatus === 'rejected')
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ translate('cmr.badge_rejected') }}
                                    @else
                                        <i class="fas fa-file-contract mr-1"></i>{{ translate('cmr.action_deliver') }}
                                    @endif
                                </a>
                                @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="lg:hidden space-y-4">
            @foreach($cargo as $item)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden cursor-pointer"
                 onclick="handleCargoCardClick(event, '{{ route('cargo.show', $item) }}')">
                <div class="p-4">
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $item->localized_from_location }} → {{ $item->localized_to_location }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $item->cargo_type }}</p>
                        </div>
                        @if($item->status === 'picked_up')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700 shrink-0">
                                <i class="fas fa-truck mr-1"></i>{{ translate('my_cargo.status_in_delivery') }}
                            </span>
                        @elseif($item->status === 'delivered')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 shrink-0">
                                <i class="fas fa-check-double mr-1"></i>{{ translate('my_cargo.status_delivered') }}
                            </span>
                        @endif
                    </div>

                    {{-- Progress bar --}}
                    <div class="flex items-center gap-2 mb-3">
                        <div class="flex-1 flex items-center gap-1">
                            <div class="flex-1 h-1.5 rounded-full {{ in_array($item->status, ['picked_up','delivered']) ? 'bg-amber-400' : 'bg-slate-200' }}"></div>
                            <div class="flex-1 h-1.5 rounded-full {{ $item->status === 'delivered' ? 'bg-emerald-400' : 'bg-slate-200' }}"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="text-slate-500">{{ translate('my_cargo.volume_label') }} <span class="font-medium text-slate-700">{{ $item->volume }} m³</span></div>
                        <div class="text-slate-500">{{ translate('my_cargo.weight_label') }} <span class="font-medium text-slate-700">{{ $item->weight }} kg</span></div>
                        <div class="text-slate-500">{{ translate('my_cargo.picked_label') }} <span class="font-medium text-slate-700">{{ $item->picked_at?->format($dateFmt) ?? '—' }}</span></div>
                    </div>
                </div>
                <div class="px-4 pb-4 flex items-center justify-between gap-2" onclick="event.stopPropagation()">
                    <a href="{{ route('cargo.show', $item) }}"
                       class="inline-flex items-center px-3 py-1.5 min-h-[44px] bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium rounded-lg transition-colors">
                        <i class="fas fa-eye mr-1.5"></i>{{ translate('my_cargo.view_button') }}
                    </a>
                    @if($item->status === 'in_progress' || $item->status === 'picked_up')
                    @php $app = $applications->get($item->id); @endphp
                    @if($app)
                    @php $cmrStatus = $app->cmr_status ?? 'not_uploaded'; @endphp
                    <a href="{{ route('my-cargo.applications.show-from-my-cargo', $app) }}"
                       class="inline-flex items-center px-3 py-1.5 min-h-[44px] text-xs font-semibold rounded-lg transition-colors
                              @if($cmrStatus === 'pending_review') bg-amber-100 hover:bg-amber-200 text-amber-700
                              @elseif($cmrStatus === 'confirmed') bg-emerald-100 hover:bg-emerald-200 text-emerald-700
                              @elseif($cmrStatus === 'rejected') bg-rose-100 hover:bg-rose-200 text-rose-700
                              @else bg-indigo-600 hover:bg-indigo-700 text-white
                              @endif">
                        @if($cmrStatus === 'pending_review')
                            <i class="fas fa-clock mr-1.5"></i>{{ translate('cmr.badge_pending') }}
                        @elseif($cmrStatus === 'confirmed')
                            <i class="fas fa-check-circle mr-1.5"></i>{{ translate('cmr.banner_confirmed_title') }}
                        @elseif($cmrStatus === 'rejected')
                            <i class="fas fa-exclamation-circle mr-1.5"></i>{{ translate('cmr.badge_rejected') }}
                        @else
                            <i class="fas fa-file-contract mr-1.5"></i>{{ translate('cmr.action_deliver') }}
                        @endif
                    </a>
                    @endif
                    @endif
                </div>
            </div>
            @endforeach
        </div>

    @else
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm py-16 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-truck text-slate-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-700 mb-1">{{ translate('my_cargo.no_cargo_title') }}</h3>
            <p class="text-sm text-slate-500 mb-6">{{ translate('my_cargo.no_cargo_desc') }}</p>
            <a href="{{ route('cargo.index') }}"
               class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                <i class="fas fa-box mr-2"></i>{{ translate('cargo.available_cargo') }}
            </a>
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
