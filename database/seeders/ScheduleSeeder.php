<?php

namespace Database\Seeders;

use App\Helpers\ScheduleHelpers;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class ScheduleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    $startDateTime = Carbon::now()->subHours(6);
    $currentStartDateTime = ScheduleHelpers::roundToNearestHalfHour($startDateTime);

    // List of Canadian timezones
    $canadianTimezones = [
        'America/Vancouver', // Pacific Time
        'America/Edmonton',  // Mountain Time
        'America/Winnipeg',  // Central Time
        'America/Toronto',   // Eastern Time
        'America/Halifax',   // Atlantic Time
        'America/St_Johns',  // Newfoundland Time
    ];

    for ($i = 0; $i < 144; $i++) {
      $schedule = Schedule::factory()->make();

      // Randomly select a Canadian timezone
      $randomTimezone = $canadianTimezones[array_rand($canadianTimezones)];
      $schedule->timezone = $randomTimezone;

      // Set start and end times in Server's UTC
      $schedule->start_dateTime_utc = $currentStartDateTime->copy()->setTimezone('UTC');
      $schedule->end_dateTime_utc = $currentStartDateTime->copy()->addMinutes($schedule->duration_minutes)->setTimezone('UTC');

      // Convert start and end times to the selected timezone
      $schedule->start_dateTime = Carbon::parse($schedule->start_dateTime_utc, 'UTC')->setTimezone($randomTimezone)->toDateTimeString();
      $schedule->end_dateTime = Carbon::parse($schedule->end_dateTime_utc, 'UTC')->setTimezone($randomTimezone)->toDateTimeString();

      $schedule->recurrence_flag = 0;

      // Save the schedule
      $schedule->save();

      // Update the current start time for the next schedule item
      $currentStartDateTime = ScheduleHelpers::roundToNearestHalfHour(Carbon::parse($schedule->end_dateTime_utc, 'UTC'));
    }
  }

}
