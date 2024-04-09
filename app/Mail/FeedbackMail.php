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

class FeedbackMail extends Mailable
{
    use Queueable, SerializesModels;

//    public $feedback;
  protected $data;
  public $attachmentPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $attachmentPath = null)
    {
      $this->data = $data;
      $this->attachmentPath = $attachmentPath;
    }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    Log::alert($this->data);
//        return $this->attachFromStorageDisk('public', 'filename.png', 'another_name.png')->subject($subject)->markdown('mail.verify')->to($this->user->email);
    $email =  $this->markdown('mail.feedback', [
//        'url' => route('stream'),
        'message' => $this->data['message'] ?? 'No Message',
        'name' => $this->data['name'] ?? 'No Name',
        'email' => $this->data['email'] ?? 'No Email',
        'phone' => $this->data['phone'] ?? 'No Phone Number',
        'city' => $this->data['city'] ?? 'No City',
        'province' => $this->data['province'] ?? 'No Province',
        'url'   => $this->data['url'] ?? 'No Attachments',
    ]);
    if ($this->attachmentPath) {
      $email->attachFromStorage($this->attachmentPath);
    }
    return $email;
  }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'notTV Feedback Mail',
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

      // Check if there's an attachment path provided
      if ($this->attachmentPath) {
        // Get the file extension
        $extension = strtolower(pathinfo($this->attachmentPath, PATHINFO_EXTENSION));

        // Determine the file name and MIME type based on the extension
        $fileName = 'screenshot'; // Base name for the screenshot
        switch ($extension) {
          case 'jpg':
          case 'jpeg':
            $fileName .= '.jpg';
            $mime = 'image/jpeg';
            break;
          case 'png':
            $fileName .= '.png';
            $mime = 'image/png';
            break;
          default:
            // Default case if the file extension is not recognized
            $fileName .= '.' . $extension;
            $mime = 'application/octet-stream'; // Generic binary data MIME type
            break;
        }

        // Create and return the attachment
        return [
            Attachment::fromPath($this->attachmentPath)
                ->as($fileName) // Rename the file in the email
//                ->mime($mime), // Set the appropriate MIME type
        ];
      }
      return []; // Return an empty array if no attachments
    }
}
