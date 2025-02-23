<?php

use App\Http\Controllers\MailBoxController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    require __DIR__ . "/api_auth.php";

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('user')->group(function () {
            Route::apiResource('/', UserController::class)->only(
                ['index', 'update']
            )->name('update', 'user.update');
            Route::get('delete-account-init', [UserController::class, 'init_delete']);
            Route::post('delete-account-verify', [UserController::class, 'delete'])
                ->name('delete_account_verify');
        });

        Route::apiResource('mailbox', MailBoxController::class);
    });
});
