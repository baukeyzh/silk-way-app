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

    // Build the reactive docs array initialiser for Alpine — all UI-state
    // fields default to falsy so Alpine owns them from first render.
    $docsJson = $documents->map(function ($d) use ($icons) {
        return [
            'id'               => $d->id,
            'document_type'    => $d->document_type,
            'status'           => $d->status,
            'original_filename'=> $d->original_filename,
            'expires_at'       => $d->expires_at?->format('Y-m-d'),
            'rejection_reason' => $d->rejection_reason,
            'is_optional'      => $d->isOptional(),
            'label'            => $d->document_type_label,
            'icon'             => $icons[$d->document_type] ?? 'fa-file-alt',
            // UI-only state — initialised here so Alpine sees them as reactive
            'selectedFile'     => null,
            'selectedFileName' => '',
            'selectedExpiryDate' => $d->expires_at?->format('Y-m-d') ?? '',
            'uploading'        => false,
            'uploadError'      => '',
        ];
    })->values()->toArray();
@endphp

{{--
    Single Alpine component wrapping the entire page.
    uploadBase is passed via a data attribute so the JS never hard-codes a URL.
--}}
<div
    x-data="docManager()"
    x-init="init()"
    data-upload-base="{{ url('/documents') }}"
    data-batch-url="{{ route('documents.batch') }}"
    data-docs='@json($docsJson)'
    class="space-y-6"
