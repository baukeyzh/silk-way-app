@extends('layouts.app')

@section('title', translate('warehouse.create'))

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <button type="button" onclick="history.back()"
                class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-600 transition-colors shrink-0">
            <i class="fas fa-arrow-left text-sm"></i>
        </button>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ translate('warehouse.create') }}</h1>
        </div>
    </div>

    <form action="{{ route('warehouses.store') }}" method="POST">
        @csrf
        <div class="space-y-5">

            @include('warehouses._form', [
                'warehouse'       => null,
                'cities'          => $cities,
                'preselectedCity' => $preselectedCity,
            ])

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="history.back()"
                        class="px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors">
                    {{ translate('cargo.cancel') }}
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <i class="fas fa-save mr-2"></i>{{ translate('warehouse.save') }}
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
