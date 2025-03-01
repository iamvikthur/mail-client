<?php

namespace App\Jobs;

use App\Enums\QuickMailStateEnum;
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
    private array $attachments;

    /**
     * Create a new job instance.
     */
    public function __construct(
        array $smtpConfig,
        array $recipients,
        array $emailData,
        array $attachments,
        QuickMail $quickMail
    ) {
        $this->smtpConfig = $smtpConfig;
        $this->recipients = $recipients;
        $this->emailData = $emailData;
        $this->attachments = $attachments;
        $this->quickMail = $quickMail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::build($this->smtpConfig)
            ->to($this->recipients)
            ->cc($this->emailData['cc'])
            ->bcc($this->emailData['bcc'])
            ->send(new QuickMailable(
                $this->emailData,
                $this->attachments,
                $this->smtpConfig,
                $this->quickMail
            ));

        // $this->quickMail->update(["state" => QuickMailStateEnum::SENDING]);
    }
}
