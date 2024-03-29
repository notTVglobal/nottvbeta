<?php

namespace App\Http\Controllers;

use App\Jobs\MistStreamPushAutoAddJob;
use App\Jobs\MistStreamPushAutoRemoveJob;
use App\Jobs\MistStreamPushStartJob;
use App\Jobs\MistStreamPushStopJob;
use App\Models\MistServerActivePush;
use App\Models\MistStreamPushDestination;
use App\Models\MistStreamWildcard;
use App\Services\PushDestinationService;
use App\Services\RecordingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MistStreamPushDestinationController extends Controller
{

  protected PushDestinationService $pushDestinationService;
  protected RecordingService $recordingService;

  public function __construct(PushDestinationService $pushDestinationService, RecordingService $recordingService)
  {
    $this->pushDestinationService = $pushDestinationService;
    $this->recordingService = $recordingService;
  }


  public function index(Request $request) {
    $request->validate(['wildcardId' => 'required|string']);
    $wildcardId = $request->query('wildcardId');

    // Assuming you have a model called `MistStreamPushDestination` that corresponds to your database table
    // and it's related to `MistStreamWildcard` by `mist_stream_wildcard_id`


    // Fetch destinations filtered by wildcardId if provided, otherwise fetch all
    $destinations = MistStreamPushDestination::when($wildcardId, function ($query, $wildcardId) {
      return $query->where('mist_stream_wildcard_id', $wildcardId);
    })->get();

    // Update statuses of the fetched destinations
//    $updatedDestinations = $this->pushDestinationService->updatePushDestinationsStatus($destinations);

    $updateInfo = $this->pushDestinationService->updatePushDestinationsStatus($destinations);
// Make sure $updateInfo is indeed an array with the expected keys before trying to access them
    if (!isset($updateInfo['updatedDestinations']) || !isset($updateInfo['isRecording'])) {
      Log::error('Unexpected structure returned from updatePushDestinationsStatus', ['response' => $updateInfo]);
      // Consider adding error handling here, such as returning a default response structure or an error message
    }

    return response()->json([
        'destinations' => $updateInfo['updatedDestinations'] ?? [], // Provide a default empty array as a fallback
        'isRecording' => $updateInfo['isRecording'] ?? false, // Provide a default false as a fallback
    ]);
    // Return the updated list of push destinations
//    return response()->json($updatedDestinations);
  }
//    // Assuming you want to fetch push destinations associated with a specific wildcard ID
//    $pushDestinations = MistStreamPushDestination::where('mist_stream_wildcard_id', $wildcardId)
//        ->get();
//
//    // Return the list of push destinations
//    return response()->json($pushDestinations);
//  }

  public function store(Request $request)
  {
    // Log the entire request payload
    Log::alert('Request received', [
        'url' => $request->fullUrl(), // Gets the full URL for the request
        'method' => $request->method(), // Gets the HTTP method for the request
        'headers' => $request->header(), // Gets all headers (Consider security/privacy before logging all headers)
        'payload' => $request->all(), // Gets all input data (Be cautious with sensitive information)
    ]);

    // Validate the request data
    $validated = $request->validate([
        'mist_stream_wildcard_id' => 'required|string',
        'rtmp_url' => 'required|custom_streaming_url',
        'rtmp_key' => 'nullable|string|max:255',
        'comment' => 'nullable|string',
    ]);

    // Determine destination details
    $details = $this->determineDestinationDetails($validated['rtmp_url'], $validated['rtmp_key'] ?? '');
    $validated = array_merge($validated, $details);

    // Create the destination
    $destination = MistStreamPushDestination::create($validated);

    return response()->json($destination, 201);

  }

  public function update(Request $request, $id)
  {
    // Find and validate as before
    $destination = MistStreamPushDestination::findOrFail($id);

    // Validate the request data
    $validated = $request->validate([
        'mist_stream_wildcard_id' => 'required|string',
        'rtmp_url' => 'required|custom_streaming_url',
        'rtmp_key' => 'nullable|string|max:255', // Changed to 'sometimes' to allow optional key
        'comment' => 'nullable|string',
    ]);

    // Determine destination details if rtmp_url is provided
    if (isset($validated['rtmp_url'])) {
      $details = $this->determineDestinationDetails($validated['rtmp_url'], $validated['rtmp_key'] ?? '');
      $validated = array_merge($validated, $details);
    }

    // if $destination->has_auto_push then cancel auto push.
    if ($destination->has_auto_push) {
      $destination->has_auto_push = 0;
      try {
        // Dispatch the MistStreamPushAutoRemoveJob with the destination model
        MistStreamPushAutoRemoveJob::dispatch($destination);

        return response()->json(['message' => 'Push auto remove job dispatched successfully.']);
      } catch (\Exception $e) {
        Log::error('Failed to dispatch MistStreamPushAutoRemoveJob', ['exception' => $e->getMessage()]);
        return response()->json(['error' => 'Failed to dispatch job.'], 500);
      }
    }

    // Update the destination
    $destination->update($validated);

    return response()->json($destination);
  }

  private function determineDestinationDetails($url, $key): array {

    // Initial key adjustment logic based on the URL and key relationship
    if (str_contains($key, '?') && !str_ends_with($key, '?')) {
      $key .= '?';
    } else if ((!str_contains($key, '?') && strlen($key) > 1 && str_contains($url, '?')) || (empty($key) && str_contains($url, '?'))) {
      $key .= '?';
    }
    // No need for an explicit condition for leaving the key as it is

    // Ensure URL is correctly formatted
    if (str_contains($url, '?') && !str_ends_with($url, '?')) {
      $url .= '?';
    }

    if (!str_contains($url, '?') && !str_ends_with($url, '/')) {
      $url .= '/';
    }

    // Default values
    $details = [
        'rtmp_url' => $url, // Update the URL with the adjusted one
        'rtmp_key' => $key, // The key with the adjusted one, if applicable
        'destination_name' => 'Custom RTMP Destination',
        'destination_image' => 'https://cdn.nottv.io/public/2024/03/images/ARERR4HkdO0tBXuXoV3pMnjVT9df1CV7MEB7olfd.png', // Replace with your default image URL
    ];

    if (str_contains($url, 'facebook')) {
      $details['destination_name'] = 'Facebook';
      $details['destination_image'] = 'https://cdn.nottv.io/public/2024/03/images/fHL00bkodNOe1oDRUlUu9Z9p7VaUsRlcpHU1JdWx.png';
    } elseif (str_contains($url, 'rumble')) {
      $details['destination_name'] = 'Rumble';
      $details['destination_image'] = 'https://cdn.nottv.io/public/2024/03/images/8m5K3r1gSRmiSbFKaO7BN1wnPOaLlqwmfjW5QvPW.png';
    } elseif (str_contains($url, 'youtube')) {
      $details['destination_name'] = 'YouTube';
      $details['destination_image'] = 'https://cdn.nottv.io/public/2024/03/images/KTAI6CmAjfDzMHI5muYCqvjtBmoRz7jbnnz5Y2Rc.png';
    } elseif (str_contains($url, 'librti')) {
      $details['destination_name'] = 'Librti';
      $details['destination_image'] = 'https://cdn.nottv.io/public/2024/03/images/2obTxvAcBB94EBbF9IYHBsABv7J8ju5jHW9Vmvrs.png';
    } elseif (str_contains($url, 'fbcdn')) {
      $details['destination_name'] = 'Instagram';
      $details['destination_image'] = 'https://cdn.nottv.io/public/2024/03/images/Y3l3cvBI2dEnYBQ7RuavkZyMibTVJVUUTZf9Tbc1.png';
    } elseif (str_contains($url, 'twitch')) {
      $details['destination_name'] = 'Twitch';
      $details['destination_image'] = 'https://cdn.nottv.io/public/2024/03/images/NDJRdtqNSZfHdtmJdsgNmzenUkJlrqo5DcZJU916.png';
    }

    return $details;
  }

  public function updateStreamPushStatus($destinations) {

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
//          'id' => $destination->id,
//          'push_is_started' => $destination->push_is_started,
//          'is_recording' => $isRecording,
//          'push_started_changed' => $originalPushStartedStatus !== $destination->push_is_started,
//          'stream_name' => $streamName,
//      ];
//    }
//
//    return $updatedDestinationsInfo;









//    $request->validate([
//        'mist_stream_wildcard_id' => 'required|string', // Validate request
//    ]);
//    // Request contains wildcard_id
//    $wildcardId = $request->mist_stream_wildcard_id;
//
//    $mistStreamWildcard = MistStreamWildcard::findOrFail($wildcardId);
//    $streamName = $mistStreamWildcard->name;
//
//    // Attempt to find an active push based on stream name
//    $activePush = MistServerActivePush::where('stream_name', $streamName)->first();
//
//    // Update PushDestinations related to this Wildcard
//    $pushDestinations = MistStreamPushDestination::where('mist_stream_wildcard_id', $wildcardId)->get(); // Get related destinations
//
//    foreach ($pushDestinations as $destination) {
//      // Example condition to match destination, adjust as necessary
//      if ($activePush && $destination->rtmp_url . $destination->rtmp_key == $activePush->original_uri) {
//        $destination->push_is_started = 1;
//      } else {
//        $destination->push_is_started = 0;
//      }
//      $destination->save(); // Save the updated push destination
//    }
//
//    // Update Stream recording status
//    if (!$activePush) {
//      $mistStreamWildcard->is_recording = 0;
//    } else {
//      $isRecording = $this->recordingService->checkForRecording($activePush->original_uri);
//      if ($isRecording) {
//        $this->recordingService->handleIfRecording($mistStreamWildcard, $activePush->original_uri);
//      } else {
//        $mistStreamWildcard->is_recording = 0; // Assuming you want to update this if not recording
//      }
//    }
//
//    $mistStreamWildcard->save(); // Save any updates to the wildcard
//
//    // change the response to give us an array of all the push destinations and if push_is_started true or false
//    // and an array item if isRecording true or false
//    // we are going to use this data in our Vue front end.
//    return response()->json(['message' => 'Stream push status updated.']);

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy($id)
  {
    try {
      $destination = MistStreamPushDestination::findOrFail($id);
      $destination->delete();

      return response()->json(['message' => 'Destination deleted successfully'], 200);
    } catch (\Exception $e) {
      Log::error('Error deleting MistStreamPushDestination: ' . $e->getMessage());
      return response()->json(['message' => 'Error deleting destination'], 500);
    }
  }

  public function getPushAutoList(Request $request) {
    // start a job to make sure any pushes that are active or auto pushes setup are up to date
    // on the MistStreamPushDestination table, has_auto_push and push_is_started.

    // create job to getPushAutoList

  }

  public function getPushList(Request $request) {
    // start a job to make sure any pushes that any active pushes matching this Push Destination
    // update the Push Destination push_is_started on the MistStreamPushDestination table.

    // create job to getPushList

  }

  public function pushAutoAdd(Request $request, PushDestinationService $pushDestinationService): \Illuminate\Http\JsonResponse {
    $validated = $request->validate([
        'destinationId' => 'required|string',
    ]);

    $destinationId = $validated['destinationId'];

    try {
      $destination = MistStreamPushDestination::findOrFail($destinationId); // Ensure this call is correct based on your model

      // tec21: moved this here from MistStreamPushAutoAddJob - 2024-03-28
      $pushDestinationService->pushAutoAdd($destination);

      // Assuming you update the job to accept the destination model and other details directly
//      MistStreamPushAutoAddJob::dispatch($destination);

      return response()->json(['message' => 'Push auto add job dispatched successfully.']);
    } catch (\Exception $e) {
      Log::error('Failed to dispatch MistStreamPushAutoAddJob', ['exception' => $e->getMessage()]);
      return response()->json(['error' => 'Failed to dispatch job.'], 500);
    }
  }

  public function pushAutoRemove(Request $request)
  {
    $validated = $request->validate([
        'destinationId' => 'required|string',
    ]);

    $destinationId = $validated['destinationId'];

    try {
      $destination = MistStreamPushDestination::findOrFail($destinationId); // Ensure this call is correct based on your model

      // Dispatch the MistStreamPushAutoRemoveJob with the destination model
      MistStreamPushAutoRemoveJob::dispatch($destination);

      return response()->json(['message' => 'Push auto remove job dispatched successfully.']);
    } catch (\Exception $e) {
      Log::error('Failed to dispatch MistStreamPushAutoRemoveJob', ['exception' => $e->getMessage()]);
      return response()->json(['error' => 'Failed to dispatch job.'], 500);
    }
  }

  public function startPush(Request $request)
  {
    $validated = $request->validate([
        'destinationId' => 'required|string', // Adjust based on your ID's data type
    ]);

    $destinationId = $validated['destinationId'];

    try {
      $destination = MistStreamPushDestination::findOrFail($destinationId); // Ensure this call is correct based on your model

      // Dispatch the MistStreamPushStartJob with the destination model
      MistStreamPushStartJob::dispatch($destination);

      return response()->json(['message' => 'Push start job dispatched successfully for destination ' . $destinationId]);
    } catch (\Exception $e) {
      Log::error('Failed to dispatch MistStreamPushStartJob', ['exception' => $e->getMessage()]);
      return response()->json(['error' => 'Failed to dispatch job.'], 500);
    }
  }

  public function stopPush(Request $request)
  {
    $validated = $request->validate([
        'destinationId' => 'required|string',
    ]);

    $destinationId = $validated['destinationId'];

    try {
      $destination = MistStreamPushDestination::findOrFail($destinationId); // Ensure this call is correct based on your model

      // Dispatch the MistStreamPushStopJob with the destination model
      MistStreamPushStopJob::dispatch($destination);

      return response()->json(['message' => 'Push stop job dispatched successfully.']);
    } catch (\Exception $e) {
      Log::error('Failed to dispatch MistStreamPushStopJob', ['exception' => $e->getMessage()]);
      return response()->json(['error' => 'Failed to dispatch job.'], 500);
    }
  }

}
