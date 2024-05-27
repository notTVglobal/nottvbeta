<?php

namespace Database\Factories;

use App\Helpers\ScheduleHelpers;
use App\Models\Schedule;
use App\Models\ScheduleRecurrenceDetails;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ScheduleRecurrenceDetails>
 */
class ScheduleRecurrenceDetailsFactory extends Factory
{
  protected $model = ScheduleRecurrenceDetails::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {

    $duration_minutes = $this->faker->numberBetween(30, 180);

    return [
        'frequency' => 'weekly',
        'days_of_week' => [], // Placeholder for `days_of_week`
        'duration_minutes' => $duration_minutes,
        'start_dateTime' => Carbon::now(),
        'end_dateTime' => Carbon::now()->addMinutes($duration_minutes),
        'timezone' => 'UTC',
    ];
  }

  /**
   * Custom state for setting dates based on Schedule instance.
   *
   * @param Schedule $schedule
   * @return $this
   */
  public function forSchedule(Schedule $schedule): static
  {
    return $this->state(function () use ($schedule) {
      return [
          'duration_minutes' => $schedule->duration_minutes,
          'start_dateTime' => $schedule->start_dateTime,
          'end_dateTime' => $schedule->end_dateTime,
          'timezone' => $schedule->timezone,
          'start_dateTime_utc' => $schedule->start_dateTime_utc,
          'end_dateTime_utc' => $schedule->end_dateTime_utc,
      ];
    });
  }

//
//  /**
//   * Get a valid datetime that falls on one of the specified days of the week.
//   *
//   * @param array $daysOfWeek
//   * @param Carbon|null $baseDate
//   * @return Carbon
//   */
//  private function getValidDateTime(array $daysOfWeek, Carbon $baseDate = null): Carbon
//  {
//    $date = $baseDate ?? Carbon::now();
//
//    while (!in_array($date->format('l'), $daysOfWeek)) {
//      $date->addDay();
//    }
//
//    return ScheduleHelpers::roundToNearestHalfHour($date);
//  }
}
