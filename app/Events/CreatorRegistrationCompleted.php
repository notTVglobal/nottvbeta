<?php

namespace App\Events;

use App\Models\InviteCode;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreatorRegistrationCompleted {
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public User $user;
  public InviteCode $inviteCode;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(User $user, InviteCode $inviteCode) {
    $this->user = $user;
    $this->inviteCode = $inviteCode;
  }

//  /**
//   * Get the channels the event should broadcast on.
//   *
//   * @return \Illuminate\Broadcasting\Channel|array
//   */
//  public function broadcastOn() {
//    return new PrivateChannel('channel-name');
//  }
}
