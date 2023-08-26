<?php

namespace App\Events;

use App\Models\CurrentViewers;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ViewerCountDecrement implements ShouldBroadcastNow
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
        return new Channel('viewerCount');
    }

    public function broadcastWith(): array
    {
        return [
            'channel_id' => $this->currentViewers->channel_id,
        ];
    }
}
