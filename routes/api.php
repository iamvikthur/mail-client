<?php

use App\Http\Controllers\ContactFolderController;
use App\Http\Controllers\ContactListController;
use App\Http\Controllers\MailBoxController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    require __DIR__ . "/api_auth.php";

    Route::middleware(['auth:sanctum', 'verified'])->group(function () {

        // USER
        Route::apiResource('/user', UserController::class)->only(
            ['index', 'update']
        );
        // DELETING A USER'S ACCOUNT
        Route::prefix('user')->group(function () {
            Route::get('delete-account-init', [UserController::class, 'init_delete']);
            Route::post('delete-account-verify', [UserController::class, 'destroy'])
                ->name('delete_account_verify');
        });

        // MAILBOX
        Route::apiResource('mail_box', MailBoxController::class);
        Route::get('mail_box_check_state/{mail_box}', [
            MailBoxController::class,
            'check_mail_box_state'
        ])->name('mail_box.check_state');

        // RECIPIENT
        Route::apiResource('contact_folder', ContactFolderController::class);
        Route::apiResource('contact_folder.contact_list', ContactListController::class);
    });
});
