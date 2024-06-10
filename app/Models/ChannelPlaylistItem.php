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
      'current_viewers_count',
      'max_viewers_count',
      'additional_sources',
      'custom_playback_options',
      'metadata',
      'has_played'
  ];

  protected $casts = [
      'additional_sources'      => 'json',
      'custom_playback_options' => 'json',
      'metadata'                => 'json',
  ];


  // Other model properties and methods...

  public function playlist() {
    return $this->belongsTo(ChannelPlaylist::class, 'playlist_id');
  }

  public function content() {
    return $this->morphTo();
  }
}
