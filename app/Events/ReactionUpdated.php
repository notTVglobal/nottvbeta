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
use Illuminate\Support\Facades\Log;

class ReactionUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

  public int $messageId;
  public int $channelId;
  public string $reactionType;
  public int $userId;

  public function __construct(int $messageId, int $channelId, string $reactionType, int $userId)
  {
    $this->messageId = $messageId;
    $this->channelId = $channelId;
    $this->reactionType = $reactionType;
    $this->userId = $userId;
  }

  public function broadcastOn(): Channel|PrivateChannel|array
  {
//    Log::debug('Broadcasting reaction update', [
//        'message_id' => $this->messageId,
//        'channel_id' => $this->channelId,
//        'reaction_type' => $this->reactionType,
//        'user_id' => $this->userId,
//    ]);

    return new PrivateChannel('chat.' . $this->channelId);
  }

  public function broadcastWith(): array {
    // Simply return the existing message data without any additional database queries.
    return [
        'message_id' => $this->messageId,
        'channel_id' => $this->channelId,
        'reaction_type' => $this->reactionType,  // pass only the necessary data
        'user_id' => $this->userId,  // who reacted
    ];
  }

  // Define the custom event name for broadcasting
  public function broadcastAs(): string
  {
    return 'ReactionUpdated';
  }

//  public function broadcastToEveryone(): static
//  {
//    return $this->dontBroadcastToCurrentUser();
//  }
}
