<?php

use App\Http\Controllers\MailBoxController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    require __DIR__ . "/api_auth.php";

    Route::middleware(['auth:sanctum', 'verified'])->group(function () {

        Route::apiResource('/user', UserController::class)->only(
            ['index', 'update']
        );
        // DELETING A USER'S ACCOUNT
        Route::prefix('user')->group(function () {
            Route::get('delete-account-init', [UserController::class, 'init_delete']);
            Route::post('delete-account-verify', [UserController::class, 'destroy'])
                ->name('delete_account_verify');
        });

        Route::apiResource('mailbox', MailBoxController::class);
    });
});
