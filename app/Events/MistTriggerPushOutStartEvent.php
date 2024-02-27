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

class MistTriggerPushOutStartEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

  public mixed $streamName;
  public mixed $requestUrl;
  public mixed $mistStreamWildcardId;

    /**
     * Create a new event instance.
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
      $this->streamName = $data['streamName'];
      $this->requestUrl = $data['requestUrl'];
      $this->mistStreamWildcardId = $data['mistStreamWildcardId'];
    }

    // MistServer will be providing the mist_stream_wildcard_name as the StreamName.

  public function broadcastAs(): string
  {
    return 'push-out-start';
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return Channel
   */
  public function broadcastOn(): Channel {
    Log::info('Broadcasting on channel: mistStreamWildcard.' . $this->mistStreamWildcardId);
    return new Channel('mistStreamWildcard.' . $this->mistStreamWildcardId);
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
