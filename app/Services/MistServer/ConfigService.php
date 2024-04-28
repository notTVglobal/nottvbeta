<?php

namespace App\Services\MistServer;

use Exception;

class ConfigService extends MistServerService {

  // save() and log() need to be tied to different servers...
  // when we get into kubernetes and load balancing this will need to be reconsidered...
  // we may need to just re-create a dockerfile if the config is changed.
  public function backupConfig() {
// Assuming MistServer API supports direct backup calls
    return $this->sendRequest('/api', 'POST', ['config_backup' => new \stdClass()]);
  }

  public function restoreConfig($configDetails) {
    return $this->sendRequest('/api', 'POST', ['config_restore' => $configDetails]);
  }

  /**
   * @throws Exception
   */
  public function log(): array {
    return $this->send(['streams' => new \stdClass()]);
  }

  /**
   * @throws Exception
   * Used to force the server to save the config file.
   */
  public function save(): array {
    return $this->send(['save' => new \stdClass()]);
  }

}
