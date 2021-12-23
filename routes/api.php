<?php

use App\Http\Controllers\API\AssetController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\VendorController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\AssignAssetController;
use App\Http\Controllers\API\CategoryController;

Route::prefix('v1')->group(function () {
    // Authentication Routes
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('active')
            ->name('auth.login');
        Route::post('/register', [AuthController::class, 'registerAdmin'])
            ->name('auth.register');
        Route::get('/email/verify/{id}', [AuthController::class, 'verifyEmail'])
            ->name('verification.verify');
        Route::post('/email/resend', [AuthController::class, 'resendVerification'])
            ->name('verification.send');

        // profile
        Route::get('/profile', [AuthController::class, 'me'])
            ->middleware(['jwt', 'verified'])
            ->name('auth.profile');
    });

    // User Routes
    Route::apiResource('users', UserController::class)
        ->middleware(['jwt', 'verified', 'admin']);
    Route::patch('/users/{user}/disable', [UserController::class, 'disable'])
        ->middleware(['jwt', 'verified', 'admin'])->name('user.disable');
    // Profile picture update
    Route::post('/users/{user}/avatar', [UserController::class, 'updateAvatar'])
        ->middleware(['jwt', 'verified'])->name('user.avatar');

    // Assets Routes
    Route::apiResource('assets', AssetController::class)
        ->middleware(['jwt', 'verified', 'admin']);

    Route::post('assets/{id}/update-picture', [AssetController::class, 'update'])
        ->middleware(['jwt', 'verified', 'admin']);

    // Vendors Routes
    Route::apiResource('vendors', VendorController::class)
        ->middleware(['jwt', 'verified', 'admin']);

    // Category Routes
    Route::apiResource('categories', CategoryController::class)
        ->middleware(['jwt', 'verified', 'admin']);

    // Location Routes
    Route::apiResource('locations', LocationController::class)
        ->middleware(['jwt', 'verified', 'admin']);

    // Notification Routes
    Route::get('/notification/unassigned', [NotificationController::class, 'unassignedAssets'])
        ->middleware(['jwt', 'verified']);
    Route::get('/notification/depreciating/{threshold?}', [NotificationController::class, 'depreciatingAssets'])
        ->middleware(['jwt', 'verified']);
    Route::get('/notification/location/{location_id}', [NotificationController::class, 'assetInLocation'])
        ->middleware(['jwt', 'verified']);
    Route::get('/notification/valuation', [NotificationController::class, 'assetValuation'])
        ->middleware(['jwt', 'verified']);

    // Assign Asset Route
    Route::apiResource('assign-asset', AssignAssetController::class)
        ->middleware(['jwt', 'verified', 'admin']);
});
