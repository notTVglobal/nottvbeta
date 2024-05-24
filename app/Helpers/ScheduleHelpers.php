<?php

namespace App\Helpers;

use Carbon\Carbon;

class ScheduleHelpers
{
  /**
   * Round the given Carbon instance to the nearest hour or half-hour.
   *
   * @param Carbon $dateTime
   * @return Carbon
   */
  public static function roundToNearestHalfHour(Carbon $dateTime): Carbon {
    $minutes = $dateTime->minute;
    $seconds = 0; // Ensure seconds are set to zero
    $roundedMinutes = 0;

    if ($minutes < 15) {
      $roundedMinutes = 0;
    } elseif ($minutes < 45) {
      $roundedMinutes = 30;
    } else {
      $roundedMinutes = 0;
      $dateTime->addHour();
    }

    return $dateTime->copy()->minute($roundedMinutes)->second($seconds);
  }

  /**
   * Get a valid end date that falls on one of the specified days of the week.
   *
   * @param Carbon $baseDate
   * @param array $daysOfWeek
   * @return Carbon
   */
  public static function getValidEndDate(Carbon $baseDate, array $daysOfWeek): Carbon
  {
    while (!in_array($baseDate->format('l'), $daysOfWeek)) {
      $baseDate->addDay();
    }

    return $baseDate;
  }
}
