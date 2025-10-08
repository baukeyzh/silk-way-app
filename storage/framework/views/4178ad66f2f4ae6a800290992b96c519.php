<?php $__env->startSection('title', translate('applications.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Заголовок -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-clipboard-list mr-3 text-blue-600"></i>
                    <?php echo e(translate('applications.heading')); ?>

                </h1>
                <p class="mt-2 text-gray-600">
                    <?php if(auth()->user()->isAdmin()): ?>
                        <?php echo e(translate('applications.admin_desc')); ?>

                    <?php else: ?>
                        <?php echo e(translate('applications.driver_desc')); ?>

                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Фильтры и поиск -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" action="<?php echo e(route('applications.index')); ?>" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(translate('applications.status_label')); ?></label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value=""><?php echo e(translate('applications.all_statuses')); ?></option>
                        <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>><?php echo e(translate('applications.status_pending')); ?></option>
                        <option value="approved" <?php echo e(request('status') === 'approved' ? 'selected' : ''); ?>><?php echo e(translate('applications.status_approved')); ?></option>
                        <option value="rejected" <?php echo e(request('status') === 'rejected' ? 'selected' : ''); ?>><?php echo e(translate('applications.status_rejected')); ?></option>
                    </select>
                </div>
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(translate('applications.search_label')); ?></label>
                    <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                           placeholder="<?php echo e(translate('applications.search_placeholder')); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-search mr-2"></i><?php echo e(translate('applications.search_button')); ?>

                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Список заявок -->
    <?php if($applications->count() > 0): ?>
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Десктопная таблица -->
        <div class="hidden lg:block">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('applications.table_route')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('applications.table_driver')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('applications.table_status')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('applications.table_submitted')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(translate('applications.table_actions')); ?>

                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo e($application->cargo->from_location); ?> → <?php echo e($application->cargo->to_location); ?>

                                </div>
                                <div class="text-sm text-gray-500"><?php echo e($application->cargo->cargo_type); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($application->driver->name); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($application->driver->email); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($application->isPending()): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        <?php echo e(translate('applications.status_pending_short')); ?>

                                    </span>
                                <?php elseif($application->isApproved()): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        <?php echo e(translate('applications.status_approved_short')); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>
                                        <?php echo e(translate('applications.status_rejected_short')); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e($application->created_at->format('d.m.Y H:i')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('cargo.applications.show-from-cargo', $application)); ?>" 
                                       class="text-blue-600 hover:text-blue-900 transition duration-200">
                                        <i class="fas fa-eye mr-1"></i>
                                        <?php echo e(translate('applications.view_details')); ?>

                                    </a>
                                    <?php if($application->isPending()): ?>
                                    <form action="<?php echo e(route('applications.approve', $application)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900 transition duration-200"
                                                onclick="return confirm('<?php echo e(translate('applications.confirm_approve')); ?>')">
                                            <i class="fas fa-check mr-1"></i>
                                            <?php echo e(translate('applications.approve_button')); ?>

                                        </button>
                                    </form>
                                    <form action="<?php echo e(route('applications.reject', $application)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition duration-200"
                                                onclick="return confirm('<?php echo e(translate('applications.confirm_reject')); ?>')">
                                            <i class="fas fa-times mr-1"></i>
                                            <?php echo e(translate('applications.reject_button')); ?>

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
            <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <?php echo e($application->cargo->from_location); ?> → <?php echo e($application->cargo->to_location); ?>

                        </h3>
                        <p class="text-sm text-gray-600"><?php echo e($application->cargo->cargo_type); ?></p>
                        <p class="text-sm text-gray-500 mt-1"><?php echo e(translate('applications.driver_label')); ?> <?php echo e($application->driver->name); ?></p>
                    </div>
                    <?php if($application->isPending()): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>
                            <?php echo e(translate('applications.status_pending_short')); ?>

                        </span>
                    <?php elseif($application->isApproved()): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>
                            <?php echo e(translate('applications.status_approved_short')); ?>

                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times mr-1"></i>
                            <?php echo e(translate('applications.status_rejected_short')); ?>

                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                    <div>
                        <span class="text-gray-500"><?php echo e(translate('applications.submitted_label')); ?></span>
                        <span class="font-medium"><?php echo e($application->created_at->format('d.m.Y H:i')); ?></span>
                    </div>
                </div>

                <div class="flex justify-center space-x-2">
                    <a href="<?php echo e(route('cargo.applications.show-from-cargo', $application)); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        <?php echo e(translate('applications.view_details')); ?>

                    </a>
                    
                    <?php if($application->isPending()): ?>
                    <form action="<?php echo e(route('applications.approve', $application)); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200"
                                onclick="return confirm('<?php echo e(translate('applications.confirm_approve')); ?>')">
                            <i class="fas fa-check mr-2"></i>
                            <?php echo e(translate('applications.approve_button')); ?>

                        </button>
                    </form>
                    
                    <form action="<?php echo e(route('applications.reject', $application)); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200"
                                onclick="return confirm('<?php echo e(translate('applications.confirm_reject')); ?>')">
                            <i class="fas fa-times mr-2"></i>
                            <?php echo e(translate('applications.reject_button')); ?>

                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Пагинация -->
    <?php if($applications->hasPages()): ?>
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-center">
            <?php echo e($applications->links()); ?>

        </div>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <!-- Если нет заявок -->
    <div class="bg-white shadow rounded-lg p-12 text-center">
        <div class="text-gray-400 mb-4">
            <i class="fas fa-clipboard-list text-6xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-600 mb-2"><?php echo e(translate('applications.no_applications')); ?></h3>
        <p class="text-gray-500"><?php echo e(translate('applications.no_applications_desc')); ?></p>
    </div>
    <?php endif; ?>

    <!-- Навигация -->
    <div class="mt-8 pt-6 border-t border-gray-200">
        <button type="button" 
                onclick="history.back()"
                class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            <?php echo e(translate('applications.back_button')); ?>

        </button>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/zhandosbaukei/Desktop/projects/silk-way/silk-way-app/resources/views/applications/index.blade.php ENDPATH**/ ?>