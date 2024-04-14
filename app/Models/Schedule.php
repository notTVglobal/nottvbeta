<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model {
  use HasFactory;

  protected $table = 'show_schedule';

  protected $fillable = [
      'content_type',
      'content_id',
      'type', // for display and osd: movie, show, movie trailer, etc.
      'recurrence_flag',
      'recurrence_details_id',
      'broadcast_dates',
      'status', // scheduled, live, completed, cancelled
      'priority', // defaults to 0
      'duration_minutes',
      'start_time',
      'end_time',
      'timezone',
  ];

  protected $casts = [
      'start_time'      => 'datetime',
      'end_time'        => 'datetime',
      'broadcast_dates' => 'json',
    // other casts
  ];

  public function content(): \Illuminate\Database\Eloquent\Relations\MorphTo {
    return $this->morphTo(__FUNCTION__, 'content_type', 'content_id');
  }

  public function scheduleRecurrenceDetails(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(ScheduleRecurrenceDetails::class, 'recurrence_details_id', 'id');
  }

  public function scheduleIndexes(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(SchedulesIndex::class, 'show_schedule_id');
  }

}
