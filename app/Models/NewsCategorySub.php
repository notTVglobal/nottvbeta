<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCategorySub extends Model
{
    use HasFactory;

  protected $fillable = [
      'name',
      'description',
      'news_categories_id'
  ];

  public function newsStories()
  {
    return $this->hasMany(NewsStory::class);
  }

  public function newsCategory()
  {
    return $this->belongsTo(NewsCategory::class)->withDefault([
        'name' => 'sub category',
        'description' => 'sub category description'
    ]);
  }
}
