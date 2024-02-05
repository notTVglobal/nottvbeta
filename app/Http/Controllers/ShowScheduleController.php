<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Http\Resources\ShowEpisodeResource;
use App\Http\Resources\ShowResource;
use App\Models\ShowSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ShowScheduleController extends Controller {

  public function __construct() {
    $this->middleware('auth');
  }

  /**
   * Display Show Schedule.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse {
    // Check if the cache is valid
    if ($this->isCacheValid()) {
      // Cache is valid, use the cached data
      $path = 'json/showSchedule.json';
      $content = Storage::disk('local')->get($path);
      $cache = json_decode($content, true);

      return response()->json($cache['data']);
    }

    // Cache is not valid, fetch new data
    $schedulesByDay = $this->fetchSchedulesByDay();
    $this->cacheShowSchedule($schedulesByDay);

    return response()->json($schedulesByDay);
  }
    private function fetchSchedulesByDay(): array {
      $schedulesByDay = [];
      // Assuming $now is the current time
      $now = Carbon::now()->second(0)->microsecond(0)->startOfHour();

      for ($i = 0; $i < 5; $i++) {
        // Set the start time for each day's segment to match the hour of 'now' but on subsequent days.
        $segmentStart = $now->copy()->addDays($i)->startOfDay()->addHours($now->hour);
        $segmentEnd = $segmentStart->copy()->addHours(6); // Each segment spans 6 hours from the start time.

        // Fetch schedules within the current segment.
        $schedules = ShowSchedule::with(['show', 'mistStream', 'showEpisode', 'movie'])
            ->whereBetween('start_time', [$segmentStart, $segmentEnd])
            ->orderBy('start_time')
            ->get()
            ->map(function ($schedule) {
              return [
                  'show'        => $schedule->show ? new ShowResource($schedule->show) : null, // bigint unsigned Object with show information
                  'showEpisode' => $schedule->showEpisode ? new ShowEpisodeResource($schedule->showEpisode) : null, // bigint unsigned Object with showEpisode information
                  'movie'       => $schedule->movie ? new MovieResource($schedule->movie) : null, // bigint unsigned Object with movie information
                  'mistStream'  => $schedule->mistStream ?? null, // bigint unsigned Object with stream information
                  'type'        => $schedule->type ?? null, // varchar(255) Discriminator: show, movie, trailer, live stream
                  'start_time'  => $schedule->start_time ?? null, // datetime
                  'end_time'    => $schedule->end_time ?? null, // datetime
                  'event_type'  => $schedule->event_type ?? null, // varchar(255) Indicates if the event is one-time or recurring
                  'status'      => $schedule->status ?? null, // enum('scheduled','live','completed','cancelled')
                  'priority'    => $schedule->priority ?? null, // int used for sorting scheduling conflicts and priority scheduling
              ];
            });

        // Add the structured schedules for this segment to the collection.
        $schedulesByDay[] = $schedules;
      }

      return $schedulesByDay;
    }


  private function cacheShowSchedule($scheduleData): void {
    $path = 'json/showSchedule.json'; // Path within the "storage/app" directory
    $data = [
        'last_updated' => now()->toDateTimeString(),
        'data' => $scheduleData,
    ];
    Storage::disk('local')->put($path, json_encode($data, JSON_PRETTY_PRINT));
  }

  private function isCacheValid(): bool {
    $path = 'json/showSchedule.json';
    if (!Storage::disk('local')->exists($path)) {
      return false;
    }

    $content = Storage::disk('local')->get($path);
    $cache = json_decode($content, true);

    $lastUpdated = Carbon::parse($cache['last_updated']);
    $expiresAt = $lastUpdated->addMinutes(15); // Cache validity: 15 minutes

    return now()->lessThan($expiresAt);
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
