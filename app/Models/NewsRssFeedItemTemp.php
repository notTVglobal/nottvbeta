<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsRssFeedItemTemp extends Model
{
  use HasFactory;

  protected $fillable = [
      'news_rss_feed_id',
      'title',
      'description',
      'link',
      'pubDate',
      'image_url',
      'is_saved',
      'extra_metadata',
      'saved_by_user_id'
  ];

//  protected $attributes = [
//      'title' => 'Default Title', // Default value for title
//      'description' => 'Default Description', // Default value for description
//      'link' => 'Default Link', // Default value for link
//      'pubDate' => 'Default PubDate', // Default value for pubDate
//  ];

  /**
   * Get the news RSS feed that the archived item belongs to.
   */
  public function newsRssFeed()
  {
    return $this->belongsTo(NewsRssFeed::class, 'news_rss_feed_id');
  }

  public function savedBy()
  {
    return $this->belongsTo(User::class, 'saved_by_user_id');
  }

}
