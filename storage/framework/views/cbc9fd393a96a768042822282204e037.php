<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Silk Way - ' . \App\Helpers\LocalizationHelper::t('header.footer_text')); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen <?php echo e(auth()->check() ? (auth()->user()->isDriver() ? 'driver-user' : 'admin-warehouse-user') : ''); ?>">
    <!-- Навигация -->
    <?php if(auth()->guard()->check()): ?>
    <nav class="bg-blue-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-white text-xl font-bold">Silk Way</h1>
                    </div>
                    <!-- Десктопное меню -->
                    <div class="hidden md:block ml-10">
                        <div class="flex items-baseline space-x-4">
                            <?php if(auth()->user()->isAdmin()): ?>
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-cog mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.admin_panel')); ?>

                            </a>
                            <a href="<?php echo e(route('admin.users')); ?>" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-users mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.users')); ?>

                            </a>
                            <a href="<?php echo e(route('admin.translations.index')); ?>" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-language mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('admin.translations')); ?>

                            </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('cargo.index')); ?>" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200 <?php echo e((request()->routeIs('cargo.index') || request()->routeIs('cargo.edit') || request()->routeIs('cargo.create') || request()->routeIs('cargo.show') || request()->routeIs('cargo.applications.*') || (request()->routeIs('applications.*') && !auth()->user()->isDriver())) && !request()->routeIs('cargo.my-cargo') ? 'bg-blue-800' : ''); ?>">
                                <i class="fas fa-box mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.cargo')); ?>

                            </a>
                            <?php if(auth()->user()->isAdmin() || auth()->user()->isWarehouseEmployee()): ?>
                            <a href="<?php echo e(route('cargo.create')); ?>" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-plus mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.add_cargo')); ?>

                            </a>
                            <a href="<?php echo e(route('applications.index')); ?>" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200 <?php echo e(request()->routeIs('applications.*') && !auth()->user()->isDriver() ? 'bg-blue-800' : ''); ?>">
                                <i class="fas fa-clipboard-list mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.applications')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if(auth()->user()->isAdmin()): ?>
                            <a href="<?php echo e(route('cars.all')); ?>" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-car mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.all_cars')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if(auth()->user()->isDriver()): ?>
                            <a href="<?php echo e(route('cargo.my-cargo')); ?>" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200 <?php echo e(request()->routeIs('cargo.my-cargo') || request()->routeIs('my-cargo.applications.*') ? 'bg-blue-800' : ''); ?>">
                                <i class="fas fa-truck mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.my_cargo')); ?>

                            </a>
                            <a href="<?php echo e(route('applications.my-applications')); ?>" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200 <?php echo e(request()->routeIs('applications.my-applications') ? 'bg-blue-800' : ''); ?>">
                                <i class="fas fa-clipboard-list mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.my_applications')); ?>

                            </a>
                            <a href="<?php echo e(route('cars.my-cars')); ?>" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200 <?php echo e(request()->routeIs('cars.*') || request()->routeIs('cars.show') || request()->routeIs('cars.edit') || request()->routeIs('cars.create') ? 'bg-blue-800' : ''); ?>">
                                <i class="fas fa-car mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.my_cars')); ?>

                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Мобильное меню кнопка -->
                <div class="md:hidden flex items-center">
                    <button type="button" 
                            onclick="toggleMobileMenu()"
                            class="text-white hover:bg-blue-700 p-2 rounded-md transition duration-200">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

                <!-- Переключатель языков -->
                <div class="flex items-center mr-4">
                    <div class="flex items-center space-x-2">
                        <a href="<?php echo e(request()->fullUrlWithQuery(['lang' => 'rus'])); ?>" 
                           class="text-white hover:bg-blue-700 px-2 py-1 rounded text-sm font-medium transition duration-200 <?php echo e(app()->getLocale() === 'rus' ? 'bg-blue-800' : ''); ?>">
                            RU
                        </a>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['lang' => 'kaz'])); ?>" 
                           class="text-white hover:bg-blue-700 px-2 py-1 rounded text-sm font-medium transition duration-200 <?php echo e(app()->getLocale() === 'kaz' ? 'bg-blue-800' : ''); ?>">
                            KK
                        </a>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['lang' => 'chn'])); ?>" 
                           class="text-white hover:bg-blue-700 px-2 py-1 rounded text-sm font-medium transition duration-200 <?php echo e(app()->getLocale() === 'chn' ? 'bg-blue-800' : ''); ?>">
                            中
                        </a>
                    </div>
                </div>

                <!-- Пользователь и выход -->
                <div class="flex items-center">
                    <div class="ml-3 relative">
                        <div class="flex items-center space-x-4">
                            <span class="text-white text-sm hidden sm:block">
                                <i class="fas fa-user mr-2"></i>
                                <?php echo e(user_role_name()); ?>

                            </span>
                            <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                                    <i class="fas fa-sign-out-alt mr-2"></i><span class="hidden sm:inline"><?php echo e(\App\Helpers\LocalizationHelper::t('header.logout')); ?></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Мобильное меню -->
        <div id="mobileMenu" class="md:hidden hidden bg-blue-700">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <?php if(auth()->user()->isAdmin()): ?>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200">
                    <i class="fas fa-cog mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.admin_panel')); ?>

                </a>
                <a href="<?php echo e(route('admin.users')); ?>" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200">
                    <i class="fas fa-users mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.users')); ?>

                </a>
                <a href="<?php echo e(route('admin.translations.index')); ?>" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200">
                    <i class="fas fa-language mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('admin.translations')); ?>

                </a>
                <?php endif; ?>
                <a href="<?php echo e(route('cargo.index')); ?>" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 <?php echo e((request()->routeIs('cargo.index') || request()->routeIs('cargo.edit') || request()->routeIs('cargo.create') || request()->routeIs('cargo.show') || request()->routeIs('cargo.applications.*') || (request()->routeIs('applications.*') && !auth()->user()->isDriver())) && !request()->routeIs('cargo.my-cargo') ? 'bg-blue-600' : ''); ?>">
                    <i class="fas fa-box mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.cargo')); ?>

                </a>
                <?php if(auth()->user()->isAdmin() || auth()->user()->isWarehouseEmployee()): ?>
                <a href="<?php echo e(route('cargo.create')); ?>" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200">
                    <i class="fas fa-plus mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.add_cargo')); ?>

                </a>
                <a href="<?php echo e(route('applications.index')); ?>" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 <?php echo e(request()->routeIs('applications.*') && !auth()->user()->isDriver() ? 'bg-blue-600' : ''); ?>">
                    <i class="fas fa-clipboard-list mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.applications')); ?>

                </a>
                <?php endif; ?>
                <?php if(auth()->user()->isAdmin()): ?>
                <a href="<?php echo e(route('cars.all')); ?>" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 <?php echo e(request()->routeIs('cars.*') || request()->routeIs('cars.show') || request()->routeIs('cars.edit') || request()->routeIs('cars.create') ? 'bg-blue-600' : ''); ?>">
                    <i class="fas fa-car mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.all_cars')); ?>

                </a>
                <?php endif; ?>
                <?php if(auth()->user()->isDriver()): ?>
                <a href="<?php echo e(route('cargo.my-cargo')); ?>" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 <?php echo e(request()->routeIs('cargo.my-cargo') || request()->routeIs('my-cargo.applications.*') ? 'bg-blue-600' : ''); ?>">
                    <i class="fas fa-truck mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.my_cargo')); ?>

                </a>
                <a href="<?php echo e(route('applications.my-applications')); ?>" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 <?php echo e(request()->routeIs('applications.my-applications') ? 'bg-blue-600' : ''); ?>">
                    <i class="fas fa-clipboard-list mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.my_applications')); ?>

                </a>
                <a href="<?php echo e(route('cars.my-cars')); ?>" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 <?php echo e(request()->routeIs('cars.*') || request()->routeIs('cars.show') || request()->routeIs('cars.edit') || request()->routeIs('cars.create') ? 'bg-blue-600' : ''); ?>">
                    <i class="fas fa-car mr-2"></i><?php echo e(\App\Helpers\LocalizationHelper::t('header.my_cars')); ?>

                </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Основной контент -->
    <main class="max-w-7xl mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8">
        <?php if(session('success')): ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <i class="fas fa-check-circle mr-2"></i>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Нижняя навигационная панель (только для авторизованных пользователей) -->
    <?php if(auth()->guard()->check()): ?>
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-50 md:hidden">
        <div class="flex justify-around items-center py-2">
            <!-- Кнопка "Грузы" -->
            <div class="bottom-nav-item">
                <a href="<?php echo e(route('cargo.index')); ?>" 
                   class="flex flex-col items-center py-2 px-3 rounded-lg transition-colors duration-200 <?php echo e((request()->routeIs('cargo.index') || request()->routeIs('cargo.edit') || request()->routeIs('cargo.create') || request()->routeIs('cargo.show') || request()->routeIs('cargo.applications.*') || (request()->routeIs('applications.*') && !auth()->user()->isDriver())) && !request()->routeIs('cargo.my-cargo') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600'); ?>">
                    <i class="fas fa-box text-xl mb-1"></i>
                    <span class="text-xs font-medium"><?php echo e(\App\Helpers\LocalizationHelper::t('header.cargo')); ?></span>
                </a>
            </div>

            <!-- Кнопка "Машины" (только для админов и водителей) -->
            <?php if(auth()->user()->isAdmin() || auth()->user()->isDriver()): ?>
            <div class="bottom-nav-item">
                <a href="<?php echo e(auth()->user()->isDriver() ? route('cars.my-cars') : route('cars.all')); ?>" 
                   class="flex flex-col items-center py-2 px-3 rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('cars.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600'); ?>">
                    <i class="fas fa-car text-xl mb-1"></i>
                    <span class="text-xs font-medium"><?php echo e(auth()->user()->isDriver() ? \App\Helpers\LocalizationHelper::t('header.my_cars') : \App\Helpers\LocalizationHelper::t('header.cars')); ?></span>
                </a>
            </div>
            <?php endif; ?>

            <!-- Кнопка "Мои грузы" (только для водителей) -->
            <?php if(auth()->user()->isDriver()): ?>
            <div class="bottom-nav-item">
                <a href="<?php echo e(route('cargo.my-cargo')); ?>" 
                   class="flex flex-col items-center py-2 px-3 rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('cargo.my-cargo') || request()->routeIs('my-cargo.applications.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600'); ?>">
                    <i class="fas fa-truck text-xl mb-1"></i>
                    <span class="text-xs font-medium"><?php echo e(\App\Helpers\LocalizationHelper::t('header.my_cargo')); ?></span>
                </a>
            </div>
            <?php endif; ?>

            <!-- Кнопка "Мои заявки" (только для водителей) -->
            <?php if(auth()->user()->isDriver()): ?>
            <div class="bottom-nav-item">
                <a href="<?php echo e(route('applications.my-applications')); ?>" 
                   class="flex flex-col items-center py-2 px-3 rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('applications.my-applications') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600'); ?>">
                    <i class="fas fa-clipboard-list text-xl mb-1"></i>
                    <span class="text-xs font-medium"><?php echo e(\App\Helpers\LocalizationHelper::t('header.applications')); ?></span>
                </a>
            </div>
            <?php endif; ?>

            <!-- Кнопка "Профиль" -->
            <div class="bottom-nav-item">
                <div class="flex flex-col items-center py-2 px-3 rounded-lg transition-colors duration-200 text-gray-600 hover:text-blue-600">
                    <i class="fas fa-user text-xl mb-1"></i>
                    <span class="text-xs font-medium"><?php echo e(\App\Helpers\LocalizationHelper::t('header.profile')); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Отступ для нижней навигации на мобильных устройствах -->
    <div class="h-20 md:hidden"></div>
    <?php endif; ?>

    <!-- Футер -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p>&copy; 2024 Silk Way. <?php echo e(\App\Helpers\LocalizationHelper::t('header.footer_text')); ?></p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Закрыть мобильное меню при клике вне его
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobileMenu');
            const toggleButton = event.target.closest('button');
            
            if (!menu.contains(event.target) && !toggleButton) {
                menu.classList.add('hidden');
            }
        });

        // Функция для определения активной страницы в нижней навигации
        function setActiveBottomNav() {
            // Отключаем автоматическое определение активных страниц, так как теперь это делается через Blade
            // const currentPath = window.location.pathname;
            // const bottomNavItems = document.querySelectorAll('.bottom-nav-item');
            
            // bottomNavItems.forEach(item => {
            //     const link = item.querySelector('a');
            //     if (link) {
            //         const href = link.getAttribute('href');
            //         // Проверяем активность для грузов и машин (включая просмотр, редактирование и т.д.)
            //         if (href) {
            //             let isActive = false;
                            
            //             // Для грузов
            //             if (href.includes('cargo')) {
            //                 if (href.includes('my-cargo')) {
            //                     // Кнопка "Мои грузы" активна для страниц водителя и заявок (только для водителей)
            //                     isActive = currentPath.includes('cargo') && (currentPath.includes('my-cargo') || currentPath.includes('show')) || 
            //                              (currentPath.includes('applications') && document.body.classList.contains('driver-user'));
            //                 } else {
            //                     // Кнопка "Грузы" активна для основных страниц грузов, заявок (но не для водителей), но не для "мои грузы"
            //                     isActive = (currentPath.includes('cargo') && !currentPath.includes('my-cargo')) || 
            //                              (currentPath.includes('applications') && !document.body.classList.contains('driver-user'));
            //                 }
            //             }
            //             // Для машин
            //             else if (href.includes('cars')) {
            //                 isActive = currentPath.includes('cars');
            //             }
                            
            //             if (isActive) {
            //                 item.classList.add('text-blue-600', 'bg-blue-50');
            //                 item.classList.remove('text-gray-600');
            //             } else {
            //                 item.classList.remove('text-blue-600', 'bg-blue-50');
            //                 item.classList.add('text-gray-600');
            //             }
            //         }
            //     }
            // });
        }

        // Вызываем функцию при загрузке страницы (отключено, так как активность определяется через Blade)
        // document.addEventListener('DOMContentLoaded', setActiveBottomNav);
    </script>
</body>
</html> <?php /**PATH /Users/zhandosbaukei/Desktop/projects/silk-way/silk-way-app/resources/views/layouts/app.blade.php ENDPATH**/ ?>