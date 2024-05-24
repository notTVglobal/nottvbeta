<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\ScheduleRecurrenceDetails;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleRecurrenceDetailsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(): void {
    $startDateTime = Carbon::now()->subHours(6);
    $currentStartDateTime = $startDateTime;

    for ($i = 0; $i < 144; $i++) {
      $schedule = Schedule::factory()->make();

      // Set the start time and calculate the end time based on the duration
      $schedule->start_dateTime = $currentStartDateTime;
      $schedule->end_dateTime = $currentStartDateTime->copy()->addMinutes($schedule->duration_minutes);
      $schedule->timezone = 'UTC';
      $schedule->recurrence_flag = 1;

      // Save the schedule
      $schedule->save();

      // Create recurrence details using the custom state
      $scheduleRecurrenceDetails = ScheduleRecurrenceDetails::factory()
          ->forSchedule($schedule)
          ->create();

      // Save the recurrence details
      $scheduleRecurrenceDetails->save();

      // Update the schedule with the recurrence details ID
      $schedule->recurrence_details_id = $scheduleRecurrenceDetails->id;
      $schedule->save();

      // Update the current start time for the next schedule item
      $currentStartDateTime = $schedule->end_dateTime;
    }
  }
}
