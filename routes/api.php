<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CarController;
use App\Http\Controllers\API\CargoApplicationController;
use App\Http\Controllers\API\CargoController;
use App\Http\Controllers\API\DriverDocumentController;
use App\Http\Controllers\API\DriverLoginController;
use App\Http\Controllers\API\DriverRegistrationController;
use App\Http\Controllers\API\PublicCargoController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // === AUTH ===
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login',    [AuthController::class, 'login']);

        // Driver WhatsApp OTP registration — public, no sanctum, IP-throttled
        Route::prefix('driver/register')
            ->middleware('throttle:waha-driver-register')
            ->group(function () {
                Route::post('/request', [DriverRegistrationController::class, 'requestCode']);
                Route::post('/verify',  [DriverRegistrationController::class, 'verifyCode']);
                Route::post('/resend',  [DriverRegistrationController::class, 'resendCode']);
            });

        // Driver WhatsApp OTP login — public, no sanctum, IP-throttled (separate limiter from register)
        Route::prefix('driver/login')
            ->middleware('throttle:waha-driver-login')
            ->group(function () {
                Route::post('/request', [DriverLoginController::class, 'requestCode']);
                Route::post('/verify',  [DriverLoginController::class, 'verifyCode']);
                Route::post('/resend',  [DriverLoginController::class, 'resendCode']);
            });

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
        // Static paths must precede wildcard /{application} to avoid prefix collision.
        Route::get('/applications/my',                     [CargoApplicationController::class, 'myApplications']);
        Route::get('/applications/{application}',          [CargoApplicationController::class, 'show']);
        Route::post('/cargo/{cargo}/apply',                [CargoApplicationController::class, 'apply']);
        // /deliver is kept for backwards compatibility; returns a hint to use the CMR flow.
        Route::post('/applications/{application}/deliver', [CargoApplicationController::class, 'markAsDelivered']);

        // CMR flow (driver-facing, no extra role middleware — controller enforces ownership)
        Route::post('/applications/{application}/cmr/upload',  [CargoApplicationController::class, 'uploadCmr']);
        Route::delete('/applications/{application}/cmr',       [CargoApplicationController::class, 'destroyCmr']);
        Route::get('/applications/{application}/cmr/file',     [CargoApplicationController::class, 'cmrFile']);

        // CMR review (WE-of-cargo or admin — controller enforces ownership; no blanket role middleware
        // because WE must own the cargo, not just have the role)
        Route::post('/applications/{application}/cmr/confirm', [CargoApplicationController::class, 'confirmCmr']);
        Route::post('/applications/{application}/cmr/reject',  [CargoApplicationController::class, 'rejectCmr']);

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
        // Static routes must be registered before wildcard routes to avoid
        // "types" or "batch" being matched as a document ID / type code.
        Route::get('/documents',                                  [DriverDocumentController::class, 'index'])->middleware('role:driver');
        Route::get('/documents/types',                            [DriverDocumentController::class, 'types'])->middleware('role:driver')->name('api.documents.types');
        Route::post('/documents/batch',                           [DriverDocumentController::class, 'batchUpload'])->middleware('role:driver')->name('api.documents.batchUpload');
        Route::post('/documents/by-type/{type}/upload',          [DriverDocumentController::class, 'uploadByType'])->middleware('role:driver')->name('api.documents.uploadByType');
        Route::post('/documents/{document}/upload',               [DriverDocumentController::class, 'upload'])->middleware('role:driver');
        Route::delete('/documents/{document}',                    [DriverDocumentController::class, 'destroy'])->middleware('role:driver');
        Route::get('/admin/documents',                            [DriverDocumentController::class, 'adminIndex'])->middleware('role:admin');
        Route::post('/admin/documents/{document}/verify',         [DriverDocumentController::class, 'adminVerify'])->middleware('role:admin');
    });

});
