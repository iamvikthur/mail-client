<?php

namespace App\Listeners;

use App\Enums\QuickMailStateEnum;
use App\Models\QuickMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;

class UpdateQuickMailSendingStatus
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
    public function handle(MessageSent $event): void
    {
        $data = $event->data;

        if (isset($data['quickMail'])) {
            $quickMail = $data['quickMail'];
            $quickMailId = $quickMail->id;

            $quickMailModel = QuickMail::where('id', $quickMailId)->first();

            $quickMailModel->update(["state" => QuickMailStateEnum::SENDING]);
        }
    }
}
