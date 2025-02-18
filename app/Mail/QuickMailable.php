<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuickMailable extends Mailable
{
    use Queueable, SerializesModels;

    public string $subject;
    public string $content;
    protected array $attachments;

    /**
     * Create a new message instance.
     */
    public function __construct(array $emailData, array $attachments)
    {
        $this->subject = $emailData['subject'];
        $this->content = $emailData['content'];
        $this->attachments = $attachments;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: $this->content
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
