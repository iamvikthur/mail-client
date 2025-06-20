<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactFolderController;
use App\Http\Controllers\ContactListController;
use App\Http\Controllers\ImapController;
use App\Http\Controllers\MailBoxController;
use App\Http\Controllers\MiscController;
use App\Http\Controllers\QuickMailController;
use App\Http\Controllers\TemplateController;
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
        Route::apiResource('contact_list.contact', ContactController::class);

        // TEMPLATE
        Route::apiResource('template', TemplateController::class);

        // QUICK MAILS
        Route::apiResource('quick_mail', QuickMailController::class);

        //  CAMPAIGNS
        Route::apiResource('campaign', CampaignController::class);

        // INBOX
        ROute::prefix('imap')->group(function () {
            Route::get('test-connection/{mail_box}', [ImapController::class, 'test_connection']);
            Route::get('get-folders/{mail_box}', [ImapController::class, 'index']);
            Route::get('get-mails/{mail_box}/{folder}', [ImapController::class, 'get_mails']);
            Route::get('get-mail-details/{mail_box}/{folder}/{messageId}', [ImapController::class, 'get_email_details']);
            Route::get('move-email/{mail_box}/{messageId}/{fromFolder}/{toFolder}', [ImapController::class, 'move_email']);
            Route::get('delete-email/{mail_box}/{messageId}/{folder}', [ImapController::class, 'delete_email']);
            Route::post('create-folder/{mail_box}', [ImapController::class, 'create_folder'])->name('imap.folder.create');
            Route::get('delete-folder/{mail_box}/{folder_path}', [ImapController::class, 'delete_folder']);
        });
    });

    Route::post('send-otp', []);

    // MISC
    Route::get('campaign-status-enums', [MiscController::class, 'get_campaign_statuses']);
});
