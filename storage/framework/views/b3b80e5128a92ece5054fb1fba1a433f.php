<?php $__env->startSection('title', translate('my_cargo.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Заголовок -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e(translate('my_cargo.heading')); ?></h1>
            <p class="mt-2 text-sm sm:text-base text-gray-700">
                <?php echo e(translate('my_cargo.description')); ?>

            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button type="button" 
                    onclick="history.back()"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto transition duration-200">
                                    <i class="fas fa-arrow-left mr-2"></i>
                    <?php echo e(translate('my_cargo.back_button')); ?>

                </button>
            </div>
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
                                <?php echo e(translate('my_cargo.table_route')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('my_cargo.table_cargo')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('my_cargo.table_picked')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('my_cargo.table_status')); ?>

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
                                <?php echo e($item->picked_at->format('d.m.Y H:i')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($item->status === 'picked_up'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-truck mr-1"></i>
                                        <?php echo e(translate('my_cargo.status_in_delivery')); ?>

                                    </span>
                                <?php elseif($item->status === 'delivered'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-double mr-1"></i>
                                        <?php echo e(translate('my_cargo.status_delivered')); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        <?php echo e(ucfirst($item->status)); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2" onclick="event.stopPropagation()">
                                    <a href="<?php echo e(route('cargo.show', $item)); ?>" 
                                       class="text-blue-600 hover:text-blue-900 transition duration-200">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if($item->status === 'picked_up'): ?>
                                    <form action="<?php echo e(route('cargo.mark-delivered', $item)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900 font-medium transition duration-200"
                                                                                            onclick="return confirm('<?php echo e(translate('my_cargo.confirm_mark_delivered')); ?>')">
                                        <i class="fas fa-check-double mr-1"></i>
                                        <?php echo e(translate('my_cargo.mark_delivered')); ?>

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
                        <?php if($item->status === 'picked_up'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-truck mr-1"></i>
                                <?php echo e(translate('my_cargo.status_in_delivery')); ?>

                            </span>
                        <?php elseif($item->status === 'delivered'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-double mr-1"></i>
                                <?php echo e(translate('my_cargo.status_delivered')); ?>

                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                <?php echo e(ucfirst($item->status)); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                    <div>
                        <span class="text-gray-500"><?php echo e(translate('my_cargo.volume_label')); ?></span>
                        <span class="font-medium"><?php echo e($item->volume); ?> м³</span>
                    </div>
                    <div>
                        <span class="text-gray-500"><?php echo e(translate('my_cargo.weight_label')); ?></span>
                        <span class="font-medium"><?php echo e($item->weight); ?> кг</span>
                    </div>
                    <div>
                        <span class="text-gray-500"><?php echo e(translate('my_cargo.picked_label')); ?></span>
                        <span class="font-medium"><?php echo e($item->picked_at->format('d.m.Y H:i')); ?></span>
                    </div>
                </div>

                <div class="flex justify-between items-center" onclick="event.stopPropagation()">
                    <a href="<?php echo e(route('cargo.show', $item)); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        <?php echo e(translate('my_cargo.view_button')); ?>

                    </a>
                    
                    <?php if($item->status === 'picked_up'): ?>
                    <form action="<?php echo e(route('cargo.mark-delivered', $item)); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200"
                                onclick="return confirm('<?php echo e(translate('my_cargo.confirm_mark_delivered')); ?>')">
                            <i class="fas fa-check-double mr-2"></i>
                            <?php echo e(translate('my_cargo.mark_delivered')); ?>

                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-truck text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2"><?php echo e(translate('my_cargo.no_cargo_title')); ?></h3>
            <p class="text-gray-500 mb-6"><?php echo e(translate('my_cargo.no_cargo_desc')); ?></p>
            <button type="button" 
                    onclick="history.back()"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                <?php echo e(translate('my_cargo.back_button')); ?>

            </button>
        </div>
    <?php endif; ?>

    <!-- Статистика -->
    <?php if($cargo->count() > 0): ?>
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-4 sm:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-truck text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                <?php echo e(translate('my_cargo.stats_total_picked')); ?>

                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                <?php echo e($cargo->count()); ?>

                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-4 sm:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-route text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                <?php echo e(translate('my_cargo.stats_in_delivery')); ?>

                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                <?php echo e($cargo->where('status', 'picked_up')->count()); ?>

                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-4 sm:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                <?php echo e(translate('my_cargo.stats_delivered')); ?>

                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                <?php echo e($cargo->where('status', 'delivered')->count()); ?>

                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/zhandosbaukei/Desktop/projects/silk-way/silk-way-app/resources/views/cargo/my-cargo.blade.php ENDPATH**/ ?>