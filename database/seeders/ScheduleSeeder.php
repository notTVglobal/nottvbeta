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

    $startDate = Carbon::now()->subHours(6);
    $currentStartTime = ScheduleHelpers::roundToNearestHalfHour($startDate);

    for ($i = 0; $i < 144; $i++) {
      $schedule = Schedule::factory()->make();

      // Set the start time and calculate the end time based on the duration
      $schedule->start_dateTime = $currentStartTime;
      $schedule->end_dateTime = $currentStartTime->copy()->addMinutes($schedule->duration_minutes);
      $schedule->timezone = 'UTC';
      $schedule->recurrence_flag = 0;

      // Save the schedule
      $schedule->save();

      // Update the current start time for the next schedule item
      $currentStartTime = ScheduleHelpers::roundToNearestHalfHour($schedule->end_dateTime);
    }
  }

}
