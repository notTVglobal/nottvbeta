<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteCreatorMail extends Mailable {
  use Queueable, SerializesModels;

  public $name;
  public $email;
  public $from_name;
  public $message;
  public $invite_url;
  public $invite_code;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($name, $email, $message, $from_name, $invite_url, $invite_code) {
    $this->name = $name;
    $this->email = $email;
    $this->message = $message;
    $this->from_name = $from_name;
    $this->invite_url = $invite_url;
    $this->invite_code = $invite_code;
  }

  /**
   * Get the message envelope.
   *
   * @return \Illuminate\Mail\Mailables\Envelope
   */
  public function envelope() {
    return new Envelope(
        subject: 'Exclusive Invitation: Become a notTV Creator Today!',
    );
  }

  /**
   * Get the message content definition.
   *
   * @return \Illuminate\Mail\Mailables\Content
   */
  public function content() {
    return new Content(
        markdown: 'mail.inviteCreator',
    );
  }

  /**
   * Get the attachments for the message.
   *
   * @return array
   */
  public function attachments() {
    return [];
  }
}
