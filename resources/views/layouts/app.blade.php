<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Silk Way - ' . \App\Helpers\LocalizationHelper::t('header.footer_text'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen {{ auth()->check() ? (auth()->user()->isDriver() ? 'driver-user' : 'admin-warehouse-user') : '' }}">
    <!-- Навигация -->
    @auth
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
                            @if(auth()->user()->isAdmin())
                            <!-- Выпадающее меню администратора -->
                            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                <button @click="open = !open"
                                        class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center {{ request()->routeIs('admin.*') || request()->routeIs('cities.*') ? 'bg-blue-800' : '' }}">
                                    <i class="fas fa-shield-alt mr-2"></i>
                                    {{ \App\Helpers\LocalizationHelper::t('header.admin_panel') }}
                                    <i class="fas fa-chevron-down ml-2 text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-transition
                                     class="absolute left-0 mt-1 w-52 bg-white rounded-md shadow-lg z-50 py-1">
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 font-semibold' : '' }}">
                                        <i class="fas fa-tachometer-alt mr-3 text-blue-500 w-4"></i> Дашборд
                                    </a>
                                    <a href="{{ route('admin.users') }}"
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.users') ? 'bg-gray-100 font-semibold' : '' }}">
                                        <i class="fas fa-users mr-3 text-green-500 w-4"></i> {{ \App\Helpers\LocalizationHelper::t('header.users') }}
                                    </a>
                                    <a href="{{ route('cities.index') }}"
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('cities.*') ? 'bg-gray-100 font-semibold' : '' }}">
                                        <i class="fas fa-city mr-3 text-purple-500 w-4"></i> Города
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <a href="{{ route('admin.translations.index') }}"
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.translations.*') ? 'bg-gray-100 font-semibold' : '' }}">
                                        <i class="fas fa-language mr-3 text-yellow-500 w-4"></i> {{ \App\Helpers\LocalizationHelper::t('admin.translations') }}
                                    </a>
                                </div>
                            </div>
                            @endif
                            <a href="{{ route('cargo.index') }}" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ (request()->routeIs('cargo.index') || request()->routeIs('cargo.edit') || request()->routeIs('cargo.create') || request()->routeIs('cargo.show') || request()->routeIs('cargo.applications.*') || (request()->routeIs('applications.*') && !auth()->user()->isDriver())) && !request()->routeIs('cargo.my-cargo') ? 'bg-blue-800' : '' }}">
                                <i class="fas fa-box mr-2"></i>{{ \App\Helpers\LocalizationHelper::t('header.cargo') }}
                            </a>
                            @if(auth()->user()->isAdmin() || auth()->user()->isWarehouseEmployee())
                            <a href="{{ route('cargo.create') }}" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-plus mr-2"></i>{{ \App\Helpers\LocalizationHelper::t('header.add_cargo') }}
                            </a>
                            <a href="{{ route('applications.index') }}" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('applications.*') && !auth()->user()->isDriver() ? 'bg-blue-800' : '' }}">
                                <i class="fas fa-clipboard-list mr-2"></i>{{ \App\Helpers\LocalizationHelper::t('header.applications') }}
                            </a>
                            @endif
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('cars.all') }}" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-car mr-2"></i>{{ \App\Helpers\LocalizationHelper::t('header.all_cars') }}
                            </a>
                            @endif
                            @if(auth()->user()->isDriver())
                            <a href="{{ route('cargo.my-cargo') }}" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('cargo.my-cargo') || request()->routeIs('my-cargo.applications.*') ? 'bg-blue-800' : '' }}">
                                <i class="fas fa-truck mr-2"></i>{{ \App\Helpers\LocalizationHelper::t('header.my_cargo') }}
                            </a>
                            <a href="{{ route('applications.my-applications') }}" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('applications.my-applications') ? 'bg-blue-800' : '' }}">
                                <i class="fas fa-clipboard-list mr-2"></i>{{ \App\Helpers\LocalizationHelper::t('header.my_applications') }}
                            </a>
                            <a href="{{ route('cars.my-cars') }}" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('cars.*') || request()->routeIs('cars.show') || request()->routeIs('cars.edit') || request()->routeIs('cars.create') ? 'bg-blue-800' : '' }}">
                                <i class="fas fa-car mr-2"></i>{{ \App\Helpers\LocalizationHelper::t('header.my_cars') }}
                            </a>
                            @endif
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
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'ru']) }}" 
                           class="text-white hover:bg-blue-700 px-2 py-1 rounded text-sm font-medium transition duration-200 {{ app()->getLocale() === 'ru' ? 'bg-blue-800' : '' }}">
                            RU
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'kz']) }}" 
                           class="text-white hover:bg-blue-700 px-2 py-1 rounded text-sm font-medium transition duration-200 {{ app()->getLocale() === 'kz' ? 'bg-blue-800' : '' }}">
                            KK
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'cn']) }}" 
                           class="text-white hover:bg-blue-700 px-2 py-1 rounded text-sm font-medium transition duration-200 {{ app()->getLocale() === 'cn' ? 'bg-blue-800' : '' }}">
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
                                {{ user_role_name() }}
                            </span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                                    <i class="fas fa-sign-out-alt mr-2"></i><span class="hidden sm:inline">{{ \App\Helpers\LocalizationHelper::t('header.logout') }}</span>
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
                @if(auth()->user()->isAdmin())
                <div class="border-b border-blue-500 pb-2 mb-2">
                    <p class="text-blue-200 text-xs font-semibold uppercase px-3 py-1 tracking-wider">Администрирование</p>
                    <a href="{{ route('admin.dashboard') }}" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-tachometer-alt mr-2"></i>Дашборд
                    </a>
                    <a href="{{ route('admin.users') }}" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 {{ request()->routeIs('admin.users') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-users mr-2"></i>{{ \App\Helpers\LocalizationHelper::t('header.users') }}
                    </a>
                    <a href="{{ route('cities.index') }}" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 {{ request()->routeIs('cities.*') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-city mr-2"></i>Города
                    </a>
                    <a href="{{ route('admin.translations.index') }}" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 {{ request()->routeIs('admin.translations.*') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-language mr-2"></i>{{ \App\Helpers\LocalizationHelper::t('admin.translations') }}
                    </a>
                </div>
                @endif
                <a href="{{ route('cargo.index') }}" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 {{ (request()->routeIs('cargo.index') || request()->routeIs('cargo.edit') || request()->routeIs('cargo.create') || request()->routeIs('cargo.show') || request()->routeIs('cargo.applications.*') || (request()->routeIs('applications.*') && !auth()->user()->isDriver())) && !request()->routeIs('cargo.my-cargo') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-box mr-2"></i>{{ \App\Helpers\LocalizationHelper::t('header.cargo') }}
                </a>
                @if(auth()->user()->isAdmin() || auth()->user()->isWarehouseEmployee())
                <a href="{{ route('cargo.create') }}" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200">
                    <i class="fas fa-plus mr-2"></i>{{ \App\Helpers\LocalizationHelper::t('header.add_cargo') }}
                </a>
                <a href="{{ route('applications.index') }}" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 {{ request()->routeIs('applications.*') && !auth()->user()->isDriver() ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-clipboard-list mr-2"></i>{{ \App\Helpers\LocalizationHelper::t('header.applications') }}
                </a>
                @endif
                @if(auth()->user()->isAdmin())
                <a href="{{ route('cars.all') }}" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 {{ request()->routeIs('cars.*') || request()->routeIs('cars.show') || request()->routeIs('cars.edit') || request()->routeIs('cars.create') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-car mr-2"></i>{{ \App\Helpers\LocalizationHelper::t('header.all_cars') }}
                </a>
                @endif
                @if(auth()->user()->isDriver())
                <a href="{{ route('cargo.my-cargo') }}" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 {{ request()->routeIs('cargo.my-cargo') || request()->routeIs('my-cargo.applications.*') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-truck mr-2"></i>{{ \App\Helpers\LocalizationHelper::t('header.my_cargo') }}
                </a>
                <a href="{{ route('applications.my-applications') }}" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 {{ request()->routeIs('applications.my-applications') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-clipboard-list mr-2"></i>{{ \App\Helpers\LocalizationHelper::t('header.my_applications') }}
                </a>
                <a href="{{ route('cars.my-cars') }}" class="text-white hover:bg-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 {{ request()->routeIs('cars.*') || request()->routeIs('cars.show') || request()->routeIs('cars.edit') || request()->routeIs('cars.create') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-car mr-2"></i>{{ \App\Helpers\LocalizationHelper::t('header.my_cars') }}
                </a>
                @endif
            </div>
        </div>
    </nav>
    @endauth

    <!-- Боковая панель администратора (справа) -->
    @auth
    @if(auth()->user()->isAdmin())
    <div x-data="{ open: false }" class="fixed right-0 top-1/2 -translate-y-1/2 z-40 flex items-center">
        <!-- Кнопка открытия -->
        <button @click="open = !open"
                class="bg-blue-600 hover:bg-blue-700 text-white w-6 h-16 rounded-l-lg shadow-lg flex items-center justify-center transition-all duration-300"
                :title="open ? 'Скрыть' : 'Меню администратора'">
            <i class="fas fa-chevron-left text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
        </button>

        <!-- Панель с иконками -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-4"
             class="bg-white shadow-xl rounded-l-xl border border-gray-200 py-3 flex flex-col items-center gap-1 min-w-[56px]">

            <a href="{{ route('admin.dashboard') }}"
               title="Дашборд"
               class="flex flex-col items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                <i class="fas fa-tachometer-alt text-lg"></i>
                <span class="text-[10px] mt-1">Дашборд</span>
            </a>

            <a href="{{ route('admin.users') }}"
               title="Пользователи"
               class="flex flex-col items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.users') ? 'text-green-600 bg-green-50' : 'text-gray-500 hover:text-green-600 hover:bg-gray-50' }}">
                <i class="fas fa-users text-lg"></i>
                <span class="text-[10px] mt-1">Юзеры</span>
            </a>

            <a href="{{ route('cargo.index') }}"
               title="Грузы"
               class="flex flex-col items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('cargo.*') && !request()->routeIs('cargo.my-cargo') ? 'text-blue-600 bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                <i class="fas fa-box text-lg"></i>
                <span class="text-[10px] mt-1">Грузы</span>
            </a>

            <a href="{{ route('applications.index') }}"
               title="Заявки"
               class="flex flex-col items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('applications.*') ? 'text-yellow-600 bg-yellow-50' : 'text-gray-500 hover:text-yellow-600 hover:bg-gray-50' }}">
                <i class="fas fa-clipboard-list text-lg"></i>
                <span class="text-[10px] mt-1">Заявки</span>
            </a>

            <a href="{{ route('cars.all') }}"
               title="Машины"
               class="flex flex-col items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('cars.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-500 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="fas fa-car text-lg"></i>
                <span class="text-[10px] mt-1">Машины</span>
            </a>

            <a href="{{ route('cities.index') }}"
               title="Города"
               class="flex flex-col items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('cities.*') ? 'text-purple-600 bg-purple-50' : 'text-gray-500 hover:text-purple-600 hover:bg-gray-50' }}">
                <i class="fas fa-city text-lg"></i>
                <span class="text-[10px] mt-1">Города</span>
            </a>

            <div class="w-8 border-t border-gray-200 my-1"></div>

            <a href="{{ route('admin.translations.index') }}"
               title="Переводы"
               class="flex flex-col items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.translations.*') ? 'text-orange-600 bg-orange-50' : 'text-gray-500 hover:text-orange-600 hover:bg-gray-50' }}">
                <i class="fas fa-language text-lg"></i>
                <span class="text-[10px] mt-1">Переводы</span>
            </a>
        </div>
    </div>
    @endif
    @endauth

    <!-- Основной контент -->
    <main class="max-w-7xl mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Нижняя навигационная панель (только для авторизованных пользователей) -->
    @auth
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-50 md:hidden">
        <div class="flex justify-around items-center py-2">
            <!-- Кнопка "Грузы" -->
            <div class="bottom-nav-item">
                <a href="{{ route('cargo.index') }}" 
                   class="flex flex-col items-center py-2 px-3 rounded-lg transition-colors duration-200 {{ (request()->routeIs('cargo.index') || request()->routeIs('cargo.edit') || request()->routeIs('cargo.create') || request()->routeIs('cargo.show') || request()->routeIs('cargo.applications.*') || (request()->routeIs('applications.*') && !auth()->user()->isDriver())) && !request()->routeIs('cargo.my-cargo') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600' }}">
                    <i class="fas fa-box text-xl mb-1"></i>
                    <span class="text-xs font-medium">{{ \App\Helpers\LocalizationHelper::t('header.cargo') }}</span>
                </a>
            </div>

            <!-- Кнопка "Машины" (только для админов и водителей) -->
            @if(auth()->user()->isAdmin() || auth()->user()->isDriver())
            <div class="bottom-nav-item">
                <a href="{{ auth()->user()->isDriver() ? route('cars.my-cars') : route('cars.all') }}" 
                   class="flex flex-col items-center py-2 px-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('cars.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600' }}">
                    <i class="fas fa-car text-xl mb-1"></i>
                    <span class="text-xs font-medium">{{ auth()->user()->isDriver() ? \App\Helpers\LocalizationHelper::t('header.my_cars') : \App\Helpers\LocalizationHelper::t('header.cars') }}</span>
                </a>
            </div>
            @endif

            <!-- Кнопка "Мои грузы" (только для водителей) -->
            @if(auth()->user()->isDriver())
            <div class="bottom-nav-item">
                <a href="{{ route('cargo.my-cargo') }}" 
                   class="flex flex-col items-center py-2 px-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('cargo.my-cargo') || request()->routeIs('my-cargo.applications.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600' }}">
                    <i class="fas fa-truck text-xl mb-1"></i>
                    <span class="text-xs font-medium">{{ \App\Helpers\LocalizationHelper::t('header.my_cargo') }}</span>
                </a>
            </div>
            @endif

            <!-- Кнопка "Мои заявки" (только для водителей) -->
            @if(auth()->user()->isDriver())
            <div class="bottom-nav-item">
                <a href="{{ route('applications.my-applications') }}" 
                   class="flex flex-col items-center py-2 px-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('applications.my-applications') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600' }}">
                    <i class="fas fa-clipboard-list text-xl mb-1"></i>
                    <span class="text-xs font-medium">{{ \App\Helpers\LocalizationHelper::t('header.applications') }}</span>
                </a>
            </div>
            @endif

            <!-- Кнопка "Профиль" -->
            <div class="bottom-nav-item">
                <div class="flex flex-col items-center py-2 px-3 rounded-lg transition-colors duration-200 text-gray-600 hover:text-blue-600">
                    <i class="fas fa-user text-xl mb-1"></i>
                    <span class="text-xs font-medium">{{ \App\Helpers\LocalizationHelper::t('header.profile') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Отступ для нижней навигации на мобильных устройствах -->
    <div class="h-20 md:hidden"></div>
    @endauth

    <!-- Футер -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p>&copy; 2026 Silk Way. {{ \App\Helpers\LocalizationHelper::t('header.footer_text') }}</p>
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
</html> 