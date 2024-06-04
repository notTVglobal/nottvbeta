<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class NewsStory extends Model {
  use SoftDeletes;
  use HasFactory;

  protected $fillable = [
      'user_id',
      'news_person_id',
      'title',
      'author',
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
      'meta',
      'published_at',
      'video_id',
      'content_json'
  ];

  protected $casts = [
      'meta' => 'json',
      'content_json' => 'json'
  ];

  public function getRouteKeyName() {
    return 'slug';
  }

  // Define a scope to filter published news stories
  public function scopePublished($query)
  {
    return $query->where('status', 6);
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

  public function newsPerson()
  {
    return $this->belongsTo(NewsPerson::class);
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

  public function playlistItems()
  {
    return $this->morphMany(ChannelPlaylistItem::class, 'content');
  }

  public function schedules(): \Illuminate\Database\Eloquent\Relations\MorphMany {
    return $this->morphMany(Schedule::class, 'content');
  }

  public function scheduleIndexes(): \Illuminate\Database\Eloquent\Relations\MorphMany {
    return $this->morphMany(SchedulesIndex::class, 'content');
  }

  // Retrieves the cached category or loads it if not cached
  public function getCachedStatus()
  {
    return Cache::rememberForever('newsStatus_' . $this->status, function () {
      return $this->newsStatus ? $this->newsStatus->only(['id', 'name']) : null;
    });
  }


  // Retrieves the cached category or loads it if not cached
  public function getCachedCategory()
  {
    return Cache::rememberForever('newsCategory_' . $this->news_category_id, function () {
      return $this->newsCategory ? $this->newsCategory->only(['id', 'slug', 'name', 'description']) : null;
    });
  }

  // Retrieves the cached subcategory or loads it if not cached
  public function getCachedSubCategory()
  {
    return Cache::rememberForever('newsSubcategory_' . $this->news_category_sub_id, function () {
      return $this->newsCategorySub ? $this->newsCategorySub->only(['id', 'slug', 'name', 'description']) : null;
    });
  }

  // Retrieves the cached city or loads it if not cached
  public function getCachedCity()
  {
    return Cache::rememberForever('newsCity_' . $this->city_id, function () {
      return $this->city ? $this->city->only(['id', 'slug', 'name', 'description']) : null;
    });
  }

  // Retrieves the cached province or loads it if not cached
  public function getCachedProvince()
  {
    return Cache::rememberForever('newsProvince_' . $this->province_id, function () {
      return $this->province ? $this->province->only(['id', 'slug', 'name', 'description']) : null;
    });
  }

  // Retrieves the cached federal electoral district or loads it if not cached
  public function getCachedFederalElectoralDistrict()
  {
    return Cache::rememberForever('newsFederalElectoralDistrict_' . $this->news_federal_electoral_district_id, function () {
      return $this->federalElectoralDistrict ? $this->federalElectoralDistrict->only(['id', 'slug', 'name', 'description']) : null;
    });
  }

  // Retrieves the cached subnational electoral district or loads it if not cached
  public function getCachedSubnationalElectoralDistrict()
  {
    return Cache::rememberForever('newsSubnationalElectoralDistrict_' . $this->news_subnational_electoral_district_id, function () {
      return $this->subnationalElectoralDistrict ? $this->subnationalElectoralDistrict->only(['id', 'slug', 'name', 'description']) : null;
    });
  }

}
