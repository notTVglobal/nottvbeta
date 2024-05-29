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
use Illuminate\Support\Facades\Redis;

class UpdateBroadcastDates implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

  // This runs every time an object gets added to the schedule.
  // This creates a Json Array on schedules called broadcast_dates.

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

    // Check if the job is part of a batch and if the batch has been cancelled
    if ($this->batch() && $this->batch()->cancelled()) {
      Log::info('Batch cancelled, job will not run', ['scheduleId' => $this->schedule->id]);
      return;
    }

    try {
      $schedule = $this->schedule; // Use the passed schedule with preloaded relationships

      if (is_null($schedule->content)) {
        Log::warning('Failed to load schedule with content or content is null', ['scheduleId' => $schedule->id]);

        return;
      }

      // Update broadcast dates
      $broadcastDates = $this->updateBroadcastDates($schedule);
      $closestBroadcastDate = $this->findClosestBroadcastDate($broadcastDates);

      $redisKey = 'schedule_broadcast_dates_' . $schedule->id;
      Redis::set($redisKey, json_encode([
          'dates'                => $broadcastDates,
          'closestBroadcastDate' => $closestBroadcastDate
      ]));
    } catch (Exception $e) {
      Log::error('Failed to update schedule broadcast dates', [
          'scheduleId' => $this->schedule->id,
          'error'      => $e->getMessage(),
          'trace'      => $e->getTraceAsString()
      ]);
    }
  }


//
//
//      // First, update broadcast dates and find the closest broadcast date
//      $broadcastDates = $this->updateBroadcastDates($schedule);
//      $closestBroadcastDate = $this->findClosestBroadcastDate($broadcastDates);
//
//      // Now update the schedules index with the closest broadcast date
//      if ($closestBroadcastDate) {
//        $this->updateScheduleIndex($closestBroadcastDate, $schedule);
//      }
//
//      // Format for storage
//      $broadcastDatesData = [
//          'modelType'       => $schedule->content_type,
//          'modelId'         => $schedule->content_id,
//          'priority'        => $schedule->priority, // Assuming the same priority for all schedules for simplicity
//          'timezone'        => 'UTC',
//          'durationMinutes' => $schedule->duration_minutes,
//          'broadcastDates'  => $broadcastDates
//      ];
//
//      // Update the compiled dates into the database
//      $schedule->broadcast_dates = json_encode($broadcastDatesData);
//      $schedule->save();
//
////        Log::debug('Updated broadcast dates', ['scheduleId' => $schedule->id, 'dates' => $broadcastDatesData]);
//
//    } catch (Exception $e) {
//      Log::error('Failed to update schedule with transaction', [
//          'scheduleId' => $this->schedule->id,
//          'error'      => $e->getMessage(),
//          'trace'      => $e->getTraceAsString()
//      ]);
//    }
//  }


  /**
   * @throws Exception
   */
  protected function updateBroadcastDates($schedule): array {
    // Initialize array for storing calculated dates
    $broadcastDates = [];

    try {
      if (!$schedule->recurrence_flag) {
        // Handle non-recurring schedules
        $broadcastDates[] = $schedule->start_dateTime_utc->toIso8601String();
      } else {
        // Handle recurring schedules
        $broadcastDates = $this->processRecurrentSchedule($schedule);
      }

      // Sort the dates directly as strings
      sort($broadcastDates);

    } catch (Exception $e) {
      Log::error('Error processing schedule', [
          'scheduleId' => $schedule->id,
          'error'      => $e->getMessage(),
          'trace'      => $e->getTraceAsString()
      ]);

      return []; // Return an empty array or another appropriate value indicating failure
    }

    // Return the array of all broadcast dates
    return $broadcastDates;
  }

  protected function findClosestBroadcastDate($broadcastDates): ?string {
    $now = Carbon::now('UTC');
    $closestDate = null;

    foreach ($broadcastDates as $date) {
      $broadcastTime = Carbon::parse($date, 'UTC');
      if ($broadcastTime->gt($now) && ($closestDate === null || $broadcastTime->lt($closestDate))) {
        $closestDate = $broadcastTime->toIso8601String();
      }
    }

    return $closestDate;
  }

