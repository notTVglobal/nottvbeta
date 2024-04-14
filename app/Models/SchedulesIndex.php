<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedulesIndex extends Model
{
  protected $table = 'schedule_index'; // Ensure the table name is correctly specified

  protected $fillable = [
      'team_id',
      'show_id',
      'schedule_id',
      'next_broadcast'
  ];

  protected $casts = [
      'next_broadcast' => 'datetime',
  ];

  public function team(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(Team::class, 'team_id');
  }

  public function show(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(Show::class, 'show_id');
  }

  public function schedule(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(Schedule::class, 'show_schedule_id');
  }
}
