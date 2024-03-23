<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InviteRequestMail extends Mailable
{
    use Queueable, SerializesModels;

//    public $feedback;
  protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
      $this->data = $data;
    }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->markdown('mail.inviteRequest', [
        'message' => $this->data['message'] ?? 'No Message',
        'name' => $this->data['name'] ?? 'No Name',
        'email' => $this->data['email'] ?? 'No Email',
        'phone' => $this->data['phone'] ?? 'No Phone Number',
    ]);
  }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'notTV Invite Request Mail',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return Content
     */
    public function content(): Content
    {
      return new Content(
//          view: 'mail.feedback',
//          with: [
//              'message' => $this->data['message'] ?? 'No Message',
//              'name' => $this->data['name'] ?? 'No Name',
//              'email' => $this->data['email'] ?? 'No Email',
//              'phone' => $this->data['phone'] ?? 'No Phone Number',
//              'city' => $this->data['city'] ?? 'No City',
//              'province' => $this->data['province'] ?? 'No Province',
//            // Add other fields as needed
//          ]
      );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
      return []; // Return an empty array if no attachments
    }
}
