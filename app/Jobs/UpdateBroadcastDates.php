<?php

namespace App\Jobs;

use App\Models\Schedule;
use App\Models\SchedulesIndex;
use Carbon\Carbon;
use DateTimeZone;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateBroadcastDates implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

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

  public function handle() {

    if ($this->batch()->cancelled()) {
      // Determine if the batch has been cancelled...

      return;
    }

    try {

        // Reload the schedule and ensure it's not null before proceeding
        $schedule = Schedule::with('content')->find($this->schedule->id);

        if (!$schedule || is_null($schedule->content)) {
          Log::debug('Failed to load schedule with content or content is null', ['scheduleId' => $schedule->id]);

          return;
        }

        // First, update broadcast dates and find the closest broadcast date
        $broadcastDates = $this->updateBroadcastDates($schedule);
        $closestBroadcastDate = $this->findClosestBroadcastDate($broadcastDates);

        // Now update the schedules index with the closest broadcast date
        if ($closestBroadcastDate) {
          $this->updateScheduleIndex($closestBroadcastDate, $schedule);
        } else {
          // Log or handle the scenario where no future broadcast dates are found
          Log::debug('No upcoming broadcast dates found for schedule.', ['scheduleId' => $schedule->id]);
        }

        // Clear the broadcast_dates before updating
//        $schedule->broadcast_dates = json_encode([]);
//        $schedule->save();

        // Format for storage
        $broadcastDatesData = [
            'modelType'       => $schedule->content_type,
            'modelId'         => $schedule->content_id,
            'priority'        => $schedule->priority, // Assuming the same priority for all schedules for simplicity
            'timezone'        => 'UTC',
            'durationMinutes' => $schedule->duration_minutes,
            'broadcastDates'  => $broadcastDates
        ];

        // Update the compiled dates into the database
        $schedule->broadcast_dates = json_encode($broadcastDatesData);
        $schedule->save();

        Log::debug('Updated broadcast dates', ['scheduleId' => $schedule->id, 'dates' => $broadcastDatesData]);

    } catch (Exception $e) {
      Log::error('Failed to update schedule with transaction', [
          'scheduleId' => $this->schedule->id,
          'error'      => $e->getMessage(),
          'trace'      => $e->getTraceAsString()
      ]);
    }
  }


  /**
   * @throws Exception
   */
  protected function updateBroadcastDates($schedule): array {

    // Initialize array for storing calculated dates
    $broadcastDates = [];

//    Log::debug('Test Conversion to UTC', [
//        'localTime' => $testTime,
//        'UTCTime' => $startTimeUTC->toDateTimeString(),  // This should log '2024-05-09 02:30:00'
//        'timezone' => 'America/Vancouver'
//    ]);
//
//    Log::debug('this is the $schedule->start_dateTime: ' . $schedule->start_dateTime);

//    Log::debug('Converted time to UTC', [
//        'localTime' => $schedule->start_dateTime,
//        'UTCTime'   => $startTimeUTC->toDateTimeString(),  // Should log '2024-05-09 02:30:00'
//        'timezone'  => $schedule->timezone
//    ]);

//    Log::debug('Converted time to UTC', ['localTime' => $schedule->start_dateTime, 'UTCTime' => $startTimeUTC->toDateTimeString(), 'timezone' => $schedule->timezone]);

    try {
      if (!$schedule->recurrence_flag) {
        // Convert schedule times from local to UTC
        $startDateTimeUTC = Carbon::createFromFormat('Y-m-d H:i:s', $schedule->start_dateTime, new DateTimeZone($schedule->timezone))
            ->setTimezone('UTC');
        // Handle non-recurring schedules
        $broadcastDates[] = $startDateTimeUTC->toDateTimeString();
      } else {
        // Handle recurring schedules
        $broadcastDates = $this->processRecurrentSchedule($schedule);
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

    try {
      // Convert all date strings to timestamps for sorting
      $allTimestamps = array_map(function ($date) {
        return strtotime($date);
      }, $broadcastDates);

      // Sort timestamps directly
      sort($allTimestamps);

      // Convert the sorted timestamps back to date strings
      $broadcastDates = array_map(function ($timestamp) {
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
//    Log::debug('All sorted broadcast dates', ['dates' => $broadcastDates]);

    // Return the array of all broadcast dates
    return $broadcastDates;
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


  protected function updateScheduleIndex($closestBroadcastDate, $schedule): void {

    if (!$closestBroadcastDate) {
      Log::warning('No valid closest broadcast date found', ['scheduleId' => $schedule->id]);

      return;
    }

// Extract content_type and content_id from the schedule
    $contentType = $schedule->content_type;
    $contentId = $schedule->content_id;

    $teamId = match ($contentType) {
      'App\Models\Show', 'App\Models\Movie' => $schedule->content->team_id ?? null,
      'App\Models\ShowEpisode' => $schedule->content->show->team_id ?? null,
      default => null,
    };

    try {
      // Clear the next_broadcast field if the entry already exists
      SchedulesIndex::where('schedule_id', $schedule->id)->update(['next_broadcast' => null]);

      SchedulesIndex::updateOrCreate(
          ['schedule_id' => $schedule->id],
          [
              'next_broadcast' => $closestBroadcastDate->toDateTimeString(),
              'content_type'   => $contentType,
              'content_id'     => $contentId,
              'team_id'        => $teamId ?? null
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
          'schedule_id' => $schedule->id,
          'error'       => $e->getMessage(),
          'team_id'     => $teamId
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
      $currentDateTimeLocal = Carbon::now($timezone); // Current date in schedule's timezone

      $startDateTimeLocal = Carbon::createFromFormat('Y-m-d H:i:s', $details->start_dateTime, $timezone);
      $endDateTimeLocal = Carbon::createFromFormat('Y-m-d H:i:s', $details->end_dateTime, $timezone);

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
      $effectiveStartDateTimeLocal = $startDateTimeLocal->copy()->second(0);
//      Log::debug('Adjusted effectiveStartDateLocal', [
//          'effectiveStartDateLocal' => $effectiveStartDateLocal->toDateTimeString()
//      ]);

      // Iterate over the dates until the endDateLocal
      while ($effectiveStartDateTimeLocal->lte($endDateTimeLocal)) {
        // Log current date being processed
//        Log::debug('Processing date', [
//            'effectiveStartDateLocal' => $effectiveStartDateLocal->toDateTimeString()
//        ]);

        // Check if the current date matches any of the specified days of the week
        if (in_array(strtolower($effectiveStartDateTimeLocal->format('l')), $daysOfWeek)) {
          $recurrentDates[] = $effectiveStartDateTimeLocal->copy()->second(0); // Store a copy with zeroed seconds
//          Log::debug('Added recurrent date', [
//              'recurrentDate' => $effectiveStartDateLocal->toDateTimeString()
//          ]);
        }
        $effectiveStartDateTimeLocal->addDay(); // Move to the next day
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
          'error'      => $e->getMessage(),
          'trace'      => $e->getTraceAsString()
      ]);
      // Rethrow or handle the exception as needed
      throw new Exception("Failed to process schedule: " . $e->getMessage());
    }
  }


}
