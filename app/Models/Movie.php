<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Movie extends Model {

  use HasFactory;

  protected $fillable = [
      'user_id',
      'creative_commons_id',
      'team_id',
      'image_id',
      'name',
      'description',
      'slug',
      'extension',
      'size',
      'file_path',
      'file_url',
      'meta',
      'release_year',
      'www_url',
      'instagram_name',
      'telegram_url',
      'twitter_handle',
      'movie_category_id',
      'movie_category_sub_id',
      'app_setting_id',
      'isBeingEditedByUser_id',
      'logline',
      'video_id',
      'movies_trailer_id',
      'status_id',
      'copyrightYear',
      'releaseDateTime'
  ];

  protected $casts = [
      'meta' => 'json',
  ];

  public function getRouteKeyName() {
    return 'slug';
  }

  public function user() {
    return $this->belongsTo(User::class);
  }

  public function team() {
    return $this->belongsTo(Team::class);
  }

  public function image() {
    return $this->belongsTo(Image::class);
  }

  public function video() {
    return $this->belongsTo(Video::class);
  }

  public function trailers() {
    return $this->hasMany(MovieTrailer::class, 'movie_id');
  }

  public function category() {
    return $this->belongsTo(MovieCategory::class, 'movie_category_id');
  }

  public function subCategory() {
    return $this->belongsTo(MovieCategorySub::class, 'movie_category_sub_id');
  }

  public function appSetting(): BelongsTo {
    return $this->belongsTo(AppSetting::class)->withDefault([
    ]);
  }

  public function status() {
    return $this->belongsTo(MovieStatus::class, 'status_id');
  }

  public function creativeCommons()
  {
    return $this->belongsTo(CreativeCommons::class, 'creative_commons_id');
  }

  public function playlistItems()
  {
    return $this->morphMany(ChannelPlaylistItem::class, 'content');
  }

  // Retrieves the cached category or loads it if not cached
  public function getCachedCategory()
  {
    return Cache::rememberForever('movie_category_' . $this->movie_category_id, function () {
      return $this->category;
    });
  }

  // Retrieves the cached subcategory or loads it if not cached
  public function getCachedSubCategory()
  {
    return Cache::rememberForever('movie_subcategory_' . $this->movie_category_sub_id, function () {
      return $this->subCategory;
    });
  }
}


