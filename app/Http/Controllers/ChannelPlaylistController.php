<?php

namespace App\Http\Controllers;

use App\Http\Resources\SimpleMovieResource;
use App\Http\Resources\SimpleNewsStoryResource;
use App\Http\Resources\SimpleShowEpisodeResource;
use App\Models\ChannelPlaylist;
use App\Models\ChannelPlaylistItem;
use App\Models\Movie;
use App\Models\NewsStory;
use App\Models\OtherContent;
use App\Models\ShowEpisode;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ChannelPlaylistController extends Controller {

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
      $maxDurationMinutes = $request->input('maxDurationMinutes');
      $page = $request->input('page', 1);
      $search = $request->input('search', '');
      $startDateTime = $request->input('start_dateTime');

      Log::info('Request received', [
          'type'               => $type,
          'maxDurationMinutes' => $maxDurationMinutes,
          'page'               => $page,
          'search'             => $search,
          'start_dateTime'     => $startDateTime,
      ]);

      if (!$type || !$maxDurationMinutes || !$startDateTime) {
        return response()->json(['message' => 'Invalid request parameters', 'status' => 'error'], 400);
      }

      $maxDurationSeconds = $maxDurationMinutes * 60;

      $contentQuery = match ($type) {
        'ShowEpisode' => ShowEpisode::with('image'),
        'Movie' => Movie::with('image'),
        'NewsStory' => NewsStory::with('image'),
        'OtherContent' => OtherContent::query(),
        default => throw new \Exception('Invalid content type'),
      };

      $contentQuery->where('duration', '<=', $maxDurationSeconds);

      if ($search) {
        $contentQuery->where('name', 'like', "%$search%");
      }

      // Log the query for debugging
      Log::info('Query being executed', ['query' => $contentQuery->toSql(), 'bindings' => $contentQuery->getBindings()]);

      $content = $contentQuery->paginate(10, ['*'], 'page', $page);

      // Log the actual items fetched from the query
      Log::info('Items fetched', ['items' => $content->items()]);

      $formattedContent = $content->map(function ($item) use ($type, $startDateTime) {
        $durationMinutes = $item->duration / 60;
        $startDateTimeParsed = Carbon::parse($startDateTime);
        $endDateTime = $startDateTimeParsed->copy()->addMinutes($durationMinutes);

        return [
            'content'          => match ($type) {
              'ShowEpisode' => new SimpleShowEpisodeResource($item),
              'Movie' => new SimpleMovieResource($item),
              'NewsStory' => new SimpleNewsStoryResource($item),
              'OtherContent' => $item,
            },
//            'start_dateTime' => $startDateTimeParsed->format('Y-m-d\TH:i:s.v\Z'),
            'start_dateTime'   => $startDateTimeParsed->format('Y-m-d\TH:i:s.v\Z'),
            'end_dateTime'     => $endDateTime->format('Y-m-d\TH:i:s.v\Z'),
            'duration_minutes' => $durationMinutes,
            'type'             => lcfirst($type),
        ];
      });

      Log::info('Content fetched successfully', [
          'items'        => $formattedContent,
          'current_page' => $content->currentPage(),
          'total_pages'  => $content->lastPage(),
      ]);

      return response()->json([
          'items'        => $formattedContent,
          'current_page' => $content->currentPage(),
          'total_pages'  => $content->lastPage(),
          'message'      => 'Content fetched successfully',
          'status'       => 'success',
      ]);
    } catch (\Exception $e) {
      Log::error('Exception in adminGetContent', ['exception' => $e->getMessage()]);

      return response()->json(['message' => 'An unexpected error occurred', 'status' => 'error', 'details' => $e->getMessage()], 500);
    }
  }

  public function adminGetPlaylists(): JsonResponse
  {
    try {
      Log::info('Fetching channel playlists');

      // Fetch playlists from the database
      $playlists = ChannelPlaylist::all();

      Log::info('Playlists fetched successfully', ['playlists' => $playlists]);

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
    // Fetch all playlists with the next playlist information
    $playlists = ChannelPlaylist::with('nextPlaylist')->get();

    // Transform the data to include the name of the next playlist
    $playlists = $playlists->map(function ($playlist) {
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
    try {
      // Validate the incoming request
      $validator = Validator::make($request->all(), [
          'name' => 'required|string|max:255|unique:channel_playlists,name',
          'description' => 'nullable|string',
          'url' => 'nullable|url',
          'type' => 'required|string|in:regular,event,special',
          'start_dateTime' => 'required|date',
          'end_dateTime' => 'required|date|after_or_equal:start_dateTime',
          'priority' => 'required|integer',
          'repeat_mode' => 'required|string|in:repeat_all,repeat_last,shuffle,stop,next_playlist',
          'next_playlist_id' => 'nullable|exists:channel_playlists,id',
          'scheduleItems.*.content.id' => 'required|integer',
          'scheduleItems.*.type' => 'required|string',
          'scheduleItems.*.start_dateTime' => 'required|date',
          'scheduleItems.*.end_dateTime' => 'required|date|after_or_equal:scheduleItems.*.start_dateTime',
      ]);

      if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'status' => 'error',
            'errors' => $validator->errors()
        ], 422);
      }

      Log::info('Creating ChannelPlaylist', $request->all());

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

      Log::info('ChannelPlaylist created', ['playlist' => $channelPlaylist]);

      // Iterate over scheduleItems and create a ChannelPlaylistItem for each
      $channelPlaylistItems = [];
      foreach ($request->scheduleItems as $index => $item) {
        $itemStartDateTime = Carbon::parse($item['start_dateTime'])->format('Y-m-d H:i:s');
        $itemEndDateTime = Carbon::parse($item['end_dateTime'])->format('Y-m-d H:i:s');

        $channelPlaylistItem = ChannelPlaylistItem::create([
            'playlist_id' => $channelPlaylist->id,
            'content_id' => $item['content']['id'],
            'content_type' => ucfirst($item['type']),
            'order' => $index + 1,  // Assuming the order is based on the index
            'media_type' => null, // Adjust based on your requirements
            'source_path' => null, // Adjust based on your requirements
            'source_type' => null, // Adjust based on your requirements
            'is_live' => false, // Adjust based on your requirements
            'current_viewers_count' => 0,
            'max_viewers_count' => 0,
            'additional_sources' => null, // Adjust based on your requirements
            'custom_playback_options' => null, // Adjust based on your requirements
            'metadata' => null, // Adjust based on your requirements
            'has_played' => false,
            'start_dateTime' => $itemStartDateTime,
            'end_dateTime' => $itemEndDateTime,
        ]);
        $channelPlaylistItems[] = $channelPlaylistItem;
      }

      Log::info('ChannelPlaylistItems created', ['items' => $channelPlaylistItems]);

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
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return Response
   */
  public function store(Request $request) {
    //
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
   * @param \Illuminate\Http\Request $request
   * @param ChannelPlaylist $channelPlaylist
   * @return Response
   */
  public function update(Request $request, ChannelPlaylist $channelPlaylist) {
    //
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
