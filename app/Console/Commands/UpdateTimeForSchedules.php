<?php

namespace App\Console\Commands;

use App\Models\Schedule;
use App\Models\ScheduleRecurrenceDetails;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateTimeForSchedules extends Command
{

  protected $signature = 'update:schedule-time';
  protected $description = 'Update start_dateTime_utc and end_dateTime_utc fields for Schedule and ScheduleRecurrenceDetails';

    /**
     * Execute the console command.
     */
  public function handle(): void {
    $this->updateScheduleUtcDateTime();
    $this->updateScheduleRecurrenceDetailsUtcDateTime();

    $this->info('UTC DateTime fields updated successfully.');
  }

  private function updateScheduleUtcDateTime()
  {
    $schedules = Schedule::all();
    $first = true;

    foreach ($schedules as $schedule) {
      if ($schedule->start_dateTime && $schedule->end_dateTime && $schedule->timezone) {
        $startDateTime = Carbon::parse($schedule->start_dateTime, $schedule->timezone);
        $endDateTime = Carbon::parse($schedule->end_dateTime, $schedule->timezone);

        if ($first) {
          Log::debug('Original Start DateTime: ' . $startDateTime->toDateTimeString() . ' ' . $schedule->timezone);
          Log::debug('Original End DateTime: ' . $endDateTime->toDateTimeString() . ' ' . $schedule->timezone);
        }

        $schedule->start_dateTime_utc = $startDateTime->copy()->setTimezone('UTC')->toDateTimeString();
        $schedule->end_dateTime_utc = $endDateTime->copy()->setTimezone('UTC')->toDateTimeString();

        if ($first) {
          Log::debug('Converted Start DateTime UTC: ' . $schedule->start_dateTime_utc);
          Log::debug('Converted End DateTime UTC: ' . $schedule->end_dateTime_utc);
          $first = false;
        }

        $schedule->save();
      }
    }
  }

  private function updateScheduleRecurrenceDetailsUtcDateTime()
  {
    $scheduleRecurrenceDetails = ScheduleRecurrenceDetails::all();
    $first = true;

    foreach ($scheduleRecurrenceDetails as $detail) {
      if ($detail->start_dateTime && $detail->end_dateTime && $detail->timezone) {
        $startDateTime = Carbon::parse($detail->start_dateTime, $detail->timezone);
        $endDateTime = Carbon::parse($detail->end_dateTime, $detail->timezone);

        if ($first) {
          Log::debug('Original Start DateTime: ' . $startDateTime->toDateTimeString() . ' ' . $detail->timezone);
          Log::debug('Original End DateTime: ' . $endDateTime->toDateTimeString() . ' ' . $detail->timezone);
        }

        $detail->start_dateTime_utc = $startDateTime->copy()->setTimezone('UTC')->toDateTimeString();
        $detail->end_dateTime_utc = $endDateTime->copy()->setTimezone('UTC')->toDateTimeString();

        if ($first) {
          Log::debug('Converted Start DateTime UTC: ' . $detail->start_dateTime_utc);
          Log::debug('Converted End DateTime UTC: ' . $detail->end_dateTime_utc);
          $first = false;
        }

        $detail->save();
      }
    }
  }
}
