<?php

namespace App\Jobs;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateBroadcastDatesOnSchedules implements ShouldQueue
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
   * @throws \Exception
   */

  public function handle(): void {
    $schedule = $this->schedule;

    if (!$schedule) {
      Log::warning('No schedule found', ['scheduleId' => $this->schedule->id]);
      return;
    }
    // Fetch the newest schedule for the specified content type and ID
//    $schedule = Schedule::where('content_type', $this->contentType)
//        ->where('content_id', $this->contentId)
//        ->latest('created_at')
//        ->first();

    if (!$schedule) {
      Log::warning('No schedule found for the specified content type and ID', [
          'contentType' => $schedule->contentType,
          'contentId' => $schedule->contentId
      ]);
      return;
    }

    // Initialize arrays for storing calculated dates
    $broadcastDates = [];
    $oneTimeDates = [];
    $recurrentDates = [];

    // Convert schedule times from local to UTC
    $startTimeUTC = Carbon::createFromFormat('Y-m-d H:i:s', $schedule->start_time, $schedule->timezone)
        ->setTimezone('UTC');
    $endTimeUTC = Carbon::createFromFormat('Y-m-d H:i:s', $schedule->end_time, $schedule->timezone)
        ->setTimezone('UTC');

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

    // Format for storage
    $broadcastDates = [
        'modelType' => $schedule->contentType,
        'modelId' => $schedule->contentId,
        'priority' => $schedule->priority, // Assuming the same priority for all schedules for simplicity
        'timezone' => 'UTC',
        'broadcastDates' => $allDates
    ];

    // Update the compiled dates into the database
    $schedule->broadcast_dates = json_encode($broadcastDates);
    $schedule->save();

    Log::info('Updated broadcast dates', ['scheduleId' => $schedule->id, 'dates' => $broadcastDates]);
  }


  /**
   * @throws \Exception
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
    } catch (\Exception $e) {
      Log::error('Failed to process recurrent schedule', [
          'scheduleId' => $schedule->id,
          'error' => $e->getMessage(),
          'trace' => $e->getTraceAsString()
      ]);
      // Rethrow or handle the exception as needed
      throw new \Exception("Failed to process schedule: " . $e->getMessage());
    }
  }

}
