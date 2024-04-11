<?php

namespace App\Services\MistServer;

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
//    Log::debug('Recording Started',['data' => $data]);

    // Assuming $data is an associative array for clarity. If it's indexed, consider using list($streamName, $fullPushUri) = $data;
    $streamName = $data[0];
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
      $recording->comment = 'Recording started by ' . Auth::user()->name; // Ensure authenticated user is available
      $recording->start_time = Carbon::now(); // Set the start_time to the current time
      $recording->save();

      return true;
    } else {
      Log::warning('Failed to start recording', ['response' => $response]);
      return false;
    }
  }

  public function stopRecording($streamName): bool {

    // check for pushes and match the stream and the uri (contains '/media/recordings')
    $pushList = $this->send(['push_list' => true]);
//    Log::debug('pushList: ' . json_encode($pushList));
    $pushListItems = collect($pushList['push_list'] ?? []);

    // Find a matching item based on the stream name and 'original_uri' containing '/media/recordings'
    // and skip the automated recording.
    $matchedItem = $pushListItems->first(function ($item) use ($streamName) {
      // Check if the stream name matches
      if ($item[1] !== $streamName) {
        return false;
      }

      // Check if 'media/recordings' is a substring of the item at index [2]
      // and ensure the item does not literally contain the undesired string
      if (str_contains($item[2], 'media/recordings') &&
          !str_contains($item[2], '/media/recordings/$stream_$datetime.mkv')) {
        return true;
      }

      return false;
    });

    if (!$matchedItem) {
      Log::warning('No matching push stream found', ['streamName' => $streamName]);
      return false; // Early return if no match is found
    }

    // Use the push ID from the matched item
    $pushId = $matchedItem[0];
    $response = $this->send(['push_stop' => $pushId]);
//    Log::debug('stopRecording: ' . json_encode($response));

    // Check the response status
    if (isset($response['authorize']) && $response['authorize']['status'] === 'OK') {
//      Log::info('Recording successfully stopped', ['response' => $response]);
      return true;
    } else {
      Log::warning('Failed to stop recording', ['response' => $response]);
      return false;
    }

  }
}