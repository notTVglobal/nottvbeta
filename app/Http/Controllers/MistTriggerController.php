<?php

namespace App\Http\Controllers;

use App\Events\MistTriggerPushEndEvent;
use App\Events\MistTriggerPushOutStartEvent;
use App\Models\AppSetting;
use App\Models\MistStreamPushDestination;
use App\Models\MistTrigger;
use App\Models\Recording;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MistTriggerController extends Controller {


  public function handleMistPush(Request $request): Response|Application|ResponseFactory|null {
//    Log::info('MistServer Push Trigger', [
//        'headers' => $request->headers->all(),
//        'body'    => $request->getContent()
//    ]);

    $triggerType = $request->header('X-TRIGGER');

    // Route to the appropriate handler based on the trigger type
    switch ($triggerType) {
      case 'PUSH_OUT_START':
        $this->handlePushOutStart($request);
        return response('1', 200); // Positive response for blocking triggers
      case 'RECORDING_END':
        $this->handleRecordingEnd($request);
        return response('1', 200); // Positive response for blocking triggers
      // Add more cases as needed for other trigger types
      default:
        Log::warning("Unhandled trigger type: {$triggerType}");

        return response('Trigger type not handled', 400);
    }
  }


  public function handlePushOutStart(Request $request): Response|Application|ResponseFactory {
//    Log::info('handlePushOutStart Raw Request', [
//        'headers' => $request->headers->all(),
//        'body'    => $request->getContent()
//    ]);

    $bodyContent = $request->getContent();
    $lines = explode("\n", $bodyContent);

    $streamName = trim($lines[0]) ?? 'unknown';
    $requestUrl = trim($lines[1]) ?? 'unknown';

    // Directly check if it's a recording without querying for a MistStreamPushDestination
    if ($this->isRecording($requestUrl)) {
      Log::info('push is a recording');
//      Log::info("Processing as a recording for: {$streamName}");
      // Call your recording processing logic here
      // E.g., creating a Recording entity
      $this->processRecording($streamName, $requestUrl);
      return response('Recording processed', 200);
    } else {
      Log::warning('push is not a recording');
    }

    // Attempt to find the matching MistStreamPushDestination
    $pushDestination = MistStreamPushDestination::whereHas('mistStreamWildcard', function ($query) use ($streamName) {
      $query->where('name', $streamName);
    })
        ->where(function ($query) use ($requestUrl) {
          // Here you might need to adjust based on how your requestUrl is structured vs how data is stored
          $query->whereRaw("CONCAT(rtmp_url, rtmp_key) = ?", [$requestUrl]);
        })
        ->first();

    if ($pushDestination) {
      Log::info("Match found for stream name: {$streamName} with URL: {$requestUrl}");
      $pushDestination->push_is_started = 1;
      $pushDestination->update();
      // Proceed with your event broadcast or other logic
      broadcast(new MistTriggerPushOutStartEvent([
          'streamName'           => $streamName,
          'mistStreamWildcardId' => $pushDestination->mist_stream_wildcard_id,
          'requestUrl'           => $requestUrl
      ]));

      // Determine if it's a recording based on $requestUrl or other criteria
      if ($this->isRecording($requestUrl)) {
        // Handle as recording
        $this->processRecording($streamName, $requestUrl, $pushDestination);
      } else {
        // Handle as live stream push
        $this->processLiveStreamPush($streamName, $requestUrl, $pushDestination);
      }

    } else {
      Log::warning("No matching destination found for stream name: {$streamName} with incoming URL: {$requestUrl}");
      // Handle unrecognized push destinations here, if needed
    }

    return response('Processed PUSH_OUT_START', 200);

  }

  protected function isRecording($requestUrl): bool {
    // This regular expression checks if the URL path suggests it's a recording,
    // allowing for query parameters after the .mkv extension
    return preg_match('/\/media\/recordings\/.*\.mkv(\?.*)?$/', $requestUrl) > 0;
  }

  protected function processRecording($streamName, $requestUrl): void {
    Log::info("Starting recording for: {$streamName}");

    // Remove the query string from the URL if present
    $urlWithoutQuery = parse_url($requestUrl, PHP_URL_PATH);

    // Now extract the file extension using pathinfo() on the URL without the query string
    $fileExtension = pathinfo($urlWithoutQuery, PATHINFO_EXTENSION);

    // Here, you might create a new Recording instance or update existing data
    Recording::create([
        'stream_name' => $streamName,
        'path' => null,
        'start_time' => now(),
        'file_extension' => $fileExtension,
        'file_size' => null,
      // Include other necessary fields according to your model's design
    ]);
    // Additional recording-specific logic here
  }

  public function handleRecordingEnd(Request $request): Response {
    $bodyContent = $request->getContent();
    $lines = explode("\n", $bodyContent);

    // Parse the body content
    $streamName = trim($lines[0]) ?? 'unknown';
    $filePath = trim($lines[1]) ?? 'unknown';
    $fileType = trim($lines[2]) ?? 'unknown';
    $bytesRecorded = (int) trim($lines[3]) ?? 0;
    $secondsSpentRecording = (int) trim($lines[4]) ?? 0;
    $unixTimeRecordingStarted = (int) trim($lines[5]) ?? 0;
    $unixTimeRecordingStopped = (int) trim($lines[6]) ?? 0;
    $totalMillisecondsRecorded = (int) trim($lines[7]) ?? 0;
    $millisecondsFirstPacket = (int) trim($lines[8]) ?? 0;
    $millisecondsLastPacket = (int) trim($lines[9]) ?? 0;
    $machineReadableReason = trim($lines[10]) ?? 'unknown';
    $humanReadableReason = trim($lines[11]) ?? 'unknown';

    // Convert total milliseconds of media data recorded to seconds for the duration
    $totalSecondsRecorded = $totalMillisecondsRecorded / 1000;

    // Convert Unix timestamps to Carbon instances for start and end times
    $startTime = Carbon::createFromTimestamp($unixTimeRecordingStarted);
    $endTime = Carbon::createFromTimestamp($unixTimeRecordingStopped);

    // Remove the query string from the URL if present
    $urlWithoutQuery = parse_url($filePath, PHP_URL_PATH);
    // Extract the file extension using pathinfo() on the URL without the query string
    $fileExtension = pathinfo($urlWithoutQuery, PATHINFO_EXTENSION);

    // Create a new recording entry
    Recording::create([
        'stream_name' => $streamName,
        'path' => $filePath,
        'file_extension' => $fileExtension,
        'mime_type' => $fileType,
        'start_time' => $startTime,
        'end_time' => $endTime,
        'file_size' => $bytesRecorded,
        'duration' => $secondsSpentRecording,
        'milliseconds_first_packet' => $millisecondsFirstPacket,
        'milliseconds_last_packet' => $millisecondsLastPacket,
        'reason_for_exit' => $machineReadableReason,
        'human_readable_reason_for_exit' => $humanReadableReason,
    ]);

    Log::info("New recording created for: {$streamName}");

    return response('1', 200);
  }

//  public function handleRecordingEnd(Request $request): Response|Application|ResponseFactory|null {
////    Log::info('Recording End Trigger', [
////        'headers' => $request->headers->all(),
////        'body' => $request->getContent()
////    ]);
//
//    $bodyContent = $request->getContent();
//    $lines = explode("\n", $bodyContent);
//
//    // Assuming the first line is the stream name and the second line is the file path
//
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
//    // Convert total milliseconds of media data recorded to seconds
//    $totalSecondsRecorded = $totalMillisecondsRecorded / 1000;
//
//    // Attempt to find the matching Recording initiated at the start
//    // Convert Unix timestamp to Carbon instance for comparison
//    $recordingStartTime = Carbon::createFromTimestamp($unixTimeRecordingStarted);
//
//// Query to find the closest start_time to $unixTimeRecordingStarted for the given stream_name
//    $recording = Recording::where('stream_name', $streamName)
//        ->orderByRaw('ABS(TIMESTAMPDIFF(SECOND, start_time, ?))', [$recordingStartTime])
//        ->first();
////    $recording = Recording::where('stream_name', $streamName)->first();
//
//    // Extract additional details from the body, such as end time, if applicable
//    // For example, parsing the datetime from the file path
//
//    if ($recording) {
//      $startTime = Carbon::createFromTimestamp($unixTimeRecordingStarted);
//      $endTime = Carbon::createFromTimestamp($unixTimeRecordingStopped);
//
//      // Remove the query string from the URL if present
//      $urlWithoutQuery = parse_url($filePath, PHP_URL_PATH);
//
//      // Now extract the file extension using pathinfo() on the URL without the query string
//      $fileExtension = pathinfo($urlWithoutQuery, PATHINFO_EXTENSION);
//
//      // Update the recording with complete details
//      $recording->update([
//          'path' => $filePath,
//          'file_extension' => $fileExtension,
//          'mime_type' => $fileType,
//          'start_time' => $startTime,
//          'end_time' => $endTime,
//          'file_size' => $bytesRecorded,
//          'duration' => $totalSecondsRecorded,
//        // Include new fields
//          'milliseconds_first_packet' => $millisecondsFirstPacket,
//          'milliseconds_last_packet' => $millisecondsLastPacket,
//          'reason_for_exit' => $machineReadableReason,
//          'human_readable_reason_for_exit' => $humanReadableReason,
//      ]);
//
//      Log::info("Recording ended for: {$streamName}");
//    } else {
//      Log::warning("No matching recording start found for: {$streamName}");
//    }
//
//    return response('Recording end processed', 200);
//  }

  protected function processLiveStreamPush($streamName, $requestUrl, $pushDestination): void {
    Log::info("Processing live stream push for: {$streamName}");
    // Update pushDestination or handle live stream logic
    // For example:
    // $pushDestination->live_stream_started = true;
    // $pushDestination->save();
    // Optionally trigger an event or additional logic here
  }


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

//  public function handleMistPush(Request $request): Response|Application|ResponseFactory {
//    Log::info('MistServer Push Trigger', [
//        'headers' => $request->headers->all(),
//        'body' => $request->getContent() // Log raw body content for inspection
//    ]);
//
//    // Parse the request payload to understand the push context
//    $bodyContent = $request->getContent();
//    $lines = explode("\n", $bodyContent);
//    // Example of parsing - adjust according to your actual payload
//    $streamName = $lines[0] ?? 'unknown';
//    // More parsing as needed...
//
//    // Determine the context/type of push based on parsed data
//    // This is where you implement logic to differentiate between a live stream push and a recording
//    // Example conditional logic (you'll need to adjust based on actual indicators in your payload)
//    if ($this->isRecordingPush($streamName, $lines)) {
//      // Handle recording push
//      $this->processRecordingPush($streamName, $lines);
//    } else {
//      // Handle live streaming push or other types of pushes
//      $this->processLiveStreamPush($streamName, $lines);
//    }
//
//    return response('Push processed', 200);
//  }
//
//  protected function isRecordingPush($streamName, $lines): bool {
//    // Implement logic to determine if the push is for a recording
//    // This could be based on specific naming conventions, flags, or other indicators in your payload
//    // Example:
//    return str_contains($streamName, 'recording_'); // Adjust based on your naming convention or payload structure
//  }
//
//  protected function processRecordingPush($streamName, $lines): void {
//    // Logic to handle recording push
//    // E.g., creating or updating a Recording model instance
//    Log::info("Processing recording push for: {$streamName}");
//    // Example:
//    // Recording::create([...]);
//  }
//
//  protected function processLiveStreamPush($streamName, $lines): void {
//    // Logic to handle live stream push or other non-recording pushes
//    Log::info("Processing live stream or other push for: {$streamName}");
//    // Implement as needed based on your application requirements
//  }


  // tec21: this was our first push... I'm implementing a new handlePush
  // method to handle incoming pushes triggered by MistServer.
  // We need to determine if the push is a recording or
  // pushing to a new destination.
//  public function handlePushOutStart(Request $request): void {
//    Log::info('handlePushOutStart Raw Request', [
//        'headers' => $request->headers->all(),
//        'body'    => $request->getContent() // For raw body content
//    ]);
//
//    // Parsing the request body
//    $bodyContent = $request->getContent();
//    $lines = explode("\n", $bodyContent);
//
//    // The first line contains the mistStreamWildcard->name
//    $streamName = trim($lines[0]) ?? 'unknown';
//    // The second line contains the URI (the combination of rtmp_url and rtmp_key)
//    $requestUrl = trim($lines[1]) ?? 'unknown';
//
//    // Attempt to find the matching MistStreamPushDestination
//    $pushDestination = MistStreamPushDestination::whereHas('mistStreamWildcard', function ($query) use ($streamName) {
//      $query->where('name', $streamName);
//    })
//        ->where(function ($query) use ($requestUrl) {
//          // Concatenate rtmp_url and rtmp_key and compare with requestUrl
//          // Adjust the concatenation based on your DB driver
//          $query->whereRaw("CONCAT(rtmp_url, rtmp_key) = ?", [$requestUrl]);
//        })
//        ->first();
//
//    if ($pushDestination) {
//      // If a match is found, process accordingly
//      Log::info("Match found for stream name: {$streamName} with URL: {$requestUrl}");
//      $pushDestination->push_is_started = 1;
//      $pushDestination->update();
//      // Proceed with your event broadcast or other logic
//      broadcast(new MistTriggerPushOutStartEvent([
//          'streamName' => $streamName,
//          'mistStreamWildcardId' => $pushDestination->mist_stream_wildcard_id,
//          'requestUrl' => $requestUrl
//      ])); // Customize with your actual event and data
//    } else {
//      Log::warning("No matching destination found for stream name: {$streamName} with incoming URL: {$requestUrl}");
//    }
//  }


  public function handlePushEnd(Request $request) {
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
