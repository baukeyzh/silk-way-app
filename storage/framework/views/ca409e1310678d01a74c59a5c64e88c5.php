<?php $__env->startSection('title', \App\Helpers\LocalizationHelper::t('auth.register_title') . ' - Silk Way'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-blue-100">
                <i class="fas fa-user-plus text-blue-600 text-2xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                <?php echo e(\App\Helpers\LocalizationHelper::t('auth.register_heading')); ?>

            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                <?php echo e(\App\Helpers\LocalizationHelper::t('auth.register_desc')); ?>

            </p>
            
            <!-- Переключатель языков -->
            <div class="mt-4 flex justify-center">
                <div class="flex items-center space-x-2">
                    <a href="<?php echo e(request()->fullUrlWithQuery(['lang' => 'rus'])); ?>" 
                       class="px-3 py-1 rounded text-sm font-medium transition duration-200 <?php echo e(app()->getLocale() === 'rus' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'); ?>">
                        RU
                    </a>
                    <a href="<?php echo e(request()->fullUrlWithQuery(['lang' => 'kaz'])); ?>" 
                       class="px-3 py-1 rounded text-sm font-medium transition duration-200 <?php echo e(app()->getLocale() === 'kaz' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'); ?>">
                        KK
                    </a>
                    <a href="<?php echo e(request()->fullUrlWithQuery(['lang' => 'chn'])); ?>" 
                       class="px-3 py-1 rounded text-sm font-medium transition duration-200 <?php echo e(app()->getLocale() === 'chn' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'); ?>">
                        中
                    </a>
                </div>
            </div>
        </div>
        <form class="mt-8 space-y-6" action="<?php echo e(route('register')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(\App\Helpers\LocalizationHelper::t('auth.full_name')); ?></label>
                    <input id="name" name="name" type="text" required 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="<?php echo e(\App\Helpers\LocalizationHelper::t('auth.full_name_placeholder')); ?>"
                           value="<?php echo e(old('name')); ?>">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(\App\Helpers\LocalizationHelper::t('auth.email')); ?></label>
                    <input id="email" name="email" type="email" required 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="<?php echo e(\App\Helpers\LocalizationHelper::t('auth.email_placeholder')); ?>"
                           value="<?php echo e(old('email')); ?>">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(\App\Helpers\LocalizationHelper::t('auth.password')); ?></label>
                    <input id="password" name="password" type="password" required 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="<?php echo e(\App\Helpers\LocalizationHelper::t('auth.password_placeholder')); ?>">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(\App\Helpers\LocalizationHelper::t('auth.password_confirmation')); ?></label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="<?php echo e(\App\Helpers\LocalizationHelper::t('auth.password_confirmation_placeholder')); ?>">
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(\App\Helpers\LocalizationHelper::t('auth.role')); ?></label>
                    <select id="role" name="role" required 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value=""><?php echo e(\App\Helpers\LocalizationHelper::t('auth.select_role')); ?></option>
                        <option value="warehouse_employee" <?php echo e(old('role') == 'warehouse_employee' ? 'selected' : ''); ?>>
                            <?php echo e(\App\Helpers\LocalizationHelper::t('auth.warehouse_employee')); ?>

                        </option>
                        <option value="driver" <?php echo e(old('role') == 'driver' ? 'selected' : ''); ?>>
                            <?php echo e(\App\Helpers\LocalizationHelper::t('auth.driver_role')); ?>

                        </option>
                    </select>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-user-plus"></i>
                    </span>
                    <?php echo e(\App\Helpers\LocalizationHelper::t('auth.register_button')); ?>

                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    <?php echo e(\App\Helpers\LocalizationHelper::t('auth.have_account')); ?> 
                    <a href="<?php echo e(route('login')); ?>" class="font-medium text-blue-600 hover:text-blue-500 transition duration-200">
                        <?php echo e(\App\Helpers\LocalizationHelper::t('auth.login_link')); ?>

                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/zhandosbaukei/Desktop/projects/silk-way/silk-way-app/resources/views/auth/register.blade.php ENDPATH**/ ?>