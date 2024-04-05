<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\Uid\Ulid;

class Video extends Model {
  use SoftDeletes;
  use HasFactory;

  protected static function booted() {
    static::creating(function ($model) {
      $model->ulid = (string) Ulid::generate();
    });
  }

  protected $fillable = [
      'user_id',
      'file_name',
      'extension',
      'size',
      'type',
      'poster',
      'sprite_path',
      'sprite_full_url',
      'live_count',
      'is_live',
      'video_status',
      'category',
      'commons_license',
      'access_level',
      'app_setting_id',
      'cloud_folder',
      'cloud_private_folder',
      'folder',
      'storage_location',
      'temp_path',
      'magnet_link',
      'show_episodes_id',
      'movies_id',
      'movie_trailers_id',
      'is_processing',
      'name',
      'video_url',
      'mist_stream_id',
      'mist_stream_wildcard_id',
      'audio_codec',
      'video_codec',
      'audio_channels',
      'length',
  ];

  protected $hidden = [
      'encryption_key',
      'hash',
      'nft_address'

  ];

  public function mistStream() {
    return $this->belongsTo(MistStream::class, 'mist_stream_id');
  }

  public function mistStreamWildcard() {
    return $this->belongsTo(MistStreamWildcard::class, 'mist_stream_wildcard_id');
  }

  public function newsStory() {
    return $this->hasOne(NewsStory::class);
  }

  public function showEpisode() {
    return $this->hasOne(ShowEpisode::class);
  }

  public function movie() {
    return $this->hasOne(Movie::class);
  }

  public function movieTrailer() {
    return $this->hasOne(MovieTrailer::class);
  }

  public function user() {
    return $this->belongsTo(User::class);
  }

  public function image() {
    return $this->belongsTo(Image::class);
  }

  public function appSetting() {
    return $this->belongsTo(AppSetting::class);
  }

  public function channels() {
    return $this->hasMany(Channel::class);
  }
}
