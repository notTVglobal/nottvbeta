<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MistServerController extends Controller
{
    public function handleTrigger(Request $request)
    {
      $triggerName = $request->header('X-Trigger');
      $payload = $request->getContent(); // For raw POST body; consider json_decode($request->getContent()) if it's JSON

      // Log for debugging
      Log::info('Received MistServer trigger', compact('triggerName', 'payload'));

      // Your handling logic here, e.g., validating the request_url if present
      if (!isset($payload['request_url'])) {
        Log::error('Missing request_url');
        return response('Missing request_url', 400);
      }

      // Positive response for blocking triggers
      return response('1', 200);
    }

    public function uri() {
      return AppSetting::where('id', 1)->pluck('mist_server_uri')->first();
    }
}
