<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsRssFeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'url',
        'title',
        'description',
        'language',
        'last_build_date',
        'image_url',
        'link'
    ];

    public function getRouteKeyName() {
        return 'slug';
    }

  public function newsRssFeedItemArchives()
  {
    return $this->hasMany(NewsRssFeedItemArchive::class, 'news_rss_feed_id');
  }

  public function newsRssFeedItemTemps()
  {
    return $this->hasMany(NewsRssFeedItemTemp::class, 'news_rss_feed_id');
  }
}
