<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creator extends Model {
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
      'user_id',
      'profile_is_public'
  ];

  protected $casts = [
      'profile_is_public' => 'boolean',
  ];

  public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(User::class);
  }

  // team leader
  public function teamsLed(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(Team::class, 'team_leader');
  }

  // show runner
  public function show(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(Show::class);
  }

  public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(CreatorStatus::class, 'status_id');
  }
}
