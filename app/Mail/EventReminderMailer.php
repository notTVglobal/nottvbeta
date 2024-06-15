<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventReminderMailer extends Mailable implements ShouldQueue {
  use Queueable, SerializesModels;

  public $eventDetails;
  public $recipientEmail;

  /**
   * Create a new message instance.
   */
  public function __construct($eventDetails, $recipientEmail) {
    $this->eventDetails = $eventDetails;
    $this->recipientEmail = $recipientEmail;
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope {
    return new Envelope(
        to: $this->recipientEmail,
        subject: 'Upcoming Event Reminder'
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content {
    return new Content(
        markdown: 'mail.eventReminder',
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
