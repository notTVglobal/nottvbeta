<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelPlaylistItem extends Model {

  use HasFactory;

  protected $fillable = [
      'playlist_id',
      'content_type',
      'content_id',
      'order',
      'media_type',
      'source_path',
      'source_type',
      'is_live',
      'is_scheduled',
      'current_viewers_count',
      'max_viewers_count',
      'additional_sources',
      'custom_playback_options',
      'metadata',
      'has_played',
      'start_dateTime',
      'end_dateTime',
      'duration_minutes'
  ];

  protected $casts = [
      'additional_sources'      => 'json',
      'custom_playback_options' => 'json',
      'metadata'                => 'json',
      'is_live'                 => 'boolean',
      'is_scheduled'            => 'boolean',
  ];


  // Other model properties and methods...

  public function playlist() {
    return $this->belongsTo(ChannelPlaylist::class, 'playlist_id');
  }

  public function content() {
    return $this->morphTo();
  }
}
