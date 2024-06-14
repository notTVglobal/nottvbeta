<?php

namespace App\Http\Controllers;

use App\Helpers\ChannelPlaylistItemCreator;
use App\Helpers\ChannelPlaylistItemHelper;
use App\Helpers\ChannelPlaylistRequestValidator;
use App\Helpers\PlaylistContentHelper;
use App\Http\Resources\SimpleMovieResource;
use App\Http\Resources\SimpleNewsStoryResource;
use App\Http\Resources\SimpleShowEpisodeResource;
use App\Models\ChannelPlaylist;
use App\Models\ChannelPlaylistItem;
use App\Models\MistStream;
use App\Models\Movie;
use App\Models\NewsStory;
use App\Models\OtherContent;
use App\Models\ShowEpisode;
use App\Traits\PreloadContentRelationships;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ChannelPlaylistController extends Controller {

  use PreloadContentRelationships;

  public function __construct() {

    $this->middleware('can:viewAny,' . ChannelPlaylist::class)->only(['index']);
    $this->middleware('can:view,channelPlaylist')->only(['show']);
    $this->middleware('can:create,' . ChannelPlaylist::class)->only(['create']);
    $this->middleware('can:store,' . ChannelPlaylist::class)->only(['store']);
    $this->middleware('can:edit,channelPlaylist')->only(['edit']);
    $this->middleware('can:update,channelPlaylist')->only(['update']);
    $this->middleware('can:delete,channelPlaylist')->only(['destroy']);
    $this->middleware('can:restore,channelPlaylist')->only(['restore']);
    $this->middleware('can:forceDelete,channelPlaylist')->only(['forceDelete']);
  }


  public function adminSearchChannelPlaylists(Request $request): \Inertia\Response {
    $search = $request->input('search', '');

    // Directly fetch mistStreams based on the search input.
    $channelPlaylists = ChannelPlaylist::with('channel')
        ->when($search, function ($query, $search) {

          $query->where('name', 'like', "%{$search}%");
        })->latest()
        ->paginate(13, ['*'], 'admin/channels')
        ->withQueryString()
        ->through(fn($channelPlaylist) => [
            'channelPlaylist' => $channelPlaylist,
            'channel'         => $channelPlaylist->channel,
        ]);

    $filters = [
        'search' => $search, // Use the $search variable you've already set
    ];

    return Inertia::render('Admin/Channels/Index', [
        'channelPlaylists'              => $channelPlaylists, // Pass the fetched mistStreams directly
        'channelPlaylistsSearchFilters' => $filters,
    ]);

  }

  public function adminGetContent(Request $request): JsonResponse {
    try {
      $type = $request->input('type');
      $maxDurationMinutes = intval($request->input('maxDurationMinutes')); // Ensure this is an integer
      $page = intval($request->input('page', 1)); // Ensure this is an integer
      $search = $request->input('search', '');
      $startDateTime = $request->input('start_dateTime');

      if (!$type || !$maxDurationMinutes || !$startDateTime) {
        Log::error('Invalid request parameters', [
            'type' => $type,
            'maxDurationMinutes' => $maxDurationMinutes,
            'startDateTime' => $startDateTime,
        ]);
        return response()->json(['message' => 'Invalid request parameters', 'status' => 'error'], 400);
      }

      $maxDurationSeconds = $maxDurationMinutes * 60;

      $contentQuery = match ($type) {
        'ShowEpisode' => ShowEpisode::with('image'),
        'Movie' => Movie::with('image'),
        'NewsStory' => NewsStory::with('image'),
        'OtherContent' => OtherContent::query(),
        'MistStream' => MistStream::query(),
        default => throw new \Exception("Invalid content type: $type"),
      };

      if ($type !== 'MistStream') {
        $contentQuery->where('duration', '<=', $maxDurationSeconds);
      }

      if ($search) {
        $contentQuery->where('name', 'like', "%$search%");
      }

      $content = $contentQuery->paginate(10, ['*'], 'page', $page);

      $formattedContent = $content->map(function ($item) use ($type, $startDateTime, $maxDurationMinutes) {
        $durationMinutes = $type !== 'MistStream' ? $item->duration / 60 : $maxDurationMinutes;
        $startDateTimeParsed = Carbon::parse($startDateTime);
        $endDateTime = $startDateTimeParsed->copy()->addMinutes((int)$durationMinutes);

        return [
            'content' => \App\Helpers\PlaylistContentHelper::getFormattedContent($item, $type),
            'start_dateTime' => $startDateTimeParsed->format('Y-m-d\TH:i:s.v\Z'),
            'end_dateTime' => $endDateTime->format('Y-m-d\TH:i:s.v\Z'),
            'duration_minutes' => $durationMinutes,
            'type' => lcfirst(class_basename($type)),
        ];
      });

      return response()->json([
          'items' => $formattedContent,
          'current_page' => $content->currentPage(),
          'total_pages' => $content->lastPage(),
          'message' => 'Content fetched successfully',
          'status' => 'success',
      ]);
    } catch (\Exception $e) {
      Log::error('Exception in adminGetContent', [
          'exception' => $e->getMessage(),
          'type' => $type,
          'maxDurationMinutes' => $maxDurationMinutes,
          'page' => $page,
          'search' => $search,
          'startDateTime' => $startDateTime,
      ]);

      return response()->json(['message' => 'An unexpected error occurred', 'status' => 'error', 'details' => $e->getMessage()], 500);
    }
  }






  public function adminGetPlaylists(): JsonResponse
  {
    try {
//      Log::debug('Fetching channel playlists');

      // Fetch playlists from the database
      $playlists = ChannelPlaylist::all();

//      Log::debug('Playlists fetched successfully', ['playlists' => $playlists]);

      return response()->json([
          'message' => 'Playlists fetched successfully',
          'status' => 'success',
          'playlists' => $playlists
      ], 200);
    } catch (\Exception $e) {
      Log::error('Exception in adminGetPlaylists', ['exception' => $e->getMessage()]);

      return response()->json([
          'message' => 'An unexpected error occurred',
          'status' => 'error',
          'details' => $e->getMessage()
      ], 500);
    }
  }


  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {

    // Fetch all playlists with the next playlist information and items with content
    $playlists = ChannelPlaylist::with(['nextPlaylist', 'items'])->get();

    // Preload additional relationships for each item's content
    foreach ($playlists as $playlist) {
      foreach ($playlist->items as $item) {
        $this->preloadContentRelationships($item);
      }
    }

    // Transform the data to include the name of the next playlist
    $playlists = $playlists->map(function ($playlist) {
//      Log::debug('Processing playlist', ['playlist' => $playlist->toArray()]);
      return [
          'id' => $playlist->id,
          'channel_id' => $playlist->channel_id,
          'name' => $playlist->name,
          'description' => $playlist->description,
          'url' => $playlist->url,
          'type' => $playlist->type,
          'status' => $playlist->status,
          'start_dateTime' => $playlist->start_dateTime,
          'end_dateTime' => $playlist->end_dateTime,
          'priority' => $playlist->priority,
          'repeat_mode' => $playlist->repeat_mode,
          'next_playlist_id' => $playlist->next_playlist_id,
          'next_playlist_name' => $playlist->nextPlaylist ? $playlist->nextPlaylist->name : null,
          'playlist_items' => $playlist->items->map(function ($item) {
//            Log::debug('Processing item', ['item' => $item->toArray()]);
//            $durationMinutes = $item->duration / 60;
//            $startDateTimeParsed = Carbon::parse($item->start_dateTime);
//            $endDateTime = $startDateTimeParsed->copy()->addMinutes($durationMinutes);
            $startDateTimeParsed = $item->start_dateTime ? Carbon::parse($item->start_dateTime) : null;
            $endDateTimeParsed = $item->end_dateTime ? Carbon::parse($item->end_dateTime) : null;

            return [
                'id' => $item->id,
                'content_id' => $item->content_id,
                'content_type' => $item->content_type,
                'order' => $item->order,
                'media_type' => $item->media_type,
                'source_path' => $item->source_path,
                'source_type' => $item->source_type,
                'is_live' => $item->is_live,
                'is_scheduled' => $item->is_scheduled,
                'current_viewers_count' => $item->current_viewers_count,
                'max_viewers_count' => $item->max_viewers_count,
                'additional_sources' => $item->additional_sources ?? null,
                'custom_playback_options' => $item->custom_playback_options ?? null,
                'metadata' => $item->metadata ?? null,
                'has_played' => $item->has_played,
                'start_dateTime' => $startDateTimeParsed?->format('Y-m-d\TH:i:s.v\Z'),
                'end_dateTime' => $endDateTimeParsed?->format('Y-m-d\TH:i:s.v\Z'),
                'duration_minutes' => $item->duration_minutes,
                'type' => $item->content_type,
                'content' => PlaylistContentHelper::getFormattedContent($item->content, $item->content_type),            ];
          }),
      ];
    });

    return response()->json($playlists);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function create(Request $request): JsonResponse
  {
   //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function store(Request $request): JsonResponse
  {
    try {
      // Convert all content IDs to strings
      $requestData = $request->all();
      foreach ($requestData['scheduleItems'] as &$item) {
        $item['content']['id'] = (string) $item['content']['id'];
      }
      $request->replace($requestData);
      // Validate the incoming request
      $validator = ChannelPlaylistRequestValidator::validatePlaylistRequest($request);
      if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'status' => 'error',
            'details' => $validator->errors()
        ], 422);
      }

      // Convert datetime fields to the MySQL format
      $startDateTime = Carbon::parse($request->start_dateTime)->format('Y-m-d H:i:s');
      $endDateTime = Carbon::parse($request->end_dateTime)->format('Y-m-d H:i:s');

      // Create the ChannelPlaylist
      $channelPlaylist = ChannelPlaylist::create([
          'name' => $request->name,
          'description' => $request->description,
          'url' => $request->url,
          'type' => $request->type,
          'start_dateTime' => $startDateTime,
          'end_dateTime' => $endDateTime,
          'priority' => $request->priority,
          'repeat_mode' => $request->repeat_mode,
          'next_playlist_id' => $request->next_playlist_id,
      ]);

      // Create or Update the ChannelPlaylistItems
      $channelPlaylistItems = ChannelPlaylistItemHelper::createOrUpdateItems($request->scheduleItems, $channelPlaylist->id);

      return response()->json([
          'message' => 'Channel Playlist created successfully',
          'status' => 'success',
          'playlist' => $channelPlaylist,
          'items' => $channelPlaylistItems
      ], 201);
    } catch (\Exception $e) {
      Log::error('Exception in create method', ['exception' => $e->getMessage()]);

      return response()->json(['message' => 'An unexpected error occurred', 'status' => 'error', 'details' => $e->getMessage()], 500);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param ChannelPlaylist $channelPlaylist
   * @return Response
   */
  public function show(ChannelPlaylist $channelPlaylist) {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param ChannelPlaylist $channelPlaylist
   * @return Response
   */
  public function edit(ChannelPlaylist $channelPlaylist) {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param ChannelPlaylist $channelPlaylist
   * @return JsonResponse
   */
  public function update(Request $request, ChannelPlaylist $channelPlaylist): JsonResponse
  {
    try {
      // Convert all content IDs to strings
      $requestData = $request->all();
      foreach ($requestData['scheduleItems'] as &$item) {
        $item['content']['id'] = (string) $item['content']['id'];
      }
      $request->replace($requestData);
      // Validate the incoming request
      $validator = ChannelPlaylistRequestValidator::validatePlaylistRequest($request, $channelPlaylist->id);
      if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'status' => 'error',
            'details' => $validator->errors()
        ], 422);
      }

      // Convert datetime fields to the MySQL format
      $startDateTime = Carbon::parse($request->start_dateTime)->format('Y-m-d H:i:s');
      $endDateTime = Carbon::parse($request->end_dateTime)->format('Y-m-d H:i:s');

      // Update the ChannelPlaylist
      $channelPlaylist->update([
          'name' => $request->name,
          'description' => $request->description,
          'url' => $request->url,
          'type' => $request->type,
          'start_dateTime' => $startDateTime,
          'end_dateTime' => $endDateTime,
          'priority' => $request->priority,
          'repeat_mode' => $request->repeat_mode,
          'next_playlist_id' => $request->next_playlist_id,
      ]);

      // Create or Update the ChannelPlaylistItems
      $channelPlaylistItems = ChannelPlaylistItemHelper::createOrUpdateItems($request->scheduleItems, $channelPlaylist->id);

      // Get current item IDs to keep
      $currentItemIds = array_map(fn($item) => $item->id, $channelPlaylistItems);

      // Remove unused items
      ChannelPlaylistItemHelper::removeUnusedItems($channelPlaylist->id, $currentItemIds);

      return response()->json([
          'message' => 'Channel Playlist updated successfully',
          'status' => 'success',
          'playlist' => $channelPlaylist,
          'items' => $channelPlaylistItems
      ], 200);
    } catch (\Exception $e) {
      Log::error('Exception in update method', ['exception' => $e->getMessage()]);

      return response()->json(['message' => 'An unexpected error occurred', 'status' => 'error', 'details' => $e->getMessage()], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param ChannelPlaylist $channelPlaylist
   * @return RedirectResponse
   */
  public function destroy(ChannelPlaylist $channelPlaylist): RedirectResponse {
    $channelPlaylist->delete();

    return back()->with(['message' => 'Channel Playlist deleted successfully'], 200);

//    return response()->json([
//        'message' => 'Channel Playlist deleted successfully',
//        'status' => 'info',
//    ], 201);
  }
}
