<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ShowCategory extends Model {
  use HasFactory;

  protected static function boot() {
    parent::boot();

    static::saving(function ($model) {
      $model->slug = Str::slug($model->name);
    });

    // Clear cache after updating a category
    static::updated(function ($category) {
      Cache::forget('show_category_' . $category->id);
    });

    // Clear cache after deleting a category
    static::deleted(function ($category) {
      Cache::forget('show_category_' . $category->id);
    });
  }

  protected $fillable = [
      'name',
      'slug',
      'description'
  ];

  public function getRouteKeyName(): string {
    return 'slug';
  }

  public function show() {
    return $this->hasMany(Show::class);
  }

  public function subCategories() {
    return $this->hasMany(ShowCategorySub::class, 'show_categories_id', 'id');
  }

}
