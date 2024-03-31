<?php

namespace App\Services\MistServer;

class PlaybackService extends MistServerService {
  public function listStreams() {
    return $this->sendRequest('/api', 'POST', ['streams' => new \stdClass()]);
  }

  public function addStream($streamDetails) {
    return $this->sendRequest('/api', 'POST', ['addstream' => $streamDetails]);
  }

  public function deleteStream($streamId) {
    return $this->sendRequest('/api', 'POST', ['deletestream' => ['id' => $streamId]]);
  }
}
