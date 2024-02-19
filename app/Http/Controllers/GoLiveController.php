<?php

namespace App\Http\Controllers;

use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\TeamMember;
use App\Models\User;
use App\Services\MistServerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class GoLiveController extends Controller
{

  public function index() {

    return Inertia::render('GoLive', [
//        'shows'          => $this->listAvailableShows(),
        'can'            => [
//            'viewShows'   => Auth::user()->can('view', Show::class),
            'goLive' => Auth::user()->can('goLive', User::class),
        ]
    ]);

  }
  /**
   * List all shows available for the user to go live on.
   */
  public function listAvailableShows()
  {
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
  public function listEpisodesForShow($showId)
  {
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
  public function getStreamKeyForEpisode($episodeId)
  {
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
  public function getStreamKeyForShow($showId)
  {
    $streamDetails = [
        'name' => 'show',
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
      if ($showId->expectsJson()) {
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
  public function prepareLiveStream(Request $request, $episodeId)
  {
    // Validate user has access to the episode
    $episode = ShowEpisode::where('id', $episodeId)->whereHas('show', function ($query) {
      $query->where('user_id', Auth::id());
    })->firstOrFail();

    // Process preparation steps, such as updating episode details based on request
    // For simplicity, this is just an example placeholder
    $episode->update($request->all());

    return response()->json(['message' => 'Live stream prepared successfully.']);
  }
}
