<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Http\Resources\ShowEpisodeResource;
use App\Http\Resources\ShowResource;
use App\Models\Show;
use App\Models\ShowSchedule;
use App\Models\ShowScheduleRecurrenceDetails;
use Carbon\CarbonInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ShowScheduleController extends Controller {

  public function __construct() {
    $this->middleware('auth');
  }

  public function addToSchedule(Request $request) {
    // Manually extract the data from the request body
    $data = $request->json()->all();

    // Manually create a validator instance
    $validator = Validator::make($data, [
        'contentId'    => 'required|integer',
        'contentType'  => 'required|string',
        'scheduleType' => 'required|string',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    // Extract input
    // Correctly extract contentId and contentType using the case used in the validator
    $contentId = $data['contentId'];
    $contentType = $data['contentType'];
    $scheduleType = $data['scheduleType'];

    // Determine the correct model class based on the contentType
    $modelClass = $this->getModelClass($contentType);
    if (!$modelClass) {
      return response()->json(['message' => 'Invalid content type provided'], 400);
    }

    // Verify the specified content exists
    $content = $modelClass::find($contentId);
    if (!$content) {
      return response()->json(['message' => 'Content not found'], 404);
    }

    // Convert startDate to UTC
    $formattedStartDate = \Carbon\Carbon::parse($request->input('startDate'))
        ->setTimezone('UTC')
        ->toDateTimeString();

// Convert endDate to UTC
    $formattedEndDate = \Carbon\Carbon::parse($request->input('endDate'))
        ->setTimezone('UTC')
        ->toDateTimeString();

    // TODO: Need to convert the $overlappingSchedules below into UTC time and then compare to the
    // $formattedStartDate and $formattedEndDate
    // this doesn't take into account time changes.. so I think we should just do the prioritization
    // when we add the show to the schedule on the frontend based on the created_at of the ShowSchedule object.
    // Query for overlapping schedules
    $overlappingSchedules = ShowSchedule::where(function ($query) use ($formattedStartDate, $formattedEndDate) {
      $query->whereBetween('start_time', [$formattedStartDate, $formattedEndDate])
          ->orWhereBetween('end_time', [$formattedStartDate, $formattedEndDate]);
    })->get();

    // Determine the new priority
    // Default to 5, allowing room for manual adjustments to insert more urgent schedules if needed
    $newPriority = 5;
    if (!$overlappingSchedules->isEmpty()) {
      // If there is a conflict, adjust the priority to be one less than the lowest existing to make it more urgent
      $minPriority = $overlappingSchedules->min('priority');
      $newPriority = max(1, $minPriority - 1); // Ensure the priority doesn't go below 1
    }

    // Recurring schedule specific data handling
    $recurrenceDetailsId = null;
    if ($scheduleType === 'recurring') {
      $inputDaysOfWeek = $request->input('daysOfWeek');

      // Define the correct order of days for comparison
      $weekDaysOrdered = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

      // Sort the input days based on the week order
      $sortedDaysOfWeek = array_values(array_intersect($weekDaysOrdered, $inputDaysOfWeek));

      $recurrenceDetailsData = [
          'frequency'        => 'weekly', // Adjust based on your requirements
          'days_of_week'     => json_encode($sortedDaysOfWeek), // Now storing sorted days of week
          'duration_minutes' => $request->input('duration'),
          'start_time'       => Carbon::parse($request->input('startDate'))->format('H:i:s'),
          'start_date'       => $request->input('startDate'),
          'end_date'         => $request->input('endDate'),
          'timezone'         => $request->input('timezone'),
      ];

      // Initialize all days to false
      $allDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
      foreach ($allDays as $day) {
        $recurrenceDetailsData[$day] = false;
      }

      // Set true for the days present in the JSON array
      foreach ($inputDaysOfWeek as $day) {
        $dayLower = strtolower($day); // Ensure lowercase to match column names exactly
        if (in_array($dayLower, $allDays)) {
          $recurrenceDetailsData[$dayLower] = true;
        }
      }

      // Create ShowScheduleRecurrenceDetails and capture the ID
      $recurrenceDetails = ShowScheduleRecurrenceDetails::create($recurrenceDetailsData);
      $recurrenceDetailsId = $recurrenceDetails->id;
    }

//    // Determine the correct model class based on the contentType
//    $modelClass = $this->getModelClass($request->input('contentType'));
//    if (!$modelClass) {
//      return response()->json(['message' => 'Invalid content type provided'], 400);
//    }
//
//    // Verify the specified content exists
//    $content = $modelClass::find($request->input('contentId'));
//    if (!$content) {
//      return response()->json(['message' => 'Content not found'], 404);
//    }

    // Prepare common ShowSchedule data
    $showScheduleData = [
        'type'                  => $request->input('contentType'),
        'recurrence_flag'       => $scheduleType === 'recurring' ? 1 : 0,
        'recurrence_details_id' => $recurrenceDetailsId ?? null,
        'status'                => 'scheduled',
        'priority'              => $newPriority,
        'duration_minutes'      => $request->input('duration'),
        'start_time'            => $request->input('startDate'),
        'end_time'              => $request->input('endDate'),
        'timezone'              => $request->input('timezone'),
    ];

    // Create ShowSchedule linked to the determined content
    $showSchedule = $content->schedules()->create($showScheduleData);

    return response()->json([
        'message' => 'Schedule added successfully',
        'data'    => $showSchedule,
    ]);
  }

  protected function getModelClass($contentType): ?string {
    // Map the 'contentType' to actual model class namespaces
    $map = [
        'show'         => \App\Models\Show::class,
        'showEpisode'  => \App\Models\ShowEpisode::class,
        'movie'        => \App\Models\Movie::class,
        'movieTrailer' => \App\Models\MovieTrailer::class,
        'otherContent' => \App\Models\OtherContent::class,
      // Add other content types as needed
    ];

    return $map[$contentType] ?? null;
  }


  /**
   * Display Show Schedule.
   *
   * @return JsonResponse
   */

  public function fetchFiveDaySixHourSchedule(): JsonResponse {
//    $cachePath = 'showScheduleFiveDaySixHour'; // A unique identifier for this cache
//
//    // Check if the cache is valid
//    if ($this->isCacheValid($cachePath)) {
//      // Cache is valid, use the cached data
//      $content = Storage::disk('local')->get("json/{$cachePath}.json");
//      $cache = json_decode($content, true);
//
//      // Include the user's timezone in the response
//      return response()->json([
//          'data'         => $cache['data'],
//          'userTimezone' => auth()->user()->timezone ?? 'UTC', // Default to 'UTC' if null
//      ]);
//    }

    // Cache is not valid, fetch new data
    $schedulesByDay = $this->fetchSchedulesByDay();
//    $this->cacheData($cachePath, $schedulesByDay); // Use the generalized caching method

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
//    $cachePath = 'showScheduleWeek';

    // tec21: commenting out the Cache for testing purposes. (2024-04-09)
    // TODO: This needs to be rebuilt to use our Redis cache instead of a file.
    // same as in loadWeekFromDate() below.
//
//    if ($this->isCacheValid($cachePath)) {
//      $content = Storage::disk('local')->get("json/{$cachePath}.json");
//      $cache = json_decode($content, true);
//
//      // Include the user's timezone in the response
//      return response()->json([
//          'data'         => $cache['data'],
//          'userTimezone' => auth()->user()->timezone ?? 'UTC', // Default to 'UTC' if null
//      ]);
//    }

    // Adjust the startOfWeek and endOfWeek to explicitly set Sunday as the start of the week
    $startOfWeek = Carbon::now()->startOfWeek(CarbonInterface::SUNDAY);
    $endOfWeek = Carbon::now()->endOfWeek(CarbonInterface::SATURDAY);

    $data = $this->fetchSchedules($startOfWeek, $endOfWeek);
//    $this->cacheData($cachePath, $data);

    // Include the user's timezone in the response
    return response()->json([
        'data'         => $data,
        'userTimezone' => auth()->user()->timezone ?? 'UTC', // Default to 'UTC' if null
    ]);
  }

  public function loadWeekFromDate(Request $request): JsonResponse {
//    $date = Carbon::createFromFormat('Y-m-d', $request->formattedDate)->format('Y-m-d');

    // tec21: commenting out the Cache for testing purposes. (2024-04-09)
    // TODO: This needs to be rebuilt to use our Redis cache instead of a file.

//    $cachePath = "showScheduleWeekFromDate_{$date}";

//    if ($this->isCacheValid($cachePath)) {
//      $content = Storage::disk('local')->get("json/{$cachePath}.json");
//      $cache = json_decode($content, true);
//
//      // Include the user's timezone in the response
//      return response()->json([
//          'data'         => $cache['data'],
//          'userTimezone' => auth()->user()->timezone ?? 'UTC', // Default to 'UTC' if null
//      ]);
//    }

    $userTimezone = auth()->user()->timezone;

    // Assume the frontend sends the full ISO date-time string and timezone, e.g., "2024-04-10T06:57:00-07:00"
    $dateTimeFromFrontend = $request->formattedDateTimeUtc; // Full ISO string from the frontend
    $userRequestedDate = Carbon::parse($dateTimeFromFrontend);

    // Now adjust to the start and end of the week
    $userRequestedStartOfWeek = $userRequestedDate->copy()->startOfWeek(CarbonInterface::SUNDAY);
    $userRequestedEndOfWeek = $userRequestedDate->copy()->endOfWeek(CarbonInterface::SATURDAY);

    // Since these are already in the user's timezone, convert them directly to UTC for backend processing
//    $userRequestedStartOfWeekUTC = $userRequestedStartOfWeek->copy()->tz('UTC');
//    $userRequestedEndOfWeekUTC = $userRequestedEndOfWeek->copy()->tz('UTC');

    $data = $this->fetchSchedules($userRequestedStartOfWeek, $userRequestedEndOfWeek);

    // TODO: If we want to work on this later we can... but it will take some work to be efficient
    // tec21: 2024-04-09 ... in terms of time I'll just focus on the /schedule page.
    // this was for the Creator Dashboard.

//    $this->cacheData($cachePath, $data);

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
    $endPeriod = $now->copy()->addDays(6)->addHours(6); // 6 days ahead + 6 hours

    // Fetch all schedules in one go within the specified range
    $allSchedules = ShowSchedule::with(['content', 'showScheduleRecurrenceDetails'])
        ->whereBetween('start_time', [$now, $endPeriod])
        ->orderBy('start_time')
        ->get();

    // Iterate over the next 6 days
    for ($i = 0; $i <= 6; $i++) {
      // Filter schedules for each day based on the start hour of 'now' and for the next 6 hours
      $dayStart = $now->copy()->addDays($i)->startOfDay()->addHours($now->hour);
      $dayEnd = $dayStart->copy()->addHours(6);

      $daySchedules = $allSchedules->filter(function ($schedule) use ($dayStart, $dayEnd) {
        // Convert schedule's start_time to UTC for accurate comparison
        $scheduleStartUtc = Carbon::createFromFormat('Y-m-d H:i:s', $schedule->start_time, $schedule->timezone)
            ->setTimezone('UTC');
        return $scheduleStartUtc->between($dayStart, $dayEnd);
      })->map(function ($schedule) {
        // Handle preloading of additional relationships based on content type
        if ($schedule->content_type === 'App\Models\ShowEpisode') {
          $schedule->content->loadMissing(['appSetting', 'show.category', 'show.subCategory', 'show.image.appSetting', 'creativeCommons', 'mistStreamWildcard', 'image.appSetting', 'video.appSetting', 'video.mistStream', 'video.mistStreamWildcard']);
        } elseif ($schedule->content_type === 'App\Models\Movie') {
          $schedule->content->loadMissing(['appSetting', 'category', 'subCategory', 'creativeCommons', 'trailers', 'image.appSetting', 'video.appSetting', 'video.mistStream', 'video.mistStreamWildcard']);
        }
        // Transform the schedule's content based on its type
        $content = null;
        if ($schedule->content) {
          switch ($schedule->content_type) {
            case 'App\Models\ShowEpisode':
              $content = new ShowEpisodeResource($schedule->content);
              break;
            case 'App\Models\Movie':
              $content = new MovieResource($schedule->content);
              break;
            // Add more cases as needed
          }
        }

        return [
//            'content' => $content?->toArray(),
//          // Include additional schedule attributes as needed
//            'start_time' => $schedule->start_time->toDateTimeString(),
//            'end_time' => $schedule->end_time->toDateTimeString(),
            'content'            => $content,
            'type'               => $schedule->type ?? null, // varchar(255) Discriminator: show, movie, trailer, live stream
            'start_time'         => $schedule->start_time ?? null, // datetime
            'end_time'           => $schedule->end_time ?? null, // datetime
            'status'             => $schedule->status ?? null, // enum('scheduled','live','completed','cancelled')
            'priority'           => $schedule->priority ?? null, // int used for sorting scheduling conflicts and priority scheduling
            'recurrence_flag'    => $schedule->recurence_flag ?? null,
            'recurrence_details' => $schedule->showScheduleRecurrenceDetails ?? null,
        ];
      });

      if ($daySchedules->isNotEmpty()) {
        $schedulesByDay[$dayStart->toDateString()] = $daySchedules->toArray();
      }
    }
    Log::debug('Schedules by day:', $schedulesByDay);
    return $schedulesByDay;








//
//
//    for ($i = 0; $i < 5; $i++) {
//      // Set the start time for each day's segment to match the hour of 'now' but on subsequent days.
//      $segmentStart = $now->copy()->addDays($i)->startOfDay()->addHours($now->hour);
//      $segmentEnd = $segmentStart->copy()->addHours(6); // Each segment spans 6 hours from the start time.
//
//      Log::debug("Querying schedules from {$segmentStart->toDateTimeString()} to {$segmentEnd->toDateTimeString()}");
//
//      // Fetch schedules within the current segment.
//      $schedules = ShowSchedule::with(['content', 'showScheduleRecurrenceDetails'])
//          ->whereBetween('start_time', [$segmentStart, $segmentEnd])
//          ->orderBy('start_time')
//          ->get()
//          ->map(function ($schedule) {
//            // Preload the 'show' relationship for ShowEpisode content
//            if ($schedule->content_type === 'App\Models\ShowEpisode') {
//              $schedule->content->loadMissing(['appSetting', 'show.category', 'show.subCategory', 'show.image.appSetting', 'creativeCommons', 'mistStreamWildcard', 'image.appSetting', 'video.appSetting', 'video.mistStream', 'video.mistStreamWildcard']);
//            } elseif ($schedule->content_type === 'App\Models\Movie') {
//              $schedule->content->loadMissing(['appSetting', 'category', 'subCategory', 'creativeCommons', 'trailers', 'image.appSetting', 'video.appSetting', 'video.mistStream', 'video.mistStreamWildcard']);
//            }
//            // Handle polymorphic relationship
//            $content = null;
//            if ($schedule->content) {
//              switch ($schedule->content_type) {
//                case 'App\Models\ShowEpisode':
//                  $content = new ShowEpisodeResource($schedule->content);
//                  Log::debug('ShowEpisodeResource content:', $content->toArray());
//                  break;
//                case 'App\Models\Movie':
//                  $content = new MovieResource($schedule->content);
//                  Log::debug('MovieResource content:', $content->toArray());
//                  break;
//                // Add cases for other content types as needed
//              }
//              // Debugging: Log what is being returned by the resource
//              Log::debug('Resource content:', $content ? $content->toArray() : 'Content is null');
//
//            }
//
//            // Debugging: Check what is being returned by the resource
//            Log::debug('Resource content:', $content ? $content->toArray() : 'Content is null');
//
//
//            return [
//                'content'            => $content,
//                'type'               => $schedule->type ?? null, // varchar(255) Discriminator: show, movie, trailer, live stream
//                'start_time'         => $schedule->start_time ?? null, // datetime
//                'end_time'           => $schedule->end_time ?? null, // datetime
//                'status'             => $schedule->status ?? null, // enum('scheduled','live','completed','cancelled')
//                'priority'           => $schedule->priority ?? null, // int used for sorting scheduling conflicts and priority scheduling
//                'recurrence_flag'    => $schedule->recurence_flag ?? null,
//                'recurrence_details' => $schedule->showScheduleRecurrenceDetails ?? null,
//            ];
//          })->toArray(); // Ensure you convert the collection to an array if needed
//
//      // Debugging: Log the structured schedules for this segment
//      Log::debug('Schedules for segment:', $schedules);
//
//      // Add the structured schedules for this segment to the collection.
//      $schedulesByDay[] = $schedules;
//    }
//    Log::debug('Schedules by day:', $schedulesByDay);
//    return $schedulesByDay;
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





  /**
   * TODO: Post-Launch Optimization Steps
   *
   * 1. Implement Caching for Today's Schedules:
   *    - As an initial step towards optimization, implement caching specifically for schedules within the current day.
   *    - This targeted caching approach will help improve performance for the most frequently accessed data, reducing the load on real-time conversions.
   *    - Ensure the cache is refreshed daily to maintain accuracy, considering users' timezone preferences and daylight saving adjustments.
   *
   * 2. Monitor Resource Usage and Performance:
   *    - After launching the MVP, closely monitor the application's performance, especially around the fetchSchedules functionality.
   *    - Pay attention to server load, response times, and any user-reported issues with schedule accuracy or availability.
   *
   * 3. Evaluate the Need for Extensive Caching:
   *    - Based on the performance monitoring results, evaluate the necessity and feasibility of extending caching to a broader range of schedules (e.g., next 3 months).
   *    - Consider developing a strategy for cache invalidation and update that balances performance with accuracy, keeping in mind the diversity of user timezones.
   *
   * 4. Continuously Review User Feedback:
   *    - Keep an open channel for user feedback regarding schedule accuracy and app performance.
   *    - Use this feedback to prioritize further optimizations and feature developments, ensuring the application meets user needs effectively.
   *
   * Note: The priority is to maintain a balance between accuracy (especially with timezone conversions) and system performance. Any optimization should enhance user experience without compromising the integrity of schedule data.
   */
//
//
//  private function fetchSchedules(Carbon $userRequestedStartOfWeekUTC, Carbon $userRequestedEndOfWeekUTC) {
//
//    return ShowSchedule::where(function ($query) use ($userRequestedStartOfWeekUTC, $userRequestedEndOfWeekUTC) {
//      $query->whereBetween('start_time', [$userRequestedStartOfWeekUTC, $userRequestedEndOfWeekUTC])
//          ->with('showScheduleRecurrenceDetails')
//          ->orWhere('recurrence_flag', true); // Include recurring schedules
//    })
//        ->orderBy('start_time')
//        ->get()
//        ->flatMap(function ($schedule) use ($userRequestedStartOfWeekUTC, $userRequestedEndOfWeekUTC) {
//          // If the schedule is recurring, generate instances for each recurrence within the week
//          if ($schedule->recurrence_flag && $schedule->showScheduleRecurrenceDetails) {
//            return $this->generateRecurringInstances($schedule, $userRequestedStartOfWeekUTC, $userRequestedEndOfWeekUTC);
//          }
//
//          // Non-recurring schedules or recurring ones outside the time frame are returned directly
//          return [$this->transformSchedule($schedule)];
//        });
//  }

  private function fetchSchedules(Carbon $userRequestedStartOfWeekUTC, Carbon $userRequestedEndOfWeekUTC) {
    // Eagerly load showScheduleRecurrenceDetails for all ShowSchedule instances
    $schedules = ShowSchedule::with('showScheduleRecurrenceDetails')
        ->where(function ($query) use ($userRequestedStartOfWeekUTC, $userRequestedEndOfWeekUTC) {
          $query->whereBetween('start_time', [$userRequestedStartOfWeekUTC, $userRequestedEndOfWeekUTC])
              ->orWhere('recurrence_flag', true); // Include recurring schedules
        })
        ->orderBy('start_time')
        ->get();

    // TODO: Optimize the conditional eager loading for polymorphic `content` relationships.
    // Currently, after fetching the schedules, we iterate over them to conditionally
    // eager load the `content` and its related entities based on the content type.
    // This is necessary due to the polymorphic nature of `content`, but it adds an extra
    // iteration step which may impact performance with a large number of schedules.
    //
    // Key Considerations:
    // - Performance: This approach adds a post-query iteration step to conditionally
    //   eager load relations based on `content_type`. It's more efficient than loading
    //   these relations individually per schedule instance, but still requires careful
    //   monitoring, especially as the dataset grows.
    // - Scalability: Consider the growing number of different content types and their
    //   associated relationships. Abstracting some of this logic or finding patterns to
    //   minimize overhead will be beneficial for maintaining scalability.

    // Eager load additional relationships for `content` after determining its type
    $schedules->each(function ($schedule) {
      if ($schedule->content_type === 'App\Models\Show') {
        $schedule->load([
            'content.category',
            'content.subCategory',
            'content.showRunner.user',
            'content.image.appSetting',
        ]);
      }
      // Additional conditions for other content types can be added here
    });

    // Process schedules
    return $schedules->flatMap(function ($schedule) use ($userRequestedStartOfWeekUTC, $userRequestedEndOfWeekUTC) {
      // If the schedule is recurring, generate instances for each recurrence within the week
      if ($schedule->recurrence_flag && $schedule->showScheduleRecurrenceDetails) {
        return $this->generateRecurringInstances($schedule, $userRequestedStartOfWeekUTC, $userRequestedEndOfWeekUTC);
      }

      // Non-recurring schedules or recurring ones outside the time frame are returned directly
      return [$this->transformSchedule($schedule)];
    });
  }


  private function generateRecurringInstances($schedule, Carbon $userRequestedStartOfWeekUTC, Carbon $userRequestedEndOfWeekUTC): array {
//    Log::debug('Direct DB Recurrence Start Date: ' . $schedule->showScheduleRecurrenceDetails->start_date);
//    Log::debug('Direct DB Recurrence End Date: ' . $schedule->showScheduleRecurrenceDetails->end_date);

    $recurrenceDetails = $schedule->showScheduleRecurrenceDetails;
    $timezone = $recurrenceDetails->timezone; // Schedule's local timezone
    $daysOfWeek = json_decode($recurrenceDetails->days_of_week, true);

    $recurrenceStartDate = $recurrenceDetails->start_date
        ? Carbon::createFromFormat('Y-m-d H:i:s', $recurrenceDetails->start_date, $timezone)
        : null;

    $recurrenceEndDate = $recurrenceDetails->end_date
        ? Carbon::createFromFormat('Y-m-d H:i:s', $recurrenceDetails->end_date, $timezone)
        : null;
//    Log::debug('Recurrence Start Date: ' . $recurrenceStartDate->format('Y-m-d H:i:s'));
//    Log::debug('Recurrence End Date: ' . $recurrenceEndDate->format('Y-m-d H:i:s'));
    // First, find the start and end of the week in UTC
//    $utcStartOfWeek = $userRequestedStartOfWeekUTC->copy()->startOfWeek(CarbonInterface::SUNDAY);
//    $utcEndOfWeek = $userRequestedEndOfWeekUTC->copy()->endOfWeek(CarbonInterface::SATURDAY);

    // Expand the start and end of the week range to capture edge cases
// Subtract a day from the start and add a day to the end
    $utcStartOfWeekAdjusted = $userRequestedStartOfWeekUTC->copy()->subDay()->startOfWeek(CarbonInterface::SUNDAY);
    $utcEndOfWeekAdjusted = $userRequestedEndOfWeekUTC->copy()->addDay()->endOfWeek(CarbonInterface::SATURDAY);


    // Then, simply convert these UTC dates to the local timezone without recalculating the start or end of the week
    $localStartOfWeek = $utcStartOfWeekAdjusted->copy()->tz($timezone);
    $localEndOfWeek = $utcEndOfWeekAdjusted->copy()->tz($timezone);

//    Log::debug("UTC Start of Week: {$utcStartOfWeek}, UTC End of Week: {$utcEndOfWeek}");
//    Log::debug("Local Start of Week after adjustment: {$localStartOfWeek}, Local End of Week after adjustment: {$localEndOfWeek}");

//    Log::debug("Recurrence Details: ", [$recurrenceDetails->toArray()]);

    $instances = [];

    for ($date = $localStartOfWeek; $date->lte($localEndOfWeek); $date->addDay()) {
      // Ensure the date's time is set to the recurrence start time before comparing
      $dateTimeWithStartTime = $date->copy()->setTimeFromTimeString($recurrenceDetails->start_time);

      // Skip the iteration if the dateTimeWithStartTime is outside the recurrence period
      if (($recurrenceStartDate && $dateTimeWithStartTime < $recurrenceStartDate) ||
          ($recurrenceEndDate && $dateTimeWithStartTime > $recurrenceEndDate)) {
        continue;
      }

      if (in_array($date->englishDayOfWeek, $daysOfWeek)) {
        // The check above ensures this day and time is within the recurrence period

        // Convert the instance back to UTC and prepare data for ShowResource
        $dateTimeUTC = $dateTimeWithStartTime->tz('UTC');

        // Construct a unique ID using the schedule ID and the instance date-time
        // This can be adjusted to include any other unique identifiers if necessary
        $uniqueId = hash('sha256', $schedule->id . '_' . $dateTimeUTC);

        // Here, check if the content is a 'Show' and then prepare it with ShowResource
        if ($schedule->content_type === 'App\Models\Show' && $schedule->content) {
          // Instantiate ShowResource with the Show model (the content)
          $showResource = new ShowResource($schedule->content);

          // Now, you can add both the datetime and the show details to $instances
          // Adjust according to how you need to structure $instances
          $instances[] = [
              'id' => $uniqueId, // Use the generated unique ID
              'type' => 'show',
              'start_time' => $dateTimeUTC->toIso8601String(),
              'content'      => [
                  'show' => $showResource->resolve(), // Resolve to get an array
                ],
              'durationMinutes' => $recurrenceDetails->duration_minutes,
          ];
        } else {
          // Handle other content types or absence of content
          $instances[] = [
              'id' => $uniqueId, // Use the generated unique ID
              'start_time' => $dateTimeUTC->toIso8601String(),
              'durationMinutes' => $recurrenceDetails->duration_minutes,
            // Include minimal details or indicate absence of detailed content
              'content' => [], // Placeholder for non-Show content
          ];
        }
      }

    }
      // Log the generated instances for debugging
//      Log::debug('Returning instances array', ['instances' => $instances]);

      // Even after all the logic, ensure an array is returned
//      return $instances ?? []; // Ensures an array is always returned
    // Since ShowResource is JsonResource, you may need to collect() to return an array
    return $instances;

  }

//      // Check if this day is within the recurrence start and end dates, if provided
//      if ($recurrenceStartDate && $date < $recurrenceStartDate || $recurrenceEndDate && $date > $recurrenceEndDate) {
//        continue; // Skip this date as it's outside the recurrence period
//      }
//
//      // Check if this day is one of the recurrence days
//      if (in_array($date->englishDayOfWeek, $daysOfWeek)) {
//        // Schedule's start time in local timezone
//        $instanceDateTime = $date->copy()->setTimeFromTimeString($recurrenceDetails->start_time);
//        // Convert the instance back to UTC
//        $instances[] = $instanceDateTime->tz('UTC');
//      }
//    }
//
//    return $instances;

//  }

    /**
     * TODO: Implement a "Days of the Week" Table for Multilingual Support
     *
     * As part of our efforts to enhance internationalization and support multiple languages,
     * we should consider creating a "Days of the Week" table in our database. This table would map
     * day-of-week indices (0 for Sunday through 6 for Saturday) to their names in various languages.
     *
     * Steps to consider:
     * 1. Create a new database table that includes columns for the day-of-week index, the English name,
     *    and additional columns for each supported language.
     *
     * 2. Update our current logic that handles days of the week, particularly in the
     *    generateRecurringInstances function, to reference this table instead of relying on
     *    hardcoded English day names. This will involve adjusting how we store and check
     *    `daysOfWeek` in `recurrenceDetails` to use day indices, enhancing flexibility and
     *    future-proofing our application against localization needs.
     *
     * 3. Ensure that all user interfaces that display or allow selection of days of the week
     *    are updated to dynamically pull day names from the new table, allowing users to view
     *    and interact with this information in their preferred language.
     *
     * 4. Consider implementing caching strategies for the days of the week data to minimize database
     *    queries, especially on pages or in functionalities where this data is accessed frequently.
     *
     * This change will significantly contribute to our application's scalability and accessibility
     * by accommodating users from different linguistic backgrounds.
     */




//    // Convert start_date and end_date to Carbon instances, considering null values
//    $recurrenceStartDate = $recurrenceDetails->start_date ? new Carbon($recurrenceDetails->start_date) : null;
//    $recurrenceEndDate = $recurrenceDetails->end_date ? new Carbon($recurrenceDetails->end_date) : null;


    // Assuming $recurrenceDetails->start_date and $recurrenceDetails->end_date
// are in the format 'Y-m-d' and the timezone is specified in $recurrenceDetails->timezone

//// Check if start_date is provided and create a Carbon instance with the specific timezone
//    $recurrenceStartDate = $recurrenceDetails->start_date
//        ? Carbon::createFromFormat('Y-m-d H:i:s', $recurrenceDetails->start_date, $recurrenceDetails->timezone)
//        : null;
//
//// Check if end_date is provided and create a Carbon instance in the same way
//    $recurrenceEndDate = $recurrenceDetails->end_date
//        ? Carbon::createFromFormat('Y-m-d H:i:s', $recurrenceDetails->end_date, $recurrenceDetails->timezone)
//        : null;
//
//// Now, if the dates are not null, convert them to UTC
//    if ($recurrenceStartDate) {
//      $recurrenceStartDate->setTimezone('UTC');
//    }
//
//    if ($recurrenceEndDate) {
//      $recurrenceEndDate->setTimezone('UTC');
//    }
//
//    Log::debug('recurrence start: ' . $recurrenceStartDate);
//    Log::debug('recurrence end: ' . $recurrenceEndDate);
//
//
//    $period = new \DatePeriod($startTime, new \DateInterval('P1D'), $endTime);
//    foreach ($period as $date) {
//      if (in_array($date->format('l'), $daysOfWeek)) {
//        // Check if the recurrence date is within the start_date and end_date range
//        if (($recurrenceStartDate && $date < $recurrenceStartDate) || ($recurrenceEndDate && $date > $recurrenceEndDate)) {
//          continue; // Skip this date as it's outside the recurrence period
//        }
//
//        $instance = clone $schedule;
//        // Adjust the date of the cloned instance to match the recurrence date
//        $instance->start_time = $date->setTime($schedule->start_time->hour, $schedule->start_time->minute);
//        $instance->end_time = $date->setTime($schedule->end_time->hour, $schedule->end_time->minute);
//
//        $instances[] = $this->transformSchedule($instance);
//      }
//    }
//
//    return $instances;
//  }


  private function transformSchedule($schedule): array {
    $schedule->load('content');
    // Handle polymorphic relationship and other transformations
    $content = [];
    if ($schedule->content_type) {
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
        case 'App\Models\Show':
          // Load missing relationships for the Show model
          $schedule->content->loadMissing([
              'appSetting', 'category', 'subCategory',
              'image.appSetting', // Assuming this is correct, though it's duplicated in your snippet
              'mistStreamWildcard', // Ensure this relationship exists in your Show model
          ]);

          // Prepare the content array with a ShowResource
          $content = [
              'show' => new ShowResource($schedule->content),
          ];
          break;
        // Add more cases as needed
      }
      // Transform the content based on its type
      // You can adjust this logic based on how your resources are structured
    }

    return [
        'content'            => $content,
        'type'               => $schedule->type ?? null,
        'start_time'         => $schedule->start_time->toDateTimeString() ?? null,
        'end_time'           => $schedule->end_time->toDateTimeString() ?? null,
        'status'             => $schedule->status ?? null,
        'priority'           => $schedule->priority ?? null,
        'recurrence_flag'    => $schedule->recurrence_flag ?? null,
        'recurrence_details' => $schedule->showScheduleRecurrenceDetails ? $schedule->showScheduleRecurrenceDetails->toArray() : null,
        'id'                 => $schedule->id,
        'durationMinutes'    => $schedule->duration_minutes,
    ];
  }

  public function removeFromSchedule(Request $request): JsonResponse {
    // Manually extract the data from the request body
    $data = $request->json()->all();

    // Manually create a validator instance
    $validator = Validator::make($data, [
        'contentId'   => 'required|integer',
        'contentType' => 'required|string',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    // Correctly extract contentId and contentType using the case used in the validator
    $contentId = $data['contentId'];
    $contentType = $data['contentType'];

    // Convert contentType to the expected format for the polymorphic relation
    // Assuming your application uses specific namespacing for models
    $modelClass = $contentType;

    if (!class_exists($modelClass)) {
      Log::error('removeFromSchedule failed, Invalid content type provided: ' . $contentType . ', ID: ' . $contentId);

      return response()->json(['message' => 'Invalid content type provided'], 400);
    }

    // Attempt to find the content by ID
    $content = $modelClass::find($contentId);
    if (!$content) {
      return response()->json(['message' => 'Content not found'], 404);
    }

    // Assuming the slug is a property of all content types
    $slug = $content->slug;

    // Find and delete the ShowSchedule items
    $content->schedules()->each(function ($schedule) {
      // If there are any related recurrence details, delete them
      $schedule->showScheduleRecurrenceDetails()->delete();
      // Then delete the schedule itself
      $schedule->delete();
    });

    // Assuming this route and redirection logic applies universally; adjust as necessary
//    return redirect()->back()->with('message', "{$contentType} Removed From Schedule");
    return response()->json([
        'message' => "{$contentType} Removed From Schedule",
    ]);

  }

//

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
