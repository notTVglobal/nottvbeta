<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsPerson extends Model {
  use HasFactory, SoftDeletes;

  protected $fillable = [
      'user_id',
      'name',
      'slug',
      'image_id',
      'biography',
      'contact_info',
      'other_details',
      'social_media'
  ];

  protected $casts = [
      'social_media' => 'json',
  ];

  public function getRouteKeyName(): string {
    return 'slug';
  }

  public function user() {
    return $this->belongsTo(User::class);
  }

  public function roles() {
    return $this->belongsToMany(NewsPersonRole::class, 'news_person_role_user', 'news_person_id', 'role_id');
  }

  public function newsStories() {
    return $this->hasMany(NewsStory::class);
  }

  public function receivedMessages()
  {
    return $this->hasMany(NewsPersonMessage::class, 'recipient_id');
  }

  public function newsTips()
  {
    return $this->hasMany(NewsTip::class);
  }

}
