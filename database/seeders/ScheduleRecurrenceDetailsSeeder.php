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
    $startDateTimeUtc = Carbon::now()->subHours(6); // Now is server time and server time is UTC
    $currentStartDateTimeUtc = ScheduleHelpers::roundToNearestHalfHour($startDateTimeUtc);

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
      $schedule->start_dateTime_utc = $currentStartDateTimeUtc->copy()->setTimezone('UTC');
      $schedule->end_dateTime_utc = $currentStartDateTimeUtc->copy()->addMonths(3)->addMinutes($schedule->duration_minutes)->setTimezone('UTC');

      // Convert start and end times to the selected timezone using Carbon
      $schedule->start_dateTime = Carbon::parse($schedule->start_dateTime_utc, 'UTC')->setTimezone($randomTimezone)->toDateTimeString();
      $schedule->end_dateTime = Carbon::parse($schedule->end_dateTime_utc, 'UTC')->setTimezone($randomTimezone)->toDateTimeString();

      $schedule->recurrence_flag = 1;

      // Save the schedule
      $schedule->save();

      // Create recurrence details
      $daysOfWeek = collect(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])
          ->random(rand(1, 7))
          ->values()
          ->toArray();

      // Calculate the end date 3 months in the future that falls on one of the days_of_week
//      $endDate = ScheduleHelpers::getValidEndDate(Carbon::now()->addMonths(3), $daysOfWeek);

      $scheduleRecurrenceDetails = ScheduleRecurrenceDetails::factory()
          ->forSchedule($schedule)
          ->create([
              'days_of_week' => json_encode($daysOfWeek),
          ]);

      // Update the schedule with the recurrence details ID
      $schedule->recurrence_details_id = $scheduleRecurrenceDetails->id;
      $schedule->save();

      // Update the current start time for the next schedule item
      $currentStartDateTimeUtc = ScheduleHelpers::roundToNearestHalfHour(Carbon::parse($schedule->start_dateTime_utc->addMinutes($schedule->duration_minutes), 'UTC'));
    }
  }
}
