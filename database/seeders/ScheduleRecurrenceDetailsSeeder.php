<?php

namespace Database\Seeders;

use App\Helpers\ScheduleHelpers;
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
  public function run(): void
  {
    $startDateTime = Carbon::now()->subHours(6);
    $currentStartDateTime = ScheduleHelpers::roundToNearestHalfHour($startDateTime);

    for ($i = 0; $i < 144; $i++) {
      $schedule = Schedule::factory()->make();

      // Set the start time and calculate the end time based on the duration
      $schedule->start_dateTime = $currentStartDateTime;
      $schedule->end_dateTime = $currentStartDateTime->copy()->addMinutes($schedule->duration_minutes);
      $schedule->timezone = 'UTC';
      $schedule->recurrence_flag = 1;

      // Save the schedule
      $schedule->save();

      // Create recurrence details
      $daysOfWeek = collect(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])
          ->random(rand(1, 7))
          ->values()
          ->toArray();

      // Calculate the end date 3 months in the future that falls on one of the days_of_week
      $endDate = ScheduleHelpers::getValidEndDate(Carbon::now()->addMonths(3), $daysOfWeek);

      $scheduleRecurrenceDetails = ScheduleRecurrenceDetails::factory()
          ->forSchedule($schedule)
          ->create([
              'days_of_week' => json_encode($daysOfWeek),
              'end_dateTime' => $endDate,
          ]);

      // Update the schedule with the recurrence details ID
      $schedule->recurrence_details_id = $scheduleRecurrenceDetails->id;
      $schedule->save();

      // Update the current start time for the next schedule item
      $currentStartDateTime = ScheduleHelpers::roundToNearestHalfHour($schedule->end_dateTime);
    }
  }
}
