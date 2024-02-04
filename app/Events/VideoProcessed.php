<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class VideoProcessed implements ShouldBroadcastNow {
  public $userId;

  use Dispatchable, InteractsWithSockets, SerializesModels;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($userId) {
    $this->userId = $userId;
  }

  public function broadcastAs(): string {
    return 'userNotifications';
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return Channel|PrivateChannel|array
   */
  public function broadcastOn(): Channel|PrivateChannel|array {
    return new PrivateChannel('user.' . $this->userId);
  }

  public function broadcastWith() {
    return [
        'notification' => [
            'title' => 'Your video has been processed',
        ]
    ];
  }
}
