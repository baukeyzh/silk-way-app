@extends('layouts.app')

@section('title', 'Заявка на груз')

@section('content')
@php
    $cmrStatus = $application->cmr_status ?? 'not_uploaded';
    $cmrIsImage = false;
    if (!empty($application->cmr_original_filename)) {
        $cmrExt = strtolower(pathinfo($application->cmr_original_filename, PATHINFO_EXTENSION));
        $cmrIsImage = in_array($cmrExt, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
    }
@endphp

<div class="max-w-4xl mx-auto space-y-5" x-data="cmrPanel()">

    {{-- Back --}}
    <button type="button" onclick="history.back()"
            class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>Назад
    </button>

    {{-- Header with status timeline --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">Заявка на груз</p>
                <h1 class="text-2xl font-bold text-slate-900">
                    {{ $application->cargo->localized_from_location }} → {{ $application->cargo->localized_to_location }}
                </h1>
            </div>
            @if($application->isPending())
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-amber-100 text-amber-700">
                    <i class="fas fa-clock mr-1.5"></i>Ожидает рассмотрения
                </span>
            @elseif($application->isApproved())
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-700">
                    <i class="fas fa-check-circle mr-1.5"></i>Подтверждена
                </span>
            @elseif($application->isDelivered())
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-sky-100 text-sky-700">
                    <i class="fas fa-truck mr-1.5"></i>Доставлена
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-rose-100 text-rose-700">
                    <i class="fas fa-times-circle mr-1.5"></i>Отклонена
                </span>
            @endif
        </div>

        {{-- Timeline --}}
        <div class="mt-6 flex items-center gap-0">
            {{-- Step 1: Submitted --}}
            <div class="flex flex-col items-center gap-1 flex-1">
                <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center">
                    <i class="fas fa-paper-plane text-white text-xs"></i>
                </div>
                <p class="text-xs font-medium text-emerald-700 text-center">Подана</p>
                <p class="text-xs text-slate-400 text-center">{{ $application->created_at->format('d.m.Y') }}</p>
            </div>
            <div class="flex-1 h-0.5 {{ !$application->isPending() ? 'bg-emerald-400' : 'bg-slate-200' }} mb-6"></div>
            {{-- Step 2: Reviewed --}}
            <div class="flex flex-col items-center gap-1 flex-1">
                <div class="w-8 h-8 rounded-full {{ !$application->isPending() ? 'bg-emerald-500' : 'bg-slate-200' }} flex items-center justify-center">
                    <i class="fas fa-user-check {{ !$application->isPending() ? 'text-white' : 'text-slate-400' }} text-xs"></i>
                </div>
                <p class="text-xs font-medium {{ !$application->isPending() ? 'text-emerald-700' : 'text-slate-400' }} text-center">Рассмотрена</p>
                @if($application->approved_at)
                <p class="text-xs text-slate-400 text-center">{{ $application->approved_at->format('d.m.Y') }}</p>
                @endif
            </div>
            <div class="flex-1 h-0.5 {{ $application->isDelivered() ? 'bg-sky-400' : 'bg-slate-200' }} mb-6"></div>
            {{-- Step 3: Result --}}
            <div class="flex flex-col items-center gap-1 flex-1">
                <div class="w-8 h-8 rounded-full {{ $application->isDelivered() ? 'bg-sky-500' : ($application->isApproved() ? 'bg-emerald-500' : ($application->isRejected() ? 'bg-rose-400' : 'bg-slate-200')) }} flex items-center justify-center">
                    @if($application->isDelivered())
                        <i class="fas fa-truck text-white text-xs"></i>
                    @elseif($application->isApproved())
                        <i class="fas fa-check-double text-white text-xs"></i>
                    @elseif($application->isRejected())
                        <i class="fas fa-times text-white text-xs"></i>
                    @else
                        <i class="fas fa-hourglass-half text-slate-400 text-xs"></i>
                    @endif
                </div>
                <p class="text-xs font-medium {{ $application->isDelivered() ? 'text-sky-700' : ($application->isApproved() ? 'text-emerald-700' : ($application->isRejected() ? 'text-rose-700' : 'text-slate-400')) }} text-center">
                    {{ $application->isDelivered() ? 'Доставлена' : ($application->isApproved() ? 'Одобрена' : ($application->isRejected() ? 'Отклонена' : 'Ожидание')) }}
                </p>
            </div>
        </div>
    </div>

    {{-- Two-column info --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Cargo info --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-sm font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <div class="w-7 h-7 bg-indigo-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-indigo-600 text-xs"></i>
                </div>
                Информация о грузе
            </h2>
            <dl class="space-y-3">
                <div class="flex justify-between text-sm">
                    <dt class="text-slate-500">Маршрут</dt>
                    <dd class="font-medium text-slate-800">{{ $application->cargo->localized_from_location }} → {{ $application->cargo->localized_to_location }}</dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-slate-500">Тип груза</dt>
                    <dd class="font-medium text-slate-800">{{ $application->cargo->cargo_type }}</dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-slate-500">Объём</dt>
                    <dd class="font-medium text-slate-800">{{ $application->cargo->volume }} м³</dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-slate-500">Вес</dt>
                    <dd class="font-medium text-slate-800">{{ $application->cargo->weight }} кг</dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-slate-500">Дата готовности</dt>
                    <dd class="font-medium text-slate-800">{{ $application->cargo->ready_date->format('d.m.Y H:i') }}</dd>
                </div>
                <div class="flex justify-between text-sm items-center">
                    <dt class="text-slate-500">Статус груза</dt>
                    <dd>
                        @if($application->cargo->status === 'available')
                            <span class="text-emerald-600 font-medium text-xs">Доступен</span>
                        @elseif($application->cargo->status === 'in_progress' || $application->cargo->status === 'picked_up')
                            <span class="text-amber-600 font-medium text-xs">В работе</span>
                        @else
                            <span class="text-slate-500 font-medium text-xs">Доставлен</span>
                        @endif
                    </dd>
                </div>
                @if($application->cargo->comment)
                <div class="pt-3 border-t border-slate-100">
                    <dt class="text-slate-500 text-xs mb-1">Комментарий</dt>
                    <dd class="text-sm text-slate-700">{{ $application->cargo->comment }}</dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Application info --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-sm font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <div class="w-7 h-7 bg-indigo-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-indigo-600 text-xs"></i>
                </div>
                Информация о заявке
            </h2>
            <dl class="space-y-3">
                <div class="flex justify-between text-sm">
                    <dt class="text-slate-500">Водитель</dt>
                    <dd class="font-medium text-slate-800">{{ $application->driver->name }}</dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-slate-500">Дата подачи</dt>
                    <dd class="font-medium text-slate-800">{{ $application->created_at->format('d.m.Y H:i') }}</dd>
                </div>
                @if($application->approved_at)
                <div class="flex justify-between text-sm">
                    <dt class="text-slate-500">Дата рассмотрения</dt>
                    <dd class="font-medium text-slate-800">{{ $application->approved_at->format('d.m.Y H:i') }}</dd>
                </div>
                @endif
                @if($application->driver_notes)
                <div class="pt-3 border-t border-slate-100">
                    <dt class="text-slate-500 text-xs mb-1">Заметки водителя</dt>
                    <dd class="text-sm text-slate-700">{{ $application->driver_notes }}</dd>
                </div>
                @endif
                @if($application->warehouse_notes)
                <div class="pt-3 border-t border-slate-100">
                    <dt class="text-slate-500 text-xs mb-1">Заметки склада</dt>
                    <dd class="text-sm text-slate-700">{{ $application->warehouse_notes }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    {{-- Contact info (visible once the application is approved; remains after delivery) --}}
    @if(($application->isApproved() || $application->isDelivered()) && ($application->pickup_contact || $application->pickup_address || $application->delivery_contact || $application->delivery_address))
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <h2 class="text-sm font-semibold text-slate-900 mb-4">
            <i class="fas fa-phone mr-2 text-indigo-500"></i>Контактная информация
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @if($application->pickup_contact || $application->pickup_address)
            <div>
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Информация для забора</h3>
                <dl class="space-y-2">
                    @if($application->pickup_contact)
                    <div class="flex justify-between text-sm">
                        <dt class="text-slate-500">Контакт</dt>
                        <dd class="font-medium text-slate-800">{{ $application->pickup_contact }}</dd>
                    </div>
                    @endif
                    @if($application->pickup_address)
                    <div class="flex justify-between text-sm">
                        <dt class="text-slate-500">Адрес</dt>
                        <dd class="font-medium text-slate-800">{{ $application->pickup_address }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            @endif
            @if($application->delivery_contact || $application->delivery_address)
            <div>
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Информация для доставки</h3>
                <dl class="space-y-2">
                    @if($application->delivery_contact)
                    <div class="flex justify-between text-sm">
                        <dt class="text-slate-500">Контакт</dt>
                        <dd class="font-medium text-slate-800">{{ $application->delivery_contact }}</dd>
                    </div>
                    @endif
                    @if($application->delivery_address)
                    <div class="flex justify-between text-sm">
                        <dt class="text-slate-500">Адрес</dt>
                        <dd class="font-medium text-slate-800">{{ $application->delivery_address }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ================================================================
         CMR (Consignment Note) Section
         Shown to the driver when the application is approved and cargo
         is not yet confirmed delivered, OR to WE/admin when cmr_status
         is not 'not_uploaded'.
    ================================================================ --}}

    {{-- ---- DRIVER CMR PANEL ---- --}}
    @if(auth()->user()->isDriver() && $application->isApproved() && $application->cargo->status !== 'delivered')
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

        {{-- Coloured top stripe based on CMR state --}}
        <div class="h-1 w-full
            @if($cmrStatus === 'pending_review') bg-amber-400
            @elseif($cmrStatus === 'confirmed') bg-emerald-500
            @elseif($cmrStatus === 'rejected') bg-rose-500
            @else bg-slate-200
            @endif"></div>

        <div class="p-6">
            <h2 class="text-sm font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <div class="w-7 h-7 bg-indigo-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-contract text-indigo-600 text-xs"></i>
                </div>
                {{ translate('cmr.label_section_title') }}
            </h2>

            {{-- STATE: not_uploaded — show upload form --}}
            @if($cmrStatus === 'not_uploaded')
            <div class="space-y-4">
                <p class="text-sm text-slate-500">{{ translate('cmr.label_helper_text') }}</p>

                <form action="{{ route('applications.cmr.upload', $application) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="space-y-4">
                    @csrf
                    <label class="relative flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl px-4 py-8 cursor-pointer hover:border-indigo-300 hover:bg-slate-50/60 transition-colors"
                           x-data="{ fileName: '' }"
                           @dragover.prevent
                           @drop.prevent="
                               const f = $event.dataTransfer.files[0];
                               if (f) { fileName = f.name; $el.querySelector('input[type=file]').files = $event.dataTransfer.files; }
                           ">
                        <input type="file"
                               name="cmr_file"
                               accept="image/*,.pdf"
                               class="sr-only"
                               @change="fileName = $event.target.files[0]?.name ?? ''"
                               required>
                        <i class="fas text-3xl mb-3"
                           :class="fileName ? 'fa-check-circle text-indigo-500' : 'fa-cloud-upload-alt text-slate-300'"></i>
                        <p class="text-sm font-medium text-slate-600 text-center" x-show="!fileName">
                            {{ translate('cmr.label_file_types') }}
                        </p>
                        <p class="text-xs text-slate-400 mt-1 text-center" x-show="!fileName">
                            {{ translate('cmr.label_max_size') }}
                        </p>
                        <p class="text-sm font-medium text-indigo-700 truncate max-w-full"
                           x-show="fileName"
                           x-cloak
                           x-text="fileName"></p>
                    </label>

                    <button type="submit"
                            class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-2.5 min-h-[44px] bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        <i class="fas fa-upload mr-2"></i>{{ translate('cmr.action_upload') }}
                    </button>
                </form>
            </div>
            @endif

            {{-- STATE: pending_review — amber banner + preview + delete action --}}
            @if($cmrStatus === 'pending_review')
            <div class="space-y-4">
                <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3.5">
                    <i class="fas fa-clock text-amber-500 text-sm mt-0.5 shrink-0"></i>
                    <div>
                        <p class="text-sm font-semibold text-amber-800">{{ translate('cmr.banner_pending_title') }}</p>
                        @if($application->cmr_uploaded_at)
                        <p class="text-xs text-amber-600 mt-0.5">{{ $application->cmr_uploaded_at->diffForHumans() }}</p>
                        @endif
                    </div>
                </div>

                {{-- File preview --}}
                @if($cmrIsImage)
                <a href="{{ route('applications.cmr.file', $application) }}" target="_blank" rel="noopener"
                   class="block rounded-lg overflow-hidden bg-slate-50 border border-slate-200 hover:border-indigo-300 transition-colors">
                    <img src="{{ route('applications.cmr.file', $application) }}"
                         alt="CMR"
                         loading="lazy"
                         class="w-full h-40 sm:h-48 object-cover">
                </a>
                @else
                <a href="{{ route('applications.cmr.file', $application) }}" target="_blank" rel="noopener"
                   class="flex items-center gap-3 px-3 py-2.5 min-h-[44px] rounded-lg bg-rose-50 border border-rose-100 hover:bg-rose-100 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shrink-0">
                        <i class="fas fa-file-pdf text-rose-500"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-700 truncate">{{ $application->cmr_original_filename }}</p>
                        <p class="text-xs text-slate-500">{{ translate('cmr.action_open') }}</p>
                    </div>
                    <i class="fas fa-external-link-alt text-slate-400 text-xs shrink-0"></i>
                </a>
                @endif

                {{-- Delete & re-upload --}}
                <form action="{{ route('applications.cmr.destroy', $application) }}"
                      method="POST"
                      onsubmit="return confirm('{{ translate('cmr.action_delete_confirm') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2.5 min-h-[44px] border border-rose-300 text-rose-600 hover:bg-rose-50 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-trash-alt mr-2"></i>{{ translate('cmr.action_delete_reupload') }}
                    </button>
                </form>
            </div>
            @endif

            {{-- STATE: confirmed — emerald banner + preview, locked --}}
            @if($cmrStatus === 'confirmed')
            <div class="space-y-4">
                <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3.5">
                    <i class="fas fa-check-circle text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                    <div>
                        <p class="text-sm font-semibold text-emerald-800">{{ translate('cmr.banner_confirmed_title') }}</p>
                        @if($application->confirmedBy ?? null)
                        <p class="text-xs text-emerald-700 mt-0.5">
                            {{ translate('cmr.label_confirmed_by') }}: {{ $application->confirmedBy->name }}
                            @if($application->cmr_confirmed_at)
                                &middot; {{ $application->cmr_confirmed_at->diffForHumans() }}
                            @endif
                        </p>
                        @endif
                    </div>
                </div>

                {{-- File preview --}}
                @if($cmrIsImage)
                <a href="{{ route('applications.cmr.file', $application) }}" target="_blank" rel="noopener"
                   class="block rounded-lg overflow-hidden bg-slate-50 border border-slate-200 hover:border-indigo-300 transition-colors">
                    <img src="{{ route('applications.cmr.file', $application) }}"
                         alt="CMR"
                         loading="lazy"
                         class="w-full h-40 sm:h-48 object-cover">
                </a>
                @else
                <a href="{{ route('applications.cmr.file', $application) }}" target="_blank" rel="noopener"
                   class="flex items-center gap-3 px-3 py-2.5 min-h-[44px] rounded-lg bg-rose-50 border border-rose-100 hover:bg-rose-100 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shrink-0">
                        <i class="fas fa-file-pdf text-rose-500"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-700 truncate">{{ $application->cmr_original_filename }}</p>
                        <p class="text-xs text-slate-500">{{ translate('cmr.action_open') }}</p>
                    </div>
                    <i class="fas fa-external-link-alt text-slate-400 text-xs shrink-0"></i>
                </a>
                @endif

                {{-- Lock notice --}}
                <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-50 rounded-lg px-3 py-2 border border-slate-200">
                    <i class="fas fa-lock text-slate-400 shrink-0"></i>
                    <span>{{ translate('cmr.label_locked') }}</span>
                </div>
            </div>
            @endif

            {{-- STATE: rejected — rose banner + preview + re-upload form --}}
            @if($cmrStatus === 'rejected')
            <div class="space-y-4">
                <div class="flex items-start gap-3 bg-rose-50 border border-rose-200 rounded-xl px-4 py-3.5">
                    <i class="fas fa-times-circle text-rose-500 text-sm mt-0.5 shrink-0"></i>
                    <div>
                        <p class="text-sm font-semibold text-rose-800">{{ translate('cmr.banner_rejected_title') }}</p>
                        @if($application->cmr_rejection_reason)
                        <p class="text-sm text-rose-700 mt-1">{{ $application->cmr_rejection_reason }}</p>
                        @endif
                        @if($application->cmr_rejected_at)
                        <p class="text-xs text-rose-500 mt-0.5">{{ $application->cmr_rejected_at->diffForHumans() }}</p>
                        @endif
                    </div>
                </div>

                {{-- Old file preview (for reference) --}}
                @if($cmrIsImage)
                <div>
                    <p class="text-xs font-medium text-slate-500 mb-2">{{ translate('cmr.label_previous_file') }}</p>
                    <a href="{{ route('applications.cmr.file', $application) }}" target="_blank" rel="noopener"
                       class="block rounded-lg overflow-hidden bg-slate-50 border border-slate-200 hover:border-indigo-300 transition-colors opacity-60">
                        <img src="{{ route('applications.cmr.file', $application) }}"
                             alt="CMR"
                             loading="lazy"
                             class="w-full h-40 sm:h-48 object-cover">
                    </a>
                </div>
                @elseif($application->cmr_original_filename)
                <div>
                    <p class="text-xs font-medium text-slate-500 mb-2">{{ translate('cmr.label_previous_file') }}</p>
                    <a href="{{ route('applications.cmr.file', $application) }}" target="_blank" rel="noopener"
                       class="flex items-center gap-3 px-3 py-2.5 min-h-[44px] rounded-lg bg-rose-50 border border-rose-100 hover:bg-rose-100 transition-colors opacity-60">
                        <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shrink-0">
                            <i class="fas fa-file-pdf text-rose-500"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-700 truncate">{{ $application->cmr_original_filename }}</p>
                        </div>
                        <i class="fas fa-external-link-alt text-slate-400 text-xs shrink-0"></i>
                    </a>
                </div>
                @endif

                {{-- Re-upload form --}}
                <p class="text-sm font-medium text-slate-700">{{ translate('cmr.label_reupload_prompt') }}</p>
                <form action="{{ route('applications.cmr.upload', $application) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="space-y-3">
                    @csrf
                    <label class="relative flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl px-4 py-6 cursor-pointer hover:border-indigo-300 hover:bg-slate-50/60 transition-colors"
                           x-data="{ fileName: '' }"
                           @dragover.prevent
                           @drop.prevent="
                               const f = $event.dataTransfer.files[0];
                               if (f) { fileName = f.name; $el.querySelector('input[type=file]').files = $event.dataTransfer.files; }
                           ">
                        <input type="file"
                               name="cmr_file"
                               accept="image/*,.pdf"
                               class="sr-only"
                               @change="fileName = $event.target.files[0]?.name ?? ''"
                               required>
                        <i class="fas text-2xl mb-2"
                           :class="fileName ? 'fa-check-circle text-indigo-500' : 'fa-cloud-upload-alt text-slate-300'"></i>
                        <p class="text-sm text-slate-500 text-center" x-show="!fileName">{{ translate('cmr.label_file_types') }}</p>
                        <p class="text-xs text-slate-400 text-center" x-show="!fileName">{{ translate('cmr.label_max_size') }}</p>
                        <p class="text-sm font-medium text-indigo-700 truncate max-w-full"
                           x-show="fileName"
                           x-cloak
                           x-text="fileName"></p>
                    </label>
                    <button type="submit"
                            class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-2.5 min-h-[44px] bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        <i class="fas fa-upload mr-2"></i>{{ translate('cmr.action_upload') }}
                    </button>
                </form>
            </div>
            @endif

        </div>
    </div>
    @endif
    {{-- /DRIVER CMR PANEL --}}

    {{-- ---- REVIEWER CMR PANEL (WE / Admin) ---- --}}
    @if((auth()->user()->isWarehouseEmployee() || auth()->user()->isAdmin()) && $cmrStatus !== 'not_uploaded')
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

        {{-- Top stripe --}}
        <div class="h-1 w-full
            @if($cmrStatus === 'pending_review') bg-amber-400
            @elseif($cmrStatus === 'confirmed') bg-emerald-500
            @elseif($cmrStatus === 'rejected') bg-rose-500
            @else bg-slate-200
            @endif"></div>

        <div class="p-6">
            <h2 class="text-sm font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <div class="w-7 h-7 bg-indigo-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-contract text-indigo-600 text-xs"></i>
                </div>
                {{ translate('cmr.label_review_section_title') }}
            </h2>

            {{-- Status banner --}}
            @if($cmrStatus === 'pending_review')
            <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3.5 mb-4">
                <i class="fas fa-clock text-amber-500 text-sm mt-0.5 shrink-0"></i>
                <div>
                    <p class="text-sm font-semibold text-amber-800">{{ translate('cmr.banner_pending_reviewer_title') }}</p>
                    @if($application->cmr_uploaded_at)
                    <p class="text-xs text-amber-600 mt-0.5">{{ $application->cmr_uploaded_at->diffForHumans() }}</p>
                    @endif
                    @if($application->cmr_original_filename)
                    <p class="text-xs text-amber-700 mt-0.5">{{ $application->cmr_original_filename }}</p>
                    @endif
                </div>
            </div>
            @elseif($cmrStatus === 'confirmed')
            <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3.5 mb-4">
                <i class="fas fa-check-circle text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                <div>
                    <p class="text-sm font-semibold text-emerald-800">{{ translate('cmr.banner_confirmed_title') }}</p>
                    @if($application->confirmedBy ?? null)
                    <p class="text-xs text-emerald-700 mt-0.5">
                        {{ translate('cmr.label_confirmed_by') }}: {{ $application->confirmedBy->name }}
                        @if($application->cmr_confirmed_at)
                            &middot; {{ $application->cmr_confirmed_at->diffForHumans() }}
                        @endif
                    </p>
                    @endif
                </div>
            </div>
            @elseif($cmrStatus === 'rejected')
            <div class="flex items-start gap-3 bg-rose-50 border border-rose-200 rounded-xl px-4 py-3.5 mb-4">
                <i class="fas fa-times-circle text-rose-500 text-sm mt-0.5 shrink-0"></i>
                <div>
                    <p class="text-sm font-semibold text-rose-800">{{ translate('cmr.banner_rejected_title') }}</p>
                    @if($application->cmr_rejection_reason)
                    <p class="text-sm text-rose-700 mt-1">{{ $application->cmr_rejection_reason }}</p>
                    @endif
                    @if($application->cmr_rejected_at)
                    <p class="text-xs text-rose-500 mt-0.5">{{ $application->cmr_rejected_at->diffForHumans() }}</p>
                    @endif
                </div>
            </div>
            @endif

            {{-- File preview --}}
            @if($cmrIsImage)
            <a href="{{ route('applications.cmr.file', $application) }}" target="_blank" rel="noopener"
               class="block rounded-lg overflow-hidden bg-slate-50 border border-slate-200 hover:border-indigo-300 transition-colors mb-4">
                <img src="{{ route('applications.cmr.file', $application) }}"
                     alt="CMR"
                     loading="lazy"
                     class="w-full h-40 sm:h-48 object-cover">
            </a>
            @elseif($application->cmr_original_filename)
            <a href="{{ route('applications.cmr.file', $application) }}" target="_blank" rel="noopener"
               class="flex items-center gap-3 px-3 py-2.5 min-h-[44px] rounded-lg bg-rose-50 border border-rose-100 hover:bg-rose-100 transition-colors mb-4">
                <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shrink-0">
                    <i class="fas fa-file-pdf text-rose-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-700 truncate">{{ $application->cmr_original_filename }}</p>
                    <p class="text-xs text-slate-500">{{ translate('cmr.action_open') }}</p>
                </div>
                <i class="fas fa-external-link-alt text-slate-400 text-xs shrink-0"></i>
            </a>
            @endif

            {{-- Action buttons — only when pending_review --}}
            @if($cmrStatus === 'pending_review')
            <div class="space-y-3" x-data="{ showReject: false }">

                {{-- Confirm action --}}
                <form action="{{ route('applications.cmr.confirm', $application) }}"
                      method="POST"
                      x-ref="confirmForm"
                      @submit.prevent="
                          if (confirm('{{ addslashes(translate('cmr.action_confirm_dialog')) }}')) {
                              $refs.confirmForm.submit();
                          }
                      ">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 min-h-[44px] bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        <i class="fas fa-check-circle mr-2"></i>{{ translate('cmr.action_confirm') }}
                    </button>
                </form>

                {{-- Toggle reject form --}}
                <div>
                    <button type="button"
                            @click="showReject = !showReject"
                            class="inline-flex items-center px-5 py-2.5 min-h-[44px] border border-rose-300 text-rose-600 hover:bg-rose-50 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-times-circle mr-2"></i>{{ translate('cmr.action_reject') }}
                    </button>

                    <div x-show="showReject"
                         x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mt-3">
                        <form action="{{ route('applications.cmr.reject', $application) }}"
                              method="POST"
                              class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                    {{ translate('cmr.label_rejection_reason') }}
                                    <span class="text-rose-500 ml-0.5">*</span>
                                </label>
                                <textarea name="rejection_reason"
                                          rows="3"
                                          required
                                          placeholder="{{ translate('cmr.label_rejection_reason_placeholder') }}"
                                          class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-rose-400 focus:border-transparent outline-none resize-none"></textarea>
                            </div>
                            <button type="submit"
                                    class="inline-flex items-center px-5 py-2.5 min-h-[44px] bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                <i class="fas fa-times mr-2"></i>{{ translate('cmr.action_reject_submit') }}
                            </button>
                        </form>
                    </div>
                </div>

            </div>
            @endif
            {{-- /action buttons --}}

        </div>
    </div>
    @endif
    {{-- /REVIEWER CMR PANEL --}}

    {{-- Actions --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex flex-wrap gap-3">
            {{--
                Driver "Deliver" action is now replaced by the CMR flow above.
                The old mark-delivered route is kept for backward compatibility
                but the UI entry point for this application is the CMR panel.
                We show the legacy button ONLY if cmr_status is confirmed
                (CMR confirmed → cargo can be marked delivered by the reviewer,
                so from the driver's perspective it's already done), or if the
                application is in a state where CMR is not relevant.
                TODO for Laravel agent: markAsDelivered should be triggered
                automatically when CMR is confirmed, not by driver action.
            --}}
            @if(auth()->user()->isWarehouseEmployee() && $application->isPending())
            <form action="{{ route('applications.approve', $application) }}" method="POST">
                @csrf
                <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    <i class="fas fa-check mr-2"></i>Подтвердить заявку
                </button>
            </form>
            <form action="{{ route('applications.reject', $application) }}" method="POST">
                @csrf
                <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>Отклонить заявку
                </button>
            </form>
            @endif

            <button type="button" onclick="history.back()"
                    class="inline-flex items-center px-5 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Назад
            </button>
        </div>
    </div>
</div>

<script>
function cmrPanel() {
    return {};
}
</script>
@endsection
