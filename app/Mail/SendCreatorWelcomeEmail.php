<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class SendCreatorWelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

  public User $user;
  private string $verificationUrl;

  /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
      $this->user = $user;
      // Generate the email verification URL
      $this->verificationUrl = URL::temporarySignedRoute(
          'verification.verify', now()->addMinutes(60), ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]
      );
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Welcome to notTV!',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'mail.welcome',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
