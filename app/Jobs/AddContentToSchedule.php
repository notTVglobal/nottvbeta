<?php

namespace App\Jobs;

use App\Models\Schedule;
use App\Models\ScheduleRecurrenceDetails;
use App\Models\SchedulesIndex;
use App\Models\Show;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

class AddContentToSchedule implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected mixed $data;

  /**
   * Create a new job instance.
   *
   * @return void
   */

  public function __construct($data) {
    $this->data = $data;
  }

  /**
   * Maps content type to the corresponding model class.
   *
   * @param string $contentType The type of content.
   * @return string|null The fully qualified class name of the model or null if not found.
   */
  protected function getModelClass(string $contentType): ?string {
    $map = [
        'show'         => \App\Models\Show::class,
        'episode'      => \App\Models\ShowEpisode::class,
        'movie'        => \App\Models\Movie::class,
        'movieTrailer' => \App\Models\MovieTrailer::class,
        'otherContent' => \App\Models\OtherContent::class,
      // Add more mappings as needed
    ];

    return $map[$contentType] ?? null;
  }


  /**
   * Execute the job.
   *
   * @return void
   * @throws \Exception
   */
  public function handle(): void {
    $data = $this->data;

    // Manually create a validator instance
    $validator = ValidatorFacade::make($data, [
        'contentId'    => 'required|integer',
        'contentType'  => 'required|string',
        'scheduleType' => 'required|string',
        'startDate'    => 'required|date',
        'endDate'      => 'required|date',
        'daysOfWeek'   => 'required|array',
        'timezone'     => 'required|string',
        'duration'     => 'required|integer',
    ]);

    if ($validator->fails()) {
      // Here, instead of returning a response, you might log the errors or handle them otherwise:
      Log::error('Validation failed in UpdateSchedule job', $validator->errors()->toArray());

      return; // Exit the job if validation fails
    }

    // Extract input
    // Correctly extract contentId and contentType using the case used in the validator
    $contentId = $data['contentId'];
    $contentType = $data['contentType'];
    $scheduleType = $data['scheduleType'];

    // Determine the correct model class based on the contentType
    $modelClass = $this->getModelClass($contentType);
    if (!$modelClass) {
      Log::error('Invalid content type provided', ['contentType' => $contentType]);

      return; // Exit the job
    }

    // Verify the specified content exists
    $content = $modelClass::find($contentId);
    if (!$content) {
      Log::error('Content not found', ['contentId' => $contentId, 'modelClass' => $modelClass]);

      return; // Exit the job if content not found
    }

    // Check if 'priority' is provided, otherwise use a default value
    $priority = $data['priority'] ?? 5; // Use provided priority or default to 5
    $timezone = $data['timezone'] ?? 'UTC'; // Default to UTC if not provided
    $startDate = $data['startDate'];
    $endDate = $data['endDate'];

    // Need to keep dateTimes in the provided timezone
    $formattedStartDate = Carbon::parse($startDate, $timezone)->toDateTimeString();
    $formattedEndDate = Carbon::parse($endDate, $timezone)->toDateTimeString();

    // Log the attempt to find overlapping schedules
//    Log::debug('Checking for overlapping schedules', [
//        'start_date' => $formattedStartDate,
//        'end_date'   => $formattedEndDate,
//    ]);

    // Query for overlapping schedules
    $overlappingSchedules = Schedule::where(function ($query) use ($formattedStartDate, $formattedEndDate) {
      $query->whereBetween('start_time', [$formattedStartDate, $formattedEndDate])
          ->orWhereBetween('end_time', [$formattedStartDate, $formattedEndDate]);
    })->get();

    // Log the results of the overlap check
    if ($overlappingSchedules->isEmpty()) {
//      Log::debug('No overlapping schedules found');
    } else {
//      Log::debug('Overlapping schedules found', [
//          'count'     => $overlappingSchedules->count(),
//          'schedules' => $overlappingSchedules->pluck('id')->toArray()
//      ]);
    }

    // Adjust priority based on existing overlaps
    if ($overlappingSchedules->isNotEmpty()) {
      $minPriority = $overlappingSchedules->min('priority');
      $priority = max(1, $minPriority - 1); // Ensure the priority doesn't drop below 1
//      Log::debug('Adjusting schedule priority due to overlap', [
//          'new_priority'              => $priority,
//          'minimum_existing_priority' => $minPriority
//      ]);
    }

    // Initialize the schedule data with common fields that apply to all schedules
    $scheduleData = [
        'type'             => $data['contentType'],
        'recurrence_flag'  => $data['scheduleType'] === 'recurring' ? 1 : 0,
        'status'           => 'scheduled',
        'priority'         => $priority,
        'duration_minutes' => $data['duration'],
        'start_time'       => $formattedStartDate,
        'end_time'         => $formattedEndDate,
        'timezone'         => $timezone,
        'extra_metadata'   => json_encode([])  // Initialize as empty JSON
    ];

    DB::beginTransaction();
    try {

      // Check if the schedule type is recurring
      if ($data['scheduleType'] === 'recurring') {
        // Normalize input days to capitalize the first letter for JSON array
        $inputDaysOfWeek = array_map(function ($day) {
          return ucfirst(strtolower($day));  // Ensures first letter is capital
        }, $data['daysOfWeek']);

        // Define the correct order of days for comparison
        $weekDaysOrdered = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Filter and sort the days of the week based on the ordered list
        $daysOfWeekJson = array_values(array_intersect($weekDaysOrdered, $inputDaysOfWeek));

        // Assuming $startDateTime holds the value "2024-04-18T00:00:00-07:00"
        $startDateTime = Carbon::parse($data['startDate']);

        // Extract only the time part without converting the timezone
        $startTime = $startDateTime->format('H:i:s');
        // Now $startTime contains only the time in "HH:mm:ss" format

        $recurrenceDetailsData = [
            'recurrence_details_id' => $recurrenceDetailsId ?? null,
            'frequency'             => 'weekly',
            'days_of_week'          => json_encode($daysOfWeekJson),
            'duration_minutes'      => $data['duration'],
            'start_time'            => $startTime,  // Here, only the time part is set
            'start_date'            => $data['startDate'],
            'end_date'              => $data['endDate'],
            'timezone'              => $data['timezone'],
          // Boolean values for each day based on input
            'sunday'                => in_array('Sunday', $inputDaysOfWeek),
            'monday'                => in_array('Monday', $inputDaysOfWeek),
            'tuesday'               => in_array('Tuesday', $inputDaysOfWeek),
            'wednesday'             => in_array('Wednesday', $inputDaysOfWeek),
            'thursday'              => in_array('Thursday', $inputDaysOfWeek),
            'friday'                => in_array('Friday', $inputDaysOfWeek),
            'saturday'              => in_array('Saturday', $inputDaysOfWeek),
        ];

        // Create ScheduleRecurrenceDetails within the transaction
        $recurrenceDetails = ScheduleRecurrenceDetails::create($recurrenceDetailsData);
        $recurrenceDetailsId = $recurrenceDetails->id;

        // Generate a human-readable description for the days of the week
        $daysOfWeekFormatted = $this->formatDaysOfWeek($daysOfWeekJson);

        // Assign the recurrence_details_id to the scheduleData
        $scheduleData['recurrence_details_id'] = $recurrenceDetailsId;
        // Update the extra_metadata with just the necessary information:
        // Update the extra_metadata with just the necessary information:
        $scheduleData['extra_metadata'] = json_encode([
            'daysOfWeek' => $daysOfWeekFormatted
        ]);

      }

      // Create Schedule linked to the determined content
      $schedule = $content->schedules()->create($scheduleData);
//      Log::debug('Schedule created successfully', ['scheduleId' => $schedule->id]);

      // Check if the content type is 'Show' and if it has an associated team
      $teamId = null;
      if ($content instanceof Show && $content->team) {
        $teamId = $content->team->id;
      }

      // Prepare the data for SchedulesIndex
      $schedulesIndexData = [
          'team_id'        => $teamId,
          'content_id'     => $content->id,
          'content_type'   => get_class($content),
          'schedule_id'    => $schedule->id,
          'next_broadcast' => $schedule->start_time,  // Assuming start_time is the next broadcast
      ];

      // Create the SchedulesIndex entry
      SchedulesIndex::create($schedulesIndexData);
//      Log::debug('SchedulesIndex created successfully', ['scheduleId' => $schedule->id]);



      // Commit the transaction
      DB::commit();

    } catch (\Exception $e) {
      // Rollback the transaction
      DB::rollback();
      Log::error('Failed to create schedule or index', [
          'error' => $e->getMessage(),
          'trace' => $e->getTraceAsString()
      ]);

      // Optionally, rethrow the exception or handle it as needed
      throw $e;
    }
    // Dispatch the job to update broadcast dates for this schedule
    ScheduleUpdateShowBroadcastDates::dispatch($schedule);
  }

  // Function to generate a user-friendly string of days
  protected function formatDaysOfWeek($daysOfWeek): string {
    $orderedDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $pluralDays = ['Mondays', 'Tuesdays', 'Wednesdays', 'Thursdays', 'Fridays', 'Saturdays', 'Sundays'];
    usort($daysOfWeek, function ($a, $b) use ($orderedDays) {
      return array_search($a, $orderedDays) - array_search($b, $orderedDays);
    });

    if (count($daysOfWeek) == 7) {
      return 'Weekdays and Weekends';
    } elseif (count(array_intersect($daysOfWeek, $orderedDays)) == 5 && !in_array('Saturday', $daysOfWeek) && !in_array('Sunday', $daysOfWeek)) {
      return 'Weekdays';
    } elseif (count($daysOfWeek) == 2 && in_array('Saturday', $daysOfWeek) && in_array('Sunday', $daysOfWeek)) {
      return 'Weekends';
    } else {
      // Convert each day to its plural form
      $pluralizedDays = array_map(function ($day) use ($orderedDays, $pluralDays) {
        $index = array_search($day, $orderedDays);
        return $pluralDays[$index] ?? $day;  // Fallback to original if something goes wrong
      }, $daysOfWeek);

      // Join the pluralized days with commas
      return implode(', ', $pluralizedDays);
    }
  }

  protected function generateScheduleDetailsString($recurrenceDetailsData): string {
    $formattedDaysOfWeek = implode(', ', json_decode($recurrenceDetailsData['days_of_week'], true));

    return "Recurring schedule starting from " . $recurrenceDetailsData['start_time'] . $recurrenceDetailsData['timezone'] .
        " on " . $formattedDaysOfWeek . " for a duration of " . $recurrenceDetailsData['duration_minutes'] . " minutes each session.";
  }

  public function failed(\Throwable $exception): void {
    // Retry the job with a delay if it has failed fewer than 6 times
    if ($this->attempts() <= 5) {
      $this->release(120);  // Release the job back to the queue with a 2-minute delay
    } else {
      // If the job has failed more than 5 times, log critical errors with detailed trace information
      Log::critical('Job failed after multiple attempts', [
          'job'   => get_class($this),
          'error' => $exception->getMessage(),
          'trace' => $exception->getTraceAsString(),  // Providing a full stack trace for deep diagnostics
      ]);
    }
  }

}
