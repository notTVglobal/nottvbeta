<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherContent extends Model {
  use HasFactory;

  protected $fillable = [
      'title',
      'slug',
      'description',
      'type',
      'duration',
      'active',
      'show_category_id',
      'show_category_sub_id',
      'image_id',
      'video_id',
      'user_id',
      'meta'
  ];

  protected $casts = [
      'meta' => 'json',
  ];

  public function playlistItems() {
    return $this->morphMany(ChannelPlaylistItem::class, 'content');
  }

  public function showCategory() {
    return $this->belongsTo(ShowCategory::class, 'show_category_id');
  }

  public function showCategorySub() {
    return $this->belongsTo(ShowCategorySub::class, 'show_category_sub_id');
  }

  public function video() {
    return $this->belongsTo(Video::class, 'video_id');
  }

  public function user() {
    return $this->belongsTo(User::class, 'user_id');
  }
}
