<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Services\MistServerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MistServerController extends Controller {

  protected MistServerService $mistServerService;

  public function __construct(MistServerService $mistServerService) {
    $this->mistServerService = $mistServerService;
  }

  public function uri() {
    return AppSetting::where('id', 1)->pluck('mist_server_uri')->first();
  }

  public function configBackup()
  {
    return $this->mistServerService->configBackup();
  }

  public function configRestore()
  {
    return $this->mistServerService->configRestore();
  }
}
