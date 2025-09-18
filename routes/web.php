<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CargoApplicationController;
use App\Http\Controllers\CarController;

// Главная страница
Route::get('/', function () {
    return redirect()->route('cargo.index');
});


// Аутентификация
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Админ-панель
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
        Route::post('/users/{user}/reject', [AdminController::class, 'rejectUser'])->name('users.reject');
        Route::post('/users/{user}/toggle-approval', [AdminController::class, 'toggleUserApproval'])->name('users.toggle-approval');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        
        // Управление переводами
        Route::middleware(['auth', 'role:admin'])->group(function () {
            Route::get('/translations', [App\Http\Controllers\Admin\TranslationController::class, 'index'])->name('translations.index');
            Route::get('/translations/create', [App\Http\Controllers\Admin\TranslationController::class, 'create'])->name('translations.create');
            Route::post('/translations', [App\Http\Controllers\Admin\TranslationController::class, 'store'])->name('translations.store');
            Route::get('/translations/{translation}', [App\Http\Controllers\Admin\TranslationController::class, 'show'])->name('translations.show');
            Route::get('/translations/{translation}/edit', [App\Http\Controllers\Admin\TranslationController::class, 'edit'])->name('translations.edit');
            Route::put('/translations/{translation}', [App\Http\Controllers\Admin\TranslationController::class, 'update'])->name('translations.update');
            Route::post('/translations/clear-cache', [App\Http\Controllers\Admin\TranslationController::class, 'clearCache'])->name('translations.clear-cache');
            Route::get('/translations/export', [App\Http\Controllers\Admin\TranslationController::class, 'export'])->name('translations.export');
        });
    });
    
    // Система заявок на грузы
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [CargoApplicationController::class, 'index'])->name('index')->middleware('role:admin,warehouse_employee');
        Route::post('/{cargo}/apply', [CargoApplicationController::class, 'apply'])->name('apply');
        Route::get('/my-applications', [CargoApplicationController::class, 'myApplications'])->name('my-applications');
        
        // Обратная совместимость (старый роут)
        Route::get('/{application}', [CargoApplicationController::class, 'showApplication'])->name('show');
        
        Route::post('/{application}/approve', [CargoApplicationController::class, 'approveApplication'])->name('approve');
        Route::post('/{application}/reject', [CargoApplicationController::class, 'rejectApplication'])->name('reject');
        Route::post('/{application}/mark-delivered', [CargoApplicationController::class, 'markAsDelivered'])->name('mark-delivered');
    });
    
    // Отдельные роуты для заявок из разных разделов
    Route::prefix('cargo')->name('cargo.')->group(function () {
        Route::get('/applications/{application}', [CargoApplicationController::class, 'showApplication'])->name('applications.show-from-cargo');
    });
    
    Route::prefix('my-cargo')->name('my-cargo.')->group(function () {
        Route::get('/applications/{application}', [CargoApplicationController::class, 'showApplication'])->name('applications.show-from-my-cargo');
    });
    
    // Управление машинами
    Route::prefix('cars')->name('cars.')->group(function () {
        // Основной маршрут для просмотра машин (перенаправляет на my-cars для водителей)
        Route::get('/', function () {
            if (auth()->user()->isDriver()) {
                return redirect()->route('cars.my-cars');
            }
            return redirect()->route('cars.all');
        })->name('index');
        
        // Публичные маршруты (требуют только аутентификации)
        Route::get('/all', [CarController::class, 'index'])->name('all')->middleware('role:admin,warehouse_employee');
        
        // Маршруты только для водителей (должны быть выше параметризованных)
        Route::middleware('role:driver')->group(function () {
            Route::get('/my-cars', [CarController::class, 'myCars'])->name('my-cars');
            Route::get('/create', [CarController::class, 'create'])->name('create');
            Route::post('/', [CarController::class, 'store'])->name('store');
        });
        
        // Параметризованные маршруты (должны быть ниже конкретных)
        Route::get('/{car}', [CarController::class, 'show'])->name('show');
        
        // Остальные маршруты только для водителей
        Route::middleware('role:driver')->group(function () {
            Route::get('/{car}/edit', [CarController::class, 'edit'])->name('edit');
            Route::put('/{car}', [CarController::class, 'update'])->name('update');
            Route::delete('/{car}', [CarController::class, 'destroy'])->name('destroy');
            Route::post('/{car}/toggle-status', [CarController::class, 'toggleStatus'])->name('toggle-status');
        });
    });
    
    // Управление грузами - специальные маршруты должны быть выше ресурсного
    Route::get('/cargo/my-cargo', [CargoController::class, 'myCargo'])->name('cargo.my-cargo');
    
    // Ресурсный маршрут cargo должен быть последним
    Route::resource('cargo', CargoController::class);
});
