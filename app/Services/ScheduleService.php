<?php
namespace App\Services;

use App\Http\Resources\ScheduleResource;
use App\Http\Resources\SimpleMovieResource;
use App\Http\Resources\SimpleShowEpisodeResource;
use App\Http\Resources\SimpleShowResource;
use App\Jobs\UpdateAllScheduleBroadcastDates;
use App\Traits\PreloadContentRelationships;
use App\Http\Resources\MovieResource;
use App\Http\Resources\ShowEpisodeResource;
use App\Models\Schedule;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use JsonSerializable;

class ScheduleService {

  use PreloadContentRelationships;

  private int $cacheExpiryMinutes = 45; // Cache expiry time in minutes

  /**
   * Get cache key.
   *
   * @param $type
   * @param null $start
   * @param null $end
   * @return string
   */
  public function getCacheKey($type, $start = null, $end = null): string {
    // Cache paths
    return match ($type) {
      'all_schedules' => 'schedule_cache_all_schedules',
      'scheduleToday' => 'schedule_cache_scheduleToday',
      'scheduleRange' => 'schedule_cache_scheduleRange_' . $start . '_' . $end,
      'scheduleWeek' => 'schedule_cache_scheduleWeek',
      'scheduleFiveDaySixHour' => 'schedule_cache_scheduleFiveDaySixHour',
      default => '',
    };
  }

  /**
   * Check if cache is valid.
   *
   * @param string $cacheKey
   * @return bool
   */
  private function isCacheValid(string $cacheKey): bool {
    return Cache::has($cacheKey);
  }

  /**
   * Fetch and cache schedules.
   *
   * @return Arrayable|JsonSerializable|array
   */
  public function fetchAndCacheSchedules(): Arrayable|JsonSerializable|array {

    Log::info('Running fetchAndCacheSchedules()');

    // 1. Load the schedules
    $schedules = Schedule::with('content', 'scheduleRecurrenceDetails', 'scheduleIndexes')
        ->orderBy('start_dateTime_utc')
        ->get();

    // 2. Preload necessary relationships for each schedule
    $schedules->each(function ($schedule) {
      $this->preloadContentRelationships($schedule);
    });

    // 3. Transform the schedules using ScheduleResource
    $loadedSchedules = ScheduleResource::collection($schedules)->toArray(request());

    // 4. Transform the schedules using the new transformation logic
    $transformedSchedules = $this->transformFetchedSchedules($loadedSchedules);

    // 5. Sort schedules
    $sortedSchedules = $this->sortSchedules($transformedSchedules);
//    Log::debug("Sorted schedules:", $sortedSchedules);

    // 6. Resolve schedule conflicts
    $resolvedSchedules = $this->resolveScheduleConflicts($sortedSchedules);
//    Log::debug('Final schedules:', ['schedules' => $finalSchedules]);

    // Log the first 2 or 3 schedules
//    $this->logSampleSchedules($resolvedSchedules);

    // 7. Cache the schedules
    $cacheKey = $this->getCacheKey('all_schedules');
    Cache::put($cacheKey, $resolvedSchedules, now()->addMinutes($this->cacheExpiryMinutes)); // Cache for 45 minutes
    Log::info('Schedules cached successfully.');

    // 8. Return the schedules if needed elsewhere in this service
    return $resolvedSchedules;
  }

  /**
   * Log the first 2 or 3 schedules.
   *
   * @param array $schedules
   */
  protected function logSampleSchedules(array $schedules): void {
    $sampleSchedules = array_slice($schedules, 0, 3);
//    Log::info('Sample schedules going into the cache:', $sampleSchedules);
  }

  /**
   * Get schedules from the cache.
   * (if not available then fetch and cache.)
   *
   * @return array
   */
  public function getSchedulesFromCache(): array {

    $cacheKey = $this->getCacheKey('all_schedules');

    // Attempt to retrieve schedules from cache
    $schedules = Cache::get($cacheKey);

    // If cache is empty or outdated, fetch and cache schedules
    if (is_null($schedules)) {
      $schedules = $this->fetchAndCacheSchedules();
    }

    return $schedules;
  }

