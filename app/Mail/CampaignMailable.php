<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CampaignMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $campaignSubject;
    public $campaignContent;
    public $campaignAttachments;
    public $smtpConfig;

    /**
     * Create a new message instance.
     */
    public function __construct(array $emailData, array $attachments, array $smtpConfig)
    {
        $this->campaignSubject = $emailData['subject'];
        $this->campaignContent = $emailData['content'];
        $this->campaignAttachments = $attachments;
        $this->smtpConfig = $smtpConfig;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
            from: new Address(
                $this->smtpConfig['from']['address'],
                $this->smtpConfig['from']['name']
            )
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: $this->campaignContent,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return array_map(fn($file) => Attachment::fromStorageDisk('s3', $file), $this->attachments);
    }
}
