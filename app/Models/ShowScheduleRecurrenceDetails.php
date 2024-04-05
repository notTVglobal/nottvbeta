<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowScheduleRecurrenceDetails extends Model
{

  protected $table = 'show_schedule_recurrence_details';

  protected $fillable = [
      'frequency', // varchar(255) Frequency of recurrence (e.g., daily, weekly, monthly)
      'days_of_week', // JSON array of days for weekly recurrences. e.g., ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"] for weekdays
      'duration_minutes', // int Duration of the recurrence in minutes
      'start_time', // Start time of the recurrence
      'start_date', // Optional start date for the recurrence period
      'end_date', // Optional end date for the recurrence period
      'timezone',
      'monday',
      'tuesday',
      'wednesday',
      'thursday',
      'friday',
      'saturday',
      'sunday',
  ];

  protected $casts = [
      'days_of_week' => 'array',
      'start_date' => 'date',
      'end_date' => 'date',
      'monday' => 'boolean',
      'tuesday' => 'boolean',
      'wednesday' => 'boolean',
      'thursday' => 'boolean',
      'friday' => 'boolean',
      'saturday' => 'boolean',
      'sunday' => 'boolean',
    // other casts
  ];

  public function showSchedule()
  {
    return $this->hasOne(ShowSchedule::class);
  }

  // Custom accessor for 'start_time' to handle it manually or convert to Carbon
  public function getStartTimeAttribute($value)
  {
    // Optionally convert to Carbon instance if needed, or handle as string
    return $value; // As is, or convert/format as needed
  }

  // Custom mutator to ensure 'start_time' is stored correctly if you're manipulating it as Carbon
  public function setStartTimeAttribute($value): void {
    if ($value instanceof Carbon) {
      $this->attributes['start_time'] = $value->format('H:i:s');
    } else {
      $this->attributes['start_time'] = $value;
    }
  }
}
