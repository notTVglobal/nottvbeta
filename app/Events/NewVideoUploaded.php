<?php

namespace App\Events;

use App\Models\Video;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewVideoUploaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

  public $video;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
      $this->video = $video;
    }

//    /**
//     * Get the channels the event should broadcast on.
//     *
//     * @return Channel|PrivateChannel
//     */
//    public function broadcastOn(): Channel|PrivateChannel {
//        return new PrivateChannel('channel-name');
//    }
}
