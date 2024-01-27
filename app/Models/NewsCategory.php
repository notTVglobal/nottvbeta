<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function newsStories()
    {
        return $this->hasMany(NewsStory::class);
    }

  public function newsCategorySubs()
  {
    return $this->hasMany(NewsCategorySub::class, 'news_categories_id');
  }
}
