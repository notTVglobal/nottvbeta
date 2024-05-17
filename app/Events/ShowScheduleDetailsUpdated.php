<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShowScheduleDetailsUpdated implements ShouldBroadcastNow
{

  public $contentType;
  public $contentId;
  public $scheduleDetails;


    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
  public function __construct($contentType, $contentId, $scheduleDetails)
  {
    $this->contentType = $contentType;
    $this->contentId = $contentId;
    $this->scheduleDetails = $scheduleDetails;
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
        'scheduleDetails' => $this->scheduleDetails,
    ];
  }

  public function broadcastAs(): string {
    return 'ShowScheduleDetailsUpdated';
  }
}
