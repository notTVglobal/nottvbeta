<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model {
  use HasFactory;

  protected $fillable = [
      'name',
      'channel_source_id',
      'channel_playlist_id',
      'mist_stream_id',
      'playback_priority_type'
  ];

  public function messages() {
    return $this->hasMany('App\Models\ChatMessage');
  }

  public function user() {
    return $this->hasMany('App\Models\User', 'id', 'user_id');
//        return $this->belongsTo(User::class);
  }

  public function mistStream() {
    return $this->belongsTo(MistStream::class, 'mist_stream_id');
  }

  public function channelPlaylist() {
    return $this->belongsTo(ChannelPlaylist::class, 'channel_playlist_id');
  }

  public function channelExternalSource() {
    return $this->belongsTo(ChannelExternalSource::class);
  }

  public function getPriorityPlaybackAttribute() {
    // The playback_priority_type column is intended
    // to indicate which of the three associated entities
    // (MistStream, ChannelPlaylist, ChannelSource) should
    // be given priority for playback on a channel. This
    // approach offers flexibility, allowing us to dynamically
    // switch the playback source without needing to alter
    // the structure of the database or application logic
    // significantly.
    switch ($this->playback_priority_type) {
      case 'MistStream':
        return $this->mistStream;
      case 'ChannelPlaylist':
        return $this->channelPlaylist;
      case 'ChannelSource': // Assuming you have a similar method for ChannelSource
        return $this->channelSource;
      default:
        return null;
    }
  }


}
