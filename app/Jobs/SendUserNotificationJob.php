<?php

namespace App\Jobs;

use App\Events\NewNotificationEvent;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendUserNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected int $userId;
  protected string $title;
  protected string $message;
  protected string $url;

  /**
   * Create a new job instance.
   *
   * @param int $userId
   * @param string $title
   * @param string $message
   * @param string $url
   */
    public function __construct(int $userId, string $title, string $message, string $url)
    {
      $this->userId = $userId;
      $this->title = $title;
      $this->message = $message;
      $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
      $notification = new Notification;
      $notification->user_id = $this->userId;
      $notification->url = $this->url;
      $notification->title = $this->title;
      $notification->message = $this->message;
      $notification->save();

      // Trigger the event to broadcast the new notification
      event(new NewNotificationEvent($notification));
    }
}