  /**
   * Fetch schedules for the next 6 days and group them by day and hour.
   *
   * @return array
   */
  public function fetchFiveDaySixHourSchedule(): array {
    $cacheKey = $this->getCacheKey('scheduleFiveDaySixHour');

    // Attempt to retrieve grouped schedules from cache
    $fiveDaySixHourSchedule = Cache::get($cacheKey);

    if (is_null($fiveDaySixHourSchedule)) {
      $schedulesByDay = [];
      $now = Carbon::now()->second(0)->microsecond(0)->startOfHour();
      $endPeriod = $now->copy()->addDays(6)->addHours(6);

      // Fetch schedules from cache or database
      $allSchedules = $this->getSchedulesFromCache();

      for ($i = 0; $i <= 6; $i++) {
        $dayStart = $now->copy()->addDays($i)->startOfDay()->addHours($now->hour);
        $dayEnd = $dayStart->copy()->addHours(6);

        $daySchedules = collect($allSchedules)->filter(function ($schedule) use ($dayStart, $dayEnd) {
          $scheduleStartUtc = Carbon::parse($schedule['start_dateTime'])->setTimezone('UTC');
          $scheduleEndUtc = Carbon::parse($schedule['end_dateTime'])->setTimezone('UTC');

          return ($scheduleStartUtc->between($dayStart, $dayEnd) ||
              $scheduleEndUtc->between($dayStart, $dayEnd) ||
              ($scheduleStartUtc->lessThanOrEqualTo($dayStart) && $scheduleEndUtc->greaterThanOrEqualTo($dayEnd)));
        });

        if ($daySchedules->isNotEmpty()) {
          $schedulesByDay[$dayStart->toDateString()] = $daySchedules->toArray();
        }
      }

      // Cache the grouped schedules
      Cache::put($cacheKey, $schedulesByDay, now()->addMinutes($this->cacheExpiryMinutes));
    }

    // Log::debug('Schedules by day:', $schedulesByDay);

    return $schedulesByDay;
  }

//  tec21: 2024-05-26 The transformSchedule() is now performed in the ScheduleResource
  // we process the schedule in the CacheAllSchedules job.

//  /**
//   * Transform the schedule's content based on its type.
//   *
//   * @param $content
//   * @return array
//   */
//  private function transformSchedule($content): array {
//    if (!$content) {
//      return []; // Return empty array if no content is associated
//    }
//
//    // Direct transformation using appropriate resource based on content type
//    return match ($content->getMorphClass()) {
//      'App\Models\ShowEpisode' => (new \App\Http\Resources\SimpleShowEpisodeResource($content))->resolve(),
//      'App\Models\Movie' => (new \App\Http\Resources\SimpleMovieResource($content))->resolve(),
//      'App\Models\Show' => (new \App\Http\Resources\SimpleShowResource($content))->resolve(),
//      default => [],
//    };
//  }


  /**
   * Cache the data.
   *
   * @param string $cacheKey
   * @param array $data
   * @param Carbon $start
   * @param Carbon $end
   */
  private function cacheData(string $cacheKey, array $data, Carbon $start, Carbon $end): void {
    $dataToCache = [
        'timestamp'      => Carbon::now()->toDateTimeString(),
        'start_dateTime' => $start->toDateTimeString(),
        'end_dateTime'   => $end->toDateTimeString(),
        'data'           => $data,
    ];

    Cache::put($cacheKey, $dataToCache, $this->cacheExpiryMinutes * 60); // Cache for expiry time in seconds
  }

  private function isRequestedRangeWithinCache(Carbon $start, Carbon $end, array $cachedData): bool {
    $cachedStart = Carbon::parse($cachedData['start_dateTime']);
    $cachedEnd = Carbon::parse($cachedData['end_dateTime']);

    return $start->greaterThanOrEqualTo($cachedStart) && $end->lessThanOrEqualTo($cachedEnd);
  }

  /**
   * Invalidate caches.
   */
  public function invalidateCaches(): array {
    try {
      $keys = ['schedule_cache_scheduleToday', 'schedule_cache_scheduleWeek', 'schedule_cache_scheduleFiveDaySixHour', 'schedule_cache_all_schedules'];

      // Invalidate keys with patterns
      $patternKeys = Redis::connection()->keys('schedule_cache_scheduleRange_*');
      $keys = array_merge($keys, $patternKeys);

      foreach ($keys as $key) {
        Cache::forget($key);
      }

      return [
          'message' => 'All caches invalidated successfully.',
          'type'    => 'success'
      ];
    } catch (Exception $e) {
      Log::error('Error invalidating caches', [
          'error' => $e->getMessage(),
          'trace' => $e->getTraceAsString()
      ]);

      return [
          'message' => 'Error invalidating caches.',
          'type'    => 'error'
      ];
    }
  }


  public function updateSchedule(): array {
    try {
      dispatch(new UpdateAllScheduleBroadcastDates());

      return [
          'message' => 'Update schedule job dispatched successfully.',
          'type'    => 'success'
      ];
    } catch (Exception $e) {
      Log::error('Error dispatching update schedule job', [
          'error' => $e->getMessage(),
          'trace' => $e->getTraceAsString()
      ]);

      return [
          'message' => 'Error dispatching update schedule job.',
          'type'    => 'error'
      ];
    }
  }


