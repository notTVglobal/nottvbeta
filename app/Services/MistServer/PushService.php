<?php

namespace App\Services\MistServer;

class PushService extends MistServerService {
  public function pushList() {
    return $this->sendRequest('/api', 'POST', ['push_list' => new \stdClass()]);
  }

  public function pushStart($streamDetails) {
    return $this->sendRequest('/api', 'POST', ['push_start' => $streamDetails]);
  }

  public function pushStop($pushId) {
    return $this->sendRequest('/api', 'POST', ['push_stop' => ['id' => $pushId]]);
  }

  public function pushAutoAdd($ruleDetails) {
    return $this->sendRequest('/api', 'POST', ['push_auto_add' => $ruleDetails]);
  }

  public function pushAutoRemove($ruleId) {
    return $this->sendRequest('/api', 'POST', ['push_auto_remove' => ['id' => $ruleId]]);
  }

  public function pushAutoList() {
    return $this->sendRequest('/api', 'POST', ['push_auto_list' => new \stdClass()]);
  }

  public function pushSettings($settings) {
    return $this->sendRequest('/api', 'POST', ['push_settings' => $settings]);
  }

// Add more methods as necessary
}
