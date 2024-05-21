<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class MovieCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

  public function getRouteKeyName(): string {
    return 'slug';
  }

  public function movies()
    {
        return $this->hasMany(Movie::class);
    }

    public function subCategories()
    {
        return $this->hasMany(MovieCategorySub::class, 'movie_categories_id');
    }

  protected static function boot() {
    parent::boot();

    static::saving(function ($model) {
      $model->slug = Str::slug($model->name);
    });

    static::updated(function ($category) {
      Cache::forget('movie_category_' . $category->id);
    });

    static::deleted(function ($category) {
      Cache::forget('movie_category_' . $category->id);
    });
  }
}
