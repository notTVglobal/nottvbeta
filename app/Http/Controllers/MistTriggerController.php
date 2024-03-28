<?php

namespace App\Http\Controllers;

use App\Events\MistTriggerPushEnd;
use App\Events\MistTriggerPushOutStart;
use App\Events\MistTriggerRecordingStop;
use App\Jobs\UpdateRecordingModel;
use App\Jobs\UpdateRecordingModelAndNotify;
use App\Models\AppSetting;
use App\Models\MistStreamPushDestination;
use App\Models\MistStreamWildcard;
use App\Models\MistTrigger;
use App\Models\Recording;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MistTriggerController extends Controller {


  public function handlePushOutStart(Request $request): Response {
//    Log::debug('handlePushOutStart Raw Request', [
//    'headers' => $request->headers->all(),
//    'body'    => $request->getContent() // For raw body content
//    ]);
//
    Log::alert('handle Push Out Start');

    $bodyContent = $request->getContent();
    $lines = explode("\n", $bodyContent);

    $streamName = trim($lines[0]) ?? 'unknown';
    $requestUrl = trim($lines[1]) ?? 'unknown';

    // First, retrieve the MistStreamWildcard based on $streamName
    $mistStreamWildcard = MistStreamWildcard::where('name', $streamName)->first();

    if ($mistStreamWildcard) {
      // Attempt to find the matching MistStreamPushDestination directly from the wildcard
      $pushDestination = $mistStreamWildcard->mistStreamPushDestination()
          ->whereRaw("CONCAT(rtmp_url, rtmp_key) = ?", [$requestUrl])
          ->first();

      if ($pushDestination) {
        Log::debug('push is a destination');
        $pushDestination->push_is_started = 1;
        $pushDestination->update();
        broadcast(new MistTriggerPushOutStart([
            'streamName'           => $streamName,
            'mistStreamWildcardId' => $pushDestination->mist_stream_wildcard_id,
            'requestUrl'           => $requestUrl
        ]));
      } elseif ($this->checkForRecording($requestUrl)) {
        Log::debug('push is a recording');
        // Handle recording logic here. You might want to flag the recording in the database or perform some other action.
        $this->handleIfRecording($mistStreamWildcard, $requestUrl);
      } else {
        Log::debug('push is neither a known destination nor a recording');
        // Handle the scenario where the stream is neither a known push destination nor a recording.
      }
    } else {
      Log::debug('MistStreamWildcard not found for the given stream name.');
      // Handle the scenario where no wildcard matches the stream name.
    }

    return response('1', 200);
  }

  public function handlePushEnd(Request $request): Response {
//    Log::debug('handlePushEnd Raw Request', [
//        'headers' => $request->headers->all(),
//        'body'    => $request->getContent() // For raw body content
//    ]);

    Log::alert('handle Push End');

    // Similar to handlePushOutStart

    //// tec21: We'll come back to this
//    broadcast(new MistTriggerPushEnd($request->all())); // Customize

//    push ID (integer)
//    stream name (string)
//    target URI, before variables/triggers affected it (string)
//    target URI, afterwards, as actually used (string)
//    last 10 log messages (JSON array string)
//    most recent push status (JSON object string)
    return response('1', 200);

  }

  public function handleRecordingEnd(Request $request): Response {
    // Log the incoming request content for debugging
//    Log::debug('Handling recording end. Request content:', ['content' => $request->getContent()]);

    Log::alert('handle Recording End');

    $parsedContent = $this->parseRecordingEndContent($request->getContent());
    // Optionally log the parsed content if needed
    Log::debug('Parsed recording end content:', ['parsedContent' => $parsedContent]);

    $recording = $this->createRecordingEntry($parsedContent);
    // Log after creating the recording entry
    Log::debug('Recording entry created:', ['recording' => $recording]);

    $this->clearRecordingMetadataAndBroadcast($parsedContent['streamName']);
    // Log after clearing recording metadata and broadcasting
    Log::debug('Cleared recording metadata and broadcast for stream:', ['streamName' => $parsedContent['streamName']]);

    UpdateRecordingModelAndNotify::dispatch($recording);
    // Log after dispatching the job
    Log::debug('Dispatched UpdateRecordingModelAndNotify job for recording:', ['recording' => $recording]);

    return response('1', 200);
  }




//  public function handleRecordingEnd(Request $request): Response {
//    $bodyContent = $request->getContent();
//    $lines = explode("\n", $bodyContent);
//
//    // Parse the body content
//    $streamName = trim($lines[0]) ?? 'unknown';
//    $filePath = trim($lines[1]) ?? 'unknown';
//    $fileType = trim($lines[2]) ?? 'unknown';
//    $bytesRecorded = (int) trim($lines[3]) ?? 0;
//    $secondsSpentRecording = (int) trim($lines[4]) ?? 0;
//    $unixTimeRecordingStarted = (int) trim($lines[5]) ?? 0;
//    $unixTimeRecordingStopped = (int) trim($lines[6]) ?? 0;
//    $totalMillisecondsRecorded = (int) trim($lines[7]) ?? 0;
//    $millisecondsFirstPacket = (int) trim($lines[8]) ?? 0;
//    $millisecondsLastPacket = (int) trim($lines[9]) ?? 0;
//    $machineReadableReason = trim($lines[10]) ?? 'unknown';
//    $humanReadableReason = trim($lines[11]) ?? 'unknown';
//
//    // Convert Unix timestamps to Carbon instances for start and end times
//    $startTime = Carbon::createFromTimestamp($unixTimeRecordingStarted);
//    $endTime = Carbon::createFromTimestamp($unixTimeRecordingStopped);
//
//    // Remove the query string from the URL if present
//    $urlWithoutQuery = parse_url($filePath, PHP_URL_PATH);
//    // Extract the file extension using pathinfo() on the URL without the query string
//    $fileExtension = pathinfo($urlWithoutQuery, PATHINFO_EXTENSION);
//
//    // Create a new recording entry
//    $recording = Recording::create([
//        'stream_name'                    => $streamName,
//        'path'                           => $filePath,
//        'file_extension'                 => $fileExtension,
//        'mime_type'                      => $fileType,
//        'start_time'                     => $startTime,
//        'end_time'                       => $endTime,
//        'bytes_recorded'                 => $bytesRecorded,
//        'seconds_spent_recording'        => $secondsSpentRecording,
//        'total_milliseconds_recorded'    => $totalMillisecondsRecorded,
//        'milliseconds_first_packet'      => $millisecondsFirstPacket,
//        'milliseconds_last_packet'       => $millisecondsLastPacket,
//        'reason_for_exit'                => $machineReadableReason,
//        'human_readable_reason_for_exit' => $humanReadableReason,
//    ]);
//
//    Log::info("New recording created for: {$streamName}");
//
//    // First, retrieve the MistStreamWildcard based on $streamName
//    $mistStreamWildcard = MistStreamWildcard::where('name', $streamName)->first();
//    if ($mistStreamWildcard) {
//      $mistStreamWildcard->is_recording = false;
//
//      // Retrieve the current metadata, or initialize as an empty array if null
//      $currentMetadata = $mistStreamWildcard->metadata ?? [];
//
//      // Remove 'recordingDestination' and 'recordingStartTime' from metadata
//      unset($currentMetadata['recordingDestination'], $currentMetadata['recordingStartTime']);
//
//      // Update the metadata column
//      $mistStreamWildcard->metadata = $currentMetadata;
//
//      // Save the changes to the database
//      $mistStreamWildcard->save();
//
//      // Optionally, log or broadcast an event
//      Log::info("Cleared recording metadata for stream: {$mistStreamWildcard->name}");
//    }
//
//    UpdateRecordingModelAndNotify::dispatch($recording);
//
//    return response('1', 200);
//  }


  /**
   * Validates a video request to ensure it meets criteria for viewing.
   *
   * @param Request $request
   * @return Application|ResponseFactory|Response
   */
  public function validateUser(Request $request): Response|Application|ResponseFactory {

    // NOTE: This currently uses the USER_NEW trigger.
    // The request_url is on a different line of the
    // request body in CONN_PLAY. And CONN_PLAY makes
    // more requests to the endpoint than USER_NEW.
    // It sends a request with every packet played.
    // Whereas USER_NEW sends a request to start a
    // new session and caches it on the MistServer.

    // Check for allowed triggers from the header

//    Log::debug('Raw Request', [
//        'headers' => $request->headers->all(),
//        'body'    => $request->getContent() // For raw body content
//    ]);
//    return response('1', 200)
//        ->header('Content-Type', 'text/plain');
//
    $allowedTriggers = ["USER_NEW", "CONN_OPEN", "CONN_CLOSE", "CONN_PLAY"];

    if (!in_array($request->header('X-Trigger'), $allowedTriggers)) {
      error_log("This script is not compatible with triggers other than USER_NEW, CONN_OPEN, CONN_CLOSE, and CONN_PLAY");
      Log::warning('invalid_trigger');
      //     Log raw request data for testing only
//      Log::debug('Raw Request', [
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
      Log::alert("Hash mismatch for user ID: {$userId}");

      return response('Unauthorized - Hash mismatch', 401);  // Respond with 401 Unauthorized for invalid hash
    }

//    Log::debug('Hash Debug', [
//        'Expected Hash' => $hashExpected,
//        'Received Hash' => $hashReceived,
//        'Hash Base String (Expected)' => $userId . $ipAddress . $secretKey,
//        'User ID' => $userId,
//        'IP Address from Mist' => $ipAddress,
//        'Secret Key' => $secretKey,
//    ]);

    // If hash matches, proceed with additional checks (if any) and ultimately approve access
//    Log::debug('Access granted');

    return response('1', 200); // Positive response for blocking triggers

  }

  public function logTrigger(Request $request) {

    if ($request->header('X-Trigger')) {

//            $payload = array(["stream","ip","protocol", "request_url"]);

      MistTrigger::create(['data' => $request]);

      return response('1', 'http://beta.local:8081/api/mistTrigger')->header('X-Trigger', 'STREAM_PUSH');
    }

  }

  protected function checkForRecording($requestUrl): bool {
    // This regular expression checks if the URL path suggests it's a recording,
    // allowing for query parameters after the .mkv extension
    return preg_match('/\/media\/recordings\/.*\.mkv(\?.*)?$/', $requestUrl) > 0;
  }

  protected function handleIfRecording($mistStreamWildcard, $requestUrl): void {
    Log::debug("Recording detected for stream: $mistStreamWildcard->name on URL: $requestUrl");
    // Marking the recording as started in the database
    $mistStreamWildcard->is_recording = true;

    // Preparing the metadata update, adding or updating the 'recordingDestination' and 'recordingStartTime'
    $currentMetadata = $mistStreamWildcard->metadata ?? []; // Get current metadata or initialize as empty array
    $currentMetadata['recordingDestination'] = $requestUrl; // Set or update the recording URL
    $currentMetadata['recordingStartTime'] = now()->toDateTimeString(); // Set or update the recording start time

    $mistStreamWildcard->metadata = $currentMetadata; // Update the metadata column

    $mistStreamWildcard->save(); // Save the changes to the database

    // Optionally, you could broadcast an event or perform other actions as needed
  }

  protected function parseRecordingEndContent(string $bodyContent): array {
    $lines = explode("\n", $bodyContent);
    $parsedContent = [
        'streamName' => trim($lines[0]) ?? 'unknown',
        'filePath' => trim($lines[1]) ?? 'unknown',
        'fileType' => trim($lines[2]) ?? 'unknown',
        'bytesRecorded' => (int) trim($lines[3]) ?? 0,
        'secondsSpentRecording' => (int) trim($lines[4]) ?? 0,
        'unixTimeRecordingStarted' => (int) trim($lines[5]) ?? 0,
        'unixTimeRecordingStopped' => (int) trim($lines[6]) ?? 0,
        'totalMillisecondsRecorded' => (int) trim($lines[7]) ?? 0,
        'millisecondsFirstPacket' => (int) trim($lines[8]) ?? 0,
        'millisecondsLastPacket' => (int) trim($lines[9]) ?? 0,
        'machineReadableReason' => trim($lines[10]) ?? 'unknown',
        'humanReadableReason' => trim($lines[11]) ?? 'unknown',
    ];

    $parsedContent['urlWithoutQuery'] = parse_url($parsedContent['filePath'], PHP_URL_PATH);
    $parsedContent['fileExtension'] = pathinfo($parsedContent['urlWithoutQuery'], PATHINFO_EXTENSION);
    $parsedContent['startTime'] = Carbon::createFromTimestamp($parsedContent['unixTimeRecordingStarted']);
    $parsedContent['endTime'] = Carbon::createFromTimestamp($parsedContent['unixTimeRecordingStopped']);

    return $parsedContent;
  }

  protected function createRecordingEntry(array $parsedContent): Recording {
    return Recording::create([
        'stream_name' => $parsedContent['streamName'],
        'path' => $parsedContent['filePath'],
        'file_extension' => $parsedContent['fileExtension'],
        'mime_type' => $parsedContent['fileType'],
        'start_time' => $parsedContent['startTime'],
        'end_time' => $parsedContent['endTime'],
        'bytes_recorded' => $parsedContent['bytesRecorded'],
        'seconds_spent_recording' => $parsedContent['secondsSpentRecording'],
        'total_milliseconds_recorded' => $parsedContent['totalMillisecondsRecorded'],
        'milliseconds_first_packet' => $parsedContent['millisecondsFirstPacket'],
        'milliseconds_last_packet' => $parsedContent['millisecondsLastPacket'],
        'reason_for_exit' => $parsedContent['machineReadableReason'],
        'human_readable_reason_for_exit' => $parsedContent['humanReadableReason'],
    ]);
  }


  protected function clearRecordingMetadataAndBroadcast(string $streamName): void {
    $mistStreamWildcard = MistStreamWildcard::where('name', $streamName)->first();
    if ($mistStreamWildcard) {
      $currentMetadata = $mistStreamWildcard->metadata ?? [];
      unset($currentMetadata['recordingDestination'], $currentMetadata['recordingStartTime']);
      $mistStreamWildcard->metadata = $currentMetadata;
      $mistStreamWildcard->is_recording = false;
      $mistStreamWildcard->save();
      Log::info("Cleared recording metadata for stream: {$streamName}");
      broadcast(new MistTriggerRecordingStop($mistStreamWildcard->id, 'stopped'));
    }
  }

//  public function handleTrigger(Request $request) {
//    $triggerName = $request->header('X-Trigger');
//    $payload = $request->getContent(); // For raw POST body; consider json_decode($request->getContent()) if it's JSON
//
//    // Log for debugging
//    Log::info('Received MistServer trigger', compact('triggerName', 'payload'));
//
//    // Your handling logic here, e.g., validating the request_url if present
//    if (!isset($payload['request_url'])) {
//      Log::error('Missing request_url');
//
//      return response('Missing request_url', 400);
//    }
//
//    // Positive response for blocking triggers
//    return response('1', 200);
//  }
}
