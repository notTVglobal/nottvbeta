<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model {
  use HasFactory;

//  protected $table = 'show_schedule';

  protected $fillable = [
      'content_type',
      'content_id',
      'frequency',
      'type', // for display and osd: movie, show, movie trailer, etc.
      'recurrence_flag',
      'recurrence_details_id',
      'broadcast_dates',
      'status', // scheduled, live, completed, cancelled
      'priority', // defaults to 0
      'duration_minutes',
      'start_dateTime',
      'end_dateTime',
      'timezone',
      'extra_metadata',
  ];

  protected $casts = [
      'start_dateTime'  => 'datetime',
      'end_dateTime'    => 'datetime',
      'broadcast_dates' => 'json',
      'extra_metadata'  => 'json',
    // other casts
  ];

  public function content(): \Illuminate\Database\Eloquent\Relations\MorphTo {
    return $this->morphTo('content', 'content_type', 'content_id');
  }

  public function scheduleRecurrenceDetails(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(ScheduleRecurrenceDetails::class, 'recurrence_details_id', 'id');
  }

  public function scheduleIndexes(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(SchedulesIndex::class, 'schedule_id');
  }

}
