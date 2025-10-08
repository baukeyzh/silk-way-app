<?php $__env->startSection('title', 'Просмотр груза - Silk Way'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Информация о грузе
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Детальная информация о грузе и его статусе
            </p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Маршрут
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex items-center">
                            <span class="font-medium"><?php echo e($cargo->from_location); ?></span>
                            <i class="fas fa-arrow-right mx-2 text-gray-400"></i>
                            <span class="font-medium"><?php echo e($cargo->to_location); ?></span>
                        </div>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Тип груза
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <?php echo e($cargo->cargo_type); ?>

                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Объем и вес
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <?php echo e($cargo->volume); ?> м³, <?php echo e($cargo->weight); ?> кг
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Дата готовности
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <?php echo e($cargo->ready_date->format('d.m.Y H:i')); ?>

                    </dd>
                </div>
                <?php if($cargo->comment): ?>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Комментарий
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <?php echo e($cargo->comment); ?>

                    </dd>
                </div>
                <?php endif; ?>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Статус
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <?php if($cargo->status === 'available'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Доступен
                            </span>
                        <?php elseif($cargo->status === 'in_progress'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-truck mr-1"></i>
                                В работе
                            </span>
                        <?php elseif($cargo->status === 'delivered'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-check-double mr-1"></i>
                                Доставлен
                            </span>
                        <?php endif; ?>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Создан
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <?php echo e($cargo->createdBy->name); ?> - <?php echo e($cargo->created_at->format('d.m.Y H:i')); ?>

                    </dd>
                </div>
                <?php if($cargo->hasApprovedApplication()): ?>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Водитель
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <?php echo e($cargo->getApprovedApplication()->driver->name); ?>

                    </dd>
                </div>
                <?php endif; ?>
            </dl>
        </div>
    </div>

    <!-- Заявки на груз (только для сотрудников склада) -->
    <?php if(auth()->user()->isWarehouseEmployee() && $cargo->applications->count() > 0): ?>
    <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Заявки на груз
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Заявки от водителей на перевозку этого груза
            </p>
        </div>
        <div class="border-t border-gray-200">
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Водитель
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Дата подачи
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Статус
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Заметки
                            </th>
                            <th class="relative px-6 py-3">
                                <span class="sr-only">Действия</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $cargo->applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo e($application->driver->name); ?>

                                </div>
                                <div class="text-sm text-gray-500">
                                    <?php echo e($application->driver->email); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($application->created_at->format('d.m.Y H:i')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($application->isPending()): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Ожидает
                                    </span>
                                <?php elseif($application->isApproved()): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Подтверждена
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Отклонена
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <?php if($application->driver_notes): ?>
                                    <div class="max-w-xs truncate" title="<?php echo e($application->driver_notes); ?>">
                                        <?php echo e(Str::limit($application->driver_notes, 50)); ?>

                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-500">Нет заметок</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('cargo.applications.show-from-cargo', $application)); ?>" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if($application->isPending()): ?>
                                    <form action="<?php echo e(route('applications.approve', $application)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900"
                                                onclick="return confirm('Подтвердить заявку этого водителя?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="<?php echo e(route('applications.reject', $application)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Отклонить заявку этого водителя?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Статус заявки для водителей -->
    <?php if(auth()->user()->isDriver()): ?>
        <?php if($cargo->applications()->where('driver_id', auth()->id())->exists()): ?>
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Ваша заявка
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Статус вашей заявки на этот груз
                </p>
            </div>
            <div class="border-t border-gray-200">
                <?php
                    $myApplication = $cargo->applications()->where('driver_id', auth()->id())->first();
                ?>
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Статус:</span>
                            <?php if($myApplication->isPending()): ?>
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Ожидает рассмотрения
                                </span>
                            <?php elseif($myApplication->isApproved()): ?>
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Подтверждена
                                </span>
                            <?php else: ?>
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Отклонена
                                </span>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo e(route('cargo.applications.show-from-cargo', $myApplication)); ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-eye mr-2"></i>
                            Подробнее
                        </a>
                    </div>
                    <?php if($myApplication->driver_notes): ?>
                    <div class="mt-4">
                        <span class="text-sm font-medium text-gray-500">Ваши заметки:</span>
                        <p class="mt-1 text-sm text-gray-900"><?php echo e($myApplication->driver_notes); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
    
    <div class="mt-6 flex justify-between">
        <button type="button" 
                onclick="history.back()"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Назад
        </button>
        
        <?php if(auth()->user()->isWarehouseEmployee() && $cargo->status === 'available'): ?>
        <div class="flex space-x-3">
            <a href="<?php echo e(route('cargo.edit', $cargo)); ?>" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-edit mr-2"></i>
                Редактировать
            </a>
            <form action="<?php echo e(route('cargo.destroy', $cargo)); ?>" method="POST" class="inline">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        onclick="return confirm('Удалить этот груз?')">
                    <i class="fas fa-trash mr-2"></i>
                    Удалить
                </button>
            </form>
        </div>
        <?php elseif(auth()->user()->isDriver()): ?>
            <?php if($cargo->status === 'available' && !$cargo->applications()->where('driver_id', auth()->id())->exists()): ?>
            <button type="button" 
                    onclick="showApplicationModal(<?php echo e($cargo->id); ?>)"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-paper-plane mr-2"></i>
                Подать заявку
            </button>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Модальное окно для подачи заявки -->
<?php if(auth()->user()->isDriver()): ?>
<div id="applicationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Подать заявку на груз</h3>
            <form id="applicationForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label for="driver_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Ваши заметки (необязательно)
                    </label>
                    <textarea id="driver_notes" name="driver_notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Дополнительная информация о вашем опыте или условиях перевозки..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideApplicationModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Отмена
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Подать заявку
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
<?php endif; ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/zhandosbaukei/Desktop/projects/silk-way/silk-way-app/resources/views/cargo/show.blade.php ENDPATH**/ ?>