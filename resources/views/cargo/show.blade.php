@extends('layouts.app')

@section('title', translate('cargo.show_title'))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ translate('cargo.show_heading') }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                {{ translate('cargo.show_desc') }}
            </p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ translate('cargo.table_route') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex items-center">
                            <span class="font-medium">{{ $cargo->localized_from_location }}</span>
                            <i class="fas fa-arrow-right mx-2 text-gray-400"></i>
                            <span class="font-medium">{{ $cargo->localized_to_location }}</span>
                        </div>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ translate('cargo.cargo_type') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $cargo->cargo_type }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ translate('cargo.volume_weight_label') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $cargo->volume }} м³, {{ $cargo->weight }} кг
                    </dd>
                </div>
                @if($cargo->price_usd)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ translate('cargo.price_usd') }}
                    </dt>
                    <dd class="mt-1 text-sm font-medium text-green-600 sm:mt-0 sm:col-span-2">
                        ${{ number_format($cargo->price_usd, 2) }}
                    </dd>
                </div>
                @endif
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ translate('cargo.ready_date_label') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $cargo->ready_date->format('d.m.Y H:i') }}
                    </dd>
                </div>
                @if($cargo->comment)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ translate('cargo.comment_label') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $cargo->comment }}
                    </dd>
                </div>
                @endif
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ translate('applications.status_label') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($cargo->status === 'available')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                {{ translate('cargo.status_available') }}
                            </span>
                        @elseif($cargo->status === 'in_progress')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-truck mr-1"></i>
                                {{ translate('cargo.status_picked_up') }}
                            </span>
                        @elseif($cargo->status === 'delivered')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-check-double mr-1"></i>
                                {{ translate('cargo.status_delivered') }}
                            </span>
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ translate('cargo.created_by_label') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $cargo->createdBy->name }} - {{ $cargo->created_at->format('d.m.Y H:i') }}
                    </dd>
                </div>
                @if($cargo->hasApprovedApplication())
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ translate('cargo.driver_label') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $cargo->getApprovedApplication()->driver->name }}
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Заявки на груз (только для сотрудников склада) -->
    @if(auth()->user()->isWarehouseEmployee() && $cargo->applications->count() > 0)
    <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ translate('cargo.applications_heading') }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                {{ translate('cargo.applications_desc') }}
            </p>
        </div>
        <div class="border-t border-gray-200">
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('applications.table_driver') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('applications.table_submitted') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('applications.status_label') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ translate('applications.table_notes') }}
                            </th>
                            <th class="relative px-6 py-3">
                                <span class="sr-only">{{ translate('common.actions') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($cargo->applications as $application)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $application->driver->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $application->driver->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $application->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($application->isPending())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ translate('applications.status_pending_short') }}
                                    </span>
                                @elseif($application->isApproved())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ translate('applications.status_approved_short') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ translate('applications.status_rejected_short') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($application->driver_notes)
                                    <div class="max-w-xs truncate" title="{{ $application->driver_notes }}">
                                        {{ Str::limit($application->driver_notes, 50) }}
                                    </div>
                                @else
                                    <span class="text-gray-500">{{ translate('applications.no_notes') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('cargo.applications.show-from-cargo', $application) }}"
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($application->isPending())
                                    <form action="{{ route('applications.approve', $application) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-green-600 hover:text-green-900"
                                                onclick="return confirm('{{ translate('applications.confirm_approve') }}')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('applications.reject', $application) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900"
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
    </div>
    @endif

    <!-- Статус заявки для водителей -->
    @if(auth()->user()->isDriver())
        @if($cargo->applications()->where('driver_id', auth()->id())->exists())
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ translate('cargo.my_application_heading') }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ translate('cargo.my_application_desc') }}
                </p>
            </div>
            <div class="border-t border-gray-200">
                @php
                    $myApplication = $cargo->applications()->where('driver_id', auth()->id())->first();
                @endphp
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm font-medium text-gray-500">{{ translate('applications.status_label') }}:</span>
                            @if($myApplication->isPending())
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ translate('applications.status_pending') }}
                                </span>
                            @elseif($myApplication->isApproved())
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ translate('applications.status_approved_short') }}
                                </span>
                            @else
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ translate('applications.status_rejected_short') }}
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('cargo.applications.show-from-cargo', $myApplication) }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-eye mr-2"></i>
                            {{ translate('applications.view_details') }}
                        </a>
                    </div>
                    @if($myApplication->driver_notes)
                    <div class="mt-4">
                        <span class="text-sm font-medium text-gray-500">{{ translate('applications.my_notes_label') }}</span>
                        <p class="mt-1 text-sm text-gray-900">{{ $myApplication->driver_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    @endif

    <div class="mt-6 flex justify-between">
        <button type="button"
                onclick="history.back()"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-arrow-left mr-2"></i>
            {{ translate('common.back') }}
        </button>

        @if(auth()->user()->isWarehouseEmployee() && $cargo->status === 'available')
        <div class="flex space-x-3">
            <a href="{{ route('cargo.edit', $cargo) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-edit mr-2"></i>
                {{ translate('common.edit') }}
            </a>
            <form action="{{ route('cargo.destroy', $cargo) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        onclick="return confirm('{{ translate('cargo.confirm_delete') }}')">
                    <i class="fas fa-trash mr-2"></i>
                    {{ translate('common.delete') }}
                </button>
            </form>
        </div>
        @elseif(auth()->user()->isDriver())
            @if($cargo->status === 'available' && !$cargo->applications()->where('driver_id', auth()->id())->exists())
            <button type="button"
                    onclick="showApplicationModal({{ $cargo->id }})"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-paper-plane mr-2"></i>
                {{ translate('cargo.apply_button') }}
            </button>
            @endif
        @endif
    </div>
</div>

<!-- Модальное окно для подачи заявки -->
@if(auth()->user()->isDriver())
<div id="applicationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ translate('applications.apply_modal_title') }}</h3>
            <form id="applicationForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="driver_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ translate('applications.my_notes_optional') }}
                    </label>
                    <textarea id="driver_notes" name="driver_notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="{{ translate('applications.apply_placeholder') }}"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideApplicationModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        {{ translate('cargo.cancel') }}
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        {{ translate('cargo.apply_button') }}
                    </button>
                </div>
            </form>
        </div>
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

// Закрытие модального окна при клике вне его
document.getElementById('applicationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideApplicationModal();
    }
});
</script>
@endif
@endsection
