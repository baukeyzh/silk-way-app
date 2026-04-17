@extends('layouts.app')

@section('title', 'Заявка на груз')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

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
            <div class="flex-1 h-0.5 {{ $application->isApproved() && $application->cargo->status === 'delivered' ? 'bg-emerald-400' : 'bg-slate-200' }} mb-6"></div>
            {{-- Step 3: Result --}}
            <div class="flex flex-col items-center gap-1 flex-1">
                <div class="w-8 h-8 rounded-full {{ $application->isApproved() ? 'bg-emerald-500' : ($application->isRejected() ? 'bg-rose-400' : 'bg-slate-200') }} flex items-center justify-center">
                    @if($application->isApproved())
                        <i class="fas fa-check-double text-white text-xs"></i>
                    @elseif($application->isRejected())
                        <i class="fas fa-times text-white text-xs"></i>
                    @else
                        <i class="fas fa-hourglass-half text-slate-400 text-xs"></i>
                    @endif
                </div>
                <p class="text-xs font-medium {{ $application->isApproved() ? 'text-emerald-700' : ($application->isRejected() ? 'text-rose-700' : 'text-slate-400') }} text-center">
                    {{ $application->isApproved() ? 'Одобрена' : ($application->isRejected() ? 'Отклонена' : 'Ожидание') }}
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

    {{-- Contact info (approved only) --}}
    @if($application->isApproved() && ($application->pickup_contact || $application->pickup_address || $application->delivery_contact || $application->delivery_address))
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

    {{-- Actions --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex flex-wrap gap-3">
            @if(auth()->user()->isDriver() && $application->isApproved() && $application->cargo->status !== 'delivered')
            <form action="{{ route('applications.mark-delivered', $application) }}" method="POST">
                @csrf
                <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    <i class="fas fa-check mr-2"></i>Отметить как доставленный
                </button>
            </form>
            @endif

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
@endsection
