@extends('layouts.app')

@section('title', translate('cargo.show_title'))

@section('content')
@php $dateFmt = app()->getLocale() === 'cn' ? 'Y年n月j日' : 'd.m.Y'; @endphp
<div class="max-w-4xl mx-auto space-y-5">

    {{-- Back + actions row --}}
    <div class="flex items-center justify-between gap-4">
        <button type="button" onclick="history.back()"
                class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>{{ translate('common.back') }}
        </button>
        <div class="flex items-center gap-2">
            @if(auth()->user()->isWarehouseEmployee() && $cargo->status === 'available')
            <a href="{{ route('cargo.edit', $cargo) }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-pencil-alt mr-2"></i>{{ translate('common.edit') }}
            </a>
            <form action="{{ route('cargo.destroy', $cargo) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium rounded-lg transition-colors"
                        onclick="return confirm('{{ translate('cargo.confirm_delete') }}')">
                    <i class="fas fa-trash-alt mr-2"></i>{{ translate('common.delete') }}
                </button>
            </form>
            @elseif(auth()->user()->isDriver() && $cargo->status === 'available' && !$cargo->applications()->where('driver_id', auth()->id())->exists())
            <button type="button" onclick="showApplicationModal({{ $cargo->id }})"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                <i class="fas fa-paper-plane mr-2"></i>{{ translate('cargo.apply_button') }}
            </button>
            @endif
        </div>
    </div>

    {{-- Hero route card --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-4 flex-wrap">
                <div class="text-center">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.from_label') }}</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $cargo->localized_from_location }}</p>
                </div>
                <div class="flex items-center gap-2 text-slate-300">
                    <div class="h-px w-8 bg-slate-200"></div>
                    <div class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-200 flex items-center justify-center">
                        <i class="fas fa-arrow-right text-indigo-500 text-sm"></i>
                    </div>
                    <div class="h-px w-8 bg-slate-200"></div>
                </div>
                <div class="text-center">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.to_label') }}</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $cargo->localized_to_location }}</p>
                </div>
            </div>
            <div>
                @if($cargo->status === 'available')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-700">
                        <i class="fas fa-check-circle mr-1.5"></i>{{ translate('cargo.status_available') }}
                    </span>
                @elseif($cargo->status === 'in_progress' || $cargo->status === 'picked_up')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-amber-100 text-amber-700">
                        <i class="fas fa-truck mr-1.5"></i>{{ translate('cargo.status_picked_up') }}
                    </span>
                @elseif($cargo->status === 'delivered')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-slate-100 text-slate-600">
                        <i class="fas fa-check-double mr-1.5"></i>{{ translate('cargo.status_delivered') }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Info grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.cargo_type') }}</p>
            <p class="text-sm font-semibold text-slate-900">{{ $cargo->cargo_type }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.volume_weight_label') }}</p>
            <p class="text-sm font-semibold text-slate-900">{{ $cargo->volume }} m³ · {{ $cargo->weight }} kg</p>
        </div>
        @if($cargo->price_usd)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.price_usd') }}</p>
            <p class="text-lg font-bold text-emerald-600">${{ number_format($cargo->price_usd, 2) }}</p>
        </div>
        @endif
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.ready_date_label') }}</p>
            <p class="text-sm font-semibold text-slate-900">{{ $cargo->ready_date->format($dateFmt.' H:i') }}</p>
        </div>
    </div>

    {{-- Additional info --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.created_by_label') }}</p>
                <p class="text-slate-800 font-medium">{{ $cargo->createdBy->name }}</p>
                <p class="text-xs text-slate-400 mt-0.5">{{ $cargo->created_at->format($dateFmt.' H:i') }}</p>
            </div>
            @if($cargo->hasApprovedApplication())
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.driver_label') }}</p>
                <p class="text-slate-800 font-medium">{{ $cargo->getApprovedApplication()->driver->name }}</p>
            </div>
            @endif
            @if($cargo->comment)
            <div class="sm:col-span-2">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('cargo.comment_label') }}</p>
                <p class="text-slate-700">{{ $cargo->comment }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Applications section (warehouse employee) --}}
    @if(auth()->user()->isWarehouseEmployee() && $cargo->applications->count() > 0)
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-900">{{ translate('cargo.applications_heading') }}</h2>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                {{ $cargo->applications->count() }}
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('applications.table_driver') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('applications.table_submitted') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('applications.status_label') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('applications.table_notes') }}</th>
                        <th class="relative px-6 py-3"><span class="sr-only">{{ translate('common.actions') }}</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($cargo->applications as $application)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-semibold shrink-0">
                                    {{ strtoupper(substr($application->driver->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-800">{{ $application->driver->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $application->driver->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $application->created_at->format($dateFmt.' H:i') }}</td>
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
                        <td class="px-6 py-4 text-sm text-slate-600 max-w-xs">
                            @if($application->driver_notes)
                                <span class="truncate block" title="{{ $application->driver_notes }}">{{ Str::limit($application->driver_notes, 50) }}</span>
                            @else
                                <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('cargo.applications.show-from-cargo', $application) }}"
                                   class="text-indigo-600 hover:text-indigo-800 text-sm transition-colors">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($application->isPending())
                                <form action="{{ route('applications.approve', $application) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-emerald-600 hover:text-emerald-800 text-sm transition-colors"
                                            onclick="return confirm('{{ translate('applications.confirm_approve') }}')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('applications.reject', $application) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 text-sm transition-colors"
                                            onclick="return confirm('{{ translate('applications.confirm_reject') }}')">
                                        <i class="fas fa-times"></i>
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
    @endif

    {{-- Driver's own application status --}}
    @if(auth()->user()->isDriver())
        @if($cargo->applications()->where('driver_id', auth()->id())->exists())
        @php $myApplication = $cargo->applications()->where('driver_id', auth()->id())->first(); @endphp
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-sm font-semibold text-slate-900 mb-4">{{ translate('cargo.my_application_heading') }}</h2>
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-sm text-slate-500">{{ translate('applications.status_label') }}:</span>
                    @if($myApplication->isPending())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                            <i class="fas fa-clock mr-1"></i>{{ translate('applications.status_pending') }}
                        </span>
                    @elseif($myApplication->isApproved())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                            <i class="fas fa-check mr-1"></i>{{ translate('applications.status_approved_short') }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">
                            <i class="fas fa-times mr-1"></i>{{ translate('applications.status_rejected_short') }}
                        </span>
                    @endif
                </div>
                <a href="{{ route('cargo.applications.show-from-cargo', $myApplication) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-eye mr-2"></i>{{ translate('applications.view_details') }}
                </a>
            </div>
            @if($myApplication->driver_notes)
            <div class="mt-4 pt-4 border-t border-slate-100">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">{{ translate('applications.my_notes_label') }}</p>
                <p class="text-sm text-slate-700">{{ $myApplication->driver_notes }}</p>
            </div>
            @endif
        </div>
        @endif
    @endif
</div>

{{-- Apply modal --}}
@if(auth()->user()->isDriver())
<div id="applicationModal"
     class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
        <div class="px-6 pt-6 pb-4 border-b border-slate-100">
            <h3 class="text-lg font-semibold text-slate-900">{{ translate('applications.apply_modal_title') }}</h3>
            <p class="text-sm text-slate-500 mt-1">{{ $cargo->localized_from_location }} → {{ $cargo->localized_to_location }}</p>
        </div>
        <form id="applicationForm" method="POST" class="p-6">
            @csrf
            <div class="mb-5">
                <label for="driver_notes" class="block text-sm font-medium text-slate-700 mb-1.5">
                    {{ translate('applications.my_notes_optional') }}
                </label>
                <textarea id="driver_notes" name="driver_notes" rows="4"
                          class="block w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                          placeholder="{{ translate('applications.apply_placeholder') }}"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="hideApplicationModal()"
                        class="flex-1 px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors">
                    {{ translate('cargo.cancel') }}
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i>{{ translate('cargo.apply_button') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showApplicationModal(cargoId) {
    document.getElementById('applicationForm').action = `/applications/${cargoId}/apply`;
    document.getElementById('applicationModal').classList.remove('hidden');
}
function hideApplicationModal() {
    document.getElementById('applicationModal').classList.add('hidden');
}
document.getElementById('applicationModal').addEventListener('click', function(e) {
    if (e.target === this) hideApplicationModal();
});
</script>
@endif
@endsection
