<?php

namespace App\Listeners;

use App\Jobs\SendAccountVerifiedEmailJob;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AccountVerifiedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        $user = $event->user;

        dispatch(new SendAccountVerifiedEmailJob($user));
    }
}
