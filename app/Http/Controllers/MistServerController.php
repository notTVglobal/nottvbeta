<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;
use App\Services\MistServer\MistServerService;
use App\Factories\MistServerServiceFactory;
use Illuminate\Support\Facades\Log;

class MistServerController extends Controller {

  protected MistServerService $mistService;

  public function checkSend(Request $request): \Illuminate\Http\JsonResponse {
    // Validate the serverType from the request
    $validated = $request->validate([
        'serverType' => 'required|string'
    ]);

    // Extract serverType from the validated data
    $serverType = $validated['serverType'];

    // Instantiate the MistServerService using the factory with the dynamic serverType
    $this->mistService = MistServerServiceFactory::make($serverType);

    // Prepare your data for the API request
    $originalData = [
      // Your API request data here
        'capabilities' => 'true',
    ];

    try {
      // Use the service to perform the desired operation, e.g., sending data to MistServer
      $response = $this->mistService->send($originalData);

      // Return a successful response
      return response()->json([
          'success' => true,
          'message' => 'Successfully communicated with MistServer.',
          'response' => $response,
      ]);
    } catch (\Exception $e) {
      // Log the error and return an error response
      \Log::error('MistServer checkSend error:', ['error' => $e->getMessage()]);

      return response()->json([
          'success' => false,
          'message' => 'Failed to communicate with MistServer.',
          'error' => $e->getMessage(),
      ], 500);
    }
  }

  public function configBackup(): \Illuminate\Http\JsonResponse {
    // TODO: Create a command to backup the configs for all of the MistServer's by ServerType
    // TODO: Use the code below that is commented out to get started. The Backup and Restore
    // TODO: buttons in the Admin/Settings will need to be checked ... we may not want to restore
    // TODO: all of the MistServers at the same time, so we need a way to select the server.
    // Validate the serverType from the request
//    $validated = $request->validate([
//        'serverType' => 'required|string'
//    ]);

    // Extract serverType from the validated data
//    $serverType = $validated['serverType'];

    // Instantiate the MistServerService using the factory with the dynamic serverType
    $this->mistService = MistServerServiceFactory::make('push');
    return $this->mistService->configBackup();
  }

  public function configRestore(): \Illuminate\Http\JsonResponse {
    $this->mistService = MistServerServiceFactory::make('push');
    return $this->mistService->configRestore();
  }

  public function uri() {
    return AppSetting::where('id', 1)->pluck('mist_server_uri')->first();
  }

}
