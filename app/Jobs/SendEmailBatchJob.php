<?php

namespace App\Jobs;

use App\Models\QuickMail;
use App\Mail\QuickMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;


class SendEmailBatchJob implements ShouldQueue
{
    use Queueable;

    private array $smtpConfig;
    private QuickMail $quickMail;
    private array $recipients;

    private array $emailData;

    /**
     * Create a new job instance.
     */
    public function __construct(array $smtpConfig, array $recipients, array $emailData)
    {
        $this->smtpConfig = $smtpConfig;
        $this->recipients = $recipients;
        $this->emailData = $emailData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::build($this->smtpConfig)
            ->to($this->recipients)
            ->send(new QuickMailable($this->emailData));
    }
}
