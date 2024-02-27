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

class MistTriggerPushEndEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

  public mixed $streamName;
  public mixed $requestUrl;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
      $this->streamName = $data['streamName'];
      $this->requestUrl = $data['requestUrl'];
    }

  // MistServer will be providing the mist_stream_wildcard_name as the StreamName.

  public function broadcastAs(): string
  {
//            return 'chat.'. $this->chatMessage->channel_id;
    return 'push-end';
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return Channel|PrivateChannel
   */
    public function broadcastOn(): Channel|PrivateChannel {
      // Ensure that 'streamName' is available in the data array
      // and dynamically use it to define the channel name.
      return new PrivateChannel('mistStreamWildcard.' . $this->streamName);
    }

  /**
   * Get the data to broadcast.
   *
   * @return array
   */
  public function broadcastWith(): array {
    return [
        'streamName' => $this->streamName,
        'requestUrl' => $this->requestUrl,
    ];
  }
}
