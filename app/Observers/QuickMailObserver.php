<?php

namespace App\Observers;

use App\Jobs\SendQuickMailJob;
use App\Models\QuickMail;

class QuickMailObserver
{
    /**
     * Handle the QuickMail "created" event.
     */
    public function created(QuickMail $quickMail): void
    {
        dispatch(new SendQuickMailJob($quickMail));
    }

    /**
     * Handle the QuickMail "updated" event.
     */
    public function updated(QuickMail $quickMail): void
    {
        //
    }

    /**
     * Handle the QuickMail "deleted" event.
     */
    public function deleted(QuickMail $quickMail): void
    {
        //
    }

    /**
     * Handle the QuickMail "restored" event.
     */
    public function restored(QuickMail $quickMail): void
    {
        //
    }

    /**
     * Handle the QuickMail "force deleted" event.
     */
    public function forceDeleted(QuickMail $quickMail): void
    {
        //
    }
}
