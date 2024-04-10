<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

  public function getRouteKeyName(): string {
    return 'slug';
  }

    public function newsStories()
    {
        return $this->hasMany(NewsStory::class);
    }

  public function newsCategorySubs()
  {
    return $this->hasMany(NewsCategorySub::class, 'news_categories_id');
  }
}
