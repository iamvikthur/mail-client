<?php

namespace App\Jobs;

use App\Models\Campaign;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DispatchCampaignMailJob implements ShouldQueue
{
    use Queueable;

    private Campaign $campaign;
    private int $defaultMaxSendPerHour = 40;
    /**
     * Create a new job instance.
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mailBox = $this->campaign->mailbox;
        $maxSendPerHour = $mailbox->meta->max_send ?? $this->defaultMaxSendPerHour;
        $delayBetweenBatches = 60;
        $sendTime = max(0, now()->diffInSeconds($this->campaign->send_time, false));
        $attachments = $this->campaign->attachments()->pluck('attachments')->toArray();
        $cc = $this->campaign->cc ?? [];
        $bcc = $this->campaign->bcc ?? [];

        $smtpConfig = $mailBox->smtp_details();
        $smtpConfig['transport'] = 'smtp';
        $smtpConfig['from'] = [
            'address' => $mailBox->username,
            'name'    => $mailBox->title,
        ];


        $recipients =  $this->campaign->contactLists->flatMap->contacts;
        $emailBody = $this->campaign->template;
        $subject = $this->campaign->subject;

        $emailData = [
            'subject' => $subject,
            'content' => $emailBody,
            'cc' => $cc,
            'bcc' => $bcc,
        ];

        $chunks = array_chunk($recipients, $maxSendPerHour);

        foreach ($chunks as $index => $chunk) {
            $batchSendTime = max($sendTime, now())->addMinutes($index * $delayBetweenBatches);

            DispatchCampaignEmailBatchJob::dispatch($smtpConfig, $chunk, $emailData, $attachments)
                ->delay($batchSendTime);
        }
    }
}
