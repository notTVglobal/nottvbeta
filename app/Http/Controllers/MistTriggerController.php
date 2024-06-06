<?php

namespace App\Http\Controllers;

use App\Events\MistTriggerPushEnd;
use App\Events\MistTriggerPushOutStart;
use App\Events\MistTriggerRecordingStop;
use App\Jobs\AddOrUpdateMistStreamJob;
use App\Jobs\UpdateRecordingModelAndNotify;
use App\Models\AppSetting;
use App\Models\MistStreamPushDestination;
use App\Models\MistStreamWildcard;
use App\Models\MistTrigger;
use App\Models\Recording;
use App\Services\RecordingServiceOLD;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MistTriggerController extends Controller {

  protected RecordingServiceOLD $recordingService;

  public function __construct(RecordingServiceOLD $recordingService) {
    $this->recordingService = $recordingService;
  }

  public function handlePushOutStart(Request $request): Response {
//    Log::alert('handle Push Out Start');
//    Log::debug('handlePushOutStart Raw Request', [
//      'headers' => $request->headers->all(),
//      'body'    => $request->getContent() // For raw body content
//    ]);

    $bodyContent = $request->getContent();
    $lines = explode("\n", $bodyContent);

    $streamName = trim($lines[0]) ?? 'unknown';
    $requestUrl = trim($lines[1]) ?? 'unknown';

    // First, retrieve the MistStreamWildcard based on $streamName
    try {
      $mistStreamWildcard = MistStreamWildcard::where('name', $streamName)->firstOrFail();
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      // Handle the error, perhaps log it, or customize the response
      Log::error("MistStreamWildcard not found for name: {$streamName}");
      // Optionally, throw the exception further if you want the framework to handle it
      throw $e;
    }

    if ($mistStreamWildcard) {
      // Attempt to find the matching MistStreamPushDestination directly from the wildcard
      $pushDestination = $mistStreamWildcard->mistStreamPushDestination()
          ->whereRaw("CONCAT(rtmp_url, rtmp_key) = ?", [$requestUrl])
          ->first();

      if ($pushDestination) {
//        Log::debug('push is a destination');
        $pushDestination->push_is_started = 1;
        $pushDestination->update();
        broadcast(new MistTriggerPushOutStart([
            'streamName'           => $streamName,
            'mistStreamWildcardId' => $pushDestination->mist_stream_wildcard_id,
            'requestUrl'           => $requestUrl
        ]));
      } elseif ($this->recordingService->checkForRecording($requestUrl)) {
//        Log::debug('push is a recording');
        // Handle recording logic here. You might want to flag the recording in the database or perform some other action.
        $this->recordingService->handleIfRecording($mistStreamWildcard, $requestUrl);
      } else {
//        Log::debug('push is neither a known destination nor a recording');
        // Handle the scenario where the stream is neither a known push destination nor a recording.
      }
    } else {
//      Log::debug('MistStreamWildcard not found for the given stream name.');
      // Handle the scenario where no wildcard matches the stream name.
    }

    return response('1', 200);
  }

  public function handlePushEnd(Request $request): Response {
//    Log::alert('handle Push End');
//    Log::debug('handlePushEnd Raw Request', [
//      'headers' => $request->headers->all(),
//      'body'    => $request->getContent() // For raw body content
//    ]);

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

    Log::alert('New Recording End');
//    Log::debug('handlePushEnd Raw Request', [
//        'headers' => $request->headers->all(),
//        'body'    => $request->getContent() // For raw body content
//    ]);

    $parsedContent = $this->parseRecordingEndContent($request->getContent());
    // Optionally log the parsed content if needed
//    Log::debug('Parsed recording end content:', ['parsedContent' => $parsedContent]);

    // Check if the filePath contains 'backup'
    if (str_contains($parsedContent['filePath'], 'backup')) {
      // Return response immediately if 'backup' is found in the filePath
//      Log::debug('Recording is a backup:', ['parsedContent' => $parsedContent]);
      return response('1', 200);
    }

    $recording = $this->createRecordingEntry($parsedContent);
    // Log the creation of a new recording entry.
//    Log::info("Created new recording entry for unique identifier: {$recording->path}");
    // Log after creating the recording entry
//    Log::debug('Recording entry created:', ['recording' => $recording]);

    $this->clearRecordingMetadataAndBroadcast($parsedContent['streamName']);
    // Log after clearing recording metadata and broadcasting
//    Log::debug('Cleared recording metadata and broadcast for stream:', ['streamName' => $parsedContent['streamName']]);

    if ($recording) {
      if (str_contains($parsedContent['filePath'], 'auto')) {
        // Add or Update Mist Stream for recording playbck of folder contents if 'auto' is found in filePath.
        // TODO: auto should be the name of the folder 'recordings_auto' we currently have it as prepended to the file name.
        //  and the folder is just 'recordings'

        // Dispatch the job to add or update the mist stream
        $recordingStreamName = $recording->stream_name;
        $newMistStreamName = 'recordings_' . str_replace('+', '_', $recordingStreamName);

        $settings = AppSetting::find(1);
        $autoRecordingsPath = $settings->mist_server_settings['mist_server_automated_recording_folder'] ?? null;
        $fullAutoRecordingsPath = $autoRecordingsPath . $recordingStreamName . '/';

        Log::debug($fullAutoRecordingsPath);
        AddOrUpdateMistStreamJob::dispatch([
            'name'   => $newMistStreamName,
            'source' => $fullAutoRecordingsPath
        ], $newMistStreamName);
      }
      UpdateRecordingModelAndNotify::dispatch($recording);
    }
    // Log after dispatching the job
//    Log::debug('Dispatched UpdateRecordingModelAndNotify job for recording:', ['recording' => $recording]);

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
//        'start_dateTime'                     => $startTime,
//        'end_dateTime'                       => $endTime,
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
  public function handleValidateUser(Request $request): Response|Application|ResponseFactory {
    Log::alert('handle Validate User');
//    Log::debug('handleValidateUser Raw Request', [
//        'headers' => $request->headers->all(),
//        'body'    => $request->getContent() // For raw body content
//    ]);
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

  protected function handleIfRecording(MistStreamWildcard $mistStreamWildcard, $requestUrl): void {
//    Log::debug("Recording detected for stream: $mistStreamWildcard->name on URL: $requestUrl");
    // Marking the recording as started in the database
    $mistStreamWildcard->is_recording = true;

    // Preparing the metadata update, adding or updating the 'recordingDestination' and 'recordingStartTime'
    $currentMetadata = $mistStreamWildcard->metadata ?? []; // Get current metadata or initialize as empty array
    $currentMetadata['recordingDestination'] = $requestUrl; // Set or update the recording URL
    $currentMetadata['recordingStartTime'] = now()->toDateTimeString(); // Set or update the recording start time

    $mistStreamWildcard->metadata = $currentMetadata; // Update the metadata column

    $mistStreamWildcard->save(); // Save the changes to the database

    // Optionally, you could broadcast an event or perform other actions as needed
    broadcast(new MistTriggerPushOutStart([
        'mistStreamWildcardId' => $mistStreamWildcard,
    ]));
  }

  protected function parseRecordingEndContent(string $bodyContent): array {
    $lines = explode("\n", $bodyContent);
    $parsedContent = [
        'streamName'                => trim($lines[0]) ?? 'unknown',
        'filePath'                  => trim($lines[1]) ?? 'unknown',
        'fileType'                  => trim($lines[2]) ?? 'unknown',
        'bytesRecorded'             => (int) trim($lines[3]) ?? 0,
        'secondsSpentRecording'     => (int) trim($lines[4]) ?? 0,
        'unixTimeRecordingStarted'  => (int) trim($lines[5]) ?? 0,
        'unixTimeRecordingStopped'  => (int) trim($lines[6]) ?? 0,
        'totalMillisecondsRecorded' => (int) trim($lines[7]) ?? 0,
        'millisecondsFirstPacket'   => (int) trim($lines[8]) ?? 0,
        'millisecondsLastPacket'    => (int) trim($lines[9]) ?? 0,
        'machineReadableReason'     => trim($lines[10]) ?? 'unknown',
        'humanReadableReason'       => trim($lines[11]) ?? 'unknown',
    ];

    $parsedContent['urlWithoutQuery'] = parse_url($parsedContent['filePath'], PHP_URL_PATH);
    $parsedContent['fileExtension'] = pathinfo($parsedContent['urlWithoutQuery'], PATHINFO_EXTENSION);
    $parsedContent['startTime'] = Carbon::createFromTimestamp($parsedContent['unixTimeRecordingStarted']);
    $parsedContent['endTime'] = Carbon::createFromTimestamp($parsedContent['unixTimeRecordingStopped']);

    return $parsedContent;
  }

  protected function createRecordingEntry(array $parsedContent): ?Recording {

    // Construct a unique identifier for the recording.
    $uniqueFilePath = $parsedContent['filePath'];
    $streamName = $parsedContent['streamName'];
    $convertedStreamName = str_replace('+', '_', $streamName);
    $recordingDate = $parsedContent['startTime']->format('Y.m.d.H.i.s'); // Convert to yyyy.mm.dd.hh.mm.ss

    // Extract mistWildcardId from streamName
    $mistWildcardId = explode('+', $streamName)[1] ?? '';

    // Initialize URLs and comment
    $download_url = '';
    $share_url = '';
    $playback_stream_name = '';
    $comment = '';

    $settings = AppSetting::find(1);

    $mistServerUri = $settings->mist_server_uri;
    $userRecordingsPath = $settings->mist_server_settings['mist_server_user_recording_folder'] ?? null;
    $autoRecordingsPath = $settings->mist_server_settings['mist_server_automated_recording_folder'] ?? null;

    if (!$userRecordingsPath || !$autoRecordingsPath) {
      Log::warning('No user recording path and no auto recording path found. MistTriggerController.');

      return null;
    }

    // Get the full file name
    $filename = basename($uniqueFilePath);

    if (str_contains($uniqueFilePath, 'auto')) {
      // Auto recordings
      $prefix = 'recordings_' . $convertedStreamName . '%2B';
    } elseif (str_contains($uniqueFilePath, 'user')) {
      // User recordings
      $relativePath = substr($uniqueFilePath, strlen($userRecordingsPath));
      $parts = explode('/', $relativePath, 3);
      if (count($parts) < 3) return null; // Skip invalid paths
      $userIdPart = 'recordings_user_' . $parts[1];
      $prefix = $userIdPart . '%2B';
    } elseif (str_contains($uniqueFilePath, 'backup')) {
      // Backup recordings
      $prefix = 'recordings_backup%2B';
      $comment = 'backup recording';
    } elseif (str_contains($uniqueFilePath, 'recording')) {
      // General recordings
      $prefix = 'recordings%2B';
    } else {
      return null; // Invalid path
    }

    // Append the prefix to the filename
    $fileWithPrefix = $prefix . $filename;

    // Generate URLs
    $download_url = $mistServerUri . $fileWithPrefix . '.mp4?dl=' . $convertedStreamName . $recordingDate . '.mp4';
    $share_url = $mistServerUri . $fileWithPrefix . '.html';
    $playback_stream_name = $fileWithPrefix . '.mp4';

    // Convert '+' to '%2B' in URLs
    $download_url = str_replace('+', '%2B', $download_url);
    $share_url = str_replace('+', '%2B', $share_url);
    $playback_stream_name = str_replace('+', '%2B', $playback_stream_name);


    // First, check if a recording with the same unique identifier already exists.
    $existingRecording = Recording::where('path', $uniqueFilePath)->first();

    if ($existingRecording) {
//      Log::info("Found existing recording entry, checking if update is needed.", ['uniqueId' => $uniqueId]);

      // Only update the recording if the comment is not 'automated recording'
      if ($existingRecording->comment !== 'automated recording') {
        Log::info("Updating non-automated existing recording.", ['uniqueFilePath' => $uniqueFilePath]);
        $updateData = [
            'stream_name'                    => $parsedContent['streamName'],
            'path'                           => $parsedContent['filePath'],
            'file_extension'                 => $parsedContent['fileExtension'],
            'mime_type'                      => $parsedContent['fileType'],
            'start_dateTime'                 => $parsedContent['startTime'],
            'end_dateTime'                   => $parsedContent['endTime'],
            'bytes_recorded'                 => $parsedContent['bytesRecorded'],
            'seconds_spent_recording'        => $parsedContent['secondsSpentRecording'],
            'total_milliseconds_recorded'    => $parsedContent['totalMillisecondsRecorded'],
            'milliseconds_first_packet'      => $parsedContent['millisecondsFirstPacket'],
            'milliseconds_last_packet'       => $parsedContent['millisecondsLastPacket'],
            'reason_for_exit'                => $parsedContent['machineReadableReason'],
            'human_readable_reason_for_exit' => $parsedContent['humanReadableReason'],
            'download_url'                   => $download_url,
            'share_url'                      => $share_url,
            'playback_stream_name'           => $playback_stream_name,
        ];

        if ($comment !== '') {
          $updateData['comment'] = $comment;
        }

        $existingRecording->update($updateData);
      } else {
        Log::info("Automated recording found, no update performed.", ['uniqueFilePath' => $uniqueFilePath]);
      }

      return $existingRecording;
    }

    // No existing recording found, proceed to create a new entry.
    return Recording::create([
        'comment'                        => $comment !== '' ? $comment : 'automated recording',
        'stream_name'                    => $parsedContent['streamName'],
        'path'                           => $parsedContent['filePath'],
        'file_extension'                 => $parsedContent['fileExtension'],
        'mime_type'                      => $parsedContent['fileType'],
        'start_dateTime'                 => $parsedContent['startTime'],
        'end_dateTime'                   => $parsedContent['endTime'],
        'bytes_recorded'                 => $parsedContent['bytesRecorded'],
        'seconds_spent_recording'        => $parsedContent['secondsSpentRecording'],
        'total_milliseconds_recorded'    => $parsedContent['totalMillisecondsRecorded'],
        'milliseconds_first_packet'      => $parsedContent['millisecondsFirstPacket'],
        'milliseconds_last_packet'       => $parsedContent['millisecondsLastPacket'],
        'reason_for_exit'                => $parsedContent['machineReadableReason'],
        'human_readable_reason_for_exit' => $parsedContent['humanReadableReason'],
        'download_url'                   => $download_url,
        'share_url'                      => $share_url,
        'playback_stream_name'           => $playback_stream_name,
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
//      Log::debug("Cleared recording metadata for stream: {$streamName}");
      // TODO: Broadcast New Recoding to Team
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
