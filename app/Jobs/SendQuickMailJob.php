<?php

namespace App\Jobs;

use App\Enums\QuickMailStateEnum;
use App\Models\QuickMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

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
        $mailBox = $this->quickMail->mailBox;
        $maxSendPerHour = $mailbox->meta->max_send ?? $this->defaultMaxSendPerHour;
        $delayBetweenBatches = 60;
        $sendTime = max(0, now()->diffInSeconds($this->quickMail->send_time, false));
        $attachments = $this->quickMail->has('attachments') ?
            $this->quickMail->attachments()->pluck('attachment')->toArray() :
            [];
        $recipients = collect();
        $subject = $this->quickMail->subject;
        $cc = $this->quickMail->cc ?? [];
        $bcc = $this->quickMail->bcc ?? [];
        $content = "";

        $smtpConfig = $mailBox->smtp_details();
        $smtpConfig['transport'] = 'smtp';
        $smtpConfig['from'] = [
            'address' => $mailBox->smtp_username,
            'name'    => $mailBox->title,
        ];

        if ($this->quickMail->has('template')) {
            $content = optional($this->quickMail->template)->template;
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
            'subject' => $subject,
            'content' => $content,
            'cc' => $cc,
            'bcc' => $bcc,
        ];


        $chunks = array_chunk($recipients, $maxSendPerHour);

        foreach ($chunks as $index => $emailBatch) {

            $batchSendTime = max($sendTime, now()->diffInSeconds(now()->addMinutes($index * $delayBetweenBatches)));

            SendEmailBatchJob::dispatch(
                $smtpConfig,
                $emailBatch,
                $emailData,
                $attachments,
                $this->quickMail
            )
                ->delay($batchSendTime);
        }

        $this->quickMail->update(["state" => QuickMailStateEnum::INQUEUE]);
    }
}
