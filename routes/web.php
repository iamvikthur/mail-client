<?php

use App\Http\Controllers\EmailSendingDetailController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth',])->group(function () {
    Route::resource('email_sending_details', EmailSendingDetailController::class);
});


require __DIR__ . '/auth.php';
