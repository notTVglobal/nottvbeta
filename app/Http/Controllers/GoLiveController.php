<?php

namespace App\Http\Controllers;

use App\Factories\MistServerServiceFactory;
use App\Models\AppSetting;
use App\Models\MistStreamPushDestination;
use App\Models\MistStreamWildcard;
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

    // Get shows that belong to those teams and have a status of 1, 2, 8, or 9
    return Show::whereIn('team_id', $userActiveTeamIds)
        ->whereIn('show_status_id', [1, 2, 8, 9]) // 1 = new, 2 = active, 8 = hidden, 9 = creators only
        ->with('mistStreamWildcard')
        ->with(['scheduleIndexes' => function($query) {
          $query->orderBy('created_at')->limit(1); // Limit to the first scheduleIndex
        }])
        ->get()
        ->map(function ($show) {
          $show->nextBroadcast = optional($show->scheduleIndexes->first())->next_broadcast;
          unset($show->scheduleIndexes); // Remove the scheduleIndexes array
          return $show;
        });
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

  public function fetchPushDestinations(Request $request): \Illuminate\Http\JsonResponse {
    $validated = $request->validate([
        'showId'          => 'nullable|integer',
        'streamName'      => 'required|string',
        'backgroundFetch' => 'nullable|boolean'
    ]);

    $streamName = $validated['streamName'];

    // tec21: 2024-03-31 for the first fetch we want to get the active push list
    // directly from MistServer. Then for the subsequent background
    // fetches we can use the Cache which gets update through our
    // Command that runs every minute. This will be updated to handle
    // Real-time notifications when we build out more of our web sockets.
    if ($validated['backgroundFetch']) {
      // Assuming allActivePushes is a collection of arrays with 'stream_name' and 'original_uri' keys
      $allActivePushesCollection = collect(Cache::get('all_active_pushes', []));
    } else {
      // Fetch the new push list from the push service
      $newPushList = $this->pushService->fetchPushList();
      $newPushListItems = collect($newPushList['push_list'] ?? []);
      // Prepare the $allActivePushes array
      $allActivePushesCollection = $newPushListItems->map(function ($item) {
        return [
            'push_id'       => $item[0], // Assuming the ID is the first element
            'stream_name'   => $item[1],
            'original_uri'  => $item[2],
            'processed_uri' => $item[3] ?? '', // Provide defaults as necessary
            'logs'          => $item[4] ?? [],
            'push_status'   => $item[5] ?? [],
        ];
      });
//      Log::debug('all active pushes: ', ['allActivePushes' => $allActivePushesCollection->toArray()]);
    }

    // Get all destinations for the showId
    $pushDestinations = MistStreamPushDestination::with(['mistStreamWildcard' => function ($query) use ($streamName) {
      $query->where('name', $streamName);
    }])
        ->whereHas('mistStreamWildcard', function ($query) use ($streamName) {
          $query->where('name', $streamName);
        })
        ->get();

    $anyRecording = false; // Flag to track if any destination is recording
    $mistStreamWildcardToUpdate = null; // Placeholder for the mistStreamWildcard

// Iterate through each destination to update its 'push_is_started' status and check recording status
    foreach ($pushDestinations as $destination) {
      $isMatched = $allActivePushesCollection->contains(function ($push) use ($destination) {
        return $push['stream_name'] === $destination->stream_name && $push['original_uri'] === $destination->full_push_uri;
      });

      // Update the push_is_started status based on match
      $destination->push_is_started = $isMatched ? 1 : 0;
      $destination->save();

      $settings = AppSetting::find(1);

      $userRecordingsPath = $settings->mist_server_settings['mist_server_user_recording_folder'] ?? null;
      $autoRecordingsPath = $settings->mist_server_settings['mist_server_automated_recording_folder'] ?? null;

      // delete these:
//      $userRecordingsPath = config('paths.user_recordings_path');
//      $autoRecordingsPath = config('paths.auto_recordings_path');

      // Perform the additional check for recording status
      $isRecording = !is_null($allActivePushesCollection->first(function ($item) use ($autoRecordingsPath, $userRecordingsPath, $destination) {
        return $item['stream_name'] === $destination->stream_name &&
            str_contains($item['original_uri'], $userRecordingsPath) &&
            !str_contains($item['original_uri'], $autoRecordingsPath . '$stream_$datetime.mkv');
      }));

      if ($isRecording) {
        $anyRecording = true; // Set flag if any recording conditions are met
      }

      // Assign the mistStreamWildcard for later update
      if ($destination->mistStreamWildcard) {
        $mistStreamWildcardToUpdate = $destination->mistStreamWildcard;
      }
    }

// After determining $anyRecording, update the mistStreamWildcard if it has been assigned
    if ($mistStreamWildcardToUpdate) {
      $mistStreamWildcardToUpdate->is_recording = $anyRecording ? 1 : 0;
      $mistStreamWildcardToUpdate->save();
    }

    // Optionally, return the updated list of destinations
    $updatedDestinations = $pushDestinations->fresh();
    // After the loop completes, log and return the aggregated recording status ($anyRecording)
//    Log::debug('is recording?? ' . json_encode($anyRecording));
    return response()->json([
        'status'       => 'success',
        'message'      => "Push destinations updated for stream: $streamName",
        'destinations' => $updatedDestinations,
        'recording'    => $anyRecording,  // Return the aggregated recording status
    ]);
  }

  private function validateShowOrEpisodeId($showId, $episodeId): ?\Illuminate\Http\JsonResponse {
    // Validate that at least one of showId or episodeId is provided
    if (!$showId && !$episodeId) {
      return response()->json([
          'status'  => 'error',
          'message' => 'Either showId or episodeId must be provided.',
      ], 400);
    }

    // Check if the show or episode exists
    if ($showId && !Show::where('id', $showId)->exists()) {
      return response()->json([
          'status'  => 'error',
          'message' => 'The provided show ID does not exist.',
      ], 404);
    }

    if ($episodeId && !ShowEpisode::where('id', $episodeId)->exists()) {
      return response()->json([
          'status'  => 'error',
          'message' => 'The provided episode ID does not exist.',
      ], 404);
    }

    return null; // Indicates no errors
  }

  public function getExistingDestinations(Request $request): \Illuminate\Http\JsonResponse {
    $showId = $request->input('showId');
    $episodeId = $request->input('episodeId');
    $userId = $request->user()->id; // Assuming you have user authentication

    // Log the incoming request data
    Log::debug('getExistingDestinations called', [
        'showId' => $showId,
        'episodeId' => $episodeId,
        'userId' => $userId,
    ]);

    $validationResponse = $this->validateShowOrEpisodeId($showId, $episodeId);
    if ($validationResponse) {
      Log::debug('Validation failed', ['response' => $validationResponse]);
      return $validationResponse;
    }

    // Get the teams the user is a member of
    $teamIds = TeamMember::where('user_id', $userId)->pluck('team_id');
    Log::debug('Team IDs fetched', ['teamIds' => $teamIds]);

    // Fetch all shows belonging to these teams
    $showIds = Show::whereIn('team_id', $teamIds)->pluck('id');
    Log::debug('Show IDs fetched', ['showIds' => $showIds]);

    // Fetch destinations from these shows and eager load the show
    $existingDestinations = MistStreamPushDestination::whereIn('show_id', $showIds)
        ->with('show:id,name') // Eager load the show with only the id and name
        ->get(['id', 'rtmp_url', 'rtmp_key', 'comment', 'show_id']); // Select necessary fields
    Log::debug('Existing destinations fetched', ['destinations' => $existingDestinations]);

    // Remove duplicates based on rtmp_url and rtmp_key
    $existingDestinations = $existingDestinations->unique(function ($item) {
      return $item['rtmp_url'] . $item['rtmp_key'];
    });
    Log::debug('Duplicates removed from destinations', ['uniqueDestinations' => $existingDestinations]);

    // Add the show name to each destination
    $existingDestinations = $existingDestinations->map(function ($destination) {
      $destination->show_name = $destination->show->name;
      unset($destination->show); // Remove the show relationship to keep the response clean

      return $destination;
    });
    Log::debug('Show names added to destinations', ['finalDestinations' => $existingDestinations]);

    return response()->json([
        'status'       => 'success',
        'message'      => 'Existing destinations fetched successfully.',
        'destinations' => $existingDestinations,
    ]);
  }

  public function copyDestinations(Request $request): \Illuminate\Http\JsonResponse {
    // Log the request data
    Log::info('Received copy destinations request', $request->all());

    // Validate the request data
    $validatedData = $request->validate([
        'destinationIds'       => 'required|array',
        'destinationIds.*'     => 'exists:mist_stream_push_destinations,id',
        'showId'               => 'nullable|exists:shows,id',
        'episodeId'            => 'nullable|exists:show_episodes,id',
        'mistStreamWildcardId' => 'nullable|exists:mist_stream_wildcards,id'
    ]);

    $destinationIds = $validatedData['destinationIds'];
    $showId = $validatedData['showId'] ?? null;
    $episodeId = $validatedData['episodeId'] ?? null;

    $validationResponse = $this->validateShowOrEpisodeId($showId, $episodeId);
    if ($validationResponse) {
      Log::error('Validation error', ['validationResponse' => $validationResponse]);

      return $validationResponse;
    }

    // Fetch the destinations to be copied
    $destinationsToCopy = MistStreamPushDestination::whereIn('id', $destinationIds)->get();

    Log::info('Destinations to copy', ['destinationsToCopy' => $destinationsToCopy]);

    $updatedDestinations = [];

    foreach ($destinationsToCopy as $destination) {
      $newDestination = $destination->replicate();
      if ($showId) {
        $newDestination->show_id = $showId;
      }
      if ($episodeId) {
        $newDestination->episode_id = $episodeId;
      }
      $newDestination->mist_stream_wildcard_id = $validatedData['mistStreamWildcardId'];
      $newDestination->save();
      $updatedDestinations[] = $newDestination;
    }

    Log::info('Copied destinations', ['updatedDestinations' => $updatedDestinations]);

    return response()->json([
        'status'       => 'success',
        'message'      => 'Push destinations copied successfully.',
        'destinations' => $updatedDestinations,
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
