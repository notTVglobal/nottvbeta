<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreatorContentStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

  public $contentType;
  public $contentId;
  public $meta;

    /**
     * Create a new event instance.
     *
     * @return void
     */
  public function __construct($contentType, $contentId, $meta)
  {
    $this->contentType = $contentType;
    $this->contentId = $contentId;
    $this->meta = $meta;
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return Channel|PrivateChannel|array
   */
    public function broadcastOn(): Channel|PrivateChannel|array {
      return new PresenceChannel("creator.{$this->contentType}.{$this->contentId}");
    }

  public function broadcastWith(): array {
    return [
        'meta' => $this->meta,
    ];
  }

  public function broadcastAs(): string {
    return 'CreatorContentStatusUpdated';
  }
}
