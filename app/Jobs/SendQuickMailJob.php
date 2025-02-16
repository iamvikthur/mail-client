<?php

namespace App\Jobs;

use App\Models\QuickMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendQuickMailJob implements ShouldQueue
{
    use Queueable;

    private QuickMail $quickMail;
    private int $defaultMaxSendPerHour = 40;

    /**
     * Create a new job instance.
     */
    public function __construct(QuickMail $quickMail)
    {
        $this->quickMail = $quickMail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mailBox = $this->quickMail->mailbox;
        $maxSendPerHour = $mailbox->meta->max_send ?? $this->defaultMaxSendPerHour;
        $delayBetweenBatches = 60;
        $delay = max(0, now()->diffInSeconds($this->quickMail->send_time, false));
        $recipients = collect();
        $subject = $this->quickMail->subject;
        $content = "";
        $smtpConfig = [
            'transport'  => 'smtp',
            'host'       => $mailBox->host,
            'port'       => 587,
            'encryption' => $mailBox->encryption ?? null,
            'username'   => $mailBox->username,
            'password'   => $mailBox->password,
            'from'       => [
                'address' => $mailBox->username,
                'name'    => $mailBox->title,
            ],
        ];


        if ($this->quickMail->has('template')) {
            $content = $this->quickMail->template->longText;
        }

        if ($this->quickMail->body !== null) {
            $content = $this->quickMail->body;
        }


        if ($this->quickMail->has('contactLists')) {
            $recipients = $this->quickMail->contactLists->flatMap->contacts->pluck('email')->toArray();
        }

        if ($this->quickMail->recipients !== null) {
            $recipients = $this->quickMail->recipients;
        }

        $emailData = [
            'subject' => $subject->subject,
            'content' => $content,
        ];


        $chunks = array_chunk($recipients, $maxSendPerHour);

        foreach ($chunks as $index => $emailBatch) {
            SendEmailBatchJob::dispatch($smtpConfig, $this->quickMail, $emailBatch, $emailData)
                ->delay(now()->addMinutes($index * $delayBetweenBatches));
        }
    }
}
