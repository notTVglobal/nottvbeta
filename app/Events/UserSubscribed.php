<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserSubscribed implements ShouldBroadcastNow {
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public User $user;

  /**
   * Create a new event instance.
   */
  public function __construct(User $user) {
    $this->user = $user;
  }

  public function broadcastAs(): string {
    return 'userSubscribed';
  }


  /**
   * Get the channels the event should broadcast on.
   *
   * @return PrivateChannel
   */
  public function broadcastOn(): PrivateChannel {
    return new PrivateChannel('user.' . $this->user->id);

  }

  /**
   * Get the data to broadcast.
   *
   * @return array
   */
  public function broadcastWith() {
    return [
        'isSubscriber'         => $this->user->subscribed('default'),
        'hasAccount'           => $this->user->hasAccount(),
        'subscription'         => $this->user->subscription('default'),
        'isSubscriptionActive' => (bool) optional($this->user->subscription('default'))->active(),

    ];
  }
}
