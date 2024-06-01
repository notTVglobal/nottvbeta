<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PressReleaseMailer extends Mailable
{
    use Queueable, SerializesModels;

  public $filePath;
  public $recipientEmail;

    /**
     * Create a new message instance.
     */
  public function __construct($filePath, $recipientEmail)
  {
    $this->filePath = $filePath;
    $this->recipientEmail = $recipientEmail;
  }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Press Release Received',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.pressrelease',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
      return [
          Attachment::fromPath(storage_path('app/public/' . $this->filePath))
      ];
    }
}
