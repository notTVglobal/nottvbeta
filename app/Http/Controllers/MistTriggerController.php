<?php

namespace App\Http\Controllers;

use App\Events\MistTriggerPushEndEvent;
use App\Events\MistTriggerPushOutStartEvent;
use App\Models\AppSetting;
use App\Models\MistStreamPushDestination;
use App\Models\MistTrigger;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class MistTriggerController extends Controller {


  /**
   * Validates a video request to ensure it meets criteria for viewing.
   *
   * @param Request $request
   * @return Application|ResponseFactory|Response
   */
  public function validateUser(Request $request) {

    // NOTE: This currently uses the USER_NEW trigger.
    // The request_url is on a different line of the
    // request body in CONN_PLAY. And CONN_PLAY makes
    // more requests to the endpoint than USER_NEW.
    // It sends a request with every packet played.
    // Whereas USER_NEW sends a request to start a
    // new session and caches it on the MistServer.

    // Check for allowed triggers from the header

    Log::info('Raw Request', [
        'headers' => $request->headers->all(),
        'body'    => $request->getContent() // For raw body content
    ]);
//    return response('1', 200)
//        ->header('Content-Type', 'text/plain');
//
    $allowedTriggers = ["USER_NEW", "CONN_OPEN", "CONN_CLOSE", "CONN_PLAY"];

    if (!in_array($request->header('X-Trigger'), $allowedTriggers)) {
      error_log("This script is not compatible with triggers other than USER_NEW, CONN_OPEN, CONN_CLOSE, and CONN_PLAY");
      Log::info('invalid_trigger');
      //     Log raw request data for testing only
//      Log::info('Raw Request', [
//          'headers' => $request->headers->all(),
//          'body' => $request->getContent() // For raw body content
//      ]);
      return response('Invalid trigger', 400); // Use a 400 Bad Request response for invalid triggers
    }

    // Retrieve the secret key from configuration or fallback
    $secretKey = AppSetting::where('id', 1)->first()->mist_access_control_secret ?? 'default_secret';

    // Parsing the request body
    $bodyContent = $request->getContent();
    // Split the string by line breaks to get an array of lines
    $lines = explode("\n", $bodyContent);

    $streamName = $lines[0] ?? 'unknown';
    // Assuming the IP address is always on the second line
    $ipAddress = $lines[1] ?? 'unknown'; // Default to 'unknown' if not found
    $requestUrl = $lines[4] ?? 'unknown'; // it's only line 5 if USER_NEW, otherwise its line 4.


//    Log::info($streamName);
    if ($streamName === 'test' || $streamName === 'first-play') {
      return response('1', 200);
    }

    parse_str(parse_url($requestUrl, PHP_URL_QUERY), $requestUrlParams);

    // Extracting userId and received hash from URL parameters
    $userId = $requestUrlParams['user'] ?? null;
    if (!$userId) {
      return response('User ID is required', 400);
    }
    $hashReceived = $requestUrlParams['hash'] ?? null;
    $hashExpected = hash('sha256', $userId . $ipAddress . $secretKey);

    // Comparing the expected hash with the received hash
    if ($hashReceived !== $hashExpected) {
      Log::warning("Hash mismatch for user ID: {$userId}");

      return response('Unauthorized - Hash mismatch', 401);  // Respond with 401 Unauthorized for invalid hash
    }

//    Log::alert('Hash Debug', [
//        'Expected Hash' => $hashExpected,
//        'Received Hash' => $hashReceived,
//        'Hash Base String (Expected)' => $userId . $ipAddress . $secretKey,
//        'User ID' => $userId,
//        'IP Address from Mist' => $ipAddress,
//        'Secret Key' => $secretKey,
//    ]);

    // If hash matches, proceed with additional checks (if any) and ultimately approve access
    Log::info('Access granted');

    return response('1', 200); // Positive response for blocking triggers

  }

  public function handlePushOutStart(Request $request): void {
    Log::info('handlePushOutStart Raw Request', [
        'headers' => $request->headers->all(),
        'body'    => $request->getContent() // For raw body content
    ]);

    // Parsing the request body
    $bodyContent = $request->getContent();
    $lines = explode("\n", $bodyContent);

    // The first line contains the mistStreamWildcard->name
    $streamName = trim($lines[0]) ?? 'unknown';
    // The second line contains the URI (the combination of rtmp_url and rtmp_key)
    $requestUrl = trim($lines[1]) ?? 'unknown';

    // Attempt to find the matching MistStreamPushDestination
    $pushDestination = MistStreamPushDestination::whereHas('mistStreamWildcard', function ($query) use ($streamName) {
      $query->where('name', $streamName);
    })
        ->where(function ($query) use ($requestUrl) {
          // Concatenate rtmp_url and rtmp_key and compare with requestUrl
          // Adjust the concatenation based on your DB driver
          $query->whereRaw("CONCAT(rtmp_url, rtmp_key) = ?", [$requestUrl]);
        })
        ->first();

    if ($pushDestination) {
      // If a match is found, process accordingly
      Log::info("Match found for stream name: {$streamName} with URL: {$requestUrl}");
      $pushDestination->push_is_started = 1;
      $pushDestination->update();
      // Proceed with your event broadcast or other logic
      broadcast(new MistTriggerPushOutStartEvent([
          'streamName' => $streamName,
          'mistStreamWildcardId' => $pushDestination->mist_stream_wildcard_id,
          'requestUrl' => $requestUrl
      ])); // Customize with your actual event and data
    } else {
      Log::warning("No matching destination found for stream name: {$streamName} with incoming URL: {$requestUrl}");
    }
  }


  public function handlePushEnd(Request $request)
  {
    Log::info('handlePushEnd Raw Request', [
        'headers' => $request->headers->all(),
        'body'    => $request->getContent() // For raw body content
    ]);
    // Similar to handlePushOutStart
    broadcast(new MistTriggerPushEndEvent($request->all())); // Customize
  }



  public function logTrigger(Request $request) {

    if ($request->header('X-Trigger')) {

//            $payload = array(["stream","ip","protocol", "request_url"]);

      MistTrigger::create(['data' => $request]);

      return response('1', 'http://beta.local:8081/api/mistTrigger')->header('X-Trigger', 'STREAM_PUSH');
    }

  }

  public function handleTrigger(Request $request) {
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
}
