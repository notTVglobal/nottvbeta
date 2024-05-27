<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
      'start_dateTime_utc',
      'end_dateTime_utc',
      'extra_metadata',
  ];

  protected $casts = [
      'start_dateTime'  => 'datetime',
      'end_dateTime'    => 'datetime',
      'start_dateTime_utc'  => 'datetime',
      'end_dateTime_utc'    => 'datetime',
      'broadcast_dates' => 'json',
      'extra_metadata'  => 'json',
    // other casts
  ];

  public function content(): MorphTo {
    return $this->morphTo();
  }

  public function scheduleRecurrenceDetails(): BelongsTo {
    return $this->belongsTo(ScheduleRecurrenceDetails::class, 'recurrence_details_id', 'id');
  }

  public function scheduleIndexes(): HasMany {
    return $this->hasMany(SchedulesIndex::class, 'schedule_id');
  }

}
