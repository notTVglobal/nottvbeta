<?php

namespace App\Jobs;

use App\Models\Schedule;
use App\Models\SchedulesIndex;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScheduleUpdateShowBroadcastDates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // TODO: This runs every time an object gets added to the schedule.
    //  This creates a Json Array on schedules called broadcast_dates.

  public Schedule $schedule;

  public function __construct(Schedule $schedule)
  {
    $this->schedule = $schedule;
  }

  /**
   * Execute the job.
   *
   * @return void
   * @throws Exception
   */

  public function handle(): void {
    $schedule = $this->schedule;

    // Check if the polymorphic relationship 'content' is loaded and not null
    if (!$schedule->relationLoaded('content') || is_null($schedule->content)) {
      Log::info('Content relationship not loaded or content is null', ['scheduleId' => $schedule->id]);
      return;
    }

    // Check if content is actually loaded and contains elements
    // This is useful if content is expected to be a collection, not typical in morphTo relationships
    if ($schedule->content instanceof Collection && $schedule->content->isEmpty()) {
      Log::info('Content is loaded but contains no elements', ['scheduleId' => $schedule->id]);
      return;
    }

//    if (!$schedule) {
//      Log::warning('No schedule found', ['scheduleId' => $this->schedule->id]);
//      return;
//    }
//    // Fetch the newest schedule for the specified content type and ID
////    $schedule = Schedule::where('content_type', $this->contentType)
////        ->where('content_id', $this->contentId)
////        ->latest('created_at')
////        ->first();
//
//    if (!$schedule) {
//      Log::warning('No schedule found for the specified content type and ID', [
//          'contentType' => $schedule->contentType,
//          'contentId' => $schedule->contentId
//      ]);
//      return;
//    }

    // First, update broadcast dates and find the closest broadcast date
    $broadcastDates  = $this->updateBroadcastDates();
    $closestBroadcastDate = $this->findClosestBroadcastDate($broadcastDates);

    // Now update the schedules index with the closest broadcast date
    if ($closestBroadcastDate) {
      $this->updateSchedulesIndex($closestBroadcastDate);
    } else {
      // Log or handle the scenario where no future broadcast dates are found
      Log::info('No upcoming broadcast dates found for schedule.', ['scheduleId' => $this->schedule->id]);
    }


    // Format for storage
    $broadcastDates = [
        'modelType' => $schedule->content_type,
        'modelId' => $schedule->content_id,
        'priority' => $schedule->priority, // Assuming the same priority for all schedules for simplicity
        'timezone' => 'UTC',
        'broadcastDates' => $broadcastDates
    ];

    // Update the compiled dates into the database
    $schedule->broadcast_dates = json_encode($broadcastDates);
    $schedule->save();

    Log::info('Updated broadcast dates', ['scheduleId' => $schedule->id, 'dates' => $broadcastDates]);
  }

  /**
   * @throws Exception
   */
  protected function updateBroadcastDates(): array {
    $schedule = $this->schedule;

    // Initialize arrays for storing calculated dates
    $oneTimeDates = [];
    $recurrentDates = [];

    // Convert schedule times from local to UTC
    $startTimeUTC = Carbon::createFromFormat('Y-m-d H:i:s', $schedule->start_time, $schedule->timezone)
        ->setTimezone('UTC');
//    $endTimeUTC = Carbon::createFromFormat('Y-m-d H:i:s', $schedule->end_time, $schedule->timezone)
//        ->setTimezone('UTC');

    if (!$schedule->recurrence_flag) {
      // Handle non-recurring schedules
      $oneTimeDates[] = $startTimeUTC->toDateTimeString();
    } else {
      // Handle recurring schedules
      $this->processRecurrentSchedule($schedule, $recurrentDates);
    }

    // Merge and sort all dates
    $allDates = array_merge($oneTimeDates, $recurrentDates);
    usort($allDates, function ($a, $b) {
      return strtotime($a) - strtotime($b);
    });

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
              'content_type' => $contentType,
              'content_id' => $contentId,
              'team_id' => $teamId
          ]
      );
      Log::info('Schedule index updated with next broadcast date', [
          'date' => $closestBroadcastDate->toDateTimeString(),
          'scheduleId' => $schedule->id,
          'team_id' => $teamId,
          'content_type' => $contentType,
          'content_id' => $contentId
      ]);
    } catch (Exception $e) {
      Log::error('Failed to update schedule index', [
          'scheduleId' => $schedule->id,
          'error' => $e->getMessage(),
          'team_id' => $teamId
      ]);
    }
  }


  /**
   * @throws Exception
   */
  protected function processRecurrentSchedule($schedule, &$recurrentDates): void {
    try {
      $details = $schedule->scheduleRecurrenceDetails;
      $currentDateUTC = Carbon::now('UTC');

      // Convert the start and end dates to UTC
      $startDate = Carbon::parse($details->start_date, $details->timezone)->setTimezone('UTC');
      $endDate = Carbon::parse($details->end_date, $details->timezone)->setTimezone('UTC');

      // Ensure the start date is not before the current date
      $effectiveStartDate = $startDate->isPast() ? $currentDateUTC : $startDate;

      while ($effectiveStartDate->lte($endDate)) {
        $dayOfWeek = strtolower($effectiveStartDate->format('l')); // 'monday', 'tuesday', etc.
        if ($details->$dayOfWeek) { // Check if the day is scheduled using the boolean columns
          $dateTime = $effectiveStartDate->copy()->setTimeFromTimeString($details->start_time)->setTimezone('UTC');
          $recurrentDates[] = $dateTime->toDateTimeString();
        }

        // Move to the next day
        $effectiveStartDate->addDay();
      }
      Log::info('Recurrent schedule processed successfully', ['scheduleId' => $schedule->id]);
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
