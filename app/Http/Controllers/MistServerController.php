<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AppSetting;
use App\Services\MistServer\MistServerService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class MistServerController extends Controller {

  protected $mistServerService;

  public function __construct(MistServerService $mistServerService) {
    $this->mistServerService = $mistServerService;
  }

  public function uri() {
    return AppSetting::where('id', 1)->pluck('mist_server_uri')->first();
  }

  public function checkSend(Request $request): \Illuminate\Http\JsonResponse {
    try {

      // TODO: Allow the send() to accept the serverType
      // A simple MistServer check
      $response = $this->mistServerService->send([
          'capabilities' => 'true'
      ]);

      // Log the response from the MistServer for debugging purposes
      Log::info('MistServer checkSend response:', ['response' => $response]);

      // Return a successful response to the Vue frontend
      return response()->json([
          'success' => true,
          'message' => 'Successfully communicated with MistServer.',
          'response' => $response, // Optionally include the response from the MistServer
      ]);

    } catch (\Exception $e) {
      // Log the error
      Log::error('MistServer checkSend error:', ['error' => $e->getMessage()]);

      // Return an error response to the frontend
      return response()->json([
          'success' => false,
          'message' => 'Failed to communicate with MistServer.',
          'error' => $e->getMessage(),
      ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
    }
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
