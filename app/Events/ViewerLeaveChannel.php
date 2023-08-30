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
use Illuminate\Broadcasting\InteractsWithBroadcasting;

class ViewerLeaveChannel implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $currentViewers;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($currentViewers)
    {
        $this->currentViewers = $currentViewers;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('viewerCount.'. $this->currentViewers->channel_id);
    }

    /**
     * Get the data to broadcast for the model.
     *
     * @param  string  $event
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'viewer_count' => 1,
        ];
    }
}
