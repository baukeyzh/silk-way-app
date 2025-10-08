<?php $__env->startSection('title', translate('my_applications.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Заголовок -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e(translate('my_applications.heading')); ?></h1>
            <p class="mt-2 text-sm sm:text-base text-gray-700">
                <?php echo e(translate('my_applications.description')); ?>

            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="<?php echo e(route('cargo.index')); ?>" 
               class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto transition duration-200">
                <i class="fas fa-box mr-2"></i>
                <?php echo e(translate('my_applications.view_cargo_button')); ?>

            </a>
        </div>
    </div>

    <!-- Статистика -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-4 sm:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                <?php echo e(translate('my_applications.stats_pending')); ?>

                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                <?php echo e($pendingApplications->count()); ?>

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
                        <i class="fas fa-check text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                <?php echo e(translate('my_applications.stats_approved')); ?>

                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                <?php echo e($approvedApplications->count()); ?>

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
                        <i class="fas fa-times text-red-600 text-2xl"></i>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                <?php echo e(translate('my_applications.stats_rejected')); ?>

                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                <?php echo e($rejectedApplications->count()); ?>

                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ожидающие заявки -->
    <?php if($pendingApplications->count() > 0): ?>
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-clock mr-2 text-yellow-600"></i>
                <?php echo e(translate('my_applications.pending_title')); ?> (<?php echo e($pendingApplications->count()); ?>)
            </h2>
        </div>
        
        <!-- Десктопная таблица -->
        <div class="hidden lg:block">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('my_applications.table_route')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('my_applications.table_cargo')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('my_applications.table_submitted')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('my_applications.table_actions')); ?>

                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $pendingApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo e($application->cargo->from_location); ?> → <?php echo e($application->cargo->to_location); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($application->cargo->cargo_type); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($application->cargo->volume); ?> м³, <?php echo e($application->cargo->weight); ?> кг</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e($application->created_at->format('d.m.Y H:i')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?php echo e(route('my-cargo.applications.show-from-my-cargo', $application)); ?>" 
                                   class="text-blue-600 hover:text-blue-900 transition duration-200">
                                    <i class="fas fa-eye mr-1"></i>
                                    <?php echo e(translate('my_applications.view_details')); ?>

                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Мобильные карточки -->
        <div class="lg:hidden divide-y divide-gray-200">
            <?php $__currentLoopData = $pendingApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <?php echo e($application->cargo->from_location); ?> → <?php echo e($application->cargo->to_location); ?>

                        </h3>
                        <p class="text-sm text-gray-600"><?php echo e($application->cargo->cargo_type); ?></p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-1"></i>
                        <?php echo e(translate('my_applications.status_pending')); ?>

                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                    <div>
                        <span class="text-gray-500"><?php echo e(translate('my_applications.volume_label')); ?></span>
                        <span class="font-medium"><?php echo e($application->cargo->volume); ?> м³</span>
                    </div>
                    <div>
                        <span class="text-gray-500"><?php echo e(translate('my_applications.weight_label')); ?></span>
                        <span class="font-medium"><?php echo e($application->cargo->weight); ?> кг</span>
                    </div>
                    <div>
                        <span class="text-gray-500"><?php echo e(translate('my_applications.submitted_label')); ?></span>
                        <span class="font-medium"><?php echo e($application->created_at->format('d.m.Y H:i')); ?></span>
                    </div>
                </div>

                <div class="flex justify-center">
                    <a href="<?php echo e(route('my-cargo.applications.show-from-my-cargo', $application)); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        <?php echo e(translate('my_applications.view_details')); ?>

                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Подтвержденные заявки -->
    <?php if($approvedApplications->count() > 0): ?>
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200">
            <h2 class="text-lg sm:text-xl font-semibold text-green-700 flex items-center">
                <i class="fas fa-check mr-2"></i>
                <?php echo e(translate('my_applications.approved_title')); ?> (<?php echo e($approvedApplications->count()); ?>)
            </h2>
        </div>
        
        <!-- Десктопная таблица -->
        <div class="hidden lg:block">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Маршрут
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Груз
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Подтверждено
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Действия
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $approvedApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo e($application->cargo->from_location); ?> → <?php echo e($application->cargo->to_location); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($application->cargo->cargo_type); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($application->cargo->volume); ?> м³, <?php echo e($application->cargo->weight); ?> кг</div>
                                <div class="text-sm text-gray-500">Подтверждено: <?php echo e($application->approved_at->format('d.m.Y H:i')); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e($application->approved_at->format('d.m.Y H:i')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('my-cargo.applications.show-from-my-cargo', $application)); ?>" 
                                       class="text-blue-600 hover:text-blue-900 transition duration-200">
                                        <i class="fas fa-eye mr-1"></i>
                                        Подробнее
                                    </a>
                                    <?php if($application->cargo->status !== 'delivered'): ?>
                                    <form action="<?php echo e(route('applications.mark-delivered', $application)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900 transition duration-200">
                                            <i class="fas fa-check mr-1"></i>
                                            Доставлен
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
        <div class="lg:hidden divide-y divide-gray-200">
            <?php $__currentLoopData = $approvedApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <?php echo e($application->cargo->from_location); ?> → <?php echo e($application->cargo->to_location); ?>

                        </h3>
                        <p class="text-sm text-gray-600"><?php echo e($application->cargo->cargo_type); ?></p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check mr-1"></i>
                        Подтверждено
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                    <div>
                        <span class="text-gray-500">Объем:</span>
                        <span class="font-medium"><?php echo e($application->cargo->volume); ?> м³</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Вес:</span>
                        <span class="font-medium"><?php echo e($application->cargo->weight); ?> кг</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Подтверждено:</span>
                        <span class="font-medium"><?php echo e($application->approved_at->format('d.m.Y H:i')); ?></span>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <a href="<?php echo e(route('my-cargo.applications.show-from-my-cargo', $application)); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        Подробнее
                    </a>
                    
                    <?php if($application->cargo->status !== 'delivered'): ?>
                    <form action="<?php echo e(route('applications.mark-delivered', $application)); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                            <i class="fas fa-check mr-2"></i>
                            Отметить как доставленный
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Отклоненные заявки -->
    <?php if($rejectedApplications->count() > 0): ?>
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200">
            <h2 class="text-lg sm:text-xl font-semibold text-red-700 flex items-center">
                <i class="fas fa-times-circle mr-2"></i>
                <?php echo e(translate('my_applications.rejected_title')); ?> (<?php echo e($rejectedApplications->count()); ?>)
            </h2>
        </div>
        
        <!-- Десктопная таблица -->
        <div class="hidden lg:block">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Маршрут
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Груз
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Действия
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $rejectedApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo e($application->cargo->from_location); ?> → <?php echo e($application->cargo->to_location); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($application->cargo->cargo_type); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($application->cargo->volume); ?> м³, <?php echo e($application->cargo->weight); ?> кг</div>
                                <?php if($application->driver_notes): ?>
                                <div class="text-sm text-gray-500">Ваши заметки: <?php echo e($application->driver_notes); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?php echo e(route('my-cargo.applications.show-from-my-cargo', $application)); ?>" 
                                   class="text-red-600 hover:text-red-900 transition duration-200">
                                    <i class="fas fa-eye mr-1"></i>
                                    Подробнее
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Мобильные карточки -->
        <div class="lg:hidden divide-y divide-gray-200">
            <?php $__currentLoopData = $rejectedApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <?php echo e($application->cargo->from_location); ?> → <?php echo e($application->cargo->to_location); ?>

                        </h3>
                        <p class="text-sm text-gray-600"><?php echo e($application->cargo->cargo_type); ?></p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <i class="fas fa-times mr-1"></i>
                        <?php echo e(translate('my_applications.status_rejected')); ?>

                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                    <div>
                        <span class="text-gray-500"><?php echo e(translate('my_applications.volume_label')); ?></span>
                        <span class="font-medium"><?php echo e($application->cargo->volume); ?> м³</span>
                    </div>
                    <div>
                        <span class="text-gray-500"><?php echo e(translate('my_applications.weight_label')); ?></span>
                        <span class="font-medium"><?php echo e($application->cargo->weight); ?> кг</span>
                    </div>
                    <?php if($application->driver_notes): ?>
                    <div class="col-span-2">
                        <span class="text-gray-500"><?php echo e(translate('my_applications.driver_notes_label')); ?></span>
                        <span class="font-medium"><?php echo e($application->driver_notes); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="flex justify-center">
                    <a href="<?php echo e(route('my-cargo.applications.show-from-my-cargo', $application)); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        <?php echo e(translate('my_applications.view_details')); ?>

                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Если нет заявок -->
    <?php if($pendingApplications->count() === 0 && $approvedApplications->count() === 0 && $rejectedApplications->count() === 0): ?>
    <div class="text-center py-12">
        <div class="text-gray-400 mb-4">
            <i class="fas fa-inbox text-6xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-600 mb-2"><?php echo e(translate('my_applications.no_applications_title')); ?></h3>
        <p class="text-gray-500 mb-6"><?php echo e(translate('my_applications.no_applications_desc')); ?></p>
        <a href="<?php echo e(route('cargo.index')); ?>" 
           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
            <i class="fas fa-box mr-2"></i>
            <?php echo e(translate('my_applications.view_available_cargo')); ?>

        </a>
    </div>
    <?php endif; ?>

    <!-- Навигация -->
    <div class="mt-8 pt-6 border-t border-gray-200">
        <button type="button" 
                onclick="history.back()"
                class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            <?php echo e(translate('my_applications.back_button')); ?>

        </button>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/zhandosbaukei/Desktop/projects/silk-way/silk-way-app/resources/views/cargo/my-applications.blade.php ENDPATH**/ ?>