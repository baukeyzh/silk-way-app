@extends('layouts.app')

@section('title', translate('docs.admin_title'))

@section('content')
@php
    $icons = [
        'driver_license'   => 'fa-id-card',
        'vehicle_passport' => 'fa-file-alt',
        'trailer_passport' => 'fa-trailer',
        'category_cert'    => 'fa-certificate',
        'green_card'       => 'fa-globe',
        'insurance'        => 'fa-shield-alt',
    ];

    $filterTabs = [
        'pending'  => translate('docs.admin_filter_pending'),
        'all'      => translate('docs.admin_filter_all'),
        'rejected' => translate('docs.admin_filter_rejected'),
        'verified' => translate('docs.admin_filter_verified'),
    ];

    $statusConfig = [
        'pending' => [
            'pill'   => 'bg-amber-100 text-amber-700',
            'dot'    => 'bg-amber-400',
            'border' => 'border-amber-200',
            'stripe' => 'bg-amber-400',
            'icon'   => 'bg-amber-100 text-amber-600',
            'label'  => translate('docs.status_pending'),
        ],
        'verified' => [
            'pill'   => 'bg-emerald-100 text-emerald-700',
            'dot'    => 'bg-emerald-500',
            'border' => 'border-emerald-200',
            'stripe' => 'bg-emerald-500',
            'icon'   => 'bg-emerald-100 text-emerald-600',
            'label'  => translate('docs.status_verified'),
        ],
        'rejected' => [
            'pill'   => 'bg-rose-100 text-rose-700',
            'dot'    => 'bg-rose-500',
            'border' => 'border-rose-200',
            'stripe' => 'bg-rose-500',
            'icon'   => 'bg-rose-100 text-rose-600',
            'label'  => translate('docs.status_rejected'),
        ],
        'not_uploaded' => [
            'pill'   => 'bg-slate-100 text-slate-500',
            'dot'    => 'bg-slate-300',
            'border' => 'border-slate-200',
            'stripe' => 'bg-slate-200',
            'icon'   => 'bg-slate-100 text-slate-400',
            'label'  => translate('docs.status_not_uploaded'),
        ],
    ];
@endphp

