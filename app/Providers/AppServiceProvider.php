<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Models\QuickMail;
use App\Observers\QuickMailObserver;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Illuminate\Auth\AuthenticationException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // SANCTUM MODEL
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        // OBSERVER
        QuickMail::observe(QuickMailObserver::class);
    }
}
