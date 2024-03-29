<?php
namespace App\Services;

use App\Events\MistTriggerPushOutStart;
use App\Models\MistServerActivePush;
use App\Models\MistStreamPushDestination;
use App\Models\MistStreamWildcard;
use App\Services\MistServerService;
use App\Services\RecordingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PushDestinationService {

  protected MistServerService $mistServerService;
  protected RecordingService $recordingService;

  public function __construct(RecordingService $recordingService, MistServerService $mistServerService) {
    $this->mistServerService = $mistServerService;
    $this->recordingService = $recordingService;
  }


  public function updatePushDestinationsStatus($destinations): array {
    Log::debug('Starting updatePushDestinationsStatus');

    if ($destinations->isEmpty()) {
      Log::debug('No destinations provided for update.');
      return [];
    }

    // Fetch the active push list from the MistServer
    $activePushList = $this->mistServerService->getActivePushList();
    // Sync active pushes with the database
    $this->syncActivePushes($activePushList);

    // All destinations should belong to the same MistStreamWildcard
    $wildcard = $destinations->first()->mistStreamWildcard;
    $isRecording = $this->recordingService->checkForRecording($wildcard->name);
    $wildcard->is_recording = $isRecording ? 1 : 0;
    $wildcard->save();
    Log::debug("Updated wildcard (ID: {$wildcard->id}) is_recording status to: " . $wildcard->is_recording);

    // If recording is active, handle recording specifics
    if ($isRecording) {
      $this->recordingService->handleIfRecording($wildcard, $wildcard->name); // Assuming handleIfRecording() requires the wildcard and its name
      Log::debug("Handled recording for wildcard (ID: {$wildcard->id}).");
    }

    $updatedDestinationsInfo = [];
    foreach ($destinations as $destination) {
      // Determine if the destination matches an active push
      $destination->push_is_started = $this->isPushActiveForDestination($destination) ? 1 : 0;
      $destination->save();

      // Collect updated destination info
      $destinationInfo = $destination->attributesToArray();
      $destinationInfo['push_started_changed'] = $destination->wasChanged('push_is_started');
      $updatedDestinationsInfo[] = $destinationInfo;

      if ($destinationInfo['push_started_changed']) {
        Log::debug("Updated destination (ID: {$destination->id}) push_is_started status.");
      }
    }

//    return $updatedDestinationsInfo;
    return [
        'updatedDestinations' => $updatedDestinationsInfo, // Ensure this variable is always set
        'isRecording' => $isRecording,
    ];
  }

  protected function isPushActiveForDestination($destination): bool {
    // Now the method directly queries the MistServerActivePush table, which should be up-to-date thanks to syncActivePushes.
    return MistServerActivePush::where('stream_name', $destination->mistStreamWildcard->name)
        ->where('original_uri', $destination->rtmp_url . $destination->rtmp_key)
        ->exists();
  }


  protected function syncActivePushes($activePushList): void {
    foreach ($activePushList as $push) {
      MistServerActivePush::updateOrCreate(
          ['push_id' => $push[0]], // Assuming $push[0] is the unique push ID
          [
              'stream_name'   => $push[1],
              'original_uri'  => $push[2],
              'processed_uri' => $push[3],
            // Assuming $push[4] might be logs, and $push[5] push status details
              'logs'          => $push[4],
              'push_status'   => $push[5],
          ]
      );

      Log::debug('Synced active pushes with the database.');
    }
  }

//  public function updatePushDestinationsStatus($destinations)
//  {
//    Log::debug('Starting updatePushDestinationsStatus');
//    $isRecording = false;
//    $updatedDestinationsInfo = [];
//    $activePushList = $this->mistServerService->getActivePushList(); // Assume mistServerService is correctly injected
//
//    // Log the active push list for evaluation
//    Log::debug('Active push list:', ['activePushList' => $activePushList]);
//
//    if ($destinations->isNotEmpty()) {
//      $streamName = $destinations->first()->mistStreamWildcard->name ?? null;
//      Log::debug("Found stream name: {$streamName}");
//
//      // No longer fetching the active push this way
//      // $activePush = MistServerActivePush::where('stream_name', $streamName)->first();
//
//      // Instead, determine if there's an active push for each destination using the active push list
//      foreach ($destinations as $destination) {
//        $isPushActiveForDestination = $this->isPushActiveForDestination($destination, $activePushList);
//        $destination->push_is_started = $isPushActiveForDestination ? 1 : 0;
//        $destination->save();
//
//        $destinationInfo = $destination->attributesToArray();
//        $destinationInfo['push_started_changed'] = $destination->wasChanged('push_is_started');
//
//        if ($destinationInfo['push_started_changed']) {
//          Log::debug("Updated destination (ID: {$destination->id}) push_is_started status to: " . $destination->push_is_started);
//        }
//
//        $updatedDestinationsInfo[] = $destinationInfo;
//      }
//
//      // Update the is_recording status as before
//      $wildcard = $destinations->first()->mistStreamWildcard;
//      $isRecording = $this->recordingService->checkForRecording($wildcard->name);
//      $wildcard->is_recording = $isRecording ? 1 : 0;
//      $wildcard->save();
//      Log::debug("Updated wildcard (ID: {$wildcard->id}) is_recording status to: " . $wildcard->is_recording);
//    }
//
//    return [
//        'updatedDestinations' => $updatedDestinationsInfo,
//        'isRecording' => $isRecording
//    ];
//  }


//  protected function isPushActiveForDestination($destination, $activePushList)
//  {
//    foreach ($activePushList as $push) {
//      if ($destination->rtmp_url . $destination->rtmp_key === $push['target']) { // Adjust based on how target URL is structured in your active push list
//        return true;
//      }
//    }
//    return false;
//  }

//  protected function isPushActiveForDestination($destination)
//  {
//    // Check if there's an active push for the destination by direct comparison of `original_uri` with `rtmp_url + rtmp_key`.
//    return MistServerActivePush::where('stream_name', $destination->mistStreamWildcard->name)
//        ->where('original_uri', $destination->rtmp_url . $destination->rtmp_key)
//        ->exists(); // Returns true if an active push destination is found, otherwise false.
//  }





  public function syncActivePushesWithDatabase($activePushList)
  {
    foreach ($activePushList as $pushInfo) {
      $pushId = $pushInfo[0];
      $streamName = $pushInfo[1];
      $originalUri = $pushInfo[2];
      $processedUri = $pushInfo[3];
      $logs = $pushInfo[4];
      $pushStatus = $pushInfo[5];

      MistServerActivePush::updateOrCreate(
          ['push_id' => $pushId],
          [
              'stream_name' => $streamName,
              'original_uri' => $originalUri,
              'processed_uri' => $processedUri,
              'logs' => $logs,
              'push_status' => $pushStatus,
          ]
      );
    }

    Log::debug('Synced active pushes with database.');
  }






//
//  public function updatePushDestinationsStatus($destinations)
//  {
//    Log::debug('Starting updatePushDestinationsStatus');
//
//    $isRecording = false;
//    $updatedDestinationsInfo = [];
//
//    if ($destinations->isNotEmpty()) {
//      $streamName = $destinations->first()->mistStreamWildcard->name ?? null;
//      Log::debug("Found stream name: {$streamName}");
//
//      $activePush = MistServerActivePush::where('stream_name', $streamName)->first();
//
//      if ($activePush) {
//        Log::debug("Active push found for stream: {$streamName}");
//        $isRecording = $this->recordingService->checkForRecording($activePush->original_uri);
//        Log::debug("Is recording: " . ($isRecording ? 'Yes' : 'No'));
//      } else {
//        Log::debug("No active push found for stream: {$streamName}");
//      }
//
//      $wildcard = $destinations->first()->mistStreamWildcard;
//      $wildcard->is_recording = $isRecording ? 1 : 0;
//      $wildcard->save();
//      Log::debug("Updated wildcard (ID: {$wildcard->id}) is_recording status to: " . $wildcard->is_recording);
//    }
//
//    foreach ($destinations as $destination) {
//      $originalPushStartedStatus = $destination->push_is_started;
//      $destination->push_is_started = $activePush && $destination->rtmp_url . $destination->rtmp_key === $activePush->original_uri ? 1 : 0;
//      $destination->save();
//
//      $destinationInfo = $destination->attributesToArray();
//      $destinationInfo['push_started_changed'] = $originalPushStartedStatus !== $destination->push_is_started;
//
//      if ($destinationInfo['push_started_changed']) {
//        Log::debug("Updated destination (ID: {$destination->id}) push_is_started status to: " . $destination->push_is_started);
//      }
//
//      $updatedDestinationsInfo[] = $destinationInfo;
//    }
//
//    return [
//        'updatedDestinations' => $updatedDestinationsInfo,
//        'isRecording' => $isRecording
//    ];
//  }






//  public function updatePushDestinationsStatus($destinations): array {
//
//    $updatedDestinationsInfo = [];
//
//    foreach ($destinations as $destination) {
//      $streamName = $destination->mistStreamWildcard->name ?? null;
//      $activePush = $streamName ? MistServerActivePush::where('stream_name', $streamName)->first() : null;
//      $isRecording = false;
//
//      // Update push_is_started based on active push
//      $originalPushStartedStatus = $destination->push_is_started;
//      $destination->push_is_started = $activePush && $destination->rtmp_url . $destination->rtmp_key === $activePush->original_uri ? 1 : 0;
//      $destination->save();
//
//      // Check and update recording status
//      if ($activePush) {
//        $isRecording = $this->recordingService->checkForRecording($activePush->original_uri);
//        $destination->mistStreamWildcard->is_recording = $isRecording ? 1 : 0;
//        $destination->mistStreamWildcard->save();
//      }
//
//      // Collect information about the updated destination for the response
//      $updatedDestinationsInfo[] = [
//          'id'                   => $destination->id,
//          'push_is_started'      => $destination->push_is_started,
//          'is_recording'         => $isRecording,
//          'push_started_changed' => $originalPushStartedStatus !== $destination->push_is_started,
//          'stream_name'          => $streamName,
//      ];
//    }
//
//    return $updatedDestinationsInfo;
//
//  }






//    // Assuming $destinations is a collection of MistStreamPushDestination instances
//    $updatedDestinations = $destinations->map(function ($destination) {
//      $streamName = $destination->mistStreamWildcard->name ?? null;
//      if (!$streamName) {
//        return $destination;
//      }
//
//      $activePush = MistServerActivePush::where('stream_name', $streamName)->first();
//      $isRecording = false;
//
//      if ($activePush) {
//        $isRecording = $this->recordingService->checkForRecording($activePush->original_uri);
//        $destination->push_is_started = $destination->rtmp_url . $destination->rtmp_key == $activePush->original_uri ? 1 : 0;
//      } else {
//        $destination->push_is_started = 0;
//      }
//
//      $destination->save();
//
//      // Optionally, update the is_recording status on the related MistStreamWildcard
//      if ($isRecording) {
//        $destination->mistStreamWildcard->is_recording = 1;
//        $destination->mistStreamWildcard->save();
//      }
//
//      return $destination;
//    });
//
//    return $updatedDestinations;
//  }






  public function updateDestinationRecord(MistServerActivePush $activePush): void {
    // Example: Update database record with push status information

    // tec21: this is going to be so inefficient, this needs some attention
    // TODO: fix this for efficiency.
    // We want to update each stored push destination.

    $destination = MistStreamPushDestination::where('push_id', $activePush->push_id)->first();

    if ($destination) {
      // Update destination with status details from $pushStatus
      // Example: $destination->bytes_transferred = $pushStatus['bytes'];
      // Adjust according to your application's database schema and needs

      $destination->save();
    }
    // Consider logging or handling cases where the destination is not found

  }

  public function pushAutoAdd($destination): void {

    if (!$destination->relationLoaded('mistStreamWildcard')) {
      $destination->load('mistStreamWildcard');
    }

    // Correctly format the target URL and prepare the stream name
    $targetURL = $destination->rtmp_url . $destination->rtmp_key;
    $streamName = $destination->mistStreamWildcard->name;

//    Log::debug('add ::::: ' . $targetURL . ' ::::: ' . $streamName);
    $data = [
        "push_auto_add" => [
            "stream" => $streamName,
            "target" => $targetURL,
//            "scheduletime" => '', // this can get data from the showSchedule but is a future project.
//            "completetime" => '', // this can get data from the showSchedule but is a future project.
        ]
    ];

    try {
      $this->mistServerService->send($data); // Assuming 'send' method handles communication with MistServer
      $destination->has_auto_push = 1;
      $destination->save();
      // Log success with more detail
//      Log::debug("Push auto add successful for stream: {$streamName} to target: {$targetURL}");
    } catch (\Exception $e) {
      // Log the error with detail
      Log::error("Failed to request push_auto_add for stream: {$streamName} to target: {$targetURL}", ['exception' => $e->getMessage()]);
      // Optionally, rethrow or handle the exception as needed
    }
  }



}