<div class="space-y-6">

    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ translate('docs.admin_title') }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $drivers->total() }} {{ translate('docs.admin_drivers_count') }}</p>
        </div>

        {{-- Legend --}}
        <div class="flex items-center gap-3 flex-wrap text-xs text-slate-500">
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>{{ translate('docs.status_not_uploaded') }}</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>{{ translate('docs.status_pending') }}</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>{{ translate('docs.status_verified') }}</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>{{ translate('docs.status_rejected') }}</span>
        </div>
    </div>

    {{-- Filters + Search --}}
    <div class="flex flex-col sm:flex-row gap-3">

        {{-- Filter tabs --}}
        <div class="flex gap-1 bg-slate-100 rounded-xl p-1 flex-wrap">
            @foreach($filterTabs as $tabKey => $tabLabel)
            <a href="{{ request()->fullUrlWithQuery(['filter' => $tabKey, 'search' => $search, 'page' => 1]) }}"
               class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors whitespace-nowrap
                      {{ $filter === $tabKey
                           ? 'bg-white text-slate-900 shadow-sm'
                           : 'text-slate-500 hover:text-slate-700' }}">
                {{ $tabLabel }}
            </a>
            @endforeach
        </div>

        {{-- Search --}}
        <form method="GET" action="{{ route('admin.documents.index') }}" class="flex-1 min-w-0">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400 text-sm"></i>
                </span>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="{{ translate('docs.admin_search_placeholder') }}"
                       class="w-full pl-9 pr-4 py-2 text-sm border border-slate-300 rounded-xl bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
            </div>
        </form>

    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 text-sm text-emerald-700">
        <i class="fas fa-check-circle text-emerald-500 shrink-0"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 bg-rose-50 border border-rose-200 rounded-xl px-4 py-3 text-sm text-rose-700">
        <i class="fas fa-exclamation-circle text-rose-500 shrink-0"></i>
        {{ session('error') }}
    </div>
    @endif

    {{-- Driver accordion list --}}
    <div class="space-y-3">

        @forelse($drivers as $driver)
        @php
            $docsByType    = $driver->driverDocuments->keyBy('document_type');
            $verifiedCount = $driver->driverDocuments->where('status', 'verified')->count();
            $pendingCount  = $driver->driverDocuments->where('status', 'pending')->count();
            $rejectedCount = $driver->driverDocuments->where('status', 'rejected')->count();
            $driverId      = $driver->id;
        @endphp

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden"
             x-data="{ open: {{ $pendingCount > 0 || $rejectedCount > 0 ? 'true' : 'false' }} }">

            {{-- Accordion header / driver row --}}
            <button @click="open = !open"
                    class="w-full flex items-center gap-4 px-5 py-4 hover:bg-slate-50 transition-colors text-left">

                {{-- Avatar --}}
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm shrink-0">
                    {{ strtoupper(substr($driver->name, 0, 1)) }}
                </div>

                {{-- Name + email --}}
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-800 text-sm truncate">{{ $driver->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ $driver->email }}</p>
                </div>

                {{-- Status summary dots --}}
                <div class="hidden sm:flex items-center gap-1.5 shrink-0">
                    @foreach($documentTypes as $type)
                    @php
                        $dot = $docsByType->get($type);
                        $dotStatus = $dot?->status ?? 'not_uploaded';
                        $dotColor = $statusConfig[$dotStatus]['dot'] ?? 'bg-slate-300';
                    @endphp
                    <span class="w-2.5 h-2.5 rounded-full {{ $dotColor }}" title="{{ translate('docs.' . $type) }}"></span>
                    @endforeach
                </div>

                {{-- Verified count pill --}}
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold shrink-0
                    {{ $verifiedCount === 6 ? 'bg-emerald-100 text-emerald-700' : ($pendingCount > 0 ? 'bg-amber-100 text-amber-700' : ($rejectedCount > 0 ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-600')) }}">
                    {{ $verifiedCount }}/6
                </span>

                {{-- Chevron --}}
                <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform shrink-0"
                   :class="open ? 'rotate-180' : ''"></i>
            </button>

            {{-- Expanded document cards --}}
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 x-cloak>
                <div class="border-t border-slate-100 px-5 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

                        @foreach($documentTypes as $type)
                        @php
                            $doc       = $docsByType->get($type);
                            $status    = $doc?->status ?? 'not_uploaded';
                            $cfg       = $statusConfig[$status] ?? $statusConfig['not_uploaded'];
                            $icon      = $icons[$type] ?? 'fa-file-alt';
                            $isImage   = $doc && $doc->original_filename
                                         && in_array(strtolower(pathinfo($doc->original_filename, PATHINFO_EXTENSION)), ['jpg','jpeg','png'], true);
                            $isPdf     = $doc && $doc->original_filename
                                         && strtolower(pathinfo($doc->original_filename, PATHINFO_EXTENSION)) === 'pdf';
                            $fileUrl   = ($doc && $doc->file_path)
                                         ? route('admin.documents.file', $doc)
                                         : null;
                            $verifier  = $doc?->verifiedBy;
                        @endphp

                        <div class="rounded-xl border {{ $cfg['border'] }} overflow-hidden flex flex-col">

                            {{-- Top status stripe --}}
                            <div class="h-1 w-full {{ $cfg['stripe'] }}"></div>

                            {{-- Card header --}}
                            <div class="px-4 pt-3 pb-2 border-b border-slate-100">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $cfg['icon'] }}">
                                            <i class="fas {{ $icon }} text-sm"></i>
                                        </div>
                                        <p class="text-xs font-bold text-slate-800 truncate">{{ translate('docs.' . $type) }}</p>
                                    </div>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold shrink-0 {{ $cfg['pill'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                        {{ $cfg['label'] }}
                                    </span>
                                </div>
                            </div>

                            {{-- Card body --}}
                            <div class="px-4 py-3 flex-1 space-y-3">

                                @if($status === 'not_uploaded')
                                {{-- Empty state --}}
                                <div class="flex items-center justify-center py-4 text-slate-300">
                                    <i class="fas fa-file-upload text-2xl"></i>
                                </div>
                                <p class="text-xs text-slate-400 text-center">{{ translate('docs.admin_not_uploaded_yet') }}</p>

                                @else
                                {{-- File preview --}}
                                @if($fileUrl)
                                    @if($isImage)
                                    {{-- Image inline preview --}}
                                    <a href="{{ $fileUrl }}" target="_blank" rel="noopener" class="block">
                                        <img src="{{ $fileUrl }}"
                                             loading="lazy"
                                             alt="{{ $doc->original_filename }}"
                                             class="w-full max-h-40 object-cover rounded-lg border border-slate-200 hover:opacity-90 transition-opacity">
                                    </a>
                                    @elseif($isPdf)
                                    {{-- PDF — open in tab + download --}}
                                    <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5">
                                        <i class="fas fa-file-pdf text-rose-500 text-lg shrink-0"></i>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs font-medium text-slate-700 truncate">{{ $doc->original_filename }}</p>
                                        </div>
                                        <a href="{{ $fileUrl }}" target="_blank" rel="noopener"
                                           class="shrink-0 inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                            <i class="fas fa-external-link-alt text-xs"></i>
                                            {{ translate('docs.admin_open_file') }}
                                        </a>
                                    </div>
                                    @else
                                    {{-- Unknown file type --}}
                                    <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5">
                                        <i class="fas fa-paperclip text-slate-400 shrink-0"></i>
                                        <span class="text-xs text-slate-600 truncate flex-1">{{ $doc->original_filename }}</span>
                                        <a href="{{ $fileUrl }}" target="_blank" rel="noopener"
                                           class="shrink-0 text-xs text-indigo-600 hover:text-indigo-800">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                    @endif
                                @endif

                                {{-- Metadata row --}}
                                <div class="text-xs text-slate-400 space-y-1">
                                    @if($doc->uploaded_at ?? $doc->updated_at)
                                    <p><i class="fas fa-clock mr-1"></i>{{ translate('docs.admin_uploaded') }}: {{ ($doc->uploaded_at ?? $doc->updated_at)?->diffForHumans() }}</p>
                                    @endif
                                    @if($doc->expires_at)
                                    <p><i class="fas fa-calendar-alt mr-1"></i>{{ translate('docs.expiry_date') }}: {{ $doc->expires_at->format('d.m.Y') }}</p>
                                    @endif
                                    @if($doc->verified_at && $verifier)
                                    <p><i class="fas fa-user-check mr-1"></i>{{ $verifier->name }} &mdash; {{ $doc->verified_at->diffForHumans() }}</p>
                                    @endif
                                </div>

                                {{-- Rejection reason --}}
                                @if($status === 'rejected' && $doc->rejection_reason)
                                <div class="bg-rose-50 border border-rose-200 rounded-lg px-3 py-2">
                                    <p class="text-xs font-semibold text-rose-700 mb-0.5">{{ translate('docs.rejection_reason') }}</p>
                                    <p class="text-xs text-rose-600 leading-snug">{{ $doc->rejection_reason }}</p>
                                </div>
                                @endif

                                {{-- Approve / Reject actions --}}
                                @if($status === 'pending' || $status === 'rejected' || $status === 'verified')
                                <div x-data="{ showReject: false }" class="space-y-2 pt-1">

                                    @if($status !== 'verified')
                                    {{-- Approve button --}}
                                    <form method="POST" action="{{ route('admin.documents.verify', $doc) }}">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit"
                                                class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 min-h-[44px] bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                            <i class="fas fa-check"></i>{{ translate('docs.approve') }}
                                        </button>
                                    </form>
                                    @endif

                                    @if($status !== 'rejected')
                                    {{-- Reject toggle + form --}}
                                    <div>
                                        <button type="button"
                                                @click="showReject = !showReject"
                                                class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 min-h-[44px] border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-semibold rounded-lg transition-colors">
                                            <i class="fas fa-times"></i>{{ translate('docs.reject') }}
                                        </button>

                                        <div x-show="showReject" x-transition x-cloak class="mt-2">
                                            <form method="POST"
                                                  action="{{ route('admin.documents.verify', $doc) }}"
                                                  x-data="{ reason: '' }"
                                                  @submit.prevent="if(reason.trim()){$el.submit()}else{$el.querySelector('textarea').focus()}">
                                                @csrf
                                                <input type="hidden" name="action" value="reject">
                                                <textarea name="rejection_reason"
                                                          x-model="reason"
                                                          rows="2"
                                                          placeholder="{{ translate('docs.rejection_reason') }}"
                                                          class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none resize-none mb-2"></textarea>
                                                <button type="submit"
                                                        class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 min-h-[44px] bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                                    <i class="fas fa-ban"></i>{{ translate('docs.admin_confirm_reject') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endif

                                    @if($status === 'verified')
                                    {{-- Re-reject option for already-verified docs --}}
                                    <div>
                                        <button type="button"
                                                @click="showReject = !showReject"
                                                class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 min-h-[44px] border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-semibold rounded-lg transition-colors">
                                            <i class="fas fa-times"></i>{{ translate('docs.reject') }}
                                        </button>

                                        <div x-show="showReject" x-transition x-cloak class="mt-2">
                                            <form method="POST"
                                                  action="{{ route('admin.documents.verify', $doc) }}"
                                                  x-data="{ reason: '' }"
                                                  @submit.prevent="if(reason.trim()){$el.submit()}else{$el.querySelector('textarea').focus()}">
                                                @csrf
                                                <input type="hidden" name="action" value="reject">
                                                <textarea name="rejection_reason"
                                                          x-model="reason"
                                                          rows="2"
                                                          placeholder="{{ translate('docs.rejection_reason') }}"
                                                          class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none resize-none mb-2"></textarea>
                                                <button type="submit"
                                                        class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 min-h-[44px] bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                                    <i class="fas fa-ban"></i>{{ translate('docs.admin_confirm_reject') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endif

                                </div>
                                @endif

                                @endif {{-- end status !== not_uploaded --}}

                            </div>{{-- /card body --}}
                        </div>{{-- /card --}}

                        @endforeach

                    </div>{{-- /grid --}}
                </div>
            </div>{{-- /accordion body --}}

        </div>{{-- /driver accordion --}}

        @empty

        {{-- Empty state --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-6 py-16 text-center">
            <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-folder-open text-slate-400 text-xl"></i>
            </div>
            <p class="text-slate-700 font-semibold mb-1">{{ translate('docs.admin_empty_title') }}</p>
            <p class="text-sm text-slate-400">{{ translate('docs.admin_empty_desc') }}</p>
            @if($filter !== 'all' || $search !== '')
            <a href="{{ route('admin.documents.index') }}"
               class="mt-4 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                <i class="fas fa-times-circle text-xs"></i>{{ translate('docs.admin_clear_filters') }}
            </a>
            @endif
        </div>

        @endforelse

    </div>{{-- /accordion list --}}

    {{-- Pagination --}}
    @if($drivers->hasPages())
    <div>{{ $drivers->links() }}</div>
    @endif

</div>
@endsection
