<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Http\Resources\ShowEpisodeResource;
use App\Http\Resources\ShowResource;
use App\Models\ShowSchedule;
use Carbon\CarbonInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShowScheduleController extends Controller {

  public function __construct() {
    $this->middleware('auth');
  }

  /**
   * Display Show Schedule.
   *
   * @return JsonResponse
   */

  public function fetchFiveDaySixHourSchedule(): JsonResponse {
    $cachePath = 'showScheduleFiveDaySixHour'; // A unique identifier for this cache

    // Check if the cache is valid
    if ($this->isCacheValid($cachePath)) {
      // Cache is valid, use the cached data
      $content = Storage::disk('local')->get("json/{$cachePath}.json");
      $cache = json_decode($content, true);

      // Include the user's timezone in the response
      return response()->json([
          'data'         => $cache['data'],
          'userTimezone' => auth()->user()->timezone ?? 'UTC', // Default to 'UTC' if null
      ]);
    }

    // Cache is not valid, fetch new data
    $schedulesByDay = $this->fetchSchedulesByDay();
    $this->cacheData($cachePath, $schedulesByDay); // Use the generalized caching method

    // Include the user's timezone in the response
    return response()->json([
        'data'         => $schedulesByDay,
        'userTimezone' => auth()->user()->timezone ?? 'UTC', // Default to 'UTC' if null
    ]);
  }


  public function fetchTodaysContent(): JsonResponse {
    $cachePath = 'showScheduleToday';

    if ($this->isCacheValid($cachePath)) {
      $content = Storage::disk('local')->get("json/{$cachePath}.json");
      $cache = json_decode($content, true);

      // Include the user's timezone in the response
      return response()->json([
          'data'         => $cache['data'],
          'userTimezone' => auth()->user()->timezone ?? 'UTC', // Default to 'UTC' if null
      ]);
    }

    $data = $this->fetchSchedules(Carbon::now()->startOfDay(), Carbon::now()->endOfDay());
    $this->cacheData($cachePath, $data);

    // Include the user's timezone in the response
    return response()->json([
        'data'         => $data,
        'userTimezone' => auth()->user()->timezone ?? 'UTC', // Default to 'UTC' if null
    ]);
  }

  public function preloadWeeklyContent(): JsonResponse {
    $cachePath = 'showScheduleWeek';

    if ($this->isCacheValid($cachePath)) {
      $content = Storage::disk('local')->get("json/{$cachePath}.json");
      $cache = json_decode($content, true);

      // Include the user's timezone in the response
      return response()->json([
          'data'         => $cache['data'],
          'userTimezone' => auth()->user()->timezone ?? 'UTC', // Default to 'UTC' if null
      ]);
    }

    // Adjust the startOfWeek and endOfWeek to explicitly set Sunday as the start of the week
    $startOfWeek = Carbon::now()->startOfWeek(CarbonInterface::SUNDAY);
    $endOfWeek = Carbon::now()->endOfWeek(CarbonInterface::SATURDAY);

    $data = $this->fetchSchedules($startOfWeek, $endOfWeek);
    $this->cacheData($cachePath, $data);

    // Include the user's timezone in the response
    return response()->json([
        'data'         => $data,
        'userTimezone' => auth()->user()->timezone ?? 'UTC', // Default to 'UTC' if null
    ]);
  }

  public function loadWeekFromDate(Request $request): JsonResponse {
    $date = Carbon::createFromFormat('Y-m-d', $request->formattedDate)->format('Y-m-d');
    $cachePath = "showScheduleWeekFromDate_{$date}";

    if ($this->isCacheValid($cachePath)) {
      $content = Storage::disk('local')->get("json/{$cachePath}.json");
      $cache = json_decode($content, true);

      // Include the user's timezone in the response
      return response()->json([
          'data'         => $cache['data'],
          'userTimezone' => auth()->user()->timezone ?? 'UTC', // Default to 'UTC' if null
      ]);
    }

    $startOfWeek = Carbon::createFromFormat('Y-m-d', $request->formattedDate)->startOfWeek(CarbonInterface::SUNDAY);
    $endOfWeek = Carbon::createFromFormat('Y-m-d', $request->formattedDate)->endOfWeek(CarbonInterface::SATURDAY);

    $data = $this->fetchSchedules($startOfWeek, $endOfWeek);
    $this->cacheData($cachePath, $data);

    // Include the user's timezone in the response
    return response()->json([
        'data'         => $data,
        'userTimezone' => auth()->user()->timezone ?? 'UTC', // Default to 'UTC' if null
    ]);
  }

//  public function index(): JsonResponse {
//    // Check if the cache is valid
//    if ($this->isCacheValid()) {
//      // Cache is valid, use the cached data
//      $path = 'json/showSchedule.json';
//      $content = Storage::disk('local')->get($path);
//      $cache = json_decode($content, true);
//
//      return response()->json($cache['data']);
//    }
//
//    // Cache is not valid, fetch new data
//    $schedulesByDay = $this->fetchSchedulesByDay();
//    $this->cacheShowSchedule($schedulesByDay);
//
//    return response()->json($schedulesByDay);
//  }

  private function fetchSchedulesByDay(): array {
    $schedulesByDay = [];
    // Assuming $now is the current time
    $now = Carbon::now()->second(0)->microsecond(0)->startOfHour();

    for ($i = 0; $i < 5; $i++) {
      // Set the start time for each day's segment to match the hour of 'now' but on subsequent days.
      $segmentStart = $now->copy()->addDays($i)->startOfDay()->addHours($now->hour);
      $segmentEnd = $segmentStart->copy()->addHours(6); // Each segment spans 6 hours from the start time.

      // Fetch schedules within the current segment.
      $schedules = ShowSchedule::with(['content', 'recurrenceDetails'])
          ->whereBetween('start_time', [$segmentStart, $segmentEnd])
          ->orderBy('start_time')
          ->get()
          ->map(function ($schedule) {
            // Preload the 'show' relationship for ShowEpisode content
            if ($schedule->content_type === 'App\Models\ShowEpisode') {
              $schedule->content->loadMissing(['appSetting', 'show.category', 'show.subCategory', 'show.image.appSetting', 'creativeCommons', 'mistStreamWildcard', 'image.appSetting', 'video.appSetting', 'video.mistStream', 'video.mistStreamWildcard']);
            } elseif ($schedule->content_type === 'App\Models\Movie') {
              $schedule->content->loadMissing(['appSetting', 'category', 'subCategory', 'creativeCommons', 'trailers', 'image.appSetting', 'video.appSetting', 'video.mistStream', 'video.mistStreamWildcard']);
            }
            // Handle polymorphic relationship
            $content = null;
            if ($schedule->content) {
              switch ($schedule->content_type) {
                case 'App\Models\ShowEpisode':
                  $content = new ShowEpisodeResource($schedule->content);
                  break;
                case 'App\Models\Movie':
                  $content = new MovieResource($schedule->content);
                  break;
                // Add cases for other content types as needed
              }
            }

            return [
                'content'            => $content,
                'type'               => $schedule->type ?? null, // varchar(255) Discriminator: show, movie, trailer, live stream
                'start_time'         => $schedule->start_time ?? null, // datetime
                'end_time'           => $schedule->end_time ?? null, // datetime
                'status'             => $schedule->status ?? null, // enum('scheduled','live','completed','cancelled')
                'priority'           => $schedule->priority ?? null, // int used for sorting scheduling conflicts and priority scheduling
                'recurrence_flag'    => $schedule->recurence_flag ?? null,
                'recurrence_details' => $schedule->recurrenceDetails ?? null,
            ];
          });

      // Add the structured schedules for this segment to the collection.
      $schedulesByDay[] = $schedules;
    }

    return $schedulesByDay;
  }

  private function cacheData($path, $data): void {
    $fullPath = "json/{$path}.json"; // Dynamic path based on the method
    $cacheData = [
        'last_updated' => now()->toDateTimeString(),
        'data'         => $data,
    ];
    Storage::disk('local')->put($fullPath, json_encode($cacheData, JSON_PRETTY_PRINT));
  }

  private function isCacheValid($path): bool {
    $fullPath = "json/{$path}.json";
    if (!Storage::disk('local')->exists($fullPath)) {
      return false;
    }

    $content = Storage::disk('local')->get($fullPath);
    $cache = json_decode($content, true);

    $lastUpdated = Carbon::parse($cache['last_updated']);
    $expiresAt = $lastUpdated->addMinutes(15); // Customize this value if needed

    return now()->lessThan($expiresAt);
  }

  public function invalidateCaches() {
    $directory = 'json'; // The directory where your caches are stored
    $files = Storage::disk('local')->files($directory);

    foreach ($files as $file) {
      // Check if file matches any of the cache patterns
      if (Str::startsWith($file, "{$directory}/showScheduleWeekFromDate_") ||
          Str::startsWith($file, "{$directory}/showScheduleWeek") ||
          Str::startsWith($file, "{$directory}/showScheduleFiveDaySixHour") ||
          Str::startsWith($file, "{$directory}/showScheduleToday")) {
        // Delete the file
        Storage::disk('local')->delete($file);
      }
    }

    return back()->with(['message' => 'All caches invalidated successfully.']);

  }

//  private function cacheShowSchedule($scheduleData): void {
//    $path = 'json/showSchedule.json'; // Path within the "storage/app" directory
//    $data = [
//        'last_updated' => now()->toDateTimeString(),
//        'data'         => $scheduleData,
//    ];
//    Storage::disk('local')->put($path, json_encode($data, JSON_PRETTY_PRINT));
//  }

//  private function isCacheValid(): bool {
//    $path = 'json/showSchedule.json';
//    if (!Storage::disk('local')->exists($path)) {
//      return false;
//    }
//
//    $content = Storage::disk('local')->get($path);
//    $cache = json_decode($content, true);
//
//    $lastUpdated = Carbon::parse($cache['last_updated']);
//    $expiresAt = $lastUpdated->addMinutes(15); // Cache validity: 15 minutes
//
//    return now()->lessThan($expiresAt);
//  }


  private function fetchSchedules(Carbon $startTime, Carbon $endTime) {
    return ShowSchedule::with(['content', 'recurrenceDetails'])
        ->whereBetween('start_time', [$startTime, $endTime])
        ->orderBy('start_time')
        ->get()
        ->map(function ($schedule) {
          return $this->transformSchedule($schedule);
        });
  }

  private function transformSchedule($schedule) {
    // Handle polymorphic relationship and other transformations
    $content = null;
    if ($schedule->content) {
      // Conditionally preload additional relationships based on content type
      switch ($schedule->content_type) {
        case 'App\Models\ShowEpisode':
          $schedule->content->loadMissing([
              'appSetting', 'show.category', 'show.subCategory',
              'show.image.appSetting', 'creativeCommons',
              'mistStreamWildcard', 'image.appSetting',
              'video.appSetting', 'video.mistStream',
              'video.mistStreamWildcard'
          ]);
          $content = new ShowEpisodeResource($schedule->content);
          break;
        case 'App\Models\Movie':
          $schedule->content->loadMissing([
              'appSetting', 'category', 'subCategory',
              'creativeCommons', 'trailers', 'image.appSetting',
              'video.appSetting', 'video.mistStream',
              'video.mistStreamWildcard'
          ]);
          $content = new MovieResource($schedule->content);
          break;
        // Add more cases as needed
      }
      // Transform the content based on its type
      // You can adjust this logic based on how your resources are structured
    }

    return [
        'content'            => $content?->resolve(), // Ensure the resource is converted to an array
        'type'               => $schedule->type ?? null,
        'start_time'         => $schedule->start_time->toDateTimeString() ?? null,
        'end_time'           => $schedule->end_time->toDateTimeString() ?? null,
        'status'             => $schedule->status ?? null,
        'priority'           => $schedule->priority ?? null,
        'recurrence_flag'    => $schedule->recurrence_flag ?? null,
        'recurrence_details' => $schedule->recurrenceDetails ? $schedule->recurrenceDetails->toArray() : null,
        'id'                 => $schedule->id,
    ];
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    // this is where we create a new showSchedule object
    // from a show (with an associated mist_stream or showEpisode),
    // movie, or movieTrailer... and in due time a promo, ad, psa,
    // station id, interstitial, or filler.
  }

  /**
   * Display the specified resource.
   *
   * @param \App\Models\ShowSchedule $showSchedule
   * @return \Illuminate\Http\Response
   */
  public function show(ShowSchedule $showSchedule) {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param \App\Models\ShowSchedule $showSchedule
   * @return \Illuminate\Http\Response
   */
  public function edit(ShowSchedule $showSchedule) {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param \App\Models\ShowSchedule $showSchedule
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, ShowSchedule $showSchedule) {
    // we receive the id of the showSchedule item in the request to update.
    // the time slot can either change from a show vod (show_episode_id) to
    // a show livestream (mist_stream_id) or to a movie or a trailer (movie_trailer_id)
    // or change its start/end times,

    // Update the schedule with new show placements, handling drag-and-drop inputs.
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Models\ShowSchedule $showSchedule
   * @return \Illuminate\Http\Response
   */
  public function destroy(ShowSchedule $showSchedule) {
    //
  }
}
