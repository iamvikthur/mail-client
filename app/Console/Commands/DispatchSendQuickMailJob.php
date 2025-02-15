<?php

namespace App\Console\Commands;

use App\Models\QuickMail;
use Illuminate\Console\Command;

class DispatchSendQuickMailJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dispatch-send-quick-mail-job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $quickMails = QuickMail::groupBy('mailbox_id')->get();
    }
}
