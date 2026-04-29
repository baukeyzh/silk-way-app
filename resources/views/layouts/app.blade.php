<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Silk Way — ' . \App\Helpers\LocalizationHelper::t('header.footer_text'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

@auth
{{-- ===================== SIDEBAR (admin/warehouse only) ===================== --}}
@if(auth()->user()->isAdmin() || auth()->user()->isWarehouseEmployee())
<div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside
        :class="sidebarOpen ? 'w-60' : 'w-16'"
        class="hidden lg:flex flex-col bg-slate-900 transition-all duration-300 ease-in-out shrink-0 fixed top-0 left-0 h-full z-30">

        {{-- Logo area --}}
        <div class="flex items-center h-16 px-4 border-b border-slate-700/50">
            <div class="flex items-center space-x-3 overflow-hidden">
                <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center shrink-0">
                    <i class="fas fa-route text-white text-sm"></i>
                </div>
                <span x-show="sidebarOpen" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="text-white font-bold text-lg tracking-tight whitespace-nowrap">Silk Way</span>
            </div>
        </div>

        {{-- Nav links --}}
        <nav class="flex-1 py-4 space-y-1 overflow-y-auto overflow-x-hidden">

            @if(auth()->user()->isAdmin())
            {{-- Admin section --}}
            <div x-show="sidebarOpen" class="px-4 pb-1">
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">{{ \App\Helpers\LocalizationHelper::t('admin.section_admin') }}</p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center px-4 py-2.5 mx-2 rounded-lg transition-colors group {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-tachometer-alt w-5 text-center shrink-0"></i>
                <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">{{ \App\Helpers\LocalizationHelper::t('admin.dashboard_title') }}</span>
            </a>
            <a href="{{ route('admin.users') }}"
               class="flex items-center px-4 py-2.5 mx-2 rounded-lg transition-colors group {{ request()->routeIs('admin.users') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-users w-5 text-center shrink-0"></i>
                <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">{{ \App\Helpers\LocalizationHelper::t('header.users') }}</span>
            </a>
            <a href="{{ route('cities.index') }}"
               class="flex items-center px-4 py-2.5 mx-2 rounded-lg transition-colors group {{ request()->routeIs('cities.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-city w-5 text-center shrink-0"></i>
                <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">{{ \App\Helpers\LocalizationHelper::t('admin.cities') }}</span>
            </a>
            <a href="{{ route('admin.translations.index') }}"
               class="flex items-center px-4 py-2.5 mx-2 rounded-lg transition-colors group {{ request()->routeIs('admin.translations.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-language w-5 text-center shrink-0"></i>
                <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">{{ \App\Helpers\LocalizationHelper::t('admin.translations') }}</span>
            </a>

            <div x-show="sidebarOpen" class="px-4 pt-4 pb-1">
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">{{ \App\Helpers\LocalizationHelper::t('admin.section_operations') }}</p>
            </div>
            @endif

            <a href="{{ route('cargo.index') }}"
               class="flex items-center px-4 py-2.5 mx-2 rounded-lg transition-colors group {{ (request()->routeIs('cargo.index') || request()->routeIs('cargo.show') || request()->routeIs('cargo.edit') || request()->routeIs('cargo.create')) && !request()->routeIs('cargo.my-cargo') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-box w-5 text-center shrink-0"></i>
                <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">{{ \App\Helpers\LocalizationHelper::t('header.cargo') }}</span>
            </a>

            @if(auth()->user()->isAdmin() || auth()->user()->isWarehouseEmployee())
            <a href="{{ route('cargo.create') }}"
               class="flex items-center px-4 py-2.5 mx-2 rounded-lg transition-colors group text-slate-400 hover:bg-slate-800 hover:text-white">
                <i class="fas fa-plus w-5 text-center shrink-0"></i>
                <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">{{ \App\Helpers\LocalizationHelper::t('header.add_cargo') }}</span>
            </a>
            @php
                $cmrPendingCount = auth()->user()->isAdmin()
                    ? \App\Models\CargoApplication::where('cmr_status', \App\Models\CargoApplication::CMR_STATUS_PENDING_REVIEW)->count()
                    : \App\Models\CargoApplication::where('cmr_status', \App\Models\CargoApplication::CMR_STATUS_PENDING_REVIEW)
                        ->whereHas('cargo', fn ($q) => $q->where('created_by', auth()->id()))
                        ->count();
            @endphp
            <a href="{{ route('applications.index') }}"
               class="relative flex items-center px-4 py-2.5 mx-2 rounded-lg transition-colors group {{ request()->routeIs('applications.*') && !auth()->user()->isDriver() ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-clipboard-list w-5 text-center shrink-0"></i>
                <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">{{ \App\Helpers\LocalizationHelper::t('header.applications') }}</span>
                @if($cmrPendingCount > 0)
                <span x-show="sidebarOpen" class="ml-auto shrink-0 min-w-[20px] h-5 bg-amber-500 text-white text-xs font-bold rounded-full flex items-center justify-center px-1">
                    {{ $cmrPendingCount > 9 ? '9+' : $cmrPendingCount }}
                </span>
                <span x-show="!sidebarOpen" x-cloak class="absolute top-1.5 right-1.5 w-2 h-2 bg-amber-500 rounded-full"></span>
                @endif
            </a>
            @endif

            @if(auth()->user()->isAdmin())
            <a href="{{ route('cars.all') }}"
               class="flex items-center px-4 py-2.5 mx-2 rounded-lg transition-colors group {{ request()->routeIs('cars.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-truck w-5 text-center shrink-0"></i>
                <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">{{ \App\Helpers\LocalizationHelper::t('header.all_cars') }}</span>
            </a>
            <a href="{{ route('admin.documents.index') }}"
               class="flex items-center px-4 py-2.5 mx-2 rounded-lg transition-colors group {{ request()->routeIs('admin.documents.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-id-card w-5 text-center shrink-0"></i>
                <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">{{ \App\Helpers\LocalizationHelper::t('docs.admin_title') }}</span>
            </a>
            @endif
        </nav>

        {{-- Sidebar toggle --}}
        <div class="border-t border-slate-700/50 p-3">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="w-full flex items-center justify-center py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                <i :class="sidebarOpen ? 'fas fa-chevron-left' : 'fas fa-chevron-right'" class="text-xs"></i>
                <span x-show="sidebarOpen" class="ml-2 text-xs font-medium">{{ \App\Helpers\LocalizationHelper::t('admin.collapse') }}</span>
            </button>
        </div>
    </aside>

    {{-- Main column --}}
    <div :class="sidebarOpen ? 'lg:ml-60' : 'lg:ml-16'" class="flex flex-col flex-1 min-w-0 transition-all duration-300 ease-in-out">

        {{-- TOP NAV --}}
        <header class="bg-white border-b border-slate-200 sticky top-0 z-20">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6">
                {{-- Mobile logo --}}
                <div class="flex items-center space-x-3 lg:hidden">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-route text-white text-sm"></i>
                    </div>
                    <span class="font-bold text-slate-900 text-lg">Silk Way</span>
                </div>

                <div class="flex-1 hidden lg:block"></div>

                {{-- Right side actions --}}
                <div class="flex items-center space-x-3">
                    {{-- Language switcher --}}
                    <div class="flex items-center space-x-1 bg-slate-100 rounded-lg p-1">
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'ru']) }}"
                           class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'ru' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">RU</a>
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'kz']) }}"
                           class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'kz' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">KK</a>
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'cn']) }}"
                           class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'cn' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">中</a>
                    </div>

                    {{-- User dropdown --}}
                    <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 group">
                            <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-semibold text-sm">
                                {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1, 'UTF-8'), 'UTF-8') }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-semibold text-slate-800 leading-tight">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 leading-tight">{{ user_role_name() }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-transition x-cloak
                             class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-slate-200 py-1 z-50">
                            <div class="px-4 py-3 border-b border-slate-100">
                                <p class="text-sm font-medium text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                                <span class="mt-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ auth()->user()->isAdmin() ? 'bg-purple-100 text-purple-700' : (auth()->user()->isWarehouseEmployee() ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700') }}">
                                    {{ user_role_name() }}
                                </span>
                            </div>
                            <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <i class="fas fa-user-circle mr-2.5 w-4 text-slate-400"></i>
                                {{ \App\Helpers\LocalizationHelper::t('nav.profile') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition-colors flex items-center">
                                    <i class="fas fa-sign-out-alt mr-2.5 w-4"></i>
                                    {{ \App\Helpers\LocalizationHelper::t('header.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Mobile menu button --}}
                    <button type="button" onclick="toggleMobileMenu()"
                            class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </header>

        {{-- MOBILE MENU --}}
        <div id="mobileMenu" class="lg:hidden hidden bg-slate-900 border-b border-slate-700">
            <div class="px-4 py-4 space-y-1">
                @if(auth()->user()->isAdmin())
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider px-3 pb-2">{{ \App\Helpers\LocalizationHelper::t('admin.section_admin') }}</p>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }} transition-colors">
                    <i class="fas fa-tachometer-alt mr-3 w-4 text-center"></i>{{ \App\Helpers\LocalizationHelper::t('admin.dashboard_title') }}
                </a>
                <a href="{{ route('admin.users') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.users') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }} transition-colors">
                    <i class="fas fa-users mr-3 w-4 text-center"></i>{{ \App\Helpers\LocalizationHelper::t('header.users') }}
                </a>
                <a href="{{ route('cities.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('cities.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }} transition-colors">
                    <i class="fas fa-city mr-3 w-4 text-center"></i>{{ \App\Helpers\LocalizationHelper::t('admin.cities') }}
                </a>
                <a href="{{ route('admin.translations.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.translations.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }} transition-colors">
                    <i class="fas fa-language mr-3 w-4 text-center"></i>{{ \App\Helpers\LocalizationHelper::t('admin.translations') }}
                </a>
                <div class="border-t border-slate-700 my-2"></div>
                @endif

                <a href="{{ route('cargo.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('cargo.index') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }} transition-colors">
                    <i class="fas fa-box mr-3 w-4 text-center"></i>{{ \App\Helpers\LocalizationHelper::t('header.cargo') }}
                </a>
                @if(auth()->user()->isAdmin() || auth()->user()->isWarehouseEmployee())
                <a href="{{ route('cargo.create') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 transition-colors">
                    <i class="fas fa-plus mr-3 w-4 text-center"></i>{{ \App\Helpers\LocalizationHelper::t('header.add_cargo') }}
                </a>
                <a href="{{ route('applications.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('applications.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }} transition-colors">
                    <span class="flex items-center"><i class="fas fa-clipboard-list mr-3 w-4 text-center"></i>{{ \App\Helpers\LocalizationHelper::t('header.applications') }}</span>
                    @if(isset($cmrPendingCount) && $cmrPendingCount > 0)
                    <span class="min-w-[20px] h-5 bg-amber-500 text-white text-xs font-bold rounded-full flex items-center justify-center px-1">
                        {{ $cmrPendingCount > 9 ? '9+' : $cmrPendingCount }}
                    </span>
                    @endif
                </a>
                @endif
                @if(auth()->user()->isAdmin())
                <a href="{{ route('cars.all') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('cars.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }} transition-colors">
                    <i class="fas fa-truck mr-3 w-4 text-center"></i>{{ \App\Helpers\LocalizationHelper::t('header.all_cars') }}
                </a>
                <a href="{{ route('admin.documents.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.documents.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }} transition-colors">
                    <i class="fas fa-id-card mr-3 w-4 text-center"></i>{{ \App\Helpers\LocalizationHelper::t('docs.admin_title') }}
                </a>
                @endif
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 p-4 sm:p-6">
            {{-- Flash messages --}}
            @if(session('success'))
            <div class="mb-5 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl">
                <i class="fas fa-check-circle text-emerald-500 mt-0.5 shrink-0"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-5 flex items-start gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl">
                <i class="fas fa-exclamation-circle text-rose-500 mt-0.5 shrink-0"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
            @endif

            @if(session('warning'))
            <div class="mb-5 flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl">
                <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5 shrink-0"></i>
                <span class="text-sm font-medium">{{ session('warning') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-rose-500 mt-0.5 shrink-0"></i>
                    <ul class="text-sm space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @yield('content')
        </main>

        {{-- Mobile bottom tab bar --}}
        <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 z-20">
            <div class="flex justify-around items-center py-2 px-2">
                <a href="{{ route('cargo.index') }}"
                   class="flex flex-col items-center py-1 px-3 rounded-xl transition-colors {{ request()->routeIs('cargo.index') && !request()->routeIs('cargo.my-cargo') ? 'text-indigo-600' : 'text-slate-400' }}">
                    <i class="fas fa-box text-lg mb-0.5"></i>
                    <span class="text-xs font-medium">{{ \App\Helpers\LocalizationHelper::t('header.cargo') }}</span>
                </a>
                @if(auth()->user()->isAdmin() || auth()->user()->isWarehouseEmployee())
                <a href="{{ route('applications.index') }}"
                   class="relative flex flex-col items-center py-1 px-3 rounded-xl transition-colors {{ request()->routeIs('applications.*') ? 'text-indigo-600' : 'text-slate-400' }}">
                    <i class="fas fa-clipboard-list text-lg mb-0.5"></i>
                    <span class="text-xs font-medium">{{ \App\Helpers\LocalizationHelper::t('header.applications') }}</span>
                    @if(isset($cmrPendingCount) && $cmrPendingCount > 0)
                    <span class="absolute -top-0.5 right-0.5 min-w-[16px] h-4 bg-amber-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-0.5">
                        {{ $cmrPendingCount > 9 ? '9+' : $cmrPendingCount }}
                    </span>
                    @endif
                </a>
                @endif
                @if(auth()->user()->isAdmin())
                <a href="{{ route('cars.all') }}"
                   class="flex flex-col items-center py-1 px-3 rounded-xl transition-colors {{ request()->routeIs('cars.*') ? 'text-indigo-600' : 'text-slate-400' }}">
                    <i class="fas fa-truck text-lg mb-0.5"></i>
                    <span class="text-xs font-medium">{{ \App\Helpers\LocalizationHelper::t('header.cars') }}</span>
                </a>
                <a href="{{ route('admin.users') }}"
                   class="flex flex-col items-center py-1 px-3 rounded-xl transition-colors {{ request()->routeIs('admin.*') ? 'text-indigo-600' : 'text-slate-400' }}">
                    <i class="fas fa-users text-lg mb-0.5"></i>
                    <span class="text-xs font-medium">{{ \App\Helpers\LocalizationHelper::t('header.users') }}</span>
                </a>
                @endif
            </div>
        </div>
        <div class="h-16 lg:hidden"></div>
    </div>
</div>

@elseif(auth()->user()->isDriver())
{{-- ===================== DRIVER LAYOUT ===================== --}}
<div class="flex flex-col min-h-screen">
    <header class="bg-white border-b border-slate-200 sticky top-0 z-20">
        <div class="max-w-5xl mx-auto flex items-center justify-between h-16 px-4 sm:px-6">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-route text-white text-sm"></i>
                </div>
                <span class="font-bold text-slate-900 text-lg">Silk Way</span>
            </div>

            {{-- Desktop nav links for driver --}}
            <nav class="hidden md:flex items-center space-x-1">
                <a href="{{ route('cargo.index') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('cargo.index') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fas fa-box mr-1.5"></i>{{ \App\Helpers\LocalizationHelper::t('header.cargo') }}
                </a>
                <a href="{{ route('cargo.my-cargo') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('cargo.my-cargo') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fas fa-truck mr-1.5"></i>{{ \App\Helpers\LocalizationHelper::t('header.my_cargo') }}
                </a>
                <a href="{{ route('applications.my-applications') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('applications.my-applications') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fas fa-clipboard-list mr-1.5"></i>{{ \App\Helpers\LocalizationHelper::t('header.my_applications') }}
                </a>
                <a href="{{ route('cars.my-cars') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('cars.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fas fa-car mr-1.5"></i>{{ \App\Helpers\LocalizationHelper::t('header.my_cars') }}
                </a>
                <a href="{{ route('documents.index') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('documents.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fas fa-id-card mr-1.5"></i>{{ \App\Helpers\LocalizationHelper::t('docs.title') }}
                </a>
            </nav>

            <div class="flex items-center space-x-3">
                {{-- Language switcher --}}
                <div class="flex items-center space-x-1 bg-slate-100 rounded-lg p-1">
                    <a href="{{ request()->fullUrlWithQuery(['lang' => 'ru']) }}"
                       class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'ru' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">RU</a>
                    <a href="{{ request()->fullUrlWithQuery(['lang' => 'kz']) }}"
                       class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'kz' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">KK</a>
                    <a href="{{ request()->fullUrlWithQuery(['lang' => 'cn']) }}"
                       class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'cn' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">中</a>
                </div>

                {{-- User menu --}}
                <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-2">
                        <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-semibold text-sm">
                            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1, 'UTF-8'), 'UTF-8') }}
                        </div>
                        <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200 hidden sm:block" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition x-cloak
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 py-1 z-50">
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-sm font-medium text-slate-900">{{ auth()->user()->name }}</p>
                            <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">{{ \App\Helpers\LocalizationHelper::t('admin.driver') }}</span>
                        </div>
                        <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                            <i class="fas fa-user-circle mr-2.5 text-slate-400"></i>{{ \App\Helpers\LocalizationHelper::t('nav.profile') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition-colors flex items-center">
                                <i class="fas fa-sign-out-alt mr-2.5"></i>{{ \App\Helpers\LocalizationHelper::t('header.logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-5xl mx-auto w-full px-4 sm:px-6 py-6">
        @if(session('success'))
        <div class="mb-5 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl">
            <i class="fas fa-check-circle text-emerald-500 mt-0.5 shrink-0"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-5 flex items-start gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl">
            <i class="fas fa-exclamation-circle text-rose-500 mt-0.5 shrink-0"></i>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
        @endif

        @if(session('warning'))
        <div class="mb-5 flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl">
            <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5 shrink-0"></i>
            <span class="text-sm font-medium">{{ session('warning') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl">
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-triangle text-rose-500 mt-0.5 shrink-0"></i>
                <ul class="text-sm space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    {{-- Driver bottom tab bar --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 z-20 md:hidden">
        <div class="flex justify-around items-center py-2">
            <a href="{{ route('cargo.index') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('cargo.index') ? 'text-indigo-600' : 'text-slate-400' }}">
                <i class="fas fa-box text-xl mb-0.5"></i>
                <span class="text-xs font-medium">{{ \App\Helpers\LocalizationHelper::t('header.cargo') }}</span>
            </a>
            <a href="{{ route('cargo.my-cargo') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('cargo.my-cargo') ? 'text-indigo-600' : 'text-slate-400' }}">
                <i class="fas fa-truck text-xl mb-0.5"></i>
                <span class="text-xs font-medium">{{ \App\Helpers\LocalizationHelper::t('header.my_cargo') }}</span>
            </a>
            <a href="{{ route('applications.my-applications') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('applications.my-applications') ? 'text-indigo-600' : 'text-slate-400' }}">
                <i class="fas fa-clipboard-list text-xl mb-0.5"></i>
                <span class="text-xs font-medium">{{ \App\Helpers\LocalizationHelper::t('header.applications') }}</span>
            </a>
            <a href="{{ route('cars.my-cars') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('cars.*') ? 'text-indigo-600' : 'text-slate-400' }}">
                <i class="fas fa-car text-xl mb-0.5"></i>
                <span class="text-xs font-medium">{{ \App\Helpers\LocalizationHelper::t('header.cars') }}</span>
            </a>
            <a href="{{ route('documents.index') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('documents.*') ? 'text-indigo-600' : 'text-slate-400' }}">
                <i class="fas fa-id-card text-xl mb-0.5"></i>
                <span class="text-xs font-medium">{{ \App\Helpers\LocalizationHelper::t('docs.title') }}</span>
            </a>
        </div>
    </div>
    <div class="h-16 md:hidden"></div>
</div>

@else
{{-- Fallback for unapproved/unknown role --}}
<div class="min-h-screen flex flex-col">
    <header class="bg-white border-b border-slate-200 h-16 flex items-center px-6">
        <span class="font-bold text-slate-900 text-lg">Silk Way</span>
        <div class="ml-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-slate-600 hover:text-slate-900">
                    <i class="fas fa-sign-out-alt mr-1"></i>{{ \App\Helpers\LocalizationHelper::t('header.logout') }}
                </button>
            </form>
        </div>
    </header>
    <main class="flex-1 p-6">
        @yield('content')
    </main>
</div>
@endif

@else
{{-- ===================== GUEST LAYOUT ===================== --}}
<main class="min-h-screen bg-slate-50">
    @yield('content')
</main>
@endauth

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    if (menu) menu.classList.toggle('hidden');
}
document.addEventListener('click', function(event) {
    const menu = document.getElementById('mobileMenu');
    if (!menu) return;
    const btn = event.target.closest('[onclick="toggleMobileMenu()"]');
    if (!menu.contains(event.target) && !btn) {
        menu.classList.add('hidden');
    }
});
</script>

@auth
<script>
/**
 * Global FCM web receiver.
 *
 * Runs silently on every authenticated page load — NEVER calls requestPermission().
 * Permission must be granted via /push-test (or a future opt-in UI). Once granted,
 * this block registers the SW, fetches the FCM token, persists it to the server
 * (scoped per-user via localStorage key), and subscribes to foreground messages.
 *
 * Dynamic import keeps the Firebase SDK (~180 kB) out of the critical path for
 * users who have not yet granted permission.
 */
(async () => {
    if (!('Notification' in window) || !('serviceWorker' in navigator)) return;
    if (Notification.permission !== 'granted') return;

    const VAPID  = @json(config('services.firebase_web.vapid_key'));
    if (!VAPID) return;

    const userId = @json(auth()->id());
    const csrf   = document.querySelector('meta[name="csrf-token"]')?.content;
    const lsKey  = 'fcm_token_' + userId;

    // --- load Firebase SDK lazily ------------------------------------------
    const { initializeApp } = await import(
        "https://www.gstatic.com/firebasejs/10.13.2/firebase-app.js"
    );
    const { getMessaging, getToken, onMessage } = await import(
        "https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging.js"
    );

    // Same config as push-test.blade.php and firebase-messaging-sw.js.
    // IMPORTANT: if you rotate the Firebase project, update all three places.
    const firebaseConfig = {
        apiKey:            "AIzaSyA8IOqEZ6w_1tX3pvLf_REY-i7mnOVcOpk",
        authDomain:        "fruck-kz.firebaseapp.com",
        projectId:         "fruck-kz",
        storageBucket:     "fruck-kz.firebasestorage.app",
        messagingSenderId: "1076024786935",
        appId:             "1:1076024786935:web:0b3f398a1f1790242938f2",
    };

    const app       = initializeApp(firebaseConfig, 'silk-way-global');
    const messaging = getMessaging(app);

    // --- service worker registration ----------------------------------------
    let swReg;
    try {
        swReg = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
    } catch (err) {
        console.warn('[FCM] SW registration failed:', err);
        return;
    }

    // --- get FCM token -------------------------------------------------------
    let token;
    try {
        token = await getToken(messaging, {
            vapidKey: VAPID,
            serviceWorkerRegistration: swReg,
        });
    } catch (err) {
        console.warn('[FCM] getToken failed:', err);
        return;
    }

    if (!token) return;

    // --- persist token to server only when it changes -----------------------
    // The localStorage key is user-scoped so a different user on the same
    // browser correctly re-registers instead of reusing the previous user's
    // cached token reference.
    if (localStorage.getItem(lsKey) !== token) {
        try {
            const res = await fetch('/fcm/register', {
                method:  'POST',
                headers: {
                    'Content-Type':  'application/json',
                    'Accept':        'application/json',
                    'X-CSRF-TOKEN':  csrf,
                },
                body: JSON.stringify({ token, platform: 'web' }),
            });
            if (res.ok) {
                localStorage.setItem(lsKey, token);
            } else {
                console.warn('[FCM] register failed:', res.status);
            }
        } catch (err) {
            console.warn('[FCM] register error:', err);
        }
    }

    // --- foreground message handler -----------------------------------------
    // When the tab is active the SW does not show a notification automatically;
    // we create one here using the Web Notifications API directly.
    onMessage(messaging, (payload) => {
        if (Notification.permission !== 'granted') return;

        const title = payload.notification?.title
            ?? payload.data?.title
            ?? 'Silk Way';
        const body  = payload.notification?.body
            ?? payload.data?.body
            ?? '';

        new Notification(title, { body, icon: '/favicon.ico' });
    });
})();
</script>
@endauth

</body>
</html>
