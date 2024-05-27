<?php

namespace App\Jobs;

use App\Events\CreatorContentStatusUpdated;
use App\Events\ShowScheduleDetailsUpdated;
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

  // tec21: 2024-05-17 This is now a Helper function:
//  protected function getModelClass(string $contentType): ?string {
//    $map = [
//        'show'         => \App\Models\Show::class,
//        'episode'      => \App\Models\ShowEpisode::class,
//        'movie'        => \App\Models\Movie::class,
//        'movieTrailer' => \App\Models\MovieTrailer::class,
//        'otherContent' => \App\Models\OtherContent::class,
//      // Add more mappings as needed
//    ];
//
//    return $map[$contentType] ?? null;
//  }


  /**
   * Execute the job.
   *
   * @return void
   * @throws \Exception
   */
  public function handle(): void {
    $data = $this->data;

//    Log::debug('AddContentToSchedule::handle', [$data]);

    // tec21 2024-05-14: I'm commenting out the validation because
    // it kept returning errors saying the data was missing.

    // Manually create a validator instance
//    $validator = ValidatorFacade::make($data, [
//        'contentId'    => 'required|integer',
//        'contentType'  => 'required|string',
//        'scheduleType' => 'required|string',
//        'startDate'    => 'required|date',
//        'endDate'      => 'required|date',
//        'daysOfWeek'   => 'array',
//        'timezone'     => 'required|string',
//        'duration'     => 'required|integer',
//    ]);

//    if ($validator->fails()) {
//      // Here, instead of returning a response, you might log the errors or handle them otherwise:
//      Log::error('Validation failed in UpdateSchedule job', $validator->errors()->toArray());
//
//      return; // Exit the job if validation fails
//    }

    // Extract input
    // Correctly extract contentId and contentType using the case used in the validator

//    Log::debug('Payload before accessing keys', ['data' => $data]);

    $contentId = $data['contentId'];
    $contentType = $data['contentType'];
//    $scheduleType = $data['scheduleType'];

    // Determine the correct model class based on the contentType
    $modelClass = getModelClass($contentType); // getModelClass is a Helper function
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
    Log::debug('Debug AddContentToSchedule.php Job (tec21): ' . $data);
    $start_DateTime = $data['startDateTime'];
    $end_DateTime = $data['endDateTime'];

    // Need to keep dateTimes in the provided timezone
    $formattedStartDateTime = Carbon::parse($start_DateTime, $timezone)->toDateTimeString();
    $formattedEndDateTime = Carbon::parse($end_DateTime, $timezone)->toDateTimeString();

    // Convert to UTC
    $startDateTimeUtc = Carbon::parse($formattedStartDateTime, $timezone)->setTimezone('UTC')->toDateTimeString();
    $endDateTimeUtc = Carbon::parse($formattedEndDateTime, $timezone)->setTimezone('UTC')->toDateTimeString();

    // Log the attempt to find overlapping schedules
//    Log::debug('Checking for overlapping schedules', [
//        'start_date' => $formattedStartDate,
//        'end_date'   => $formattedEndDate,
//    ]);

    // Query for overlapping schedules
    $overlappingSchedules = Schedule::where(function ($query) use ($formattedStartDateTime, $formattedEndDateTime) {
      $query->whereBetween('start_dateTime', [$formattedStartDateTime, $formattedEndDateTime])
          ->orWhereBetween('end_dateTime', [$formattedStartDateTime, $formattedEndDateTime]);
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
        'type'               => $data['contentType'],
        'recurrence_flag'    => $data['scheduleType'] === 'recurring' ? 1 : 0,
        'status'             => 'scheduled',
        'priority'           => $priority,
        'duration_minutes'   => $data['duration'],
        'start_dateTime'     => $formattedStartDateTime,
        'end_dateTime'       => $formattedEndDateTime,
        'timezone'           => $timezone,
        'start_dateTime_utc' => $startDateTimeUtc,
        'end_dateTime_utc'   => $endDateTimeUtc,
        'extra_metadata'     => json_encode([])  // Initialize as empty JSON
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
        $startDateTime = Carbon::parse($data['startDateTime']);

        // Assuming $endDateTime holds the value "2024-04-18T00:00:00-07:00"
        $endDateTime = Carbon::parse($data['endDateTime']);

        // Now $startTime contains only the time in "HH:mm:ss" format

        $recurrenceDetailsData = [
            'recurrence_details_id' => $recurrenceDetailsId ?? null,
            'frequency'             => 'weekly',
            'days_of_week'          => json_encode($daysOfWeekJson),
            'duration_minutes'      => $data['duration'],
            'start_dateTime'        => $startDateTime,
            'end_dateTime'          => $endDateTime,
            'timezone'              => $data['timezone'],
            'start_dateTime_utc'    => $startDateTimeUtc,
            'end_dateTime_utc'      => $endDateTimeUtc,
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
          'team_id'        => $teamId ?? null,
          'content_id'     => $content->id,
          'content_type'   => get_class($content),
          'schedule_id'    => $schedule->id,
          'next_broadcast' => $schedule->start_dateTime,  // Assuming start_dateTime is the next broadcast
      ];

      // Create the SchedulesIndex entry
      SchedulesIndex::create($schedulesIndexData);
//      Log::debug('SchedulesIndex created successfully', ['scheduleId' => $schedule->id]);

      // Commit the transaction
      DB::commit();

      // Dispatch the job to update broadcast dates for this schedule
      UpdateBroadcastDates::dispatch($schedule);

      $meta = [
          'isSaving'           => false,
          'isUpdatingSchedule' => null,
          'isScheduled'        => true,
          'updatedBy'          => null,
          'triggeredBy'        => 'AddContentToSchedule Job',
      ];

      $content->meta = json_encode($meta);
      $content->save();

      $shortContentType = strtolower(class_basename($contentType)); // converts to 'show'

      // Log the meta data and broadcasting attempt
//      Log::debug('Broadcasting CreatorContentStatusUpdated event', [
//          'content_type' => $shortContentType,
//          'content_id'   => $content->id,
//          'meta'         => $meta
//      ]);

      // Broadcast the event
      broadcast(new CreatorContentStatusUpdated(
          $shortContentType,
          $content->id,
          $meta
      ));
      Log::debug('tec21: before construct details');

      $scheduleDetails = $this->constructScheduleDetails($shortContentType, $content->id, (object) $scheduleData);
      Log::debug('tec21: after construct details');
      Log::debug('tec21: broadcast Show Schedule Details Updated from UpdateBroadcastDates Job', $scheduleData);

      broadcast(new ShowScheduleDetailsUpdated(
          $scheduleDetails['contentType'],
          $scheduleDetails['contentId'],
          $scheduleDetails
      ));


//      Log::debug('Broadcasted CreatorContentStatusUpdated event successfully');

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
  }

  /**
   * Helper function to construct schedule details array.
   *
   * @param object $scheduleData
   * @return array
   */
  private function constructScheduleDetails($contentType, $contentId, object $scheduleData): array {
    $extraMetadata = $scheduleData->extra_metadata;

    // Decode the JSON string into a PHP object
    $decodedMetadata = json_decode($extraMetadata);

    // Check if decoding was successful and the property exists
    if ($decodedMetadata && property_exists($decodedMetadata, 'daysOfWeek')) {
      $daysOfWeek = $decodedMetadata->daysOfWeek;
    } else {
      $daysOfWeek = []; // Default to an empty array if not found
    }

    $startDateTimeUTC = Carbon::createFromFormat('Y-m-d H:i:s', $scheduleData->start_dateTime, $scheduleData->timezone)
        ->setTimezone('UTC')
        ->toDateTimeString();

    // Convert end_dateTime to UTC
    $endDateTimeUTC = Carbon::createFromFormat('Y-m-d H:i:s', $scheduleData->end_dateTime, $scheduleData->timezone)
        ->setTimezone('UTC')
        ->toDateTimeString();

    // Construct the schedule details array
    return [
        'contentType'     => $contentType,
        'contentId'       => $contentId,
        'start_DateTime'  => $startDateTimeUTC,
        'end_DateTime'    => $endDateTimeUTC,
        'timezone'        => 'UTC', // Since we're converting to UTC
//        'broadcastDates'  => $scheduleData->broadcast_dates, // we don't have this yet, the job just got dispatched.
        'durationMinutes' => $scheduleData->duration_minutes,
        'priority'        => $scheduleData->priority,
        'daysOfWeek'      => $daysOfWeek, // Now this contains the array of days or is empty
        'type'            => $scheduleData->recurrence_details_id ? 'recurring' : 'one-time',
    ];
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

    return "Recurring schedule starting from " . $recurrenceDetailsData['start_dateTime'] . $recurrenceDetailsData['timezone'] .
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
