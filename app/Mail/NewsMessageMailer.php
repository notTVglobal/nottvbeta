<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsMessageMailer extends Mailable {
  use Queueable, SerializesModels;

  public $recipientEmail;

  /**
   * Create a new message instance.
   */
  public function __construct($recipientEmail)
  {
    $this->recipientEmail = $recipientEmail;
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope {
//    // Determine the recipient email
//    $email = config('mail.newsMessage');
//
//    if ($this->newsPersonMessage->recipient && $this->newsPersonMessage->recipient->user) {
//      $email = $this->newsPersonMessage->recipient->user->email;
//    }

    return new Envelope(
        to: $this->recipientEmail,
        subject: 'News Message Mailer'
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content {
    return new Content(
        markdown: 'mail.newsMessage',
    );
  }

  /**
   * Get the attachments for the message.
   *
   * @return array<int, \Illuminate\Mail\Mailables\Attachment>
   */
  public function attachments(): array {
    return [];
  }
}
