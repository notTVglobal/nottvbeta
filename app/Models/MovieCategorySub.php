<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MovieCategorySub extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'movie_categories_id'
    ];

    public function movie()
    {
        return $this->hasMany(Movie::class);
    }

    public function movieCategory()
    {
        return $this->belongsTo(MovieCategory::class, 'movie_categories_id')->withDefault([
          'name' => 'category',
          'description' => 'category description'
      ]);
    }
  protected static function boot()
  {
    parent::boot();

    static::updated(function ($subCategory) {
      Cache::forget('subcategory_' . $subCategory->id);
    });

    static::deleted(function ($subCategory) {
      Cache::forget('subcategory_' . $subCategory->id);
    });
  }
}
