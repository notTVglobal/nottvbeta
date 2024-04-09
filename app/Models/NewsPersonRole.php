<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPersonRole extends Model {
  use HasFactory;

  protected $table = 'news_people_roles';

  public function newsPeople() {
    return $this->belongsToMany(NewsPerson::class, 'news_person_role_user', 'role_id', 'news_person_id');
  }
}