//
//  protected function updateScheduleIndex($closestBroadcastDate, $schedule): void {
//
//    if (!$closestBroadcastDate) {
//      Log::warning('No valid closest broadcast date found', ['scheduleId' => $schedule->id]);
//
//      return;
//    }
//
//    // Extract content_type and content_id from the schedule
//    $contentType = $schedule->content_type;
//    $contentId = $schedule->content_id;
//
//    $teamId = match ($contentType) {
//      'App\Models\Show', 'App\Models\Movie' => $schedule->content->team_id ?? null,
//      'App\Models\ShowEpisode' => $schedule->content->show->team_id ?? null,
//      default => null,
//    };
//
//    try {
//      SchedulesIndex::updateOrCreate(
//          ['schedule_id' => $schedule->id],
//          [
//              'next_broadcast' => $closestBroadcastDate->toDateTimeString(),
//              'content_type'   => $contentType,
//              'content_id'     => $contentId,
//              'team_id'        => $teamId ?? null
//          ]
//      );
////      Log::debug('Schedule index updated with next broadcast date', [
////          'date'         => $closestBroadcastDate->toDateTimeString(),
////          'scheduleId'   => $schedule->id,
////          'team_id'      => $teamId,
////          'content_type' => $contentType,
////          'content_id'   => $contentId
////      ]);
//    } catch (Exception $e) {
//      Log::error('Failed to update schedule index', [
//          'schedule_id' => $schedule->id,
//          'error'       => $e->getMessage(),
//          'team_id'     => $teamId
//      ]);
//    }
//  }


  /**
   * @throws Exception
   */
  protected function processRecurrentSchedule($schedule): array {
    $recurrentDates = [];
    try {
      $details = $schedule->scheduleRecurrenceDetails;

      // Decode the JSON string to an array and convert all entries to lowercase for comparison
      $daysOfWeek = json_decode($details->days_of_week);
      if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("JSON decode error in days_of_week: " . json_last_error_msg());
      }
      $daysOfWeek = array_map('strtolower', $daysOfWeek);

      // Use the start and end date times with the specified timezone
      $timezone = new DateTimeZone($details->timezone);
      $startDateTime = Carbon::parse($details->start_dateTime, $timezone);
      $endDateTime = Carbon::parse($details->end_dateTime, $timezone);

      $currentDateTime = $startDateTime->copy();

      // Find the first valid day within the date range
      while (!in_array(strtolower($currentDateTime->format('l')), $daysOfWeek) && $currentDateTime->lte($endDateTime)) {
        $currentDateTime->addDay();
      }


      // Generate all occurrences of the specified days within the date range
      while ($currentDateTime->lte($endDateTime)) {
        if (in_array(strtolower($currentDateTime->format('l')), $daysOfWeek)) {
          // Convert the date to utc and add it to the array
          $recurrentDates[] = $currentDateTime->copy()->setTimezone('UTC')->toIso8601String();
        }

        // Find the next valid day
        $currentDayIndex = array_search(strtolower($currentDateTime->format('l')), $daysOfWeek);
        $nextDayIndex = ($currentDayIndex + 1) % count($daysOfWeek);

        // Calculate days to add to get to the next valid day
        $daysToAdd = ($nextDayIndex > $currentDayIndex) ? $nextDayIndex - $currentDayIndex : (7 - $currentDayIndex) + $nextDayIndex;
        $currentDateTime->addDays($daysToAdd);
      }

//      Log::debug('Recurrent schedule dates', ['scheduleId' => $schedule->id, 'recurrentDates' => $recurrentDates]);

      return $recurrentDates; // Return the processed dates in UTC

    } catch (Exception $e) {
      Log::error('Failed to process recurrent schedule', [
          'scheduleId' => $schedule->id,
          'error'      => $e->getMessage(),
          'trace'      => $e->getTraceAsString()
      ]);
      throw new Exception("Failed to process schedule: " . $e->getMessage());
    }
  }

}
