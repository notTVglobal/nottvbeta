<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamInviteMail extends Mailable {
  use Queueable, SerializesModels;

  public $email;
  public $from_name;
  public $team_name;
  public $invite_url;
  public $invite_code;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($email, $from_name, $team_name, $invite_url, $invite_code) {
    $this->email = $email;
    $this->from_name = $from_name;
    $this->team_name = $team_name;
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
        subject: 'Exclusive Team Invitation: Join Us on notTV!',
    );
  }

  /**
   * Get the message content definition.
   *
   * @return \Illuminate\Mail\Mailables\Content
   */
  public function content() {
    return new Content(
        markdown: 'mail.teamInvite',
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
