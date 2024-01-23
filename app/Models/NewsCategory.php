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

  public function showCategorySub()
  {
    return $this->hasMany(NewsCategorySub::class)->withDefault([
        'name' => 'sub category',
        'description' => 'sub category description'
    ]);
  }
}
