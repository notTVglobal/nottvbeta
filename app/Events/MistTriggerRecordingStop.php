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

class MistTriggerRecordingStop implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

  public string $mistSteamWildcardId;
  public string $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($mistSteamWildcardId, $status)
    {
      $this->mistSteamWildcardId = $mistSteamWildcardId; // MistStreamWildcard->id
      $this->status = $status; // 'stopped'
    }

  // MistServer will be providing the mist_stream_wildcard_name as the StreamName.

  public function broadcastAs(): string
  {
//            return 'chat.'. $this->chatMessage->channel_id;
    return 'mist-trigger-recording-stop';
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return Channel|PrivateChannel
   */
    public function broadcastOn(): Channel|PrivateChannel {
      // Ensure that 'streamName' is available in the data array
      // and dynamically use it to define the channel name.
      return new PrivateChannel('mistStreamWildcard.' . $this->mistSteamWildcardId);
    }

  /**
   * Get the data to broadcast.
   *
   * @return array
   */
  public function broadcastWith(): array {
    return [
        'mistSteamWildcardId' => $this->mistSteamWildcardId,
        'status' => $this->status,
    ];
  }
}
