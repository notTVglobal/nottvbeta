<?php

namespace App\Events;

use App\Models\NewsPersonMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNewsPersonMessage implements ShouldBroadcastNow {
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public int $recipientId;

  /**
   * Create a new event instance.
   */
  public function __construct(int $recipientId) {
    $this->recipientId = $recipientId;
  }


  public function broadcastAs(): string {
    return 'newsPersonMessage';
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return PrivateChannel
   */
  public function broadcastOn(): PrivateChannel {
    return new PrivateChannel('news-person-messages.' . $this->recipientId);
  }

  public function broadcastWith(): array {
    return [
        'increment' => 1,
    ];
  }
}
