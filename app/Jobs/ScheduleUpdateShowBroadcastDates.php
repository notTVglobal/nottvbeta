<?php

namespace App\Jobs;

use App\Events\ShowScheduleDetailsUpdated;
use App\Models\Schedule;
use App\Models\SchedulesIndex;
use Carbon\Carbon;
use DateTimeZone;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScheduleUpdateShowBroadcastDates implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  // TODO: This runs every time an object gets added to the schedule.
  //  This creates a Json Array on schedules called broadcast_dates.

  public Schedule $schedule;

  public function __construct(Schedule $schedule) {
    $this->schedule = $schedule;
  }

  /**
   * Execute the job.
   *
   * @return void
   * @throws Exception
   */

  public function handle(): void {

    // Reload the schedule and ensure it's not null before proceeding
    $reloadedSchedule = Schedule::with('content')->find($this->schedule->id);
    //    Log::debug($reloadedSchedule);

    if (!$reloadedSchedule || is_null($reloadedSchedule->content)) {
//      Log::debug('Failed to load schedule with content or content is null', ['scheduleId' => $this->schedule->id]);
      return;
    }

    // First, update broadcast dates and find the closest broadcast date
    $broadcastDates = $this->updateBroadcastDates();
    $closestBroadcastDate = $this->findClosestBroadcastDate($broadcastDates);

    // Now update the schedules index with the closest broadcast date
    if ($closestBroadcastDate) {
      $this->updateSchedulesIndex($closestBroadcastDate);
    } else {
      // Log or handle the scenario where no future broadcast dates are found
//      Log::debug('No upcoming broadcast dates found for schedule.', ['scheduleId' => $reloadedSchedule->id]);
    }

    // Format for storage
    $broadcastDates = [
        'modelType'       => $reloadedSchedule->content_type,
        'modelId'         => $reloadedSchedule->content_id,
        'priority'        => $reloadedSchedule->priority, // Assuming the same priority for all schedules for simplicity
        'timezone'        => 'UTC',
        'durationMinutes' => $reloadedSchedule->duration_minutes,
        'broadcastDates'  => $broadcastDates
    ];

    // Update the compiled dates into the database
    $reloadedSchedule->broadcast_dates = json_encode($broadcastDates);
    $reloadedSchedule->save();

//    Log::debug('Updated broadcast dates', ['scheduleId' => $reloadedSchedule->id, 'dates' => $broadcastDates]);
  }


  /**
   * @throws Exception
   */
  protected function updateBroadcastDates(): array {
    $schedule = $this->schedule;

    // Initialize arrays for storing calculated dates
    $oneTimeDates = [];
    $recurrentDates = [];

//    Log::debug('Test Conversion to UTC', [
//        'localTime' => $testTime,
//        'UTCTime' => $startTimeUTC->toDateTimeString(),  // This should log '2024-05-09 02:30:00'
//        'timezone' => 'America/Vancouver'
//    ]);
//
//    Log::debug('this is the $schedule->start_time: ' . $schedule->start_time);

    // Convert schedule times from local to UTC
    $startTimeUTC = Carbon::createFromFormat('Y-m-d H:i:s', $schedule->start_time, new DateTimeZone($schedule->timezone))
        ->setTimezone('UTC');

//    Log::debug('Converted time to UTC', [
//        'localTime' => $schedule->start_time,
//        'UTCTime'   => $startTimeUTC->toDateTimeString(),  // Should log '2024-05-09 02:30:00'
//        'timezone'  => $schedule->timezone
//    ]);

//    Log::debug('Converted time to UTC', ['localTime' => $schedule->start_time, 'UTCTime' => $startTimeUTC->toDateTimeString(), 'timezone' => $schedule->timezone]);

    try {
      if (!$schedule->recurrence_flag) {
        // Handle non-recurring schedules
        $oneTimeDates[] = $startTimeUTC->toDateTimeString();
      } else {
        // Handle recurring schedules
        $recurrentDates = $this->processRecurrentSchedule($schedule);
      }
    } catch (Exception $e) {
      Log::error('Error processing schedule', [
          'scheduleId' => $schedule->id,
          'error'      => $e->getMessage(),
          'trace'      => $e->getTraceAsString()
      ]);
      // Optionally, rethrow or handle the exception as needed
      // throw $e; // Uncomment if you need to propagate the error upwards
      // Or handle gracefully, maybe set an error state or return a default value
      return []; // Return an empty array or another appropriate value indicating failure
    }
    // Merge and sort all dates
//    $allDates = array_merge($oneTimeDates, $recurrentDates);
//    usort($allDates, function ($a, $b) {
//      return strtotime($a) - strtotime($b);
//    });
//    $allDates = array_map(function($date) { return strtotime($date); }, $allDates);
//    sort($allDates); // Sorts the timestamps directly
//    $allDates = array_map(function($timestamp) { return date('Y-m-d H:i:s', $timestamp); }, $allDates); // Convert back to string if necessary
//

    // Merging one-time and recurrent dates into a single array
    $allDates = array_merge($oneTimeDates, $recurrentDates);

    try {
      // Convert all date strings to timestamps for sorting
      $allTimestamps = array_map(function ($date) {
        return strtotime($date);
      }, $allDates);

      // Sort timestamps directly
      sort($allTimestamps);

      // Convert the sorted timestamps back to date strings
      $allDates = array_map(function ($timestamp) {
        return date('Y-m-d H:i:s', $timestamp);
      }, $allTimestamps);

    } catch (Exception $e) {
      Log::error('Error handling date conversion or sorting', [
          'error' => $e->getMessage(),
          'trace' => $e->getTraceAsString()
      ]);

      // Handle error, such as by returning an empty array or default dates
      return []; // Similarly, indicate failure appropriately
    }

    // Optionally, log the sorted dates for debugging
//    Log::debug('All sorted broadcast dates', ['dates' => $allDates]);

    // Return the array of all broadcast dates
    return $allDates;
  }

  protected function findClosestBroadcastDate($broadcastDates): ?Carbon {
    $now = Carbon::now('UTC');
    $closestDate = null;

    foreach ($broadcastDates as $date) {
      $broadcastTime = Carbon::parse($date, 'UTC');
      // Log each date and the corresponding broadcastTime as it's processed
//      Log::debug('Processing date', ['date' => $date, 'broadcastTime' => $broadcastTime->toIso8601String()]);

      if ($broadcastTime->gt($now) && ($closestDate === null || $broadcastTime->lt($closestDate))) {
        $closestDate = $broadcastTime;
      }
    }

    return $closestDate;
  }


  protected function updateSchedulesIndex($closestBroadcastDate): void {

    $schedule = $this->schedule;

    if (!$closestBroadcastDate) {
      Log::warning('No valid closest broadcast date found', ['scheduleId' => $schedule->id]);

      return;
    }

    // Extract content_type and content_id from the schedule
    $contentType = $schedule->content_type;
    $contentId = $schedule->content_id;

    $teamId = null;
    if ($contentType === 'App\Models\Show') {
      // Assuming Show model has direct access to a team_id or its team relationship
      $teamId = $schedule->content->team_id;  // Ensure this is correct based on your Show model's relationships
    } elseif ($contentType === 'App\Models\ShowEpisode') {
      // Make sure to access 'show' relationship only if the content is ShowEpisode
      if (isset($schedule->content->show)) {
        $teamId = $schedule->content->show->team_id;
      } else {
        Log::error('Show relationship not loaded for ShowEpisode', ['contentId' => $contentId]);
      }
    }

    if (!$teamId) {
      Log::error('Failed to find team ID for schedule', ['scheduleId' => $schedule->id, 'contentType' => $contentType]);

      return;
    }

    try {
      SchedulesIndex::updateOrCreate(
          ['schedule_id' => $schedule->id],
          [
              'next_broadcast' => $closestBroadcastDate->toDateTimeString(),
              'content_type'   => $contentType,
              'content_id'     => $contentId,
              'team_id'        => $teamId
          ]
      );
//      Log::debug('Schedule index updated with next broadcast date', [
//          'date'         => $closestBroadcastDate->toDateTimeString(),
//          'scheduleId'   => $schedule->id,
//          'team_id'      => $teamId,
//          'content_type' => $contentType,
//          'content_id'   => $contentId
//      ]);
    } catch (Exception $e) {
      Log::error('Failed to update schedule index', [
          'scheduleId' => $schedule->id,
          'error'      => $e->getMessage(),
          'team_id'    => $teamId
      ]);
    }
  }


  /**
   * @throws Exception
   */
  protected function processRecurrentSchedule($schedule): array {
    try {
      $details = $schedule->scheduleRecurrenceDetails;
      $timezone = new DateTimeZone($details->timezone); // Explicitly setting the timezone
      $currentDateLocal = Carbon::now($timezone); // Current date in user's timezone

      $startDateLocal = Carbon::createFromFormat('Y-m-d H:i:s', $details->start_date, $timezone);
      $endDateLocal = Carbon::createFromFormat('Y-m-d H:i:s', $details->end_date, $timezone);

      // Log initial values
//      Log::debug('Initial values', [
//          'currentDateLocal' => $currentDateLocal->toDateTimeString(),
//          'startDateLocal' => $startDateLocal->toDateTimeString(),
//          'endDateLocal' => $endDateLocal->toDateTimeString(),
//          'timezone' => $details->timezone
//      ]);

      // Decode the JSON string to an array and convert all entries to lowercase for comparison
      $daysOfWeek = json_decode($details->days_of_week);
      if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("JSON decode error in days_of_week: " . json_last_error_msg());
      }
      $daysOfWeek = array_map('strtolower', $daysOfWeek);

      // Log days of week
//      Log::debug('Days of week', [
//          'daysOfWeek' => $daysOfWeek
//      ]);

      // Initialize the recurrentDates array
      $recurrentDates = [];

      // Adjust the start date if it's in the past
      $effectiveStartDateLocal = $startDateLocal->copy()->second(0);
//      Log::debug('Adjusted effectiveStartDateLocal', [
//          'effectiveStartDateLocal' => $effectiveStartDateLocal->toDateTimeString()
//      ]);

      // Iterate over the dates until the endDateLocal
      while ($effectiveStartDateLocal->lte($endDateLocal)) {
        // Log current date being processed
//        Log::debug('Processing date', [
//            'effectiveStartDateLocal' => $effectiveStartDateLocal->toDateTimeString()
//        ]);

        // Check if the current date matches any of the specified days of the week
        if (in_array(strtolower($effectiveStartDateLocal->format('l')), $daysOfWeek)) {
          $recurrentDates[] = $effectiveStartDateLocal->copy()->second(0); // Store a copy with zeroed seconds
//          Log::debug('Added recurrent date', [
//              'recurrentDate' => $effectiveStartDateLocal->toDateTimeString()
//          ]);
        }
        $effectiveStartDateLocal->addDay(); // Move to the next day
      }

      // Convert all local dates in the array to UTC
      $recurrentDatesUTC = array_map(function ($date) {
        return $date->setTimezone(new DateTimeZone('UTC'))->toIso8601String();
      }, $recurrentDates);

      // Log final results
//      Log::debug('Recurrent schedule processed successfully', [
//          'scheduleId' => $schedule->id,
//          'datesProcessed' => count($recurrentDates),
//          'UTC DatesAdded' => implode(', ', $recurrentDatesUTC)  // Log the converted dates
//      ]);

      return $recurrentDatesUTC; // Return the processed dates in UTC

    } catch (Exception $e) {
      Log::error('Failed to process recurrent schedule', [
          'scheduleId' => $schedule->id,
          'error' => $e->getMessage(),
          'trace' => $e->getTraceAsString()
      ]);
      // Rethrow or handle the exception as needed
      throw new Exception("Failed to process schedule: " . $e->getMessage());
    }
  }



}
