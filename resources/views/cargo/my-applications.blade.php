@extends('layouts.app')

@section('title', translate('my_applications.title'))

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ translate('my_applications.heading') }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ translate('my_applications.description') }}</p>
        </div>
        <a href="{{ route('cargo.index') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm shrink-0">
            <i class="fas fa-box mr-2"></i>{{ translate('my_applications.view_cargo_button') }}
        </a>
    </div>

    {{-- Stats row --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fas fa-clock text-amber-600"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500">{{ translate('my_applications.stats_pending') }}</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $pendingApplications->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fas fa-check text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500">{{ translate('my_applications.stats_approved') }}</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $approvedApplications->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fas fa-times text-rose-600"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500">{{ translate('my_applications.stats_rejected') }}</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $rejectedApplications->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- No applications --}}
    @if($pendingApplications->count() === 0 && $approvedApplications->count() === 0 && $rejectedApplications->count() === 0)
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm py-16 text-center">
        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-inbox text-slate-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-slate-700 mb-1">{{ translate('my_applications.no_applications_title') }}</h3>
        <p class="text-sm text-slate-500 mb-6">{{ translate('my_applications.no_applications_desc') }}</p>
        <a href="{{ route('cargo.index') }}"
           class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
            <i class="fas fa-box mr-2"></i>{{ translate('my_applications.view_available_cargo') }}
        </a>
    </div>
    @endif

    {{-- Pending applications --}}
    @if($pendingApplications->count() > 0)
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-900">{{ translate('my_applications.pending_title') }}</h2>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                {{ $pendingApplications->count() }}
            </span>
        </div>
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('my_applications.table_route') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('my_applications.table_cargo') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('my_applications.table_submitted') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ translate('my_applications.table_actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($pendingApplications as $application)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-medium text-slate-800">{{ $application->cargo->localized_from_location }} → {{ $application->cargo->localized_to_location }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm text-slate-700">{{ $application->cargo->cargo_type }}</p>
                            <p class="text-xs text-slate-400">{{ $application->cargo->volume }} м³ · {{ $application->cargo->weight }} кг</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $application->created_at->format('d.m.Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('my-cargo.applications.show-from-my-cargo', $application) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg transition-colors">
                                <i class="fas fa-eye mr-1.5"></i>{{ translate('my_applications.view_details') }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="lg:hidden divide-y divide-slate-100">
            @foreach($pendingApplications as $application)
            <div class="p-4">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $application->cargo->localized_from_location }} → {{ $application->cargo->localized_to_location }}</p>
                        <p class="text-xs text-slate-500">{{ $application->cargo->cargo_type }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700 shrink-0">
                        <i class="fas fa-clock mr-1"></i>{{ translate('my_applications.status_pending') }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 mb-3">Подано: {{ $application->created_at->format('d.m.Y H:i') }}</p>
                <a href="{{ route('my-cargo.applications.show-from-my-cargo', $application) }}"
                   class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition-colors">
                    <i class="fas fa-eye mr-1.5"></i>{{ translate('my_applications.view_details') }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Approved applications --}}
    @if($approvedApplications->count() > 0)
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-emerald-800">{{ translate('my_applications.approved_title') }}</h2>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                {{ $approvedApplications->count() }}
            </span>
        </div>
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Маршрут</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Груз</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Подтверждено</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Действия</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($approvedApplications as $application)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-medium text-slate-800">{{ $application->cargo->localized_from_location }} → {{ $application->cargo->localized_to_location }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm text-slate-700">{{ $application->cargo->cargo_type }}</p>
                            <p class="text-xs text-slate-400">{{ $application->cargo->volume }} м³ · {{ $application->cargo->weight }} кг</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $application->approved_at->format('d.m.Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex gap-2">
                                <a href="{{ route('my-cargo.applications.show-from-my-cargo', $application) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-medium rounded-lg transition-colors">
                                    <i class="fas fa-eye mr-1.5"></i>Подробнее
                                </a>
                                @if($application->cargo->status !== 'delivered')
                                <form action="{{ route('applications.mark-delivered', $application) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-lg transition-colors">
                                        <i class="fas fa-check mr-1.5"></i>Доставлен
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
        <div class="lg:hidden divide-y divide-slate-100">
            @foreach($approvedApplications as $application)
            <div class="p-4">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $application->cargo->localized_from_location }} → {{ $application->cargo->localized_to_location }}</p>
                        <p class="text-xs text-slate-500">{{ $application->cargo->cargo_type }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 shrink-0">
                        <i class="fas fa-check mr-1"></i>Принято
                    </span>
                </div>
                <p class="text-xs text-slate-400 mb-3">Подтверждено: {{ $application->approved_at->format('d.m.Y H:i') }}</p>
                <div class="flex gap-2">
                    <a href="{{ route('my-cargo.applications.show-from-my-cargo', $application) }}"
                       class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-lg transition-colors">
                        <i class="fas fa-eye mr-1.5"></i>Подробнее
                    </a>
                    @if($application->cargo->status !== 'delivered')
                    <form action="{{ route('applications.mark-delivered', $application) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-300 text-slate-700 text-xs font-medium rounded-lg transition-colors">
                            <i class="fas fa-check-double mr-1.5"></i>Отметить доставленным
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Rejected applications --}}
    @if($rejectedApplications->count() > 0)
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-rose-800">{{ translate('my_applications.rejected_title') }}</h2>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">
                {{ $rejectedApplications->count() }}
            </span>
        </div>
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Маршрут</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Груз</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Действия</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($rejectedApplications as $application)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-medium text-slate-800">{{ $application->cargo->localized_from_location }} → {{ $application->cargo->localized_to_location }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm text-slate-700">{{ $application->cargo->cargo_type }}</p>
                            <p class="text-xs text-slate-400">{{ $application->cargo->volume }} м³ · {{ $application->cargo->weight }} кг</p>
                            @if($application->driver_notes)
                            <p class="text-xs text-slate-400 mt-1">Заметки: {{ Str::limit($application->driver_notes, 40) }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('my-cargo.applications.show-from-my-cargo', $application) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-medium rounded-lg transition-colors">
                                <i class="fas fa-eye mr-1.5"></i>{{ translate('my_applications.view_details') }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="lg:hidden divide-y divide-slate-100">
            @foreach($rejectedApplications as $application)
            <div class="p-4">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $application->cargo->localized_from_location }} → {{ $application->cargo->localized_to_location }}</p>
                        <p class="text-xs text-slate-500">{{ $application->cargo->cargo_type }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700 shrink-0">
                        <i class="fas fa-times mr-1"></i>{{ translate('my_applications.status_rejected') }}
                    </span>
                </div>
                @if($application->driver_notes)
                <p class="text-xs text-slate-400 mb-3">{{ translate('my_applications.driver_notes_label') }} {{ Str::limit($application->driver_notes, 50) }}</p>
                @endif
                <a href="{{ route('my-cargo.applications.show-from-my-cargo', $application) }}"
                   class="inline-flex items-center px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-medium rounded-lg transition-colors">
                    <i class="fas fa-eye mr-1.5"></i>{{ translate('my_applications.view_details') }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
