<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminNotification;

class SendAdminNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // for production
//  public int $timeout = 60;
//  public int $tries = 10;
//  public int $backoff = 60;

  // for testing
  public int $timeout = 60;
  public int $tries = 3;
  public int $backoff = 2;

  protected $subject;
  protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subject, $message)
    {
      $this->subject = $subject;
      $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      Log::info("Sending admin notification: {$this->subject}");
      try {
        $adminEmail = env('ADMIN_EMAIL', 'default_admin@example.com'); // Set a default value as a fallback
        Mail::to($adminEmail)->send(new AdminNotification($this->subject, $this->message));
      } catch (\Exception $e) {
        Log::error("Error sending email: " . $e->getMessage());
      }
      Log::info("Admin notification sent");
    }
}
