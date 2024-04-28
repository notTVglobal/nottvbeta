<?php

namespace App\Services\MistServer;

use Exception;

class PlaybackService extends MistServerService {

  public function fetchStreamInfo(string $streamName = ''): \Illuminate\Http\JsonResponse {
    return $this->fetchStreamInfo($streamName);
  }

  /**
   * @throws Exception
   */
  public function listStreams(): array {
    return $this->send(['streams' => new \stdClass()]);
  }

  /**
   * @throws Exception
   */
  public function activeStreams(): array {
    return $this->send(['active_streams' => true]);
  }

  /**
   * @throws Exception
   */
  public function statsStreams(): array {
    return $this->send(['stats_streams' => new \stdClass()]);
  }

  /**
   * @throws Exception
   */
  public function addStream($streamDetails): array {
    return $this->send(['addstream' => $streamDetails]);
  }

  /**
   * @throws Exception
   */
  public function deleteStream($streamId): array {
    return $this->send(['deletestream' => ['id' => $streamId]]);
  }
}
