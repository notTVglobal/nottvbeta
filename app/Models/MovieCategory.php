<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MovieCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function movie()
    {
        return $this->hasMany(Movie::class);
    }

    public function subCategories()
    {
        return $this->hasMany(MovieCategorySub::class, 'movie_categories_id');
    }

  protected static function boot() {
    parent::boot();

    static::updated(function ($category) {
      Cache::forget('category_' . $category->id);
    });

    static::deleted(function ($category) {
      Cache::forget('category_' . $category->id);
    });
  }
}
