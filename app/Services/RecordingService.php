<?php
namespace App\Services;

use App\Events\MistTriggerPushOutStart;
use App\Models\MistServerActivePush;
use App\Models\MistStreamWildcard;
use App\Models\Recording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RecordingService {
  public function handleNewRecording(Request $request) {
    // Logic for handling a new recording
  }

  public function handleUpdateRecording(Request $request, $recordingId) {
    // Logic for updating an existing recording
  }

  // Additional methods as needed

  public function checkForRecording($streamName): bool {
    // Assuming you have a way to identify recording URLs in your MistServerActivePush records,
    // for example, by a specific pattern in `original_uri` or another field.
    return MistServerActivePush::where('stream_name', $streamName)
        ->where('original_uri', 'LIKE', '/media/recordings/%')
        ->exists();
  }

  public function handleIfRecording(MistStreamWildcard $mistStreamWildcard, $requestUrl): void {
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
    broadcast(new MistTriggerPushOutStart([
        'mistStreamWildcardId' => $mistStreamWildcard,
    ]));
  }
}
