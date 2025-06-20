<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    // GUEST ENDPOINTS
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('guest:sanctum')
        ->name('register');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('guest:sanctum')
        ->name('login');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('guest:sanctum')
        ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->middleware('guest:sanctum')
        ->name('password.store');

    // AUTH ENDPOINTS
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/verify-email', VerifyEmailController::class)
            ->middleware(['not.verified', 'throttle:6,1'])
            ->name('verification.verify');

        Route::get('/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['not.verified', 'throttle:6,1'])
            ->name('verification.send');

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');
    });
});
