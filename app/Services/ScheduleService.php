<?php
namespace App\Services;

use App\Http\Resources\MovieResource;
use App\Http\Resources\ShowEpisodeResource;
use App\Models\Schedule;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ScheduleService {

  private int $cacheExpiryMinutes = 60; // Cache expiry time in minutes

  /**
   * Fetch schedules for the next 6 days and group them by day and hour.
   *
   * @return array
   */
  public function fetchFiveDaySixHourSchedule(): array {
    $schedulesByDay = [];
    $now = Carbon::now()->second(0)->microsecond(0)->startOfHour();
    $endPeriod = $now->copy()->addDays(6)->addHours(6);

    $allSchedules = Schedule::with(['content', 'scheduleRecurrenceDetails'])
        ->whereBetween('start_time', [$now, $endPeriod])
        ->orderBy('start_time')
        ->get();

    for ($i = 0; $i <= 6; $i++) {
      $dayStart = $now->copy()->addDays($i)->startOfDay()->addHours($now->hour);
      $dayEnd = $dayStart->copy()->addHours(6);

      $daySchedules = $allSchedules->filter(function ($schedule) use ($dayStart, $dayEnd) {
        $scheduleStartUtc = Carbon::createFromFormat('Y-m-d H:i:s', $schedule->start_time, $schedule->timezone)
            ->setTimezone('UTC');

        return $scheduleStartUtc->between($dayStart, $dayEnd);
      })->map(function ($schedule) {
        $this->preloadContentRelationships($schedule);

        return $this->transformSchedule($schedule);
      });

      if ($daySchedules->isNotEmpty()) {
        $schedulesByDay[$dayStart->toDateString()] = $daySchedules->toArray();
      }
    }

//    Log::debug('Schedules by day:', $schedulesByDay);

    return $schedulesByDay;
  }

  /**
   * Preload additional relationships based on content type.
   *
   * @param Schedule $schedule
   */
  private function preloadContentRelationships(Schedule $schedule): void {
    if ($schedule->content_type === 'App\Models\Show') {
      $schedule->content->loadMissing([
          'image.appSetting', 'team'
      ]);
    } elseif ($schedule->content_type === 'App\Models\ShowEpisode') {
      $schedule->content->loadMissing([
          'show.image.appSetting', 'image.appSetting',
      ]);
    } elseif ($schedule->content_type === 'App\Models\Movie') {
      $schedule->content->loadMissing([
          'trailers', 'image.appSetting'
      ]);
    }
  }

  /**
   * Transform the schedule's content based on its type.
   *
   * @param $content
   * @return array
   */
  private function transformSchedule($content): array {
    if (!$content) {
      return []; // Return empty array if no content is associated
    }

    // Direct transformation using appropriate resource based on content type
    return match ($content->getMorphClass()) {
      'App\Models\ShowEpisode' => (new \App\Http\Resources\SimpleShowEpisodeResource($content))->resolve(),
      'App\Models\Movie' => (new \App\Http\Resources\SimpleMovieResource($content))->resolve(),
      'App\Models\Show' => (new \App\Http\Resources\SimpleShowResource($content))->resolve(),
      default => [],
    };
  }

  // Cache paths
  private function getCacheKey($type, $start = null, $end = null): string
  {
    return match ($type) {
      'scheduleToday' => 'scheduleToday',
      'scheduleRange' => 'scheduleRange_' . $start . '_' . $end,
      'scheduleWeek' => 'scheduleWeek',
      'scheduleFiveDaySixHour' => 'scheduleFiveDaySixHour',
      default => '',
    };
  }


  /**
   * Check if cache is valid.
   *
   * @param string $cacheKey
   * @return bool
   */
  private function isCacheValid(string $cacheKey): bool
  {
    return Cache::has($cacheKey);
  }

  /**
   * Cache the data.
   *
   * @param string $cacheKey
   * @param array $data
   * @param Carbon $start
   * @param Carbon $end
   */
  private function cacheData(string $cacheKey, array $data, Carbon $start, Carbon $end): void
  {
    $dataToCache = [
        'timestamp' => Carbon::now()->toDateTimeString(),
        'start_time' => $start->toDateTimeString(),
        'end_time' => $end->toDateTimeString(),
        'data' => $data,
    ];

    Cache::put($cacheKey, $dataToCache, $this->cacheExpiryMinutes * 60); // Cache for expiry time in seconds
  }

  private function isRequestedRangeWithinCache(Carbon $start, Carbon $end, array $cachedData): bool {
    $cachedStart = Carbon::parse($cachedData['start_time']);
    $cachedEnd = Carbon::parse($cachedData['end_time']);

    return $start->greaterThanOrEqualTo($cachedStart) && $end->lessThanOrEqualTo($cachedEnd);
  }

  /**
   * Invalidate caches.
   */
  public function invalidateCaches(): void
  {
    $keys = ['scheduleToday', 'scheduleWeek', 'scheduleFiveDaySixHour'];

    // Invalidate keys with patterns
    $patternKeys = Redis::connection()->keys('scheduleRange_*');
    $keys = array_merge($keys, $patternKeys);

    foreach ($keys as $key) {
      Cache::forget($key);
    }
  }


  public function purgeOldCacheFiles(int $hours = 1): void
  {
    $patternKeys = Redis::connection()->keys('scheduleRange_*');
    foreach ($patternKeys as $key) {
      $content = Cache::get($key);
      if ($content) {
        $cacheTime = Carbon::parse($content['timestamp']);
        if (Carbon::now()->diffInHours($cacheTime) > $hours) {
          Cache::forget($key);
        }
      }
    }
  }

  /**
   * Fetch today's schedules, either from cache or from the database.
   *
   * @return Collection|array
   */
  public function fetchTodaysContent(): Collection|array {
    $cachePath = 'scheduleToday';

    if ($this->isCacheValid($cachePath)) {
      $content = Storage::disk('local')->get("json/{$cachePath}.json");
      $cache = json_decode($content, true);
      return $cache['data'];
    }

    $data = $this->fetchContentForRange(today(), today()->endOfDay());
//    $this->cacheData($cachePath, (array) $data);

    return $data;
  }


  /**
   * Fetch schedules for a specified date range with detailed processing.
   *
   * @param string $startDate
   * @param string $endDate
   * @return array
   */
  public function fetchContentForRange(string $startDate, string $endDate): array {
    $validationError = $this->validateDate($startDate) ?: $this->validateDate($endDate);
    if ($validationError) {
      return ['error' => 'Invalid date provided', 'details' => $validationError];
    }

    // IMPORTANT: Make sure the user requested time is in UTC at this point.
    // It should get converted to UTC here. Unless we convert it before the request comes in???
    // Before we fetch the schedule... Convert UTC time to schedule's local timezone !!! IMPORTANT > Schedules are stored in the user's preferred timezone.
    // NOTE: BroadcastDates are converted to UTC when they are created and marked with the UTC timezone in the array.
    // NOTE: Start dates and times in the schedule tables other than BroadcastDates are stored in UTC as part of our standardization.
    // NOTE: The reason Schedules are saved in a specific timezone is to prevent daylight savings changes causing issues.

//    Log::debug('Fetching schedules for date range', [
//        'start' => $startDate,
//        'end'   => $endDate
//    ]);

    try {
      $start = Carbon::parse($startDate)->startOfDay();
      $end = Carbon::parse($endDate)->endOfDay();
    } catch (\Exception $e) {
      return ['error' => 'Invalid date provided', 'details' => $e->getMessage()];
    }

    // Cache path based on the requested date range
//    $cacheKey = $this->getCacheKey('scheduleRange', $startDate, $endDate);

    $cacheKey = $this->getCacheKey('scheduleRange', $startDate, $endDate);
    if ($this->isCacheValid($cacheKey)) {
      $content = Cache::get($cacheKey);
      if ($content && $this->isRequestedRangeWithinCache($start, $end, $content)) {
        return $content['data'];
      }
    }

    // Expand the fetch range by one day on each side
    $expandedStart = $start->copy()->subDay();
    $expandedEnd = $end->copy()->addDay();

    // 1. Fetch schedules for the expanded range
    $schedules = Schedule::with(['content', 'scheduleRecurrenceDetails'])
        ->whereBetween('start_time', [$expandedStart, $expandedEnd])
        ->orderBy('start_time')
        ->get();

//    Log::info('Fetched schedules:', $schedules->toArray());

    // Preload additional relationships
    foreach ($schedules as $schedule) {
      $this->preloadContentRelationships($schedule);
    }

    // 2. Transform schedules
    $transformedSchedules = $this->transformFetchedSchedules($schedules);
//    Log::debug("Transformed schedules:", $transformedSchedules);

    // 3. Sort schedules
    $sortedSchedules = $this->sortSchedules($transformedSchedules);
//    Log::debug("Sorted schedules:", $sortedSchedules);

    // 4. Resolve schedule conflicts
    $finalSchedules = $this->resolveScheduleConflicts($sortedSchedules);
//    Log::debug('Final schedules:', ['schedules' => $finalSchedules]);

    // Cache the final schedules with expanded start and end times
    $this->cacheData($cacheKey, $finalSchedules, $expandedStart, $expandedEnd);

    return $finalSchedules;

  }

  /**
   * Transform fetched schedules.
   *
   * @param Collection $schedules
   * @return array
   */
  private function transformFetchedSchedules(Collection $schedules): array {
    return $schedules->flatMap(function ($schedule) {
      $this->preloadContentRelationships($schedule);

      $broadcastDates = json_decode($schedule->broadcast_dates, true)['broadcastDates'] ?? [];
      $timezone = $schedule->timezone ?? 'UTC';

//      Log::debug('Transforming schedule', [
//          'schedule_id'     => $schedule->id,
//          'broadcast_dates' => $broadcastDates,
//          'timezone'        => $timezone
//      ]);

      return collect($broadcastDates)->map(function ($date) use ($timezone, $schedule) {
        try {
//          $startTime = new DateTime($date, new DateTimeZone($timezone));
//          $endTime = (clone $startTime)->add(new DateInterval('PT' . $schedule->duration_minutes . 'M'));

//          Log::debug('Mapped broadcast date before timecode conversion', [
//              'schedule_id' => $schedule->id,
//              'date'        => $date,
//              'duration'    => $schedule->duration_minutes
//          ]);

          // Initialize start time and end time using the provided date
          $startTime = new DateTime($date);
          $endTime = (clone $startTime)->modify("+{$schedule->duration_minutes} minutes");

          $startTime->setTimezone(new DateTimeZone('UTC'));
          $endTime->setTimezone(new DateTimeZone('UTC'));

//          Log::debug('Mapped broadcast date after timecode conversion', [
//              'schedule_id' => $schedule->id,
//              'start_time'  => $startTime->format('c'),
//              'end_time'    => $endTime->format('c'),
//              'duration'    => $schedule->duration_minutes
//          ]);

          return [
              'id'              => $schedule->content_id,
              'createdAt'       => $schedule->created_at,
              'type'            => $schedule->type,
              'startTime'      => $startTime->format('c'),
              'endTime'        => $endTime->format('c'),
              'priority'        => $schedule->priority,
              'durationMinutes' => $schedule->duration_minutes,
              'timezone'        => $timezone,
              'content'         => $this->transformSchedule($schedule->content),
          ];
        } catch (\Exception $e) {
          Log::error('Error transforming broadcast date', [
              'schedule_id' => $schedule->id,
              'date'        => $date,
              'error'       => $e->getMessage()
          ]);

          return null;
        }
      })->filter()->all();
    })->toArray();
  }

  /**
   * Sort schedules.
   *
   * @param array $schedules
   * @return array
   */
  private function sortSchedules(array $schedules): array
  {
    usort($schedules, function ($a, $b) {
      $startComparison = $a['startTime'] <=> $b['startTime'];
      if ($startComparison !== 0) return $startComparison;

      $endComparison = $a['endTime'] <=> $b['endTime'];
      if ($endComparison !== 0) return $endComparison;

      $priorityComparison = $a['priority'] <=> $b['priority'];
      if ($priorityComparison !== 0) return $priorityComparison;

      return $a['createdAt'] <=> $b['createdAt']; // Older shows have higher priority
    });

    return $schedules;
  }

  /**
   * Resolve schedule conflicts.
   *
   * @param array $schedules
   * @return array
   */
  private function resolveScheduleConflicts(array $schedules): array
  {
    // Initialize an array to track occupied rows and times for each row
    $rowOccupancy = [];

    // Sort schedules before handling overlaps to ensure they are processed in the correct order
    usort($schedules, function ($a, $b) {
      return $a['startTime'] <=> $b['startTime'] ?: $a['endTime'] <=> $b['endTime'] ?: $a['priority'] <=> $b['priority'] ?: $a['createdAt'] <=> $b['createdAt'];
    });

    return array_map(function ($item) use (&$rowOccupancy) {
      $item['gridRow'] = 1; // Start placing each item in the first row

      // Check each row to see if placing the item there would cause a conflict
      foreach ($rowOccupancy as $row => $times) {
        $conflict = false;
        foreach ($times as $time) {
          if ($item['startTime'] < $time['endTime'] && $item['endTime'] > $time['startTime']) {
            $conflict = true;
            break;
          }
        }
        if (!$conflict) {
          $item['gridRow'] = $row;
          break;
        }
        $item['gridRow']++; // Move to the next row if there is a conflict
      }

      // Record the item's time in the appropriate row's occupancy data
      $rowOccupancy[$item['gridRow']][] = ['startTime' => $item['startTime'], 'endTime' => $item['endTime']];

      return $item;
    }, $schedules);
  }

  /**
   * Validate date format.
   *
   * @param string $formattedDateTimeUtc
   * @return JsonResponse|null
   */
  public function validateDate(string $formattedDateTimeUtc): ?JsonResponse
  {
    $validator = Validator::make(['formattedDateTimeUtc' => $formattedDateTimeUtc], [
        'formattedDateTimeUtc' => 'required|date_format:Y-m-d\TH:i:s.v\Z',
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    return null; // return null if no errors
  }
}