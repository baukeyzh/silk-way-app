<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CarController;
use App\Http\Controllers\API\CargoApplicationController;
use App\Http\Controllers\API\CargoController;
use App\Http\Controllers\API\DriverDocumentController;
use App\Http\Controllers\API\PublicCargoController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // === AUTH ===
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login',    [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me',      [AuthController::class, 'me']);
        });
    });

    // === PUBLIC CARGO BROWSE (no token required) ===
    Route::prefix('public')->middleware('throttle:cargo-guest')->group(function () {
        Route::get('/cargo',         [PublicCargoController::class, 'index']);
        Route::get('/cargo/{cargo}', [PublicCargoController::class, 'show']);
    });

    // === AUTHENTICATED ROUTES ===
    Route::middleware(['auth:sanctum', 'throttle:cargo-auth'])->group(function () {

        // /cargo/my must register before /cargo/{cargo} to avoid "my" being
        // matched as a cargo ID by the wildcard route.
        Route::get('/cargo/my', [CargoController::class, 'myCargo']);

        // === CARGO (read) ===
        Route::get('/cargo',         [CargoController::class, 'index']);
        Route::get('/cargo/{cargo}', [CargoController::class, 'show']);

        // === CARGO (mutations) ===
        Route::middleware('role:warehouse_employee|admin')->group(function () {
            Route::post('/cargo',           [CargoController::class, 'store']);
            Route::put('/cargo/{cargo}',    [CargoController::class, 'update']);
            Route::delete('/cargo/{cargo}', [CargoController::class, 'destroy']);
        });

        // === CARS ===
        Route::get('/cars',       [CarController::class, 'index']);
        Route::get('/cars/my',    [CarController::class, 'myCars']);
        Route::get('/cars/{car}', [CarController::class, 'show']);

        Route::middleware('role:driver')->group(function () {
            Route::post('/cars',              [CarController::class, 'store']);
            Route::put('/cars/{car}',         [CarController::class, 'update']);
            Route::delete('/cars/{car}',      [CarController::class, 'destroy']);
            Route::post('/cars/{car}/toggle', [CarController::class, 'toggleStatus']);
        });

        // === APPLICATIONS ===
        Route::get('/applications/my',                     [CargoApplicationController::class, 'myApplications']);
        Route::get('/applications/{application}',          [CargoApplicationController::class, 'show']);
        Route::post('/cargo/{cargo}/apply',                [CargoApplicationController::class, 'apply']);
        Route::post('/applications/{application}/deliver', [CargoApplicationController::class, 'markAsDelivered']);

        Route::middleware('role:warehouse_employee|admin')->group(function () {
            Route::get('/applications',                        [CargoApplicationController::class, 'index']);
            Route::post('/applications/{application}/approve', [CargoApplicationController::class, 'approve']);
            Route::post('/applications/{application}/reject',  [CargoApplicationController::class, 'reject']);
        });

        // === ADMIN ===
        Route::prefix('admin')->middleware('role:admin')->group(function () {
            Route::get('/users',                 [AdminController::class, 'users']);
            Route::get('/users/{user}',          [AdminController::class, 'show']);
            Route::post('/users/{user}/approve', [AdminController::class, 'approveUser']);
            Route::post('/users/{user}/toggle',  [AdminController::class, 'toggleApproval']);
            Route::delete('/users/{user}',       [AdminController::class, 'deleteUser']);
        });

        // === DOCUMENTS ===
        Route::get('/documents',                          [DriverDocumentController::class, 'index'])->middleware('role:driver');
        // batch must be registered before /{document}/upload to avoid "batch" being matched as a document ID
        Route::post('/documents/batch',                   [DriverDocumentController::class, 'batchUpload'])->middleware('role:driver')->name('api.documents.batchUpload');
        Route::post('/documents/{document}/upload',       [DriverDocumentController::class, 'upload'])->middleware('role:driver');
        Route::delete('/documents/{document}',            [DriverDocumentController::class, 'destroy'])->middleware('role:driver');
        Route::get('/admin/documents',                    [DriverDocumentController::class, 'adminIndex'])->middleware('role:admin');
        Route::post('/admin/documents/{document}/verify', [DriverDocumentController::class, 'adminVerify'])->middleware('role:admin');
    });

});
