<?php

namespace App\Jobs;

use App\Mail\CampaignMailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DispatchCampaignEmailBatchJob implements ShouldQueue
{
    use Queueable;

    private array $smtpConfig;
    private array $recipients;
    private array $emailData;
    private array $attachments;

    /**
     * Create a new job instance.
     */
    public function __construct(array $smtpConfig, array $recipients, array $emailData, array $attachments)
    {
        $this->smtpConfig = $smtpConfig;
        $this->recipients = $recipients;
        $this->emailData = $emailData;
        $this->attachments = $attachments;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $firstnameVariable = "[firstname]";
        $lastnameVariable = "[lastname]";
        $emailVariable = "[email]";

        $emailBody = $this->emailData['content'];

        foreach ($this->recipients as $key => $recipient) {
            str_replace($firstnameVariable, $recipient['firstname'], $emailBody);
            str_replace($lastnameVariable, $recipient['lastname'], $emailBody);
            str_replace($emailVariable, $recipient['email'], $emailBody);

            $emailData = [
                "subject" => $this->emailData['subject'],
                "content" => $emailBody
            ];

            Log::info("RECEPIENTS", [$recipient['email']]);

            Mail::build($this->smtpConfig)
                ->to($recipient['email'])
                ->cc($this->emailData['cc'])
                ->bcc($this->emailData['bcc'])
                ->send(new CampaignMailable($emailData, $this->attachments, $this->smtpConfig));
        }
    }
}
