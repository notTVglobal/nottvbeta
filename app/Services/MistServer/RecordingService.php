<?php

namespace App\Services\MistServer;

use App\Models\AppSetting;
use App\Models\MistStreamWildcard;
use App\Models\Recording;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RecordingService extends MistServerService {

  /**
   * @throws Exception
   */
  public function startRecording($data): bool {
//    Log::debug('2c start recording in RecordingService');
// Debugging to check the structure of $data
//    Log::debug('Received data for recording:', ['data' => $data]);

    // Ensure data is extracted correctly
    $streamName = $data[0];  // Adjust according to the actual key or use indexing if array is not associative
    $fullPushUri = $data[1];

    $response = $this->send(['push_start' => $data]);
    // Check if the response has an "OK" status
    if (isset($response['authorize']) && $response['authorize']['status'] === 'OK') {
//      Log::info('Recording successfully started', ['response' => $response]);

      // Create and save the Recording model
      $recording = new Recording();
      $recording->stream_name = $streamName;
      $recording->path = $fullPushUri;
      $recording->file_extension = 'mkv';
      $recording->comment = 'Recorded by ' . Auth::user()->name; // Ensure authenticated user is available
      $recording->start_dateTime = Carbon::now(); // Set the start_dateTime to the current time
      $recording->save();

//      Log::debug('2d recording started in RecordingService');
      return true;
    } else {
      Log::warning('Failed to start recording', ['response' => $response]);
      return false;
    }
  }


  /**
   * Stops a recording by sending a stop command to the media server and updating the database.
   *
   * @param string $streamName The name of the stream to stop.
   * @return bool True if the recording was successfully stopped, false otherwise.
   * @throws Exception If there is an error in stopping the recording on the server.
   */
  public function stopRecording(string $streamName): bool {
    if (!$this->stopMediaServerRecording($streamName)) {
      return false;
    }

    $this->updateRecordingStatus($streamName, 0);
    return true;
  }

  /**
   * Sends a stop command to the media server for a specific stream.
   *
   * @param string $streamName The name of the stream to stop.
   * @return bool True if the stop command was successful, false otherwise.
   * @throws Exception If communication with the server fails.
   */
  protected function stopMediaServerRecording(string $streamName): bool {
    $pushId = $this->getMatchingPushId($streamName);
    if (!$pushId) {
      Log::warning('Stop Recording: No matching push stream found', ['streamName' => $streamName]);
      return false;
    }

    try {
    $response = $this->send(['push_stop' => $pushId]);
    if ($response['authorize']['status'] === 'OK') {
//      Log::debug('Stop Recording: Successfully stopped', ['streamName' => $streamName, 'pushId' => $pushId]);
      return true;
    } else {
      Log::error('Stop Recording: Failed to stop', [
          'streamName' => $streamName,
          'pushId' => $pushId,
          'response' => $response
      ]);
      return false;
    }
    } catch (\Throwable $e) {  // Catching Throwable to handle errors and exceptions
      Log::error('Stop Recording: Exception caught', [
          'streamName' => $streamName,
          'error' => $e->getMessage(),
          'stackTrace' => $e->getTraceAsString()  // Including stack trace can be very helpful for debugging
      ]);
      // Rethrowing the exception as a custom exception with more context or handle accordingly
      throw new Exception("Failed to stop recording due to server error: " . $e->getMessage(), 0, $e);
    }
  }

  /**
   * Updates the recording status in the database.
   *
   * @param string $streamName The name of the stream for which to update the status.
   * @param int $status The new status to set.
   */
  protected function updateRecordingStatus(string $streamName, int $status): void {
    $wildcard = MistStreamWildcard::where('name', $streamName)->first();
    if ($wildcard) {
      $wildcard->update(['is_recording' => $status]);
    }
  }

  /**
   * Fetches the push ID matching a given stream name.
   *
   * @param string $streamName The stream name to match.
   * @return int|null The push ID if a match is found, null otherwise.
   * @throws Exception
   */
  protected function getMatchingPushId(string $streamName): ?int {
    $pushList = $this->send(['push_list' => true]);

    $settings = AppSetting::find(1);

    $userRecordingsPath = $settings->mist_server_settings['mist_server_user_recording_folder'] ?? null;
    $autoRecordingsPath = $settings->mist_server_settings['mist_server_automated_recording_folder'] ?? null;

    foreach ($pushList['push_list'] ?? [] as $item) {
      if ($item[1] === $streamName && $this->isValidRecordingPath($item[2], $userRecordingsPath, $autoRecordingsPath)) {
        return $item[0];
      }
    }
    return null;
  }

  /**
   * Checks if the given path is a valid recording path based on configuration.
   * Checks if the given path is a valid recording path based on configuration.
   *
   * This method determines whether a given path is considered valid by checking
   * if it contains a specific user-defined path while not containing an automatically
   * generated path segment that follows a certain naming convention.
   *
   * @param string $path The path to validate.
   * @param string $userPath The path segment that must be included for a path to be considered valid.
   * @param string $autoPath The path segment that must not be included when followed by a specific pattern.
   * @return bool Returns true if the path is valid, false otherwise.
   */
  protected function isValidRecordingPath(string $path, string $userPath, string $autoPath): bool {
    return str_contains($path, $userPath) && !str_contains($path, $autoPath . '$stream_$datetime.mkv');
  }
//}


//  public function stopRecording($streamName): bool {
//
//
//    try {
//      // Logic to stop recording
//      $isStopped = $this->stop($streamName);
//      if (!$isStopped) {
//        Log::error('Failed to stop recording', ['streamName' => $streamName]);
//        return false;
//      }
//
//      // Update associated show or other models
//      $wildcard = MistStreamWildcard::where('stream_name', $streamName)->first();
//      if ($wildcard) {
//        $wildcard->update(['is_recording' => 0]);
//      }
//
//      return true;
//    } catch (Exception $e) {
//      Log::error('Error stopping recording', ['streamName' => $streamName, 'error' => $e->getMessage()]);
//      return false;
//    }
//
//
//
//
//
//    // check for pushes and match the stream and the uri (contains '/media/recordings')
//    $pushList = $this->send(['push_list' => true]);
//    Log::debug('pushList: ' . json_encode($pushList));
//    $pushListItems = collect($pushList['push_list'] ?? []);
//
//    // Find a matching item based on the stream name and 'original_uri' containing '/media/recordings'
//    // and skip the automated recording.
//    $matchedItem = $pushListItems->first(function ($item) use ($streamName) {
//      // Check if the stream name matches
//      if ($item[1] !== $streamName) {
//        return false;
//      }
//
//    $userRecordingsPath = config('paths.user_recordings_path');
//    $autoRecordingsPath = config('paths.auto_recordings_path');
//
//      Log::debug('Checking paths', [
//          'userRecordingsPath' => $userRecordingsPath,
//          'autoRecordingsPath' => $autoRecordingsPath,
//          'itemPath' => $item[2]
//      ]);
//
//      // Define a dynamic unwanted string pattern
//      $unwantedString = $autoRecordingsPath . '$stream_$datetime.mkv';
//
//    // Check if 'media/recordings' is a substring of the item at index [2]
//    // and ensure the item does not literally contain the undesired string
//    if (str_contains($item[2], $userRecordingsPath) &&
//        !str_contains($item[2], $autoRecordingsPath . '$stream_$datetime.mkv')) {
//      Log::info('Valid recording path found', [
//          'itemPath' => $item[2]
//      ]);
//      return true;
//    }
//
//    Log::warning('Recording path check failed', [
//        'itemPath' => $item[2],
//        'unwantedString' => $unwantedString
//    ]);
//
//      return false;
//    });
//
//    if (!$matchedItem) {
//      Log::warning('No matching push stream found', ['streamName' => $streamName]);
//      return false; // Early return if no match is found
//    }
//
//    // Use the push ID from the matched item
//    $pushId = $matchedItem[0];
//    $response = $this->send(['push_stop' => $pushId]);
////    Log::debug('stopRecording: ' . json_encode($response));
//
//    // Check the response status
//    if (isset($response['authorize']) && $response['authorize']['status'] === 'OK') {
////      Log::info('Recording successfully stopped', ['response' => $response]);
//      return true;
//    } else {
//      Log::warning('Failed to stop recording', ['response' => $response]);
//      return false;
//    }

//  }
}