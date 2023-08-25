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

class ViewerAdded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public CurrentViewers $currentViewers;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CurrentViewers $currentViewers)
    {
        $this->currentViewers = $currentViewers;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PrivateChannel
     */
    public function broadcastOn(): Channel|PrivateChannel
    {
        return new PrivateChannel('channel.'. $this->currentViewers->channel_id);
    }

//    public function broadcastAs(): string
//    {
//            return 'chat.'. $this->currentViewers->channel_id;
////        return 'chat'+'';
//    }

    public function broadcastWith(): array
    {
        return [
            1
//            'channel_id' => $this->currentViewers->channel_id,
//            'viewerCount' => 1
        ];
    }
}
