<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return env('APP_NAME') . " Visit " . env('FRONTEND_URL');
});
