<?php

namespace App\Jobs;

use App\Models\SchedulesIndex;
use App\Models\Show;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScheduleUpdateSchedulesIndexesDELETEME implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  // This job runs every 4 hours and will update
  // the Next Broadcast information for the Team page.

  // TODO: Store this table in the cache sorted by the
  //  Next Broadcast dateTime. Update the cache every time.

  public int $timeout = 180; // sets a maximum execution time of 3 minutes

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct() {
    //
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle() {

    $startTime = time();  // Capture start time
    $maxDuration = 300;   // Maximum allowed duration in seconds (5 minutes)
    $loopCounter = 0;
    $maxLoops = 1000;     // Maximum iterations

    Log::debug('UpdateNextBroadcastForShows Job started');

    // Include 'shows.scheduleIndexes' in the with() method to load related SchedulesIndex entries
    // Only fetch teams that have shows which currently have schedules
    $teams = Team::whereHas('shows.schedules', function ($query) {
      $query->where('end_dateTime', '>=', now());
    })->with([
        'shows.schedules.scheduleRecurrenceDetails',
        'shows.scheduleIndexes',
        'shows.schedules'
    ])->get();

    if ($teams->isEmpty()) {
      Log::debug('No teams found to process');
      return; // Early return if no teams
    }

    foreach ($teams as $team) {
      Log::debug('Processing team', ['team_id' => $team->id]);

      if ($team->shows->isEmpty()) {
        Log::info('No shows found for team', ['team_id' => $team->id]);
        continue; // Skip to the next team
      }

      foreach ($team->shows as $show) {
        if (time() - $startTime > $maxDuration || $loopCounter >= $maxLoops) {
          Log::error('Job exceeded maximum execution parameters', [
              'duration' => time() - $startTime,
              'loops'    => $loopCounter
          ]);

          return;  // Exit the job early
        }

        Log::info('Processing show', ['show_id' => $show->id]);
        $nextBroadcastDateTime = $this->calculateNextBroadcast($show);

        // Format the next broadcast date-time or handle null
        if ($nextBroadcastDateTime instanceof Carbon) {
          $formattedDateTime = $nextBroadcastDateTime->toDateTimeString();  // Format to SQL datetime
        } else {
          // Handle cases where nextBroadcastDateTime is not a Carbon instance
          $formattedDateTime = null;
          Log::warning('Expected a Carbon instance for the next broadcast time', [
              'receivedType' => gettype($nextBroadcastDateTime),
              'value' => $nextBroadcastDateTime
          ]);
        }

        $schedule = $show->schedules()->where('start_dateTime', '>=', now())->orderBy('start_dateTime')->first();

        if ($schedule) {
          try {
            // Attempt to update or create the SchedulesIndex entry
            SchedulesIndex::updateOrCreate(
                [
                    'content_type' => Show::class,
                    'content_id' => $show->id,
                    'team_id' => $team->id,
                    'schedule_id' => $schedule->id,  // Now correctly using the schedule ID
                ],
                [
                    'next_broadcast' => $formattedDateTime
                ]
            );
            Log::info('Updated next broadcast', ['show_id' => $show->id, 'next_broadcast' => $formattedDateTime]);
          } catch (\Exception $e) {
            Log::error('Failed to update or create SchedulesIndex', [
                'show_id' => $show->id,
                'team_id' => $team->id,
                'error' => $e->getMessage()
            ]);
          }
        } else {
          Log::warning('No upcoming schedule found for the show', ['show_id' => $show->id]);
        }
//        Log::info('Processed show', ['show_id' => $show->id]);
        $loopCounter++;  // Increment loop counter after each show is processed
      }
    }

      Log::info('UpdateNextBroadcastForShows Job completed');
  }

  private function calculateNextBroadcast($show): ?Carbon {
    $closestNextDateTime = null;

    foreach ($show->schedules as $schedule) {
      // Log the current state of the schedule
      Log::info('Processing schedule', [
          'startTime'      => $schedule->start_dateTime ? $schedule->start_dateTime->toDateTimeString() : 'null',
          'endTime'        => $schedule->end_dateTime ? $schedule->end_dateTime->toDateTimeString() : 'null',
          'recurrenceFlag' => $schedule->recurrenceFlag
      ]);

      $currentDateTime = null;

      // Ensure $schedule->startTime is not null before checking if it's in the future
      if ($schedule->start_dateTime instanceof Carbon & $schedule->start_dateTime->isFuture()) {
        // If startTime is later than now, this is a candidate
        $currentDateTime  = $schedule->start_dateTime;
      } elseif ($schedule->start_dateTime instanceof Carbon && $schedule->start_dateTime->isPast() && $schedule->end_dateTime instanceof Carbon && $schedule->end_dateTime->isFuture()) {
        if (!$schedule->recurrenceFlag) {
          // Calculate next recurrence if the schedule is recurring
          $currentDateTime = $this->calculateNextRecurrence($schedule);
        }
      }
      // Check if this currentDateTime is closer to "now" than the previously stored closestNextDateTime
      if ($currentDateTime instanceof Carbon && ($closestNextDateTime === null || $currentDateTime->isBefore($closestNextDateTime))) {
        $closestNextDateTime = $currentDateTime;
      }
    }

    return $closestNextDateTime; // Could still be null if no future schedules are found
  }

  /**
   * Calculates the next recurrence DateTime based on schedule details.
   *
   * @param mixed $schedule
   * @return Carbon|null
   */
  private function calculateNextRecurrence($schedule): ?Carbon {
    // Extract days of the week from recurrence details
    $daysOfWeek = $schedule->scheduleRecurrenceDetails->days_of_week;

    if (is_string($daysOfWeek)) {
      $daysOfWeek = json_decode($daysOfWeek, true);
    }

    // Check if $daysOfWeek is now an array and not empty
    if (!is_array($daysOfWeek) || empty($daysOfWeek)) {
      Log::error('Days of week is not an array or is empty', ['daysOfWeek' => $daysOfWeek]);
      return null;
    }

    $currentDateTime = Carbon::now();
    $earliestNextDay = null;

    foreach ($daysOfWeek as $day) {
      // Find the next occurrence of the specified day
      $nextDay = Carbon::parse("next $day");

      // If today is the day but the time has already passed, calculate for next week
      if ($nextDay->isToday() && $currentDateTime->format('H:i:s') >= explode(' ', $schedule->start_dateTime)[1]) {
        $nextDay->addWeek();
      }

      // Set the time from the schedule's start time
      if ($this->isValidTime($schedule->start_dateTime)) {
        $timeParts = explode(' ', $schedule->start_dateTime);
        $nextDay->setTimeFromTimeString($timeParts[1]); // Assuming start_dateTime format is 'YYYY-MM-DD HH:MM:SS'
      } else {
        Log::warning('Invalid time format', ['time' => $schedule->start_dateTime]);
        continue; // Skip this iteration if time format is invalid
      }

      if (!$earliestNextDay || $nextDay->isBefore($earliestNextDay)) {
        $earliestNextDay = $nextDay;
      }
    }

    // Return the earliest next day if it's in the future, otherwise return null
    return ($earliestNextDay && $earliestNextDay->gt($currentDateTime)) ? $earliestNextDay : null;

  }
  private function isValidTime($dateTime): bool {
    // Extract the time part from the dateTime string
    $timePart = explode(' ', $dateTime)[1] ?? null;

    // Return true if the time part matches the HH:MM:SS format, false otherwise
    return preg_match('/^\d{2}:\d{2}:\d{2}$/', $timePart) === 1;
  }
}
