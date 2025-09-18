<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ translate('welcome.title') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-12">
            <div class="mx-auto h-24 w-24 flex items-center justify-center rounded-full bg-blue-100 mb-6">
                <i class="fas fa-truck text-blue-600 text-4xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Silk Way</h1>
            <p class="text-xl text-gray-600">{{ translate('welcome.system_title') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <!-- Система грузов -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center mb-4">
                    <i class="fas fa-box text-4xl text-blue-600 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ translate('welcome.cargo_management') }}</h3>
                    <p class="text-gray-600">{{ translate('welcome.cargo_management_desc') }}</p>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        {{ translate('welcome.create_applications') }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        {{ translate('welcome.track_delivery') }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        {{ translate('welcome.route_management') }}
                    </div>
                </div>
            </div>

            <!-- Система машин -->
            <div class="bg-white rounded-lg shadow-md p-6 border-2 border-blue-200">
                <div class="text-center mb-4">
                    <i class="fas fa-car text-4xl text-blue-600 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ translate('welcome.car_management') }}</h3>
                    <p class="text-gray-600">{{ translate('welcome.car_management_desc') }}</p>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        {{ translate('welcome.register_cars') }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        {{ translate('welcome.technical_specs') }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        {{ translate('welcome.upload_docs') }}
                    </div>
                </div>
            </div>

            <!-- Система пользователей -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center mb-4">
                    <i class="fas fa-users text-4xl text-blue-600 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ translate('welcome.user_management') }}</h3>
                    <p class="text-gray-600">{{ translate('welcome.user_management_desc') }}</p>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        {{ translate('welcome.admins') }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        {{ translate('welcome.warehouse_workers') }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        {{ translate('welcome.drivers') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-gray-900">{{ translate('welcome.demo_title') }}</h2>
                <p class="text-gray-600 mb-6">{{ translate('welcome.demo_description') }}</p>
                
                <div class="bg-white rounded-lg shadow-md p-6 max-w-md mx-auto">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ translate('welcome.test_drivers') }}</h3>
                    <div class="space-y-3 text-left">
                        <div class="border rounded p-3">
                            <p class="font-medium">Алексей Сидоров</p>
                            <p class="text-sm text-gray-600">Email: driver@example.com</p>
                            <p class="text-sm text-gray-600">Пароль: password123</p>
                        </div>
                        <div class="border rounded p-3">
                            <p class="font-medium">Sultan</p>
                            <p class="text-sm text-gray-600">Email: asd@dsa.qwe</p>
                            <p class="text-sm text-gray-600">Пароль: password123</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 space-x-4">
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Войти в систему
                    </a>
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-user-plus mr-2"></i>
                        Регистрация
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
