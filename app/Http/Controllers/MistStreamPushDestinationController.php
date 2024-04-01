<?php

namespace App\Http\Controllers;

use App\Models\MistStreamWildcard;
use App\Services\MistServer\MistServerService;
use App\Factories\MistServerServiceFactory;
use App\Jobs\MistStreamPushAutoRemoveJob;
use App\Jobs\MistStreamPushStartJob;
use App\Jobs\MistStreamPushStopJob;
use App\Models\MistServerActivePush;
use App\Models\MistStreamPushDestination;
use App\Services\MistServer\PushService;
use App\Services\PushDestinationService;
use App\Services\RecordingService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MistStreamPushDestinationController extends Controller {

  protected MistServerService $pushService;

  public function __construct() {
    // Assuming the factory is adjusted to instantiate specific services based on the server type or another parameter
    $this->pushService = MistServerServiceFactory::make('push'); // Now returns an instance of PushService
  }

//  protected PushDestinationService $pushDestinationService;
//  protected RecordingService $recordingService;
//  protected MistServerService $mistServerService;

//  public function __construct(PushDestinationService $pushDestinationService, RecordingService $recordingService, MistServerService $mistServerService) {


//    $this->pushDestinationService = $pushDestinationService;
//    $this->recordingService = $recordingService;
//    $this->mistServerService = $mistServerService;
//  }


  // TODO: Move all of the Push related methods here.

  // Add New Push


  // Edit Push


  // Remove Push -> MistServerService::send()


  // Enable Auto Push -> MistServerService::send()
  public function pushAutoAdd(MistStreamPushDestination $mistStreamPushDestination): \Illuminate\Http\JsonResponse {
//    Log::debug('Auto push added for MistStreamPushDestination.', ['destinationId' => $mistStreamPushDestination->id]);
    try {
      $response = $this->pushService->pushAutoAdd($mistStreamPushDestination, true);
//    Log::debug('Push Destination Service -> pushAutoAdd ran successfully.', ['destinationId' => $mistStreamPushDestination->id]);
      // Successful operation
      return response()->json([
          'message' => 'Auto push added successfully for ' . $mistStreamPushDestination->id,
          'status'  => 'success', // Indicate success for the ToastNotification
          'data'    => $response,
      ]);
    } catch (Exception $e) {
      // Log the error and return an error response with a status
      Log::error('Failed to dispatch PushDestinationService for pushAutoAdd', ['exception' => $e->getMessage(), 'destinationId' => $mistStreamPushDestination->id]);
//
      // Operation failed
      return response()->json([
          'message' => 'Failed to enable auto push for ' . $mistStreamPushDestination->id,
          'status'  => 'error', // Indicate error for the ToastNotification
          'data'    => $response,
      ], 500);
    }
  }


  // Disable Auto Push -> MistServerService::send()


  // Disable All Auto Pushes for this Stream -> MistServerService::send()
  /**
   * @throws Exception
   */
  public function removeAllAutoPushesForStream(Request $request): \Illuminate\Http\JsonResponse {
    // Validate the request data
    $validated = $request->validate([
        'streamName' => 'required|string',
    ]);

    $streamName = $validated['streamName'];

    $this->pushService->pushAutoRemoveAll($streamName);

    MistStreamPushDestination::where('stream_name', $streamName)
        ->update(['has_auto_push' => 0]);

    try {
      // Respond with success
      return response()->json(['message' => "Auto pushes removed successfully for stream: {$streamName}"]);
    } catch (Exception $e) {
      Log::error('Failed to remove all auto pushes for stream.', [
          'streamName' => $streamName,
          'exception'  => $e->getMessage()
      ]);

      return response()->json(['error' => 'Failed to remove auto pushes.'], 500);
    }
  }


  // Start Push -> MistServerService::send()


  // Stop Push -> MistServerService::send()


  // Fetch Push List -> MistServerService::send()
  public function fetchPushList(): \Illuminate\Http\JsonResponse {
    // Since $this->pushService is an instance of PushService, it has the fetchPushList method available
    $response = $this->pushService->fetchPushList();

    return response()->json([
        'success' => true,
        'message' => 'Push list retrieved successfully.',
        'data'    => $response,
    ]);
  }


  // Fetch Auto Push List -> MistServerService::send()
  public function fetchAutoPushList(): \Illuminate\Http\JsonResponse {

    $response = $this->pushService->fetchAutoPushList();

    return response()->json([
        'success' => true,
        'message' => 'Push list retrieved successfully.',
        'data'    => $response,
    ]);
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
        'isRecording'  => $updateInfo['isRecording'] ?? false, // Provide a default false as a fallback
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

  public function store(Request $request): \Illuminate\Http\JsonResponse {
    // Log the entire request payload
    Log::alert('Request received', [
        'url'     => $request->fullUrl(), // Gets the full URL for the request
        'method'  => $request->method(), // Gets the HTTP method for the request
        'headers' => $request->header(), // Gets all headers (Consider security/privacy before logging all headers)
        'payload' => $request->all(), // Gets all input data (Be cautious with sensitive information)
    ]);

    // Validate the request data
    $validated = $request->validate([
        'show_id'                 => 'required|exists:shows,id',
        'stream_name'             => 'required|string',
        'mist_stream_wildcard_id' => 'required|exists:mist_stream_wildcards,id',
        'rtmp_url'                => 'required|custom_streaming_url',
        'rtmp_key'                => 'nullable|string|max:255',
        'comment'                 => 'nullable|string',
    ]);

    // Concatenate the RTMP URL and key to form the full push URI
    $fullPushUri = $validated['rtmp_url'] . $validated['rtmp_key'];
    $validated['full_push_uri'] = $fullPushUri;

    // Determine destination details
    $details = $this->determineDestinationDetails($validated['rtmp_url'], $validated['rtmp_key'] ?? '');
    $validated = array_merge($validated, $details);

    // Create the destination
    $destination = MistStreamPushDestination::create($validated);

    return response()->json($destination, 201);

  }

  public function update(Request $request, $id) {
    // Find and validate as before
    $destination = MistStreamPushDestination::findOrFail($id);

    // Validate the request data
    $validated = $request->validate([
        'show_id'                 => 'required|exists:shows,id',
        'stream_name'             => 'required|string',
        'mist_stream_wildcard_id' => 'required|exists:mist_stream_wildcards,id',
        'rtmp_url'                => 'required|custom_streaming_url',
        'rtmp_key'                => 'nullable|string|max:255', // Changed to 'sometimes' to allow optional key
        'comment'                 => 'nullable|string',
    ]);

    // Concatenate the RTMP URL and key to form the full push URI, if both are provided
    $fullPushUri = $validated['rtmp_url'] . $validated['rtmp_key'];
    $validated['full_push_uri'] = $fullPushUri;

    $details = $this->determineDestinationDetails($validated['rtmp_url'], $validated['rtmp_key'] ?? '');
    $validated = array_merge($validated, $details);

    // if $destination->has_auto_push then cancel auto push.
    if ($destination->has_auto_push) {
      $destination->has_auto_push = 0;
      try {
        // TODO: Use the PushService to remove the AutoPush
        // TODO: and remove the Job
        MistStreamPushAutoRemoveJob::dispatch($destination);

        return response()->json(['message' => 'Push destination updated.']);
      } catch (Exception $e) {
        Log::error('Failed to update push destination. ', ['exception' => $e->getMessage()]);

        return response()->json(['error' => 'Failed to update push destination. '], 500);
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
        'rtmp_url'          => $url, // Update the URL with the adjusted one
        'rtmp_key'          => $key, // The key with the adjusted one, if applicable
        'destination_name'  => 'Custom RTMP Destination',
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

  public function updateStreamPushStatus(Request $request, $destinations) {

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


    $request->validate([
        'mist_stream_wildcard_id' => 'required|string', // Validate request
    ]);
    // Request contains wildcard_id
    $wildcardId = $request->mist_stream_wildcard_id;

    $mistStreamWildcard = MistStreamWildcard::findOrFail($wildcardId);
    $streamName = $mistStreamWildcard->name;

    // Attempt to find an active push based on stream name
    $activePush = MistServerActivePush::where('stream_name', $streamName)->first();

    // Update PushDestinations related to this Wildcard
    $pushDestinations = MistStreamPushDestination::where('mist_stream_wildcard_id', $wildcardId)->get(); // Get related destinations

    foreach ($pushDestinations as $destination) {
      // Example condition to match destination, adjust as necessary
      if ($activePush && $destination->rtmp_url . $destination->rtmp_key == $activePush->original_uri) {
        $destination->push_is_started = 1;
      } else {
        $destination->push_is_started = 0;
      }
      $destination->save(); // Save the updated push destination
    }

    // Update Stream recording status
    if (!$activePush) {
      $mistStreamWildcard->is_recording = 0;
    } else {
      $isRecording = $this->recordingService->checkForRecording($activePush->original_uri);
      if ($isRecording) {
        $this->recordingService->handleIfRecording($mistStreamWildcard, $activePush->original_uri);
      } else {
        $mistStreamWildcard->is_recording = 0; // Assuming you want to update this if not recording
      }
    }

    $mistStreamWildcard->save(); // Save any updates to the wildcard

    // change the response to give us an array of all the push destinations and if push_is_started true or false
    // and an array item if isRecording true or false
    // we are going to use this data in our Vue front end.
    return response()->json(['message' => 'Stream push status updated.']);

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy($id) {
    try {
      $destination = MistStreamPushDestination::findOrFail($id);
      $destination->delete();

      return response()->json(['message' => 'Destination deleted successfully'], 200);
    } catch (Exception $e) {
      Log::error('Error deleting MistStreamPushDestination: ' . $e->getMessage());

      return response()->json(['message' => 'Error deleting destination'], 500);
    }
  }


//  /**
//   * @throws \Exception
//   */
//  public function removeAllAutoPushesForStream(Request $request): \Illuminate\Http\JsonResponse {
//    // Validate the request data
//    $validated = $request->validate([
//        'streamName' => 'required|string',
//    ]);
//
//    $streamName = $validated['streamName'];
//
//    $this->mistServerService->sendRemoveAllAutoPushesCommand($streamName);
//
//    try {
//      // Respond with success
//      return response()->json(['message' => "Auto pushes removed successfully for stream: {$streamName}"]);
//    } catch (\Exception $e) {
//      Log::error('Failed to remove all auto pushes for stream.', [
//          'streamName' => $streamName,
//          'exception'  => $e->getMessage()
//      ]);
//
//      return response()->json(['error' => 'Failed to remove auto pushes.'], 500);
//    }
//  }


  public function pushAutoRemove(MistStreamPushDestination $mistStreamPushDestination): \Illuminate\Http\JsonResponse {
    Log::debug('Auto push removed for MistStreamPushDestination.', ['destinationId' => $mistStreamPushDestination->id]);
    try {

//      $this->pushDestinationService->pushAutoRemove($mistStreamPushDestination);

      // Dispatch the MistStreamPushAutoRemoveJob with the destination model
//      MistStreamPushAutoRemoveJob::dispatch($destination);
      Log::debug('Push Destination Service -> pushAutoRemove ran successfully for ', ['destinationId' => $mistStreamPushDestination->id]);

      return response()->json(['message' => 'Auto push removed successfully for ' . $mistStreamPushDestination->id]);
    } catch (Exception $e) {
      Log::error('Failed to run PushDestinationService for pushAutoRemove', ['exception' => $e->getMessage()]);

      return response()->json(['error' => 'Failed to dispatch job.'], 500);
    }
  }


  public function startPush(Request $request): \Illuminate\Http\JsonResponse {
    $validated = $request->validate([
        'destination_id' => 'required|string',
        'stream_name'    => 'required|string',
        'full_push_uri'  => 'required|string',
    ]);

    $destinationId = $validated['destination_id'];
    $streamName = $validated['stream_name'];
    $fullPushUri = $validated['full_push_uri'];

    $data = [
        $streamName,
        $fullPushUri,
    ];

//    Log::info('data', ['as' => $data]);


//    Log::debug('Starting push for MistStreamPushDestination.', ['destinationId' => $destinationId->id]);

    try {

      // TODO: Test this further, there seems to be a bug
      // where if you start a push, it connects, then you stop the push
      // it disconnects, then you start the push again and it does not
      // connect. Then you stop and start the push and then it connects.
      // Or, you refresh the page and try to to push and it reconnects.
      // Only observed this pushing to Facebook so far. And now it won't
      // connect at all.. It's probably a rate limiter on Facebook.
      // ~ tec21, March 31, 2024
      $response = $this->pushService->pushStart($data);

      $mistStreamPushDestination = MistStreamPushDestination::find($destinationId);
      // Check if the destination was found
      if ($mistStreamPushDestination) {
        $mistStreamPushDestination->push_is_started = 1;
        $mistStreamPushDestination->save();
      } else {
        // Handle the case where the destination is not found, e.g., log an error or throw an exception
        Log::error('MistStreamPushDestination not found', ['destinationId' => $destinationId]);
      }

      // Dispatch the MistStreamPushStartJob with the destination model
      return response()->json([
          'success' => true,
          'message' => 'Push started for ' . $fullPushUri,
          'data'    => $response,
      ]);

    } catch (Exception $e) {
//      Log::error('Failed to dispatch MistStreamPushStartJob', ['destinationId' => $destinationId, 'exception' => $e->getMessage()]);

      return response()->json(['error' => 'Failed to start stream.'], 500);
    }
  }


//  public function stopPush(MistStreamPushDestination $mistStreamPushDestination): \Illuminate\Http\JsonResponse {
//    try {
//      Log::debug('About to dispatch MistStreamPushStopJob', [
//          'destinationId' => $mistStreamPushDestination->id,
//          'destination'   => $mistStreamPushDestination->toArray(), // Convert model to array for logging
//      ]);
//      // Dispatch the MistStreamPushStopJob with the destination model
////      MistStreamPushStopJob::dispatch($mistStreamPushDestination);
//
//
//      return response()->json(['message' => 'Push stop job dispatched successfully for destination ' . $mistStreamPushDestination->id]);
//    } catch (Exception $e) {
//      Log::error('Failed to dispatch MistStreamPushStopJob', ['destinationId' => $mistStreamPushDestination->id, 'exception' => $e->getMessage()]);
//
//      return response()->json(['error' => 'Failed to dispatch job.'], 500);
//    }
//  }

  public function stopPush(Request $request): \Illuminate\Http\JsonResponse {
    $validated = $request->validate([
        'destination_id' => 'required|string',
    ]);

    $destinationId = $validated['destination_id'];
    $data = null;

    $mistStreamPushDestination = MistStreamPushDestination::find($destinationId);
    // Check if the destination was found
    if ($mistStreamPushDestination) {
      $mistStreamPushDestination->push_is_started = 1;
      $mistStreamPushDestination->save();
    } else {
      // Handle the case where the destination is not found, e.g., log an error or throw an exception
      Log::error('MistStreamPushDestination not found', ['destinationId' => $destinationId]);
      return response()->json([
          'status' => 'error',
          'message' => 'Destination not found.',
      ], 404);
    }

    if ($mistStreamPushDestination->mist_push_id) {
      $data = $mistStreamPushDestination->mist_push_id;
    } else {
      $pushList = $this->pushService->fetchPushList();
      $pushListItems = collect($pushList['push_list'] ?? []);

      // Find a matching item based on the stream name of the destination
      $matchedItem = $pushListItems->first(function ($item) use ($mistStreamPushDestination) {
        return $item[1] === $mistStreamPushDestination->stream_name; // Assuming the second element [1] is the stream name
      });

      if ($matchedItem) {
        // If a match is found, perhaps use the ID from the push list for whatever next steps you need
        $data = $matchedItem[0]; // Assuming the first element [0] is some kind of ID you need
      }
    }

    // Since $this->pushService is an instance of PushService, it has the fetchPushList method available
    if ($data) {
      $response = $this->pushService->pushStop($data);
      $mistStreamPushDestination->push_is_started = 0;
      $mistStreamPushDestination->save();

      return response()->json([
          'success' => true,
          'message' => 'Push stopped for ' . $mistStreamPushDestination->full_push_uri,
          'data'    => $response,
      ]);
    } else {
      return response()->json([
          'success' => false,
          'message' => 'Matching push list item not found.',
      ], 200);
    }
  }


}
