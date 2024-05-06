<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ShowCategory extends Model {
  use HasFactory;

  protected static function boot() {
    parent::boot();

    static::updated(function ($category) {
      Cache::forget('show_category_' . $category->id);
    });

    static::deleted(function ($category) {
      Cache::forget('show_category_' . $category->id);
    });
  }

  protected $fillable = [
      'name',
      'description'
  ];

  public function show() {
    return $this->hasMany(Show::class);
  }

  public function subCategories() {
    return $this->hasMany(ShowCategorySub::class, 'show_categories_id', 'id');
  }

}
