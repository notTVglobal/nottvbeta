<?php

namespace App\Mail;

use App\Models\NewsTip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsTipMailer extends Mailable {
  use Queueable, SerializesModels;

  public $newsTip;
  public $recipientEmail;

  /**
   * Create a new message instance.
   */
  public function __construct(NewsTip $newsTip, $recipientEmail)
  {
    $this->newsTip = $newsTip;
    $this->recipientEmail = $recipientEmail;
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope {
    // Determine the recipient email
    $email = config('mail.news_tip_recipient');

    if ($this->newsTip->newsPerson && $this->newsTip->newsPerson->user) {
      $email = $this->newsTip->newsPerson->user->email;
    }

    return new Envelope(
        to: $this->recipientEmail,
        subject: 'News Tip Mailer'
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content {
    return new Content(
        markdown: 'mail.newsTip',
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
