<?php

namespace App\Mail;

use App\Models\QuickMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuickMailable extends Mailable
{
    use Queueable, SerializesModels;

    public string $quickMailSubject;
    public string $quickMailContent;
    public array $quickMailAttachments;
    public array $smtpConfig;
    public QuickMail $quickMail;

    /**
     * Create a new message instance.
     */
    public function __construct(
        array $emailData,
        array $attachments,
        array $smtpConfig,
        QuickMail $quickMail
    ) {
        $this->quickMailSubject = $emailData['subject'];
        $this->quickMailContent = $emailData['content'];
        $this->quickMailAttachments = $attachments;
        $this->smtpConfig = $smtpConfig;
        $this->quickMail = $quickMail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->quickMailSubject,
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
            htmlString: $this->quickMailContent,
            with: ["quickMail" => $this->quickMail]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return array_map(fn($file) => Attachment::fromStorageDisk('s3', $file), $this->quickMailAttachments);
    }

    public function afterSending() {}
}
