@extends('layouts.app')

@section('title', translate('docs.title'))

@section('content')
@php
    $total            = $documents->count();
    $requiredTotal    = $documents->filter(fn($d) => !$d->isOptional())->count();
    $reqVerified      = $documents->filter(fn($d) => !$d->isOptional() && $d->isVerified())->count();
    $verifiedCount    = $documents->filter(fn($d) => $d->isVerified())->count();
    $pendingCount     = $documents->filter(fn($d) => $d->isPending())->count();
    $rejectedCount    = $documents->filter(fn($d) => $d->isRejected())->count();
    $notUploadedCount = $documents->filter(fn($d) => $d->status === 'not_uploaded')->count();
    $progressPct      = $requiredTotal > 0 ? round(($reqVerified / $requiredTotal) * 100) : 0;
    $allDone          = $reqVerified === $requiredTotal;

    $icons = [
        'driver_license'   => 'fa-id-card',
        'vehicle_passport' => 'fa-file-alt',
        'trailer_passport' => 'fa-trailer',
        'category_cert'    => 'fa-certificate',
        'green_card'       => 'fa-globe',
        'insurance'        => 'fa-shield-alt',
    ];

    $stripeColors = [
        'pending'      => 'bg-amber-400',
        'verified'     => 'bg-emerald-500',
        'rejected'     => 'bg-rose-500',
        'not_uploaded' => 'bg-slate-200',
    ];
    $borderColors = [
        'pending'      => 'border-amber-200',
        'verified'     => 'border-emerald-200',
        'rejected'     => 'border-rose-200',
        'not_uploaded' => 'border-slate-200',
    ];
    $iconBgColors = [
        'pending'      => 'bg-amber-100',
        'verified'     => 'bg-emerald-100',
        'rejected'     => 'bg-rose-100',
        'not_uploaded' => 'bg-slate-100',
    ];
    $iconTextColors = [
        'pending'      => 'text-amber-600',
        'verified'     => 'text-emerald-600',
        'rejected'     => 'text-rose-600',
        'not_uploaded' => 'text-slate-400',
    ];
    $badgeColors = [
        'pending'      => 'bg-amber-100 text-amber-700',
        'verified'     => 'bg-emerald-100 text-emerald-700',
        'rejected'     => 'bg-rose-100 text-rose-700',
        'not_uploaded' => 'bg-slate-100 text-slate-600',
    ];
    $dotColors = [
        'pending'      => 'bg-amber-400',
        'verified'     => 'bg-emerald-500',
        'rejected'     => 'bg-rose-500',
        'not_uploaded' => 'bg-slate-400',
    ];
    $statusLabels = [
        'pending'      => translate('docs.status_pending'),
        'verified'     => translate('docs.status_verified'),
        'rejected'     => translate('docs.status_rejected'),
        'not_uploaded' => translate('docs.status_not_uploaded'),
    ];

    // Per-slot errors from previous batch attempt (docId => message).
    $batchErrors   = session('flash.errors', []);
    // Names of slots successfully uploaded in the last batch.
    $batchUploaded = session('flash.uploaded', []);
@endphp

{{--
    Architecture note:
    The batch upload form (id="batch-upload-form") is declared AFTER the card
    grid as a standalone element. File inputs and expiry date inputs inside the
    cards bind to it via the HTML5 `form="batch-upload-form"` attribute.
    This is the standard pattern for associating inputs with a non-ancestor form
    and is supported by all modern browsers.

    Delete forms live inside the cards as siblings of the upload controls —
    NOT inside the batch form — so there is zero form nesting and the markup
    is fully valid HTML5.

    The Alpine `batchForm()` scope sits on the outer page wrapper div so it can
    listen for `file-selected` events bubbled up from each card's docSlot().
--}}

