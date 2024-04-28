<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangeFirstPlayVideo implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $videoData;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($videoData)
    {
        $this->videoData = $videoData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('firstPlayVideo');
    }

  public function broadcastAs(): string
  {
//            return 'chat.'. $this->chatMessage->channel_id;
    return 'firstPlayVideo';
  }
  public function broadcastWith()
  {
    return [
        'firstPlayVideo' => [
            'source' => $this->videoData->source ?? null,
            'mediaType' => $this->videoData->mediaType ?? null,
            'type' => $this->videoData->sourceType ?? null,
            'name' => $this->videoData->name ?? null,
        ]
    ];
  }
}
