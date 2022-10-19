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
use App\Models\ChatMessage;

class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatMessage;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( ChatMessage $chatMessage )
    {
        $this->chatMessage = $chatMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // setup different "chat rooms" by passing an additional
        //change ('chat.) . 1 to ('chat.' . $this->chatMessage->roomId
        //
        // For more info see:
        // https://www.youtube.com/watch?v=CkRGJC0ytdU
        return new PrivateChannel('chat.'. $this->chatMessage->channel_id );
    }

    public function broadcastAs() {
            return 'message.new';
    }
}
