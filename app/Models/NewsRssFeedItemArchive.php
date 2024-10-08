<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsRssFeedItemArchive extends Model
{
  use HasFactory;

  protected $fillable = [
      'news_rss_feed_id',
      'feedName',
      'title',
      'description',
      'link',
      'pubDate',
      'image_url',
      'image_id',
      'extra_metadata',
      'saved_by_user_id'
  ];

  /**
   * Get the news RSS feed that the archived item belongs to.
   */
  public function newsRssFeed()
  {
    return $this->belongsTo(NewsRssFeed::class, 'news_rss_feed_id');
  }

  // If you have an Image model relationship
  public function image()
  {
    return $this->belongsTo(Image::class, 'image_id');
  }

  public function savedBy()
  {
    return $this->belongsTo(User::class, 'saved_by_user_id');
  }
}
