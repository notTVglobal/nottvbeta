<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ChatMessage;
use App\Models\User;

class NewChatMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatMessage;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( ChatMessage $chatMessage )
//    public function __construct( $message )
    {
        $this->chatMessage = $chatMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel|PrivateChannel|array
    {
        // setup different "chat rooms" by passing an additional
        //change ('chat.) . 1 to ('chat.' . $this->chatMessage->roomId
        //
        // For more info see:
        // https://www.youtube.com/watch?v=CkRGJC0ytdU
//        return new PrivateChannel('chat.'. $this->chatMessage->channel_id);
        return new PrivateChannel('chat.'. 1);
    }

    public function broadcastAs(): string
    {
//            return 'chat.'. $this->chatMessage->channel_id;
            return 'chat';
    }
    public function broadcastWith()
    {
        $this->chatMessage->setAttribute('created_at', Carbon::now());
        return [
            'message' => $this->chatMessage,
        ];
    }
}
