<?php

namespace App\Http\Controllers;

use App\Jobs\MistStreamPushAutoAddJob;
use App\Jobs\MistStreamPushAutoRemoveJob;
use App\Jobs\MistStreamPushStartJob;
use App\Jobs\MistStreamPushStopJob;
use App\Models\MistStreamPushDestination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MistStreamPushDestinationController extends Controller
{
  public function index(Request $request) {
    $request->validate([
        'wildcardId' => 'required|string',
    ]);

    // Assuming you have a model called `MistStreamPushDestination` that corresponds to your database table
    // and it's related to `MistStreamWildcard` by `mist_stream_wildcard_id`
    $wildcardId = $request->query('wildcardId');

    // Fetch destinations filtered by wildcardId if provided, otherwise fetch all
    $destinations = MistStreamPushDestination::when($wildcardId, function ($query, $wildcardId) {
      return $query->where('mist_stream_wildcard_id', $wildcardId);
    })->get();

    // Return the list of push destinations
    return response()->json($destinations);
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
    // Validate the request data
    $validated = $request->validate([
        'mist_stream_wildcard_id' => 'required|string',
        'rtmp_url' => 'required|url',
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
        'rtmp_url' => 'required|url',
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

  private function determineDestinationDetails($url, $key)
  {

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
        'destination_image' => 'https://img.api.video/rtmp_f9ff34ac42.png', // Replace with your default image URL
    ];

    if (str_contains($url, 'facebook')) {
      $details['destination_name'] = 'Facebook';
      $details['destination_image'] = 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Facebook_Logo_%282019%29.png/900px-Facebook_Logo_%282019%29.png'; // Replace with actual URL
    } elseif (str_contains($url, 'rumble')) {
      $details['destination_name'] = 'Rumble';
      $details['destination_image'] = 'https://cdn.freelogovectors.net/wp-content/uploads/2023/05/rumble-logo-freelogovectors.net_.png'; // Replace with actual URL
    } elseif (str_contains($url, 'youtube')) {
      $details['destination_name'] = 'YouTube';
      $details['destination_image'] = 'https://c0.klipartz.com/pngpicture/982/799/gratis-png-logo-de-youtube-logo-de-youtube-marketing-en-internet-suscribirse.png'; // Replace with actual URL
    } elseif (str_contains($url, 'librti')) {
      $details['destination_name'] = 'Librti';
      $details['destination_image'] = 'https://s3.us-central-1.wasabisys.com/lib-backup-na/bx_organizations_pics_resized/a/au/auw/auwyawyfhtf9z4yvysvptptu6zetz4bg.png'; // Replace with actual URL
    } elseif (str_contains($url, 'fbcdn')) {
      $details['destination_name'] = 'Instagram';
      $details['destination_image'] = 'https://png.pngtree.com/png-clipart/20230401/original/pngtree-three-dimensional-instagram-icon-png-image_9015419.png'; // Replace with actual URL
    }

    return $details;
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

  public function pushAutoAdd(Request $request)
  {
    $validated = $request->validate([
        'destinationId' => 'required|string',
    ]);

    $destinationId = $validated['destinationId'];

    try {
      $destination = MistStreamPushDestination::findOrFail($destinationId); // Ensure this call is correct based on your model

      // Assuming you update the job to accept the destination model and other details directly
      MistStreamPushAutoAddJob::dispatch($destination);

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
