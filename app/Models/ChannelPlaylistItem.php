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
      'custom_playback_options',
      'metadata'
  ];

  // Other model properties and methods...

  public function playlist()
  {
    return $this->belongsTo(ChannelPlaylist::class, 'playlist_id');
  }

  public function content() {
    return $this->morphTo();
  }
}
