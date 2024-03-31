<?php

namespace App\Services\MistServer;

class ConfigService extends MistServerService {
  public function backupConfig() {
// Assuming MistServer API supports direct backup calls
    return $this->sendRequest('/api', 'POST', ['config_backup' => new \stdClass()]);
  }

  public function restoreConfig($configDetails) {
    return $this->sendRequest('/api', 'POST', ['config_restore' => $configDetails]);
  }
}
