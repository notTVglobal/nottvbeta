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
      'start_time',
      'end_time',
      'priority',
      'repeat_mode'
  ];

  public function channel() {
    return $this->belongsTo('App\Models\Channel');
  }

  public function items()
  {
    return $this->hasMany(ChannelPlaylistItem::class, 'playlist_id')->orderBy('order');
  }


}
