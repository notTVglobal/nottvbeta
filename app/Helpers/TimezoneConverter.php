<?php

namespace App\Helpers;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class TimezoneConverter
{
  /**
   * Converts a date or dateTime from a user's timezone to UTC.
   *
   * @param string $date
   * @param string $userTimezone The user's timezone.
   *                       Defaults to 'Y-m-d H:i:s'.
   * @return Carbon|null The dateTime converted to UTC.
   * @throws Exception
   */
  public static function convertUserTimeToUtc(string $date, string $userTimezone): ?Carbon
  {

    // Check if $date includes time component; if not, append "00:00:00"
    $dateTime = strlen($date) > 10 ? $date : "{$date} 00:00:00";

    // Ensure the timezone is valid to prevent "timezone could not be found" errors
    if (!in_array($userTimezone, timezone_identifiers_list())) {
      throw new Exception("Invalid timezone: {$userTimezone}");
    }

    // Create a Carbon instance in the user's timezone with the provided date/time
    try {
      $dateTimeInUserTimezone = Carbon::createFromFormat('Y-m-d H:i:s', $dateTime, $userTimezone);
    } catch (\Exception $e) {
      // Catch format or other Carbon-related exceptions
      throw new Exception("Invalid date/time format or timezone: " . $e->getMessage());
    }

    // Convert the datetime to UTC and return
    return $dateTimeInUserTimezone->setTimezone('UTC');
  }



}
