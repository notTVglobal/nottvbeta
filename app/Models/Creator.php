<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Creator extends Model {
  use HasFactory;

  protected static function boot(): void {
    parent::boot();

    static::creating(function ($creator) {
      $creator->slug = static::generateUniqueSlug($creator->user->name);

      // Automatically set default settings when a new creator is created
      $defaultSettings = [
          'profile_is_public' => false
      ];
      $creator->settings = array_merge($defaultSettings, $creator->settings ?? []);
    });

    static::updating(function ($creator) {
      if ($creator->isDirty('name')) {
        $creator->slug = static::generateUniqueSlug($creator->user->name, $creator->id);
      }
    });
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
      'user_id',
      'slug',
      'description',
      'status_id',
      'first_time',
      'settings',
      'social_links'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
      'settings' => 'json',
      'social_links' => 'json',
  ];

  /**
   * Get the route key for the model.
   *
   * @return string
   */
  public function getRouteKeyName(): string {
    return 'slug';
  }

  /**
   * Generate a unique slug for the creator.
   *
   * @param string $name
   * @param int|null $excludeId
   * @return string
   */
  public static function generateUniqueSlug(string $name, int $excludeId = null): string {
    $slug = Str::slug($name);
    $originalSlug = $slug;
    $counter = 1;

    while (static::where('slug', $slug)->where('id', '<>', $excludeId)->exists()) {
      $slug = $originalSlug . '-' . $counter;
      $counter++;
    }

    return $slug;
  }

  /**
   * Determine if the creator's profile is public.
   *
   * @return bool
   */
  public function getIsPublicAttribute(): bool {
    return isset($this->settings['profile_is_public']) && $this->settings['profile_is_public'];
  }

  /**
   * Get the user that owns the creator.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(User::class);
  }

  /**
   * The teams that the creator belongs to.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function teams(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
    return $this->belongsToMany(Team::class, 'team_members', 'user_id', 'team_id');
  }

  /**
   * The teams led by the creator.
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function teamsLed(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(Team::class, 'team_leader');
  }

  /**
   * The shows run by the creator.
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function shows(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(Show::class);
  }

  /**
   * Get the status of the creator.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(CreatorStatus::class, 'status_id');
  }

  /**
   * Get the messages received by the creator.
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function receivedMessages(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(CreatorMessage::class, 'recipient_id');
  }
}
