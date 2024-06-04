<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Creator extends Model {
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
      'user_id',
      'slug',
      'status_id',
      'first_time',
      'settings'
  ];

  protected $casts = [
      'settings' => 'json',
  ];

  protected static function boot() {
    parent::boot();

    static::creating(function ($creator) {
      $creator->slug = static::generateUniqueSlug($creator->user->name);
    });

    static::updating(function ($creator) {
      if ($creator->isDirty('name')) {
        $creator->slug = static::generateUniqueSlug($creator->user->name, $creator->id);
      }
    });

    // Automatically set default settings when a new creator is created
    static::creating(function ($creator) {
      $defaultSettings = [
          'profile_is_public' => false
      ];
      $creator->settings = array_merge($defaultSettings, $creator->settings ?? []);
    });
  }

  public function getRouteKeyName(): string {
    return 'slug';
  }

  public static function generateUniqueSlug($name, $excludeId = null) {
    $slug = Str::slug($name);
    $originalSlug = $slug;
    $counter = 1;

    while (static::where('slug', $slug)->where('id', '<>', $excludeId)->exists()) {
      $slug = $originalSlug . '-' . $counter;
      $counter++;
    }

    return $slug;
  }

  public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(User::class);
  }

  public function teams() {
    return $this->belongsToMany(Team::class, 'team_members', 'user_id', 'team_id');
  }

  // team leader
  public function teamsLed(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(Team::class, 'team_leader');
  }

  // show runner
  public function shows(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(Show::class);
  }

  public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(CreatorStatus::class, 'status_id');
  }

  public function receivedMessages()
  {
    return $this->hasMany(CreatorMessage::class, 'recipient_id');
  }
}
