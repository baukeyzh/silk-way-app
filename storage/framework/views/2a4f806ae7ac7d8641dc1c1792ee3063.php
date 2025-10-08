<?php $__env->startSection('title', translate('cargo.available_cargo')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Заголовок -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e(translate('cargo.available_cargo')); ?></h1>
            <p class="mt-2 text-sm sm:text-base text-gray-700">
                <?php echo e(translate('cargo.available_cargo_desc')); ?>

            </p>
        </div>
        <?php if(auth()->user()->isWarehouseEmployee()): ?>
        <div class="mt-4 sm:mt-0">
            <a href="<?php echo e(route('cargo.create')); ?>" 
               class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto transition duration-200">
                <i class="fas fa-plus mr-2"></i>
                <?php echo e(translate('cargo.add_cargo_button')); ?>

            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Фильтры и поиск -->
    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        <form method="GET" action="<?php echo e(route('cargo.index')); ?>" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:space-x-4">
            <div class="flex-1 min-w-0">
                <label for="search" class="sr-only">Поиск</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" id="search" 
                           value="<?php echo e(request('search')); ?>"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="<?php echo e(translate('cargo.search_placeholder')); ?>">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                <select name="status" class="block w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value=""><?php echo e(translate('cargo.all_statuses')); ?></option>
                    <option value="available" <?php echo e(request('status') == 'available' ? 'selected' : ''); ?>><?php echo e(translate('cargo.status_available')); ?></option>
                    <option value="picked_up" <?php echo e(request('status') == 'picked_up' ? 'selected' : ''); ?>><?php echo e(translate('cargo.status_picked_up')); ?></option>
                    <option value="delivered" <?php echo e(request('status') == 'delivered' ? 'selected' : ''); ?>><?php echo e(translate('cargo.status_delivered')); ?></option>
                </select>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <i class="fas fa-filter mr-2"></i>
                    <?php echo e(translate('cargo.filter_button')); ?>

                </button>
            </div>
        </form>
    </div>

    <!-- Список грузов -->
    <?php if($cargo->count() > 0): ?>
        <!-- Десктопная таблица -->
        <div class="hidden lg:block bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('cargo.table_route')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('cargo.table_cargo')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('cargo.table_readiness')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('cargo.table_status')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('cargo.table_created')); ?>

                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Действия</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $cargo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition duration-200 cursor-pointer cargo-row" 
                            data-cargo-url="<?php echo e(route('cargo.show', $item)); ?>"
                            onclick="handleCargoRowClick(event, '<?php echo e(route('cargo.show', $item)); ?>')">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo e($item->from_location); ?>

                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-arrow-right mr-1"></i>
                                    <?php echo e($item->to_location); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo e($item->cargo_type); ?>

                                </div>
                                <div class="text-sm text-gray-500">
                                    <?php echo e($item->volume); ?> м³, <?php echo e($item->weight); ?> кг
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($item->ready_date->format('d.m.Y H:i')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($item->status === 'available'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        <?php echo e(translate('cargo.status_available')); ?>

                                    </span>
                                <?php elseif($item->status === 'picked_up'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-truck mr-1"></i>
                                        <?php echo e(translate('cargo.status_picked_up')); ?>

                                    </span>
                                <?php elseif($item->status === 'delivered'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-check-double mr-1"></i>
                                        <?php echo e(translate('cargo.status_delivered')); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e($item->created_at->format('d.m.Y H:i')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2" onclick="event.stopPropagation()">
                                    <a href="<?php echo e(route('cargo.show', $item)); ?>" 
                                       class="text-blue-600 hover:text-blue-900 transition duration-200">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if(auth()->user()->isWarehouseEmployee() && $item->status === 'available'): ?>
                                    <a href="<?php echo e(route('cargo.edit', $item)); ?>" 
                                       class="text-indigo-600 hover:text-indigo-900 transition duration-200">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('cargo.destroy', $item)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition duration-200"
                                                onclick="return confirm('<?php echo e(translate('cargo.confirm_delete')); ?>')">
                                            <i class="fas fa-trash"></i>
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

        <!-- Мобильные карточки -->
        <div class="lg:hidden space-y-4">
            <?php $__currentLoopData = $cargo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white shadow rounded-lg p-4 hover:shadow-md transition duration-200 cursor-pointer cargo-card" 
                 data-cargo-url="<?php echo e(route('cargo.show', $item)); ?>"
                 onclick="handleCargoCardClick(event, '<?php echo e(route('cargo.show', $item)); ?>')">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <?php echo e($item->from_location); ?> → <?php echo e($item->to_location); ?>

                        </h3>
                        <p class="text-sm text-gray-600"><?php echo e($item->cargo_type); ?></p>
                    </div>
                    <div class="ml-3">
                        <?php if($item->status === 'available'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                <?php echo e(translate('cargo.status_available')); ?>

                            </span>
                        <?php elseif($item->status === 'picked_up'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-truck mr-1"></i>
                                <?php echo e(translate('cargo.status_picked_up')); ?>

                            </span>
                        <?php elseif($item->status === 'delivered'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-check-double mr-1"></i>
                                <?php echo e(translate('cargo.status_delivered')); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                    <div>
                        <span class="text-gray-500"><?php echo e(translate('cargo.volume_label')); ?></span>
                        <span class="font-medium"><?php echo e($item->volume); ?> м³</span>
                    </div>
                    <div>
                        <span class="text-gray-500"><?php echo e(translate('cargo.weight_label')); ?></span>
                        <span class="font-medium"><?php echo e($item->weight); ?> кг</span>
                    </div>
                    <div>
                        <span class="text-gray-500"><?php echo e(translate('cargo.readiness_label')); ?></span>
                        <span class="font-medium"><?php echo e($item->ready_date->format('d.m.Y H:i')); ?></span>
                    </div>
                    <div>
                        <span class="text-gray-500"><?php echo e(translate('cargo.created_label')); ?></span>
                        <span class="font-medium"><?php echo e($item->created_at->format('d.m.Y H:i')); ?></span>
                    </div>
                </div>

                <div class="flex justify-between items-center" onclick="event.stopPropagation()">
                    <a href="<?php echo e(route('cargo.show', $item)); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        <?php echo e(translate('cargo.view_button')); ?>

                    </a>
                    
                    <?php if(auth()->user()->isWarehouseEmployee() && $item->status === 'available'): ?>
                    <div class="flex space-x-2">
                        <a href="<?php echo e(route('cargo.edit', $item)); ?>" 
                           class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="<?php echo e(route('cargo.destroy', $item)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200"
                                    onclick="return confirm('<?php echo e(translate('cargo.confirm_delete')); ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Пагинация -->
        <div class="mt-6">
            <?php echo e($cargo->links()); ?>

        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-box text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2"><?php echo e(translate('cargo.no_cargo_found')); ?></h3>
            <p class="text-gray-500 mb-6">
                <?php if(request('search') || request('status')): ?>
                    <?php echo e(translate('cargo.try_change_search')); ?>

                <?php else: ?>
                    <?php echo e(translate('cargo.no_cargo_desc')); ?>

                <?php endif; ?>
            </p>
            <?php if(request('search') || request('status')): ?>
            <a href="<?php echo e(route('cargo.index')); ?>" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                <i class="fas fa-times mr-2"></i>
                <?php echo e(translate('cargo.reset_filters')); ?>

            </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
// Функция для обработки клика по строке таблицы грузов
function handleCargoRowClick(event, url) {
    // Проверяем, что клик не по кнопкам действий
    if (event.target.closest('a, button, form') || event.target.closest('[onclick*="event.stopPropagation"]')) {
        return;
    }
    
    // Добавляем визуальный эффект при клике
    const row = event.currentTarget;
    row.style.backgroundColor = '#f3f4f6';
    
    // Переходим на страницу просмотра
    setTimeout(() => {
        window.location.href = url;
    }, 150);
}

// Функция для обработки клика по мобильной карточке грузов
function handleCargoCardClick(event, url) {
    // Проверяем, что клик не по кнопкам действий
    if (event.target.closest('a, button, form') || event.target.closest('[onclick*="event.stopPropagation"]')) {
        return;
    }
    
    // Добавляем визуальный эффект при клике
    const card = event.currentTarget;
    card.style.transform = 'scale(0.98)';
    card.style.transition = 'transform 0.1s ease';
    
    // Переходим на страницу просмотра
    setTimeout(() => {
        window.location.href = url;
    }, 100);
}

// Добавляем стили для интерактивности
document.addEventListener('DOMContentLoaded', function() {
    // Добавляем hover эффекты для строк таблицы грузов
    const rows = document.querySelectorAll('.cargo-row');
    rows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f9fafb';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // Добавляем hover эффекты для мобильных карточек грузов
    const cards = document.querySelectorAll('.cargo-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
});
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/zhandosbaukei/Desktop/projects/silk-way/silk-way-app/resources/views/cargo/index.blade.php ENDPATH**/ ?>