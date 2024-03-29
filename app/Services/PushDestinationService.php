<?php
namespace App\Services;

use App\Events\MistTriggerPushOutStart;
use App\Models\MistServerActivePush;
use App\Models\MistServerAutoPush;
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


  public function fetchPushAutoList(): array
  {
    $data = ["push_auto_list" => true]; // The value is ignored, so true is just a placeholder

    try {
      $response = $this->mistServerService->send($data);
      $activeEntries = [];

      if (isset($response['push_auto_list']) && is_array($response['push_auto_list'])) {
//        Log::debug("Successfully retrieved active push list from MistServer." . $response);

        foreach ($response['push_auto_list'] as $autoPushData) {
          Log::debug('Saving auto push entry:', ['data' => $autoPushData]);

          $newAutoPush = MistServerAutoPush::updateOrCreate(
              [
                  'stream_name' => $autoPushData[0],
                  'uri' => $autoPushData[1],
              ],
              [
                  'col_3' => $autoPushData[2],
                  'col_4' => $autoPushData[3],
                  'col_5' => $autoPushData[4],
                  'col_6' => $autoPushData[5],
                  'col_7' => $autoPushData[6],
                  'col_8' => $autoPushData[7],
                  'col_9' => $autoPushData[8],
                  'col_10' => $autoPushData[9],
                  'auto_push_entry' => $autoPushData, // This will be automatically cast to JSON by Laravel
              ]
          );

          // Collect active entries for comparison
          $activeEntries[] = $autoPushData[0] . '|' . $autoPushData[1];


          // Optionally log the outcome
          Log::debug("Processed auto push entry.", ['stream_name' => $newAutoPush->stream_name, 'uri' => $newAutoPush->uri]);
        }
        // Find and remove unmatched entries
        $allAutoPushes = MistServerAutoPush::all();
        foreach ($allAutoPushes as $autoPush) {
          $identifier = $autoPush->stream_name . '|' . $autoPush->uri;
          if (!in_array($identifier, $activeEntries)) {
            Log::info("Removing unmatched auto push entry.", ['stream_name' => $autoPush->stream_name, 'uri' => $autoPush->uri]);
            $autoPush->delete(); // Remove the unmatched entry
          }
        }

        return $response['push_auto_list'];
      } else {
        Log::error("Failed to retrieve active push list. Response was not as expected.");
        return [];
      }
    } catch (\Exception $e) {
      Log::error("Exception occurred while fetching active push list", ['exception' => $e->getMessage()]);
      return [];
    }
  }

//
//  public function removeAllAutoPushesForStream($streamName): array
//  {
//    Log::alert($streamName);
//    $data = ["push_auto_remove" => $streamName];
//
//    try {
//      $response = $this->mistServerService->send($data);
//
//      // Since we're removing auto pushes, there might not be a direct response indicating success,
//      // but you can log the streamName for which the operation was attempted.
//      Log::debug("Attempted to remove all auto pushes for stream.", ['stream_name' => $streamName]);
//
//      // Assuming the response gives some indication of success or failure
//      // This depends entirely on how your `send` method and the MistServer's API handle this operation
//      if (isset($response['success']) && $response['success']) {
//        Log::debug("Successfully removed all auto pushes for stream.", ['stream_name' => $streamName]);
//        return ['success' => true];
//      } else {
//        Log::error("Failed to remove all auto pushes for stream.", ['stream_name' => $streamName]);
//        return ['success' => false, 'error' => 'API operation failed.'];
//      }
//    } catch (\Exception $e) {
//      Log::error("Exception occurred while attempting to remove all auto pushes", ['stream_name' => $streamName, 'exception' => $e->getMessage()]);
//      return ['success' => false, 'error' => $e->getMessage()];
//    }
//  }


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

