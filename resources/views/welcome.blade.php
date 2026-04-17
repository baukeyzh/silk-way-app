<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Silk Way — {{ translate('welcome.system_title') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-white">

    {{-- Navbar --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-route text-white text-sm"></i>
                </div>
                <span class="font-bold text-slate-900 text-xl tracking-tight">Silk Way</span>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('login') }}"
                   class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 transition-colors">
                    Войти
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Регистрация
                </a>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="pt-32 pb-20 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-6 border border-indigo-100">
                <i class="fas fa-globe-asia"></i>
                Казахстан · Россия · Китай
            </div>
            <h1 class="text-5xl sm:text-6xl font-bold text-slate-900 leading-tight tracking-tight mb-6">
                Логистика<br>
                <span class="text-indigo-600">нового поколения</span>
            </h1>
            <p class="text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed mb-10">
                {{ translate('welcome.system_title') }}. Управляйте грузами, водителями и маршрутами в единой платформе.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-lg shadow-indigo-500/25">
                    <i class="fas fa-sign-in-alt mr-2.5"></i>
                    Войти в систему
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center px-6 py-3 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl border border-slate-300 transition-colors">
                    <i class="fas fa-user-plus mr-2.5"></i>
                    Создать аккаунт
                </a>
            </div>
        </div>
    </section>

    {{-- Feature cards --}}
    <section class="py-16 px-6 bg-slate-50 border-y border-slate-200">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900 mb-3">Всё что нужно для логистики</h2>
                <p class="text-slate-500 text-lg">Три роли, одна платформа</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Cargo --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-8 hover:shadow-lg hover:shadow-slate-200 transition-shadow">
                    <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center mb-5">
                        <i class="fas fa-box text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ translate('welcome.cargo_management') }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-5">{{ translate('welcome.cargo_management_desc') }}</p>
                    <ul class="space-y-2.5">
                        <li class="flex items-center text-sm text-slate-600">
                            <i class="fas fa-check text-emerald-500 mr-2.5 shrink-0"></i>
                            {{ translate('welcome.create_applications') }}
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i class="fas fa-check text-emerald-500 mr-2.5 shrink-0"></i>
                            {{ translate('welcome.track_delivery') }}
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i class="fas fa-check text-emerald-500 mr-2.5 shrink-0"></i>
                            {{ translate('welcome.route_management') }}
                        </li>
                    </ul>
                </div>

                {{-- Cars --}}
                <div class="bg-white rounded-2xl border-2 border-indigo-200 p-8 hover:shadow-lg hover:shadow-indigo-100 transition-shadow relative overflow-hidden">
                    <div class="absolute top-4 right-4">
                        <span class="text-xs bg-indigo-600 text-white font-semibold px-2.5 py-1 rounded-full">Популярное</span>
                    </div>
                    <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center mb-5">
                        <i class="fas fa-truck text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ translate('welcome.car_management') }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-5">{{ translate('welcome.car_management_desc') }}</p>
                    <ul class="space-y-2.5">
                        <li class="flex items-center text-sm text-slate-600">
                            <i class="fas fa-check text-emerald-500 mr-2.5 shrink-0"></i>
                            {{ translate('welcome.register_cars') }}
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i class="fas fa-check text-emerald-500 mr-2.5 shrink-0"></i>
                            {{ translate('welcome.technical_specs') }}
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i class="fas fa-check text-emerald-500 mr-2.5 shrink-0"></i>
                            {{ translate('welcome.upload_docs') }}
                        </li>
                    </ul>
                </div>

                {{-- Users --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-8 hover:shadow-lg hover:shadow-slate-200 transition-shadow">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mb-5">
                        <i class="fas fa-users text-emerald-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ translate('welcome.user_management') }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-5">{{ translate('welcome.user_management_desc') }}</p>
                    <ul class="space-y-2.5">
                        <li class="flex items-center text-sm text-slate-600">
                            <i class="fas fa-check text-emerald-500 mr-2.5 shrink-0"></i>
                            {{ translate('welcome.admins') }}
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i class="fas fa-check text-emerald-500 mr-2.5 shrink-0"></i>
                            {{ translate('welcome.warehouse_workers') }}
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i class="fas fa-check text-emerald-500 mr-2.5 shrink-0"></i>
                            {{ translate('welcome.drivers') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 px-6">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">{{ translate('welcome.demo_title') }}</h2>
            <p class="text-slate-500 mb-8 leading-relaxed">{{ translate('welcome.demo_description') }}</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors shadow-lg shadow-indigo-500/25">
                    <i class="fas fa-sign-in-alt mr-2.5"></i>
                    Войти
                </a>
                <a href="{{ route('register') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold rounded-xl border border-slate-300 transition-colors">
                    <i class="fas fa-user-plus mr-2.5"></i>
                    Зарегистрироваться
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-slate-200 py-8 px-6">
        <div class="max-w-6xl mx-auto text-center">
            <p class="text-sm text-slate-400">&copy; 2026 Silk Way. {{ translate('header.footer_text') }}</p>
        </div>
    </footer>

</body>
</html>
