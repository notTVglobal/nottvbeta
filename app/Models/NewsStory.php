<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsStory extends Model {
  use SoftDeletes;
  use HasFactory;

  protected $fillable = [
      'user_id',
      'title',
      'content',
      'slug',
      'news_category_id',
      'news_category_sub_id',
      'content',
      'city_id',
      'province_id',
      'country_id',
      'news_federal_electoral_district_id',
      'news_subnational_electoral_district_id',
      'image_id',
      'status',
      'published_at',
      'video_id',
  ];

  public function getRouteKeyName() {
    return 'slug';
  }

  public function newsCategory() {
    return $this->belongsTo(NewsCategory::class);
  }

  public function newsCategorySub() {
    return $this->belongsTo(NewsCategorySub::class);
  }

  public function newsStatus() {
    // keep this relationship name newsStatus not status
    // status doesn't work properly.
    return $this->belongsTo(NewsStatus::class, 'status');
  }

  public function image() {
    return $this->belongsTo(Image::class);
  }

  public function video() {
    return $this->belongsTo(Video::class);
  }

  public function user() {
    return $this->belongsTo(User::class);
  }

  public function city() {
    return $this->belongsTo(NewsCity::class);
  }

  public function province() {
    return $this->belongsTo(NewsProvince::class);
  }

  public function federalElectoralDistrict() {
    return $this->belongsTo(NewsFederalElectoralDistrict::class, 'news_federal_electoral_district_id');
  }

  public function subnationalElectoralDistrict() {
    return $this->belongsTo(NewsSubnationalElectoralDistrict::class, 'news_subnational_electoral_district_id');
  }

}