//  public function pushAutoAdd(MistStreamPushDestination $mistStreamPushDestination): void {
//
//    if (!$mistStreamPushDestination->relationLoaded('mistStreamWildcard')) {
//      $mistStreamPushDestination->load('mistStreamWildcard');
//    }
//
//    // Correctly format the target URL and prepare the stream name
//    $targetURL = $mistStreamPushDestination->rtmp_url . $mistStreamPushDestination->rtmp_key;
//    $streamName = $mistStreamPushDestination->mistStreamWildcard->name;
//
////    Log::debug('add ::::: ' . $targetURL . ' ::::: ' . $streamName);
//    $data = [
//        "push_auto_add" => [
//            "stream" => $streamName,
//            "target" => $targetURL,
////            "scheduletime" => '', // this can get data from the showSchedule but is a future project.
////            "completetime" => '', // this can get data from the showSchedule but is a future project.
//        ]
//    ];
//
//    try {
//      $this->mistServerService->send($data); // Assuming 'send' method handles communication with MistServer
//      $mistStreamPushDestination->has_auto_push = 1;
//      $mistStreamPushDestination->save();
//      // Log success with more detail
////      Log::debug("Push auto add successful for stream: {$streamName} to target: {$targetURL}");
//    } catch (\Exception $e) {
//      // Log the error with detail
//      Log::error("Failed to request push_auto_add for stream: {$streamName} to target: {$targetURL}", ['exception' => $e->getMessage()]);
//      // Optionally, rethrow or handle the exception as needed
//    }
//  }
//
//  public function pushAutoRemove(MistStreamPushDestination $mistStreamPushDestination): void {
//
//    if (!$mistStreamPushDestination->relationLoaded('mistStreamWildcard')) {
//      $mistStreamPushDestination->load('mistStreamWildcard');
//    }
//
//    // Correctly format the target URL and prepare the stream name
//    $targetURL = $mistStreamPushDestination->rtmp_url . $mistStreamPushDestination->rtmp_key;
//    $streamName = $mistStreamPushDestination->mistStreamWildcard->name;
//
////    Log::debug('add ::::: ' . $targetURL . ' ::::: ' . $streamName);
//    $data = [
//        "push_auto_add" => [
//            "stream" => $streamName,
//            "target" => $targetURL,
////            "scheduletime" => '', // this can get data from the showSchedule but is a future project.
////            "completetime" => '', // this can get data from the showSchedule but is a future project.
//        ]
//    ];
//
//    try {
//      $this->mistServerService->send($data); // Assuming 'send' method handles communication with MistServer
//      $mistStreamPushDestination->has_auto_push = 1;
//      $mistStreamPushDestination->save();
//      // Log success with more detail
////      Log::debug("Push auto add successful for stream: {$streamName} to target: {$targetURL}");
//    } catch (\Exception $e) {
//      // Log the error with detail
//      Log::error("Failed to request push_auto_add for stream: {$streamName} to target: {$targetURL}", ['exception' => $e->getMessage()]);
//      // Optionally, rethrow or handle the exception as needed
//    }
//  }

  public function pushAutoAdd(MistStreamPushDestination $mistStreamPushDestination): bool {
    $this->ensureWildcardLoaded($mistStreamPushDestination);

    $targetURL = $mistStreamPushDestination->rtmp_url . $mistStreamPushDestination->rtmp_key;
    $streamName = $mistStreamPushDestination->mistStreamWildcard->name;

    $data = [
        "push_auto_add" => [
            $streamName,
            $targetURL,
        ]
    ];
    return $this->sendPushRequest($data, $mistStreamPushDestination, true);
  }

  public function pushAutoRemove(MistStreamPushDestination $mistStreamPushDestination): bool {


    $this->fetchPushAutoList();

    // Ensure the mistStreamWildcard relationship is loaded
    $this->ensureWildcardLoaded($mistStreamPushDestination);

    // Prepare the target URL and the stream name
    $targetURL = $mistStreamPushDestination->rtmp_url . $mistStreamPushDestination->rtmp_key;
    $streamName = $mistStreamPushDestination->mistStreamWildcard->name;

    $autoPushEntry = MistServerAutoPush::where('stream_name', $streamName)->firstOrFail();

    $first10Values = array_slice($autoPushEntry->auto_push_entry, 0, 10);


    // Preprocess the array to ensure empty values are represented as ""
    $processedValues = array_map(function($value) {
      return $value === "" ? "\"\"" : $value;
    }, $first10Values);


// Convert the array into a string of values separated by commas and surrounded by braces
    $valuesString = "[" . implode(", ", $processedValues) . "]";

// Construct the final string to mimic the structure you described
    $dataString = '{"push_auto_remove": ' . $valuesString . '}';

    Log::debug('This is our autoPushEntry: ' . $dataString);
    Log::debug('remove ::::: ' . $targetURL . ' ::::: ' . $streamName);


    $data = [
        "push_auto_remove" => $dataString
    ];




    // Use the centralized method for sending the request and handling the response
    return $this->sendPushRequest($data, $mistStreamPushDestination, true);
  }















  protected function ensureWildcardLoaded(MistStreamPushDestination $mistStreamPushDestination): void {
    if (!$mistStreamPushDestination->relationLoaded('mistStreamWildcard')) {
      $mistStreamPushDestination->load('mistStreamWildcard');
    }
  }

  protected function sendPushRequest(array $data, MistStreamPushDestination $mistStreamPushDestination, bool $isAddOperation): bool {
//    try {
      $response = $this->mistServerService->send($data); // Assuming 'send' returns a response you can evaluate for success
    $mistStreamPushDestination->has_auto_push = $isAddOperation ? 1 : 0;
    $mistStreamPushDestination->save();
    return true;
      // Assuming the response provides a way to determine success or failure:
//      if ($response->successful()) { // This is just an example, adjust based on your actual response object

        // Assuming you have a way to get 'stream' and 'target' from $data for logging purposes
//        Log::debug("Push operation successful", ['stream' => $streamName, 'target' => $targetURL]); // Adjust variables as needed
//        return true;
//      } else {
//        Log::error("Push operation failed", ['data' => $data]);
//        return false;
//      }
//    } catch (\Exception $e) {
//      Log::error("Failed to perform push operation", ['exception' => $e->getMessage(), 'data' => $data]);
//      return false;
//    }
//    return false;
  }



}
