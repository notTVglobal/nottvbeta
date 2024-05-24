<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleRecurrenceDetails extends Model
{
  use HasFactory;

  protected $table = 'schedule_recurrence_details';

  protected $fillable = [
      'frequency', // varchar(255) Frequency of recurrence (e.g., daily, weekly, monthly)
      'days_of_week', // JSON array of days for weekly recurrences. e.g., ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"] for weekdays
      'duration_minutes', // int Duration of the recurrence in minutes
      'start_dateTime', // Optional start date for the recurrence period
      'end_dateTime', // Optional end date for the recurrence period
      'timezone',
  ];

  protected $casts = [
      'days_of_week' => 'array',
      'start_dateTime' => 'datetime',
      'end_dateTime' => 'datetime',
    // other casts
  ];

  public function schedule(): \Illuminate\Database\Eloquent\Relations\HasOne {
    return $this->hasOne(Schedule::class);
  }

}
