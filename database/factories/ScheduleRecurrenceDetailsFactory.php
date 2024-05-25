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
    return [
        'frequency' => 'weekly',
        'days_of_week' => [], // Placeholder for `days_of_week`
        'duration_minutes' => $this->faker->numberBetween(30, 180),
        'start_dateTime' => Carbon::now(),
        'end_dateTime' => Carbon::now()->addMinutes($this->faker->numberBetween(30, 180)),
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
    return $this->state(function (array $attributes) use ($schedule) {
      return [
          'duration_minutes' => $schedule->duration_minutes,
          'start_dateTime' => $schedule->start_dateTime,
          'end_dateTime' => $schedule->end_dateTime,
          'timezone' => $schedule->timezone,
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
