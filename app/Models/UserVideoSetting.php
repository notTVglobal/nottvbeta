<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVideoSetting extends Model {

  protected $fillable = [
      'user_id',
      'skip_first_play_video',
      'content_type',
      'content_id',
      'last_playback_position',
      'volume_setting',
      'playback_speed',
      'additional_settings'
  ];

  /**
   * Get the content that is associated with this video setting.
   */
  public function content(): \Illuminate\Database\Eloquent\Relations\MorphTo {
    return $this->morphTo();
  }

  /**
   * Get the user that owns the video settings.
   */
  public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(User::class);
  }
}
