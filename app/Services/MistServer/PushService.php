<?php

namespace App\Services\MistServer;

use App\Models\MistStreamPushDestination;
use Exception;
use Illuminate\Support\Facades\Log;

class PushService extends MistServerService {


  public function pushStart($data) {
    return $this->send(['push_start' => $data]);
  }


  public function pushStop($pushId) {
    return $this->send(['push_stop' => $pushId]);
  }

  /**
   * @throws Exception
   */
  public function pushAutoAdd(MistStreamPushDestination $mistStreamPushDestination, bool $isAddOperation): array {
    Log::debug('MistStreamPushDestination ID: ' . $mistStreamPushDestination->id); //    $this->ensureWildcardLoaded($mistStreamPushDestination);

    $targetURL = $mistStreamPushDestination->full_push_uri;
    $streamName = $mistStreamPushDestination->stream_name;

    $data = [
        "push_auto_add" => [
            $streamName,
            $targetURL,
        ]
    ];

    $mistStreamPushDestination->has_auto_push = $isAddOperation ? 1 : 0;
    $mistStreamPushDestination->save();

    return $this->send($data);
//    return $this->send(['push_auto_add' => $data]);

  }
//
//  public function pushAutoRemove($ruleId) {
//    return $this->sendRequest('/api', 'POST', ['push_auto_remove' => ['id' => $ruleId]]);
//  }

  /**
   * @throws Exception
   */
  public function pushAutoRemoveAll($streamName) {
    return $this->send(['push_auto_remove' => $streamName]);
  }

  /**
   * @throws Exception
   */
  public function fetchPushList(): array {
    return $this->send(['push_list' => true]);
  }

  public function fetchAutoPushList(): array {
    return $this->send(['push_auto_list' => true]);
  }

//  public function pushSettings($settings) {
//    return $this->sendRequest('/api', 'POST', ['push_settings' => $settings]);
//  }

// Add more methods as necessary
//  protected function ensureWildcardLoaded(MistStreamPushDestination $mistStreamPushDestination): void {
//    if (!$mistStreamPushDestination->relationLoaded('mistStreamWildcard')) {
//      $mistStreamPushDestination->load('mistStreamWildcard');
//    }
//  }
}
