<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelPlaylist extends Model {
  use HasFactory;

  protected $fillable = [
      'channel_id',
      'name',
      'description',
      'url',
      'type',
      'status',
      'start_dateTime',
      'end_dateTime',
      'priority',
      'repeat_mode',
      'next_playlist_id'
  ];

  public function channel(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo('App\Models\Channel', 'channel_playlist_id');
  }

  public function items(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(ChannelPlaylistItem::class, 'playlist_id')->orderBy('order');
  }

  public function nextPlaylist(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(ChannelPlaylist::class, 'next_playlist_id');
  }

  public function previousPlaylist(): \Illuminate\Database\Eloquent\Relations\HasOne {
    return $this->hasOne(ChannelPlaylist::class, 'next_playlist_id');
  }

}
