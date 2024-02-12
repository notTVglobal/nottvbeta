<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model {
  use HasFactory;

  protected $table = 'app_settings';

  protected $fillable = [
      'default_country',
      'cdn_endpoint',
      'cloud_folder',
      'first_play_video_source',
      'first_play_video_source_type',
      'first_play_video_name',
      'first_play_channel_id'
  ];


  // TODO: Implement a new AppSetting Table Key-Value pair technique with Caching.
  // TODO: See the documentation record for how to implement this Key-Value, Caching strategy.
  /**
   * Retrieve the value of mist_server_api_url from the first record.
   *
   * @return string|null
   */
  public static function getMistServerApiUrl(): ?string {
    // Assuming 'mist_server_api_url' is the column name
    $setting = self::query()->first(['mist_server_api_url']);

    return $setting?->mist_server_api_url;
  }


  public function image() {
    return $this->hasMany(Image::class);
  }

  public function show() {
    return $this->hasMany(Show::class);
  }

  public function movie() {
    return $this->hasMany(Movie::class);
  }

  public function team() {
    return $this->hasMany(Team::class);
  }

  public function country() {
    return $this->belongsTo(NewsCountry::class);
  }

  public function channel() {
    return $this->belongsTo(Channel::class)->withDefault([
        'name' => 'channel name',
    ]);
  }
}
