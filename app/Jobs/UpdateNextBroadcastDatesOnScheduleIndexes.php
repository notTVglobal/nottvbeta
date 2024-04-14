<?php

namespace App\Jobs;

use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateNextBroadcastDatesOnScheduleIndexes implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


  // This job runs every 30 minutes and will update
  // the Next Broadcast information for the Team page.

  // TODO: Store this table in the cache sorted by the
  //  Next Broadcast dateTime. Update the cache every time.














  public $timeout = 180; // sets a maximum execution time of 3 minutes

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

    $teams = Team::with('shows.schedules.scheduleRecurrenceDetails')->get();

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
        $show->update(['nextBroadcastDateTime' => $nextBroadcastDateTime]);

        $loopCounter++;  // Increment loop counter after each show is processed
      }
    }
    // Trigger the next job when this one is complete
      // Trigger the next job when this one is complete
      UpdateNextScheduledForBroadcast::dispatch();

      Log::info('UpdateNextBroadcastForShows Job completed');
  }

  private function calculateNextBroadcast($show) {
    $nextDateTime = null;
    foreach ($show->schedules as $schedule) {
      // Log the current state of the schedule
      Log::info('Processing schedule', [
          'startTime'      => $schedule->startTime ? $schedule->startTime->toDateTimeString() : 'null',
          'endTime'        => $schedule->endTime ? $schedule->endTime->toDateTimeString() : 'null',
          'recurrenceFlag' => $schedule->recurrenceFlag
      ]);
      // Ensure $schedule->startTime is not null before checking if it's in the future
      if ($schedule->startTime && $schedule->startTime->isFuture()) {
        // If startTime is later than now, this is a candidate
        $nextDateTime = $schedule->startTime;
      } elseif ($schedule->startTime && $schedule->startTime->isPast() && $schedule->endTime->isFuture()) {
        if (!$schedule->recurrenceFlag) {
          // If startTime is past, endTime is future, but not recurring
          $nextDateTime = null;
        } else {
          // Handle recurrence
          $nextDateTime = $this->calculateNextRecurrence($schedule);
        }
      }
      // Find the closest future date
      if ($nextDateTime && (!$nextDateTime->isPast())) {
        return $nextDateTime; // Returns the first qualifying next broadcast date-time
      }
    }

    return $nextDateTime; // This could be null if no valid date is found
  }

  /**
   * Calculates the next recurrence DateTime based on schedule details.
   *
   * @param mixed $schedule
   * @return Carbon|null
   */
  private function calculateNextRecurrence(mixed $schedule): ?Carbon {
    // Extract days of the week from recurrence details
    $daysOfWeek = $schedule->scheduleRecurrenceDetails->days_of_week;
    $currentDateTime = Carbon::now();

    foreach ($daysOfWeek as $day) {
      // Find the next occurrence of the specified day
      $nextDay = Carbon::parse("next $day");

      // If today is the same as the next day and time has passed, adjust to next week
      if ($nextDay->isToday() && $currentDateTime->copy()->setTimeFromTimeString($schedule->startTime)->isPast()) {
        $nextDay->addWeek();
      }

      // Set the time of day from the schedule's start time
      $nextDay->setTimeFromTimeString($schedule->startTime);  // Assuming startTime is a string like '15:30:00'

      if ($nextDay->gt($currentDateTime)) {
        return $nextDay;
      }
    }

    return null; // Return null if no future times are found
  }
}
