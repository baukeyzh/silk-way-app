@extends('layouts.app')

@section('title', translate('docs.admin_title'))

@section('content')
<div class="space-y-6">

    {{-- Page header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">{{ translate('docs.admin_title') }}</h1>
        <p class="mt-1 text-sm text-slate-500">{{ $drivers->total() }} {{ translate('admin.driver') ?? 'водителей' }}</p>
    </div>

    {{-- Legend --}}
    <div class="flex items-center gap-4 flex-wrap text-xs text-slate-500">
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-slate-300"></span> {{ translate('docs.status_not_uploaded') }}</span>
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-400"></span> {{ translate('docs.status_pending') }}</span>
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> {{ translate('docs.status_verified') }}</span>
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-rose-500"></span> {{ translate('docs.status_rejected') }}</span>
    </div>

    {{-- Driver table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">
                            {{ translate('header.users') ?? 'Водитель' }}
                        </th>
                        @foreach($documentTypes as $type)
                        <th class="px-3 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">
                            {{ translate('docs.' . $type) }}
                        </th>
                        @endforeach
                        <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            {{ translate('docs.status_verified') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($drivers as $driver)
                    @php
                        $docsByType = $driver->driverDocuments->keyBy('document_type');
                        $verifiedCount = $driver->driverDocuments->where('status', 'verified')->count();
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-semibold text-sm shrink-0">
                                    {{ strtoupper(substr($driver->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $driver->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $driver->email }}</p>
                                </div>
                            </div>
                        </td>

                        @foreach($documentTypes as $type)
                        @php
                            $doc = $docsByType->get($type);
                            $status = $doc?->status ?? 'not_uploaded';
                            $dotColor = match($status) {
                                'verified'     => 'bg-emerald-500',
                                'pending'      => 'bg-amber-400',
                                'rejected'     => 'bg-rose-500',
                                default        => 'bg-slate-300',
                            };
                        @endphp
                        <td class="px-3 py-4 text-center">
                            @if($doc && $doc->id && $status !== 'not_uploaded')
                            <div x-data="{ open: false }" class="relative inline-block" @click.outside="open = false">
                                <button @click="open = !open"
                                        class="w-4 h-4 rounded-full {{ $dotColor }} mx-auto block hover:scale-125 transition-transform cursor-pointer"
                                        title="{{ translate('docs.' . $type) }} — {{ translate('docs.status_' . $status) }}">
                                </button>

                                {{-- Mini action panel --}}
                                <div x-show="open" x-transition x-cloak
                                     class="absolute z-50 top-6 left-1/2 -translate-x-1/2 w-64 bg-white rounded-xl border border-slate-200 shadow-xl p-4 text-left">
                                    <p class="text-xs font-semibold text-slate-700 mb-1">{{ translate('docs.' . $type) }}</p>
                                    <p class="text-xs text-slate-500 mb-3">
                                        @if($doc->original_filename)
                                        <i class="fas fa-paperclip mr-1"></i>{{ $doc->original_filename }}
                                        @endif
                                        @if($doc->expires_at)
                                        <br><i class="fas fa-calendar mr-1"></i>{{ $doc->expires_at->format('d.m.Y') }}
                                        @endif
                                    </p>

                                    @if($doc->rejection_reason)
                                    <div class="bg-rose-50 border border-rose-100 rounded-lg p-2 mb-3">
                                        <p class="text-xs text-rose-600">{{ $doc->rejection_reason }}</p>
                                    </div>
                                    @endif

                                    @if($status === 'pending')
                                    <form method="POST" action="{{ route('admin.documents.verify', $doc) }}" class="space-y-2">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit"
                                                class="w-full inline-flex items-center justify-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-lg transition-colors">
                                            <i class="fas fa-check mr-1.5"></i>{{ translate('docs.approve') }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.documents.verify', $doc) }}"
                                          x-data="{ reason: '' }"
                                          @submit.prevent="if(reason.trim()){$el.submit()}else{alert('Укажите причину')}"
                                          class="mt-2 space-y-2">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <textarea name="rejection_reason" x-model="reason" rows="2"
                                                  placeholder="{{ translate('docs.rejection_reason') }}"
                                                  class="w-full text-xs border border-slate-200 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none resize-none"></textarea>
                                        <button type="submit"
                                                class="w-full inline-flex items-center justify-center px-3 py-1.5 border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-medium rounded-lg transition-colors">
                                            <i class="fas fa-times mr-1.5"></i>{{ translate('docs.reject') }}
                                        </button>
                                    </form>
                                    @elseif($status === 'verified')
                                    <form method="POST" action="{{ route('admin.documents.verify', $doc) }}"
                                          x-data="{ reason: '' }">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <textarea name="rejection_reason" x-model="reason" rows="2"
                                                  placeholder="{{ translate('docs.rejection_reason') }}"
                                                  class="w-full text-xs border border-slate-200 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-rose-500 outline-none resize-none mb-2"></textarea>
                                        <button type="submit"
                                                class="w-full inline-flex items-center justify-center px-3 py-1.5 border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-medium rounded-lg transition-colors">
                                            <i class="fas fa-times mr-1.5"></i>{{ translate('docs.reject') }}
                                        </button>
                                    </form>
                                    @elseif($status === 'rejected')
                                    <form method="POST" action="{{ route('admin.documents.verify', $doc) }}">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit"
                                                class="w-full inline-flex items-center justify-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-lg transition-colors">
                                            <i class="fas fa-check mr-1.5"></i>{{ translate('docs.approve') }}
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @else
                            <span class="w-4 h-4 rounded-full {{ $dotColor }} mx-auto block"></span>
                            @endif
                        </td>
                        @endforeach

                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $verifiedCount === 6 ? 'bg-emerald-100 text-emerald-700' : ($verifiedCount > 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                                {{ $verifiedCount }} / 6
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($documentTypes) + 2 }}" class="px-5 py-12 text-center text-sm text-slate-400">
                            Водители не найдены
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($drivers->hasPages())
    <div>{{ $drivers->links() }}</div>
    @endif

</div>
@endsection
