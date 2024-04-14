<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedulesIndex extends Model
{
  protected $table = 'schedules_indexes'; // Ensure the table name is correctly specified

  protected $fillable = [
      'team_id',
      'content_type',
      'content_id',
      'schedule_id',
      'next_broadcast',
  ];

  protected $casts = [
      'next_broadcast' => 'datetime',
  ];

  public function team(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(Team::class, 'team_id');
  }

  public function content(): \Illuminate\Database\Eloquent\Relations\MorphTo {
    return $this->morphTo(__FUNCTION__, 'content_type', 'content_id');
  }

  public function schedule(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(Schedule::class, 'schedule_id');
  }
}