  public function purgeOldCacheFiles(int $hours = 1): void {
    $patternKeys = Redis::connection()->keys('scheduleRange_*');
    foreach ($patternKeys as $key) {
      $content = Cache::get($key);
      if ($content) {
        $cacheTime = Carbon::parse($content['timestamp']);
        $diffInHours = Carbon::now()->diffInHours($cacheTime);

        // Check if the difference is greater than the given hours and is positive
        if ($diffInHours > $hours) {
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
   * @param string $startDateTimeUtc
   * @param string $endDateTimeUtc
   * @param bool $useSubDay
   * @return array
   */
  public function fetchContentForRange(string $startDateTimeUtc, string $endDateTimeUtc, bool $useSubDay = true): array {
    $validationError = $this->validateDate($startDateTimeUtc) ?: $this->validateDate($endDateTimeUtc);
    if ($validationError) {
      Log::error('Invalid date provided', ['details' => $validationError]);

      return ['error' => 'Invalid date provided', 'details' => $validationError];
    }

    if ($useSubDay) {
      try {
        $start = Carbon::parse($startDateTimeUtc)->startOfDay();
        $end = Carbon::parse($endDateTimeUtc)->endOfDay();
      } catch (\Exception $e) {
        Log::error('Exception parsing dates', ['exception' => $e->getMessage()]);

        return ['error' => 'Invalid date provided', 'details' => $e->getMessage()];
      }
      // Conditionally expand the fetch range by one day on each side
      $expandedStart = $start->copy()->subDay();
      $expandedEnd = $end->copy()->addDay();
    } else {
      $expandedStart = $startDateTimeUtc;
      $expandedEnd = $endDateTimeUtc;
    }

    $cacheKey = $this->getCacheKey('all_schedules');
    $cachedSchedules = Cache::get($cacheKey);

    if ($cachedSchedules) {
//      Log::debug('Using cached schedules');
//      Log::debug('Cached Schedules Data:', ['schedules' => $cachedSchedules]); // Log schedules data

      return $this->filterSchedulesByDateRange($cachedSchedules, $expandedStart, $expandedEnd);
    } else {
//      Log::debug('Fetching and caching schedules');
      $fetchedSchedules = $this->fetchAndCacheSchedules();
//      Log::debug('Fetched Schedules Data:', ['schedules' => $fetchedSchedules]); // Log schedules data

      return $this->filterSchedulesByDateRange($fetchedSchedules, $expandedStart, $expandedEnd);
    }
  }

//      // Fetch all schedules with necessary relationships
//      $schedules = Schedule::with(['content.image.appSetting'])
//          ->orderBy('start_dateTime')
//          ->get();

  // If cache is empty or invalid, fetch from the database
  // Transform the schedules using ScheduleResource
//      $transformedSchedules = ScheduleResource::collection($schedules)->toArray(request());
//      $schedules = Schedule::with(['content', 'scheduleRecurrenceDetails'])
//          ->whereBetween('start_dateTime', [$expandedStart, $expandedEnd])
//          ->orderBy('start_dateTime')
//          ->get();

//      // Preload additional relationships
//      foreach ($schedules as $schedule) {
//        $this->preloadContentRelationships($schedule);
//      }

  // Cache the schedules for future use
//      Log::error('Problem getting the schedule from the cache in the ScheduleService->fetchContentForRange().');
//      Cache::put($cacheKey, $schedules, now()->addMinutes(30));
//    }

// Convert cached data to the expected collection type if necessary
//    if (is_array($schedules)) {
//      $schedules = new EloquentCollection($schedules);
//    } elseif ($schedules instanceof Collection && !$schedules instanceof EloquentCollection) {
//      $schedules = new EloquentCollection($schedules->all());
//    }

//    Log::debug('Fetched schedules:', $schedules);

  // 2. Transform schedules
//    $transformedSchedules = $this->transformFetchedSchedules($schedules);
////    Log::debug("Transformed schedules:", $transformedSchedules);
//
//    // 3. Sort schedules
//    $sortedSchedules = $this->sortSchedules($transformedSchedules);
////    Log::debug("Sorted schedules:", $sortedSchedules);
//
//    // 4. Resolve schedule conflicts
//    return $this->resolveScheduleConflicts($sortedSchedules);
////    Log::debug('Final schedules:', ['schedules' => $finalSchedules]);

//  }

  protected function filterSchedulesByDateRange(array $schedules, $start, $end): array {
    // Ensure $schedules is a collection
    $schedulesCollection = collect($schedules);

    // Filter schedules by the requested date range
    return $schedulesCollection->filter(function ($schedule) use ($start, $end) {
      // Parse the schedule start and end times in UTC
      $scheduleStart = Carbon::parse($schedule['start_dateTime']);
      $scheduleEnd = Carbon::parse($schedule['end_dateTime']);

      // Ensure the schedule ends after the start time and starts before the end time
      return $scheduleEnd->gte($start) && $scheduleStart->lte($end);
    })->values()->toArray();
  }

  /**
   * Transform fetched schedules.
   *
   * @param array $schedules
   * @return array
   */
  public function transformFetchedSchedules(array $schedules): array {

    return array_reduce($schedules, function ($carry, $schedule) {
      // Check if 'broadcast_dates' is already an array
      $broadcastDates = is_array($schedule['broadcast_dates'])
          ? $schedule['broadcast_dates']
          : json_decode($schedule['broadcast_dates'], true)['broadcastDates'] ?? [];

      $timezone = $schedule['timezone'] ?? 'UTC';

      foreach ($broadcastDates as $date) {
        try {
          // Initialize start time and end time using the provided date
          $startDateTime = new DateTime($date, new DateTimeZone('UTC'));
          $endDateTime = (clone $startDateTime)->modify("+{$schedule['duration_minutes']} minutes");

//          $startDateTime = new DateTime($date, new DateTimeZone('UTC'));
//          $endDateTime = (clone $startDateTime)->modify("+{$schedule['duration_minutes']} minutes");
//
//
//          $startDateTimeInTimezone = (clone $startDateTime)->setTimezone(new DateTimeZone($timezone));
//          $endDateTimeInTimezone = (clone $endDateTime)->setTimezone(new DateTimeZone($timezone));

          // Add the transformed schedule to the accumulator
          $carry[] = [
              'id'                 => $schedule['id'],
              'created_at'         => $schedule['created_at'],
              'type'               => $schedule['type'],
              'start_dateTime'     => $startDateTime->format('c'),
              'end_dateTime'       => $endDateTime->format('c'),
//              'start_dateTime'     => $startDateTimeInTimezone->format('c'),
//              'end_dateTime'       => $endDateTimeInTimezone->format('c'),
//              'start_dateTime_utc' => $endDateTime->format('c'),
//              'end_dateTime_utc'   => $endDateTime->format('c'),
              'priority'           => $schedule['priority'],
              'duration_minutes'   => $schedule['duration_minutes'],
              'timezone'           => $timezone,
              'content'            => $schedule['content'],
          ];
        } catch (\Exception $e) {
          Log::error('Error transforming broadcast date', [
              'schedule_id' => $schedule['id'],
              'date'        => $date,
              'error'       => $e->getMessage()
          ]);
        }
      }

      return $carry; // Return the accumulator for the next iteration
    }, []); // Initial value of the accumulator is an empty array

  }

//
//
//    // Convert cached data to the expected collection type if necessary
//    if (is_array($schedules)) {
//      $schedules = new EloquentCollection(array_map(function ($item) {
//        return new Schedule($item);
//      }, $schedules));
//    } elseif ($schedules instanceof Collection && !$schedules instanceof EloquentCollection) {
//      $schedules = new EloquentCollection($schedules->map(function ($item) {
//        return new Schedule($item);
//      })->all());
//    }
//
//    return $schedules->flatMap(function ($schedule) {
//      $broadcastDates = json_decode($schedule->broadcast_dates, true)['broadcastDates'] ?? [];
//      $timezone = $schedule->timezone ?? 'UTC';
//
////      Log::debug('Transforming schedule', [
////          'schedule_id'     => $schedule->id,
////          'broadcast_dates' => $broadcastDates,
////          'timezone'        => $timezone
////      ]);
//
//      return collect($broadcastDates)->map(function ($date) use ($timezone, $schedule) {
//        try {
////          $startTime = new DateTime($date, new DateTimeZone($timezone));
////          $endTime = (clone $startTime)->add(new DateInterval('PT' . $schedule->duration_minutes . 'M'));
//
////          Log::debug('Mapped broadcast date before timecode conversion', [
////              'schedule_id' => $schedule->id,
////              'date'        => $date,
////              'duration'    => $schedule->duration_minutes
////          ]);
//
//          // Initialize start time and end time using the provided date
//          $startDateTime = new DateTime($date);
//          $endDateTime = (clone $startDateTime)->modify("+{$schedule->duration_minutes} minutes");
//
//          $startDateTime->setTimezone(new DateTimeZone('UTC'));
//          $endDateTime->setTimezone(new DateTimeZone('UTC'));
//
////          Log::debug('Mapped broadcast date after timecode conversion', [
////              'schedule_id'    => $schedule->id,
////              'start_dateTime' => $startDateTime->format('c'),
////              'end_dateTime'   => $endDateTime->format('c'),
////              'duration'       => $schedule->duration_minutes
////          ]);
//
//          return [
//              'id'               => $schedule->content_id,
//              'created_at'       => $schedule->created_at,
//              'type'             => $schedule->type,
//              'start_DateTime'   => $startDateTime->format('c'),
//              'end_DateTime'     => $endDateTime->format('c'),
//              'priority'         => $schedule->priority,
//              'duration_minutes' => $schedule->duration_minutes,
//              'timezone'         => $timezone,
////              'content'          => $this->transformSchedule($schedule->content),
//              'content'          => $schedule->content,
//          ];
//        } catch (\Exception $e) {
//          Log::error('Error transforming broadcast date', [
//              'schedule_id' => $schedule->id,
//              'date'        => $date,
//              'error'       => $e->getMessage()
//          ]);
//
//          return null;
//        }
//      })->filter()->all();
//    })->toArray();
//  }

  /**
   * Sort schedules.
   *
   * @param array $schedules
   * @return array
   */
  public function sortSchedules(array $schedules): array {

    // Remove duplicates based on unique composite key
    $uniqueSchedules = [];
    foreach ($schedules as $schedule) {
      $key = $schedule['id'] . '_' . $schedule['start_dateTime'] . '_' . $schedule['end_dateTime'] . '_' . $schedule['priority'];
      if (!isset($uniqueSchedules[$key])) {
        $uniqueSchedules[$key] = $schedule;
      }
    }

    // Convert associative array back to a sequential array
    $schedules = array_values($uniqueSchedules);

    usort($schedules, function ($a, $b) {
      $startComparison = $a['start_dateTime'] <=> $b['start_dateTime'];
      if ($startComparison !== 0) return $startComparison;

      $endComparison = $a['end_dateTime'] <=> $b['end_dateTime'];
      if ($endComparison !== 0) return $endComparison;

      $priorityComparison = $a['priority'] <=> $b['priority'];
      if ($priorityComparison !== 0) return $priorityComparison;

      return $a['created_at'] <=> $b['created_at']; // Older shows have higher priority
    });

    return $schedules;
  }

  /**
   * Resolve schedule conflicts.
   *
   * @param array $schedules
   * @return array
   */
  public function resolveScheduleConflicts(array $schedules): array {
    // Initialize an array to track occupied rows and times for each row
    $rowOccupancy = [];

    // Sort schedules before handling overlaps to ensure they are processed in the correct order
    usort($schedules, function ($a, $b) {
      return $a['start_dateTime'] <=> $b['start_dateTime'] ?: $a['end_dateTime'] <=> $b['end_dateTime'] ?: $a['priority'] <=> $b['priority'] ?: $a['created_at'] <=> $b['created_at'];
    });

    return array_map(function ($item) use (&$rowOccupancy) {
      $item['gridRow'] = 1; // Start placing each item in the second row (even number)

      // Check each row to see if placing the item there would cause a conflict
      while (true) {
        $conflict = false;

        if (isset($rowOccupancy[$item['gridRow']])) {
          foreach ($rowOccupancy[$item['gridRow']] as $time) {
            if ($item['start_dateTime'] < $time['end_dateTime'] && $item['end_dateTime'] > $time['start_dateTime']) {
              $conflict = true;
              break;
            }
          }
        }

        if (!$conflict) {
          break; // No conflict found, place the item in the current row
        }

        // Move to the next row if there is a conflict
        $item['gridRow'] += 1;
      }

      // Adjust the priority if the item was moved to a new row
      if ($item['gridRow'] > 2) {
        $item['priority']++;
      }

      // Record the item's time in the appropriate row's occupancy data
      $rowOccupancy[$item['gridRow']][] = [
          'start_dateTime' => $item['start_dateTime'],
          'end_dateTime'   => $item['end_dateTime']
      ];

      return $item;
    }, $schedules);
  }


  /**
   * Validate date format.
   *
   * @param string $formattedDateTimeUtc
   * @return JsonResponse|null
   */
  public function validateDate(string $formattedDateTimeUtc): ?JsonResponse {
    $validator = Validator::make(['formattedDateTimeUtc' => $formattedDateTimeUtc], [
        'formattedDateTimeUtc' => 'required|date_format:Y-m-d\TH:i:s.v\Z',
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    return null; // return null if no errors
  }
}