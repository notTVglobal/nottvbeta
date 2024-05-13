<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteExistingCreatorMail extends Mailable {
  use Queueable, SerializesModels;

  public $name;
  public $email;
  public $from_name;
  public $message;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($name, $email, $message, $from_name) {
    $this->name = $name;
    $this->email = $email;
    $this->message = $message;
    $this->from_name = $from_name;
  }

  /**
   * Get the message envelope.
   *
   * @return Envelope
   */
  public function envelope(): Envelope {
    return new Envelope(
        subject: "A Reminder from notTV: Keep Creating, {{ $this->name }}!",
    );
  }

  /**
   * Get the message content definition.
   *
   * @return Content
   */
  public function content(): Content {
    return new Content(
        markdown: 'mail.inviteExistingCreator',
    );
  }

  /**
   * Get the attachments for the message.
   *
   * @return array
   */
  public function attachments(): array {
    return [];
  }
}
