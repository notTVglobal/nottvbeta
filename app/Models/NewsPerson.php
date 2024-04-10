<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPerson extends Model {
  use HasFactory;

  protected $fillable = [
      'user_id',
  ];

  public function user() {
    return $this->belongsTo(User::class);
  }

  public function roles() {
    return $this->belongsToMany(NewsPersonRole::class, 'news_person_role_user', 'news_person_id', 'role_id');
  }

  public function newsStories()
  {
    return $this->hasMany(NewsStory::class);
  }

  public function getRouteKeyName() {
    return 'user_id';
  }
}
