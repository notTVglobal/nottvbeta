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

class NewChatTestMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

//    public $message;
//    public $user;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
//    public function __construct($message, $user)
    public function __construct($message)
    {
        $this->message = $message;
//        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('private.chatTest.1');
    }

    public function broadcastAs()
    {
        return 'playground';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message
//            'foo' => 'bar'
        ];
    }
}
