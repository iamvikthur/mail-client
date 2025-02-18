<?php

namespace App\Jobs;

use App\Mail\CampaignMailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class DispatchCampaignEmailBatchJob implements ShouldQueue
{
    use Queueable;

    private array $smtpConfig;
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
        $firstnameVariable = "[firstname]";
        $lastnameVariable = "[lastname]";
        $emailVariable = "[email]";

        $emailBody = $this->emailData['content'];

        foreach ($this->recipients as $key => $recipient) {
            str_replace($firstnameVariable, $recipient->firstname, $emailBody);
            str_replace($lastnameVariable, $recipient->lastname, $emailBody);
            str_replace($emailVariable, $recipient->email, $emailBody);

            $emailData = [
                "subject" => $this->emailData['subject'],
                "content" => $emailBody
            ];

            Mail::build($this->smtpConfig)
                ->to($recipient)
                ->send(new CampaignMailable($emailData));
        }
    }
}
