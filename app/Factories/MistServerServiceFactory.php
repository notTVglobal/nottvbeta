<?php

namespace App\Factories;

use App\Services\MistServer\MistServerService;
use App\Services\MistServer\PlaybackService;
use App\Services\MistServer\PushService;
use App\Services\MistServer\RecordingService;
use App\Services\MistServer\VodService;

class MistServerServiceFactory
{
  /**
   * Creates an instance of MistServerService or a subclass thereof, configured with the given server type.
   *
   * @param  string  $serverType  The type of server to configure the service for (e.g., 'push', 'playback').
   * @return MistServerService    An instance of MistServerService or subclass, configured according to the specified server type.
   */
  public static function make(string $serverType = 'push'): MistServerService
  {
    return match ($serverType) {
      'push' => new PushService(),
      'playback' => new PlaybackService(),
      'recording' => new RecordingService(),
      'vod' => new VodService(),
      default => new MistServerService($serverType),
    };
  }
}
