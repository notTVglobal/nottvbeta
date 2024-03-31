<?php

namespace App\Http\Controllers;

use App\Factories\MistServerServiceFactory;
use App\Models\MistStreamPushDestination;
use App\Services\MistServer\MistServerService;
use App\Models\MistServerActivePush;
use App\Models\MistServerAutoPush;
use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\TeamMember;
use App\Models\User;
use App\Services\PushDestinationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class GoLiveController extends Controller {

  protected MistServerService $pushService;

  public function __construct() {
    // Assuming the factory is adjusted to instantiate specific services based on the server type or another parameter
    $this->pushService = MistServerServiceFactory::make('push'); // Now returns an instance of PushService
  }


  public function index() {

    return Inertia::render('GoLive', [
//        'shows'          => $this->listAvailableShows(),
        'can' => [
//            'viewShows'   => Auth::user()->can('view', Show::class),
            'goLive' => Auth::user()->can('goLive', User::class),
        ]
    ]);

  }

  /**
   * List all shows available for the user to go live on.
   */
  public function listAvailableShows() {
    // Get the IDs of teams where the user is an active member
    $userActiveTeamIds = TeamMember::where('user_id', Auth::id())
        ->where('active', true)
        ->pluck('team_id');

    // Get shows that belong to those teams and have a status of 1, 2, or 9
    return Show::whereIn('team_id', $userActiveTeamIds)
        ->whereIn('show_status_id', [1, 2, 9]) // 1 = new, 2 = active, 9 = creators only
        ->with('mistStreamWildcard')
        ->get();
  }

  /**
   * List all episodes under a specific show that the user can go live in.
   */
  public function listEpisodesForShow($showId) {
    // Validate user has access to the show
    // this should get updated to allow the team members... or whoever is given access for this.
    $show = Show::where('id', $showId)->where('user_id', Auth::id())->firstOrFail();

    // episodes must belong to a new or active or creator only show... and episode must be scheduled
    // and episode must not have a video attached.

    $episodes = $show->episodes; // Assuming a 'episodes' relationship exists on your Show model

    return response()->json($episodes);
  }

  /**
   * Retrieves or generates a stream key for a specific episode.
   */
  public function getStreamKeyForEpisode($episodeId) {
    // Validate user has access to the episode
    $episode = ShowEpisode::where('id', $episodeId)->whereHas('show', function ($query) {
      $query->where('user_id', Auth::id());
    })->firstOrFail();

    // Assuming you store or generate a stream key in your Episode model
    $streamKey = $episode->stream_key;

    return response()->json(['stream_key' => $streamKey]);
  }

  /**
   * Retrieves or generates a stream key for a specific episode.
   */
  public function getStreamKeyForShow(Request $request, $showId) {
    $streamDetails = [
        'name'   => 'show',
        'source' => 'push://',
    ];
    $mistServerService = app(MistServerService::class);
    $response = $mistServerService->addOrUpdateStream('show', $streamDetails);
    try {
      $mistStreamWildcard = Show::generateStreamKey($showId);

      // Success response
      return response()->json(['stream_key' => $mistStreamWildcard->name]);
    } catch (\Exception $e) {
      Log::error("Error in generating stream key for show ID {$showId}: {$e->getMessage()}");

      // If it's an API, return a JSON response
      if ($request->expectsJson()) { // Correctly use the request object here
        return response()->json(['error' => $e->getMessage()], 500);
      }

      // For web routes, redirect back with an error message
      return back()->with(['error' => $e->getMessage()])->withInput();
    }


    // Query the Show model by slug
//    $show = Show::where('id', $showId)->firstOrFail();

    // Assuming you store or generate a stream key in your Show model
//    $streamKey = $show->stream_key;

//    return response()->json(['stream_key' => $streamKey]);
  }

  /**
   * Prepares the necessary settings and configurations for going live on a specific episode.
   */
  public function prepareLiveStream(Request $request, $episodeId) {
    // Validate user has access to the episode
    $episode = ShowEpisode::where('id', $episodeId)->whereHas('show', function ($query) {
      $query->where('user_id', Auth::id());
    })->firstOrFail();

    // Process preparation steps, such as updating episode details based on request
    // For simplicity, this is just an example placeholder
    $episode->update($request->all());

    return response()->json(['message' => 'Live stream prepared successfully.']);
  }

  public function fetchPushDestinations(Request $request): \Illuminate\Http\JsonResponse
  {
    $validated = $request->validate([
        'showId' => 'required|exists:shows,id',
        'streamName' => 'required|string',
    ]);

    $showId = $validated['showId'];
    $streamName = $validated['streamName'];

    // Assuming allActivePushes is a collection of arrays with 'stream_name' and 'original_uri' keys
    $allActivePushes = collect(Cache::get('all_active_pushes', []));

    // Log the content of the cache to debug
    Log::debug('All active pushes from cache', ['active_pushes' => $allActivePushes->toArray()]);


    // Get all destinations for the showId
    $pushDestinations = MistStreamPushDestination::with(['mistStreamWildcard' => function ($query) use ($streamName) {
      $query->where('name', $streamName);
    }])
        ->whereHas('mistStreamWildcard', function($query) use ($streamName) {
          $query->where('name', $streamName);
        })
        ->get();

    // Iterate through each destination to update its 'push_is_started' status
    foreach ($pushDestinations as $destination) {
      $isMatched = $allActivePushes->contains(function ($push) use ($destination) {
        return $push['stream_name'] === $destination->stream_name && $push['original_uri'] === $destination->full_push_uri;
      });

      $destination->push_is_started = $isMatched ? 1 : 0;
      $destination->save();
    }

    return response()->json([
        'status' => 'success',
        'message' => "Push destinations updated for stream: $streamName",
        'destinations' => $pushDestinations,
    ]);
  }




//    // Prepare the array of destinations to be returned
//    $destinations = $matchedDestinations->map(function ($destination) {
//      // Here you can structure the returned data as needed
//      return [
//          'stream_name'  => $destination['stream_name'],
//          'original_uri' => $destination['original_uri'],
//      ];
//    });
    // 2. Iterate through the cache
    // match the $allActivePushes->streamName to the $streamName
    // 3. Return the array of destinations


//    $show = Show::with('mistStreamWildcard.mistStreamPushDestination')->find($showId);
//    if (!$show) {
//      return response()->json([
//          'status'  => 'error',
//          'message' => 'Show not found',
//      ], 404);
//    }
//
//    // Assuming this relationship and custom accessor correctly resolve the push destinations
//    $pushDestinations = $show->mistStreamPushDestinations;
//    $streamName = $show->mistStreamWildcard->name;
//
//    // Fetch all active pushes once to avoid querying in a loop
//    $activePushURIs = MistServerAutoPush::where('stream_name', $streamName)
//        ->pluck('uri')
//        ->toArray();
//////
//    foreach ($pushDestinations as $destination) {
//      // Construct the destination URI for matching
//      $destinationURI = $destination->full_push_uri;
//
//      // Check against active pushes for 'has_auto_push'
////      $destination->has_auto_push = in_array($destinationURI, $activePushURIs) ? 1 : 0;
//
//      // Check for an active push match for 'push_is_started'
//      $activePushExists = MistServerActivePush::where([
//          ['stream_name', '=', $streamName],
//          ['original_uri', '=', $destinationURI],
//      ])->exists();
////      $destination->push_is_started = $activePushExists ? 1 : 0;
//
//      // Save the updated destination
//      $destination->save();
//    }

    // Optionally refresh the collection from the database if needed
//    $pushDestinations = $show->fresh()->mistStreamPushDestinations;

//    return response()->json([
//        'status'       => 'success',
//        'message'      => 'Push Destinations Updated for ' . $streamName,
//        'destinations' => $pushDestinations,
////        'isRecording'  => $show->mistStreamWildcard->is_recording,
//    ]);
//  }

}