<div class="space-y-6" x-data="batchForm()" @file-selected.window="fileSelected($event.detail.hasFile)">

    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ translate('docs.title') }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ translate('docs.description') }}</p>
        </div>

        {{-- Completion pill --}}
        <span class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold border shrink-0 self-start
              {{ $allDone ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
            <i class="fas {{ $allDone ? 'fa-check-circle text-emerald-500' : 'fa-clock text-amber-500' }} text-sm"></i>
            {{ $reqVerified }} из {{ $requiredTotal }} подтверждено
        </span>
    </div>

    {{-- Progress bar card --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-5 py-4">
        <div class="flex items-center justify-between mb-2.5">
            <p class="text-sm font-medium text-slate-700">Готовность к работе</p>
            <span class="text-sm font-bold {{ $allDone ? 'text-emerald-600' : 'text-slate-700' }}">{{ $progressPct }}%</span>
        </div>
        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all duration-500 {{ $allDone ? 'bg-emerald-500' : 'bg-indigo-500' }}"
                 style="width: {{ $progressPct }}%"></div>
        </div>
        <div class="mt-3 flex items-center gap-5 text-xs text-slate-500 flex-wrap">
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                {{ $verifiedCount }} подтверждено
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
                {{ $pendingCount }} на проверке
            </span>
            @if($rejectedCount > 0)
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-rose-400 inline-block"></span>
                {{ $rejectedCount }} отклонено
            </span>
            @endif
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-slate-300 inline-block"></span>
                {{ $notUploadedCount }} не загружено
            </span>
        </div>
    </div>

    {{-- Document grid — server-rendered, one card per slot --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @foreach($documents as $doc)
        @php
            $status    = $doc->status;
            $icon      = $icons[$doc->document_type] ?? 'fa-file-alt';
            $canUpload = in_array($status, ['not_uploaded', 'rejected'], true);
            $canDelete = in_array($status, ['pending', 'rejected'], true);
            $slotError = $batchErrors[$doc->id] ?? null;
        @endphp

        {{--
            Each card has its own x-data="docSlot()" scope for the per-card
            filename preview. Alpine isolates state per component instance, so
            selecting a file in one card never resets another card's preview.
        --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow border {{ $borderColors[$status] ?? 'border-slate-200' }}"
             x-data="docSlot()">

            {{-- Coloured top stripe --}}
            <div class="h-1 w-full shrink-0 {{ $stripeColors[$status] ?? 'bg-slate-200' }}"></div>

            {{-- Card header --}}
            <div class="px-5 pt-4 pb-3 border-b border-slate-100">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $iconBgColors[$status] ?? 'bg-slate-100' }}">
                            <i class="fas {{ $icon }} text-lg {{ $iconTextColors[$status] ?? 'text-slate-400' }}"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-900 truncate">{{ $doc->document_type_label }}</p>
                            @if($doc->isOptional())
                            <span class="inline-flex items-center mt-0.5 px-1.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-600">
                                {{ translate('docs.optional') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- Status badge --}}
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 {{ $badgeColors[$status] ?? 'bg-slate-100 text-slate-600' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dotColors[$status] ?? 'bg-slate-400' }}"></span>
                        {{ $statusLabels[$status] ?? $status }}
                    </span>
                </div>
            </div>

            {{-- Card body --}}
            <div class="px-5 py-4 flex-1 space-y-3">

                {{-- Per-slot error from previous batch attempt --}}
                @if($slotError)
                <div class="flex items-start gap-2.5 bg-rose-50 border border-rose-200 rounded-lg px-3 py-2.5">
                    <i class="fas fa-exclamation-circle text-rose-500 text-sm mt-0.5 shrink-0"></i>
                    <p class="text-xs text-rose-600 leading-snug">{{ $slotError }}</p>
                </div>
                @endif

                {{-- Rejection reason banner --}}
                @if($status === 'rejected' && $doc->rejection_reason)
                <div class="flex items-start gap-2.5 bg-rose-50 border border-rose-200 rounded-lg px-3 py-2.5">
                    <i class="fas fa-exclamation-circle text-rose-500 text-sm mt-0.5 shrink-0"></i>
                    <div>
                        <p class="text-xs font-semibold text-rose-700 mb-0.5">{{ translate('docs.rejection_reason') }}</p>
                        <p class="text-xs text-rose-600 leading-snug">{{ $doc->rejection_reason }}</p>
                    </div>
                </div>
                @endif

                {{-- Uploaded filename --}}
                @if($doc->original_filename)
                <div class="flex items-center gap-2 text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-2">
                    <i class="fas fa-paperclip text-slate-400 shrink-0"></i>
                    <span class="truncate text-slate-600 font-medium">{{ $doc->original_filename }}</span>
                </div>
                @endif

                {{-- Pending info --}}
                @if($status === 'pending')
                <div class="flex items-center gap-2 text-xs text-amber-700 bg-amber-50 rounded-lg px-3 py-2 border border-amber-100">
                    <i class="fas fa-clock text-amber-500 shrink-0"></i>
                    <span>Документ отправлен и ожидает проверки администратора</span>
                </div>
                @endif

                {{-- Verified info --}}
                @if($status === 'verified')
                <div class="flex items-center gap-2 text-xs text-emerald-700 bg-emerald-50 rounded-lg px-3 py-2 border border-emerald-100">
                    <i class="fas fa-check-circle text-emerald-500 shrink-0"></i>
                    <span>{{ translate('docs.status_verified') }}</span>
                    @if($doc->expires_at)
                    <span class="ml-auto text-slate-400">{{ $doc->expires_at->format('d.m.Y') }}</span>
                    @endif
                </div>

                {{-- Verified — lock notice, driver cannot replace --}}
                <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-50 rounded-lg px-3 py-2 border border-slate-200">
                    <i class="fas fa-lock text-slate-400 shrink-0"></i>
                    <span>Документ подтверждён — замена не требуется</span>
                </div>
                @endif

                {{-- Expiry shown for pending docs --}}
                @if($status === 'pending' && $doc->expires_at)
                <div>
                    <p class="text-xs font-medium text-slate-500 mb-1">
                        <i class="fas fa-calendar-alt mr-1"></i>{{ translate('docs.expiry_date') }}
                    </p>
                    <p class="text-sm text-slate-600">{{ $doc->expires_at->format('d.m.Y') }}</p>
                </div>
                @endif

                {{--
                    Upload controls — rendered only for not_uploaded and rejected slots.

                    Both inputs use form="batch-upload-form" to associate with the
                    batch form declared OUTSIDE the card grid (after the loop).
                    This is valid HTML5: inputs may live anywhere in the DOM and still
                    belong to a form identified by its id. No form nesting occurs.
                --}}
                @if($canUpload)
                <div class="space-y-2.5">

                    {{-- Expiry date input — bound to batch form via form= attr --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">
                            <i class="fas fa-calendar-alt mr-1"></i>{{ translate('docs.expiry_date') }}
                        </label>
                        <input type="date"
                               name="expires_at[{{ $doc->id }}]"
                               form="batch-upload-form"
                               value="{{ $doc->expires_at?->format('Y-m-d') }}"
                               class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                    </div>

                    {{--
                        File drop-zone.
                        name="files[{id}]" — keyed by document ID so batchUpload()
                        can authorise and look up the correct DriverDocument record.
                        @change fires handleSelect() for the per-card preview, then
                        dispatches the custom "file-selected" event so the outer
                        batchForm() scope can increment/decrement its counter.
                    --}}
                    <label class="relative flex flex-col items-center justify-center border-2 border-dashed rounded-xl px-4 py-5 cursor-pointer transition-colors"
                           :class="fileName ? 'border-indigo-300 bg-indigo-50/50' : 'border-slate-200 hover:border-indigo-300 hover:bg-slate-50/60'"
                           @dragover.prevent
                           @drop.prevent="handleDrop($event)">
                        <input type="file"
                               name="files[{{ $doc->id }}]"
                               form="batch-upload-form"
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="sr-only"
                               @change="handleSelect($event); $dispatch('file-selected', { hasFile: !!$event.target.files[0] })">
                        <i class="fas text-2xl mb-2"
                           :class="fileName ? 'fa-check-circle text-indigo-500' : 'fa-cloud-upload-alt text-slate-300'"></i>
                        <p class="text-xs text-slate-500 text-center" x-show="!fileName">PDF, JPG, PNG — макс. 5 MB</p>
                        <p class="text-xs text-indigo-700 font-medium truncate max-w-full"
                           x-show="fileName"
                           x-cloak
                           x-text="fileName"></p>

                        <noscript>
                            <p class="text-xs text-slate-500 text-center">PDF, JPG, PNG — макс. 5 MB</p>
                        </noscript>
                    </label>

                </div>
                @endif

            </div>

            {{--
                Card footer — delete action.
                This is a standalone <form method="POST"> with @method('DELETE').
                It is a sibling of the upload controls div, not nested inside
                the batch upload form. The batch form is declared after the grid.
            --}}
            @if($canDelete)
            <div class="px-5 pb-4 pt-1">
                <form method="POST"
                      action="{{ route('documents.destroy', $doc) }}"
                      onsubmit="return confirm('{{ translate('docs.delete_button') }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 border border-rose-200 text-rose-600 hover:bg-rose-50 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-trash-alt"></i>{{ translate('docs.delete_button') }}
                    </button>
                </form>
            </div>
            @endif

        </div>
        @endforeach

    </div>{{-- /grid --}}

    {{-- Info notice --}}
    <div class="bg-indigo-50 border border-indigo-200 rounded-xl px-5 py-4 flex items-start gap-3">
        <i class="fas fa-info-circle text-indigo-500 text-sm mt-0.5 shrink-0"></i>
        <div>
            <p class="text-sm font-semibold text-indigo-800 mb-1">Что происходит после загрузки?</p>
            <p class="text-xs text-indigo-700 leading-relaxed">
                Загруженные документы отправляются на проверку администратору. После подтверждения
                статус изменится на «Подтверждён». Если документ отклонён — вы увидите причину
                и сможете загрузить исправленный файл.
            </p>
        </div>
    </div>

    {{--
        ============================================================
        BATCH UPLOAD FORM — declared here, OUTSIDE the card grid.

        File inputs and expiry date inputs inside the cards bind to
        this form via the HTML5 `form="batch-upload-form"` attribute,
        so they are submitted with this form even though they are not
        DOM descendants of it. This eliminates any nested-form issue.

        The form element itself contains only @csrf and the submit bar.
        ============================================================
    --}}
    @if($notUploadedCount > 0 || $rejectedCount > 0)
    <div class="sticky bottom-4 z-10">
        <form id="batch-upload-form"
              action="{{ route('documents.batch') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            <div class="bg-white border border-slate-200 rounded-xl shadow-lg px-5 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">

                {{-- Live selected-count indicator, driven by Alpine on the outer div --}}
                <p class="text-sm text-slate-600" x-text="countLabel"></p>

                {{-- Single submit button for all selected files --}}
                <button type="submit"
                        :disabled="count === 0"
                        class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold rounded-lg transition-colors shadow-sm
                               bg-indigo-600 hover:bg-indigo-700 text-white
                               disabled:opacity-40 disabled:cursor-not-allowed">
                    <i class="fas fa-upload"></i>
                    {{ translate('docs.batch_upload_button') }}
                </button>
            </div>
        </form>
    </div>
    @endif

</div>{{-- /page wrapper --}}

<script>
/**
 * Per-card Alpine component.
 * Handles file-selection preview independently per card instance.
 * Alpine isolates x-data scope, so choosing a file in one card
 * does NOT reset the fileName state in any other card.
 */
function docSlot() {
    return {
        fileName: '',

        handleSelect(event) {
            const file = event.target.files[0] ?? null;
            this.fileName = file ? file.name : '';
        },

        handleDrop(event) {
            const file = event.dataTransfer.files[0] ?? null;
            if (! file) return;
            this.fileName = file.name;
            // Assign the dropped file to the hidden input so the form submits it.
            const input = this.$el.querySelector('input[type="file"]');
            if (input) {
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                // Bubble a change event so batchForm() counter updates.
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        },
    };
}

/**
 * Page-level Alpine component on the outer wrapper div.
 * Tracks how many slots have a file selected so the submit button
 * and the "Выбрано файлов: N" label stay in sync.
 *
 * Listens for the custom 'file-selected' event dispatched by each
 * card's file input @change handler. The event carries { hasFile: bool }.
 * Increment on selection, decrement (floor 0) on clear.
 */
function batchForm() {
    return {
        count: 0,

        get countLabel() {
            return '{{ translate('docs.batch_selected_count', ['count' => ':count']) }}'.replace(':count', this.count);
        },

        fileSelected(hasFile) {
            this.count = Math.max(0, this.count + (hasFile ? 1 : -1));
        },
    };
}
</script>
@endsection
