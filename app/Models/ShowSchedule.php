<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowSchedule extends Model {
  use HasFactory;

  protected $table = 'show_schedule';

  protected $fillable = [
      'content_type',
      'content_id',
      'type', // for display and osd: movie, show, movie trailer, etc.
      'recurrence_flag',
      'recurrence_details_id',
      'status', // scheduled, live, completed, cancelled
      'priority', // defaults to 0
      'duration_minutes',
      'start_time',
      'end_time',
      'timezone',
  ];

  protected $casts = [
      'start_time' => 'datetime',
      'end_time'   => 'datetime',
    // other casts
  ];

  public function content(): \Illuminate\Database\Eloquent\Relations\MorphTo {
    return $this->morphTo(__FUNCTION__, 'content_type', 'content_id');
  }

  public function showScheduleRecurrenceDetails(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(ShowScheduleRecurrenceDetails::class, 'recurrence_details_id', 'id');
  }

}