>

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
            {{ $reqVerified }} {{ translate('docs.verified_count_separator') ?? 'из' }} {{ $requiredTotal }} {{ translate('docs.verified_count_suffix') ?? 'подтверждено' }}
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

    {{-- Document grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        <template x-for="(doc, index) in docs" :key="doc.id">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow"
                 :class="cardBorderClass(doc)">

                {{-- Coloured top stripe --}}
                <div class="h-1 w-full shrink-0" :class="stripeClass(doc)"></div>

                {{-- Card header --}}
                <div class="px-5 pt-4 pb-3 border-b border-slate-100">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                                 :class="iconBgClass(doc)">
                                <i class="fas text-lg" :class="[doc.icon, iconTextClass(doc)]"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate" x-text="doc.label"></p>
                                <span x-show="doc.is_optional"
                                      class="inline-flex items-center mt-0.5 px-1.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-600">
                                    {{ translate('docs.optional') }}
                                </span>
                            </div>
                        </div>

                        {{-- Status badge --}}
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0"
                              :class="badgeClass(doc)">
                            <span class="w-1.5 h-1.5 rounded-full" :class="dotClass(doc)"></span>
                            <span x-text="statusLabel(doc)"></span>
                        </span>
                    </div>
                </div>

                {{-- Card body --}}
                <div class="px-5 py-4 flex-1 space-y-3">

                    {{-- Rejection reason --}}
                    <div x-show="doc.status === 'rejected' && doc.rejection_reason"
                         x-cloak
                         class="flex items-start gap-2.5 bg-rose-50 border border-rose-200 rounded-lg px-3 py-2.5">
                        <i class="fas fa-exclamation-circle text-rose-500 text-sm mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs font-semibold text-rose-700 mb-0.5">{{ translate('docs.rejection_reason') }}</p>
                            <p class="text-xs text-rose-600 leading-snug" x-text="doc.rejection_reason"></p>
                        </div>
                    </div>

                    {{-- Uploaded filename --}}
                    <a href="#"
                       x-show="doc.original_filename"
                       x-cloak
                       class="flex items-center gap-2 text-xs bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-200 rounded-lg px-3 py-2 transition-colors group">
                        <i class="fas fa-paperclip text-slate-400 group-hover:text-indigo-500 shrink-0 transition-colors"></i>
                        <span class="truncate text-slate-600 group-hover:text-indigo-700 font-medium transition-colors"
                              x-text="doc.original_filename"></span>
                        <i class="fas fa-external-link-alt text-slate-300 group-hover:text-indigo-400 text-xs ml-auto shrink-0 transition-colors"></i>
                    </a>

                    {{-- Pending info --}}
                    <div x-show="doc.status === 'pending'"
                         x-cloak
                         class="flex items-center gap-2 text-xs text-amber-700 bg-amber-50 rounded-lg px-3 py-2 border border-amber-100">
                        <i class="fas fa-clock text-amber-500 shrink-0"></i>
                        <span>Документ отправлен и ожидает проверки администратора</span>
                    </div>

                    {{-- Verified info --}}
                    <div x-show="doc.status === 'verified'"
                         x-cloak
                         class="flex items-center gap-2 text-xs text-emerald-700 bg-emerald-50 rounded-lg px-3 py-2 border border-emerald-100">
                        <i class="fas fa-check-circle text-emerald-500 shrink-0"></i>
                        <span>{{ translate('docs.status_verified') }}</span>
                        <span class="ml-auto text-slate-400" x-show="doc.expires_at" x-text="formatDate(doc.expires_at)"></span>
                    </div>

                    {{-- Expiry date (non-editable, for pending/verified) --}}
                    <div x-show="doc.status === 'pending' && doc.expires_at"
                         x-cloak>
                        <p class="text-xs font-medium text-slate-500 mb-1.5">
                            <i class="fas fa-calendar-alt mr-1"></i>{{ translate('docs.expiry_date') }}
                        </p>
                        <p class="text-sm text-slate-600" x-text="formatDate(doc.expires_at)"></p>
                    </div>

                    {{-- Upload / re-upload section (shown for not_uploaded and rejected) --}}
                    <div x-show="doc.status === 'not_uploaded' || doc.status === 'rejected'"
                         x-cloak
                         class="space-y-2.5">

                        {{-- Upload error --}}
                        <div x-show="doc.uploadError"
                             x-cloak
                             class="flex items-center gap-2 text-xs text-rose-700 bg-rose-50 rounded-lg px-3 py-2 border border-rose-200">
                            <i class="fas fa-exclamation-circle text-rose-500 shrink-0"></i>
                            <span x-text="doc.uploadError"></span>
                        </div>

                        {{-- Expiry date input --}}
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">
                                <i class="fas fa-calendar-alt mr-1"></i>{{ translate('docs.expiry_date') }}
                            </label>
                            <input type="date"
                                   :value="doc.selectedExpiryDate"
                                   @change="doc.selectedExpiryDate = $event.target.value"
                                   class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                        </div>

                        {{-- Drop zone --}}
                        <label class="relative flex flex-col items-center justify-center border-2 border-dashed rounded-xl px-4 py-5 cursor-pointer transition-colors"
                               :class="doc.selectedFileName ? 'border-indigo-300 bg-indigo-50/50' : 'border-slate-200 hover:border-indigo-300 hover:bg-slate-50/60'"
                               @dragover.prevent
                               @drop.prevent="handleDrop($event, doc)">
                            <input type="file"
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="sr-only"
                                   @change="handleFileSelect($event, doc)">
                            <i class="fas text-2xl mb-2"
                               :class="doc.selectedFileName ? 'fa-check-circle text-indigo-500' : 'fa-cloud-upload-alt text-slate-300'"></i>
                            <p class="text-xs text-slate-500 text-center" x-show="!doc.selectedFileName">PDF, JPG, PNG — макс. 5 MB</p>
                            <p class="text-xs text-indigo-700 font-medium truncate max-w-full"
                               x-show="doc.selectedFileName"
                               x-cloak
                               x-text="doc.selectedFileName"></p>
                        </label>

                        {{-- Single-card upload button --}}
                        <button type="button"
                                :disabled="!doc.selectedFile || doc.uploading"
                                @click="uploadSingle(doc)"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                :class="doc.status === 'rejected'
                                    ? 'bg-rose-600 hover:bg-rose-700 text-white disabled:hover:bg-rose-600'
                                    : 'bg-indigo-600 hover:bg-indigo-700 text-white disabled:hover:bg-indigo-600'">
                            <i class="fas" :class="doc.uploading ? 'fa-spinner fa-spin' : 'fa-upload'"></i>
                            <span x-text="doc.status === 'rejected' ? '{{ translate('docs.replace_button') }}' : '{{ translate('docs.upload_button') }}'"></span>
                        </button>

                    </div>

                </div>

                {{-- Card footer — delete (regular form, page reload is fine) --}}
                <div x-show="doc.status === 'pending' || doc.status === 'rejected'"
                     x-cloak
                     class="px-5 pb-4 pt-1">
                    <form method="POST"
                          :action="`/documents/${doc.id}`"
                          onsubmit="return confirm('{{ translate('docs.delete_button') }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 border border-rose-200 text-rose-600 hover:bg-rose-50 text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-trash-alt"></i>{{ translate('docs.delete_button') }}
                        </button>
                    </form>
                </div>

            </div>
        </template>

    </div>

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

    {{-- Batch submit sticky bar --}}
    <div x-show="docs.filter(d => d.selectedFile).length > 0"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="fixed bottom-16 md:bottom-4 left-0 right-0 z-40 flex justify-center px-4 pointer-events-none">
        <div class="pointer-events-auto bg-slate-900 text-white rounded-2xl shadow-2xl px-5 py-3.5 flex items-center gap-4 w-full max-w-lg">
            <i class="fas fa-layer-group text-indigo-400"></i>
            <span class="text-sm font-medium flex-1">
                <span x-text="docs.filter(d => d.selectedFile).length"></span> файл(а) готовы к отправке
            </span>
            <button @click="submitAll()"
                    :disabled="batchUploading"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-60 text-white text-sm font-semibold rounded-xl transition-colors">
                <i class="fas" :class="batchUploading ? 'fa-spinner fa-spin' : 'fa-paper-plane'"></i>Отправить всё
            </button>
            <button @click="docs.forEach(d => { d.selectedFile = null; d.selectedFileName = ''; })"
                    :disabled="batchUploading"
                    class="text-slate-400 hover:text-white transition-colors text-xs disabled:opacity-40">
                Отмена
            </button>
        </div>
    </div>

</div>

<script>
function docManager() {
    return {
        docs: [],
        batchUploading: false,

        init() {
            const el   = this.$el;
            const raw  = JSON.parse(el.dataset.docs);
            this.uploadBase = el.dataset.uploadBase;
            this.batchUrl   = el.dataset.batchUrl;
            this.csrfToken  = document.querySelector('meta[name="csrf-token"]').content;

            // Clone so Alpine picks up each property as reactive
            this.docs = raw.map(d => ({ ...d }));
        },

        // ----------------------------------------------------------------
        // File selection helpers
        // ----------------------------------------------------------------

        handleFileSelect(event, doc) {
            const file = event.target.files[0] ?? null;
            doc.selectedFile     = file;
            doc.selectedFileName = file ? file.name : '';
            doc.uploadError      = '';
        },

        handleDrop(event, doc) {
            const file = event.dataTransfer.files[0] ?? null;
            doc.selectedFile     = file;
            doc.selectedFileName = file ? file.name : '';
            doc.uploadError      = '';
        },

        // ----------------------------------------------------------------
        // Single-card AJAX upload
        // ----------------------------------------------------------------

        async uploadSingle(doc) {
            if (! doc.selectedFile || doc.uploading) return;

            doc.uploading    = true;
            doc.uploadError  = '';

            const formData = new FormData();
            formData.append('_token', this.csrfToken);
            formData.append('file', doc.selectedFile);
            if (doc.selectedExpiryDate) {
                formData.append('expires_at', doc.selectedExpiryDate);
            }

            try {
                const url      = `${this.uploadBase}/${doc.id}/upload`;
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });

                if (response.ok) {
                    const data = await response.json().catch(() => ({}));
                    doc.status            = data.status ?? 'pending';
                    doc.original_filename = data.original_filename ?? doc.selectedFile.name;
                    doc.expires_at        = data.expires_at ?? doc.selectedExpiryDate || null;
                    doc.selectedFile      = null;
                    doc.selectedFileName  = '';
                } else {
                    let msg = 'Ошибка загрузки. Попробуйте снова.';
                    try {
                        const body = await response.json();
                        msg = body.message ?? (body.errors ? Object.values(body.errors).flat()[0] : msg);
                    } catch (_) {}
                    doc.uploadError = msg;
                }
            } catch (_) {
                doc.uploadError = 'Ошибка загрузки. Попробуйте снова.';
            } finally {
                doc.uploading = false;
            }
        },

        // ----------------------------------------------------------------
        // Batch upload (all selected files in one request)
        // ----------------------------------------------------------------

        async submitAll() {
            const pending = this.docs.filter(d => d.selectedFile);
            if (! pending.length || this.batchUploading) return;

            this.batchUploading = true;

            const formData = new FormData();
            formData.append('_token', this.csrfToken);

            pending.forEach((doc, i) => {
                formData.append(`document_ids[${i}]`, doc.id);
                formData.append(`files[${i}]`, doc.selectedFile);
                formData.append(`expires_at[${i}]`, doc.selectedExpiryDate ?? '');
            });

            try {
                const response = await fetch(this.batchUrl, {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });
                const body     = await response.json();

                if (body.results && Array.isArray(body.results)) {
                    body.results.forEach(result => {
                        const doc = this.docs.find(d => d.id === result.id);
                        if (! doc) return;

                        if (result.error) {
                            doc.uploadError = result.error;
                        } else {
                            doc.status            = result.status;
                            doc.original_filename = result.original_filename;
                            doc.expires_at        = result.expires_at;
                            doc.selectedFile      = null;
                            doc.selectedFileName  = '';
                            doc.uploadError       = '';
                        }
                    });
                }
            } catch (_) {
                // Network-level failure — surface a generic error on all pending cards
                pending.forEach(doc => {
                    doc.uploadError = 'Ошибка соединения. Попробуйте снова.';
                });
            } finally {
                this.batchUploading = false;
            }
        },

        // ----------------------------------------------------------------
        // Date formatting
        // ----------------------------------------------------------------

        formatDate(dateStr) {
            if (! dateStr) return '—';
            // dateStr is YYYY-MM-DD from the server
            const [y, m, d] = dateStr.split('-');
            return `${d}.${m}.${y}`;
        },

        // ----------------------------------------------------------------
        // Dynamic CSS helpers — replicate the Blade match() logic in JS
        // ----------------------------------------------------------------

        stripeClass(doc) {
            return {
                pending:      'bg-amber-400',
                verified:     'bg-emerald-500',
                rejected:     'bg-rose-500',
                not_uploaded: 'bg-slate-200',
            }[doc.status] ?? 'bg-slate-200';
        },

        cardBorderClass(doc) {
            return 'border ' + ({
                pending:      'border-amber-200',
                verified:     'border-emerald-200',
                rejected:     'border-rose-200',
                not_uploaded: 'border-slate-200',
            }[doc.status] ?? 'border-slate-200');
        },

        iconBgClass(doc) {
            return {
                pending:      'bg-amber-100',
                verified:     'bg-emerald-100',
                rejected:     'bg-rose-100',
                not_uploaded: 'bg-slate-100',
            }[doc.status] ?? 'bg-slate-100';
        },

        iconTextClass(doc) {
            return {
                pending:      'text-amber-600',
                verified:     'text-emerald-600',
                rejected:     'text-rose-600',
                not_uploaded: 'text-slate-400',
            }[doc.status] ?? 'text-slate-400';
        },

        badgeClass(doc) {
            return {
                pending:      'bg-amber-100 text-amber-700',
                verified:     'bg-emerald-100 text-emerald-700',
                rejected:     'bg-rose-100 text-rose-700',
                not_uploaded: 'bg-slate-100 text-slate-600',
            }[doc.status] ?? 'bg-slate-100 text-slate-600';
        },

        dotClass(doc) {
            return {
                pending:      'bg-amber-400',
                verified:     'bg-emerald-500',
                rejected:     'bg-rose-500',
                not_uploaded: 'bg-slate-400',
            }[doc.status] ?? 'bg-slate-400';
        },

        statusLabel(doc) {
            return {
                pending:      '{{ translate('docs.status_pending') }}',
                verified:     '{{ translate('docs.status_verified') }}',
                rejected:     '{{ translate('docs.status_rejected') }}',
                not_uploaded: '{{ translate('docs.status_not_uploaded') }}',
            }[doc.status] ?? doc.status;
        },
    };
}
</script>
@endsection
