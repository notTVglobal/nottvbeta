<?php

namespace App\Events;

use App\Models\MistServerActivePush;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PushDataFetched implements ShouldBroadcastNow {
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public MistServerActivePush $activePush;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(MistServerActivePush $activePush) {
    $this->activePush = $activePush;
  }


  /**
   * Get the channels the event should broadcast on.
   *
   * @return Channel|PrivateChannel|array
   */
  public function broadcastOn(): Channel|PrivateChannel|array {
    return new PrivateChannel('MistServerUpdate.' . $this->activePush->streamName);
  }

//  public function broadcastWith(): array {
//    return [
//        //
//    ];
//  }
}
