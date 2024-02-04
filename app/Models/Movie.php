<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model {
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

  public function trailer() {
    return $this->HasOne(MovieTrailer::class);
  }

  public function category() {
    return $this->belongsTo(MovieCategory::class, 'movie_category_id');
  }

  public function subCategory() {
    return $this->belongsTo(MovieCategorySub::class, 'movie_category_sub_id');
  }


//  public function category(): BelongsTo {
//    return $this->belongsTo(MovieCategory::class)->withDefault([
//        'name'        => 'category',
//        'description' => 'category description'
//    ]);
//  }
//
//  public function subCategory(): BelongsTo {
//    return $this->belongsTo(MovieCategorySub::class)->withDefault([
//        'name'        => 'sub category',
//        'description' => 'sub category description'
//    ]);
//  }

  public function appSetting(): BelongsTo {
    return $this->belongsTo(AppSetting::class)->withDefault([
//            'cdn_endpoint' => 'https://development-nottv.sfo3.cdn.digitaloceanspaces.com',
    ]);
  }

  public function status() {
    return $this->belongsTo(MovieStatus::class, 'status_id');
  }

  public function creativeCommons()
  {
    return $this->belongsTo(CreativeCommons::class, 'creative_commons_id');
  }
}


