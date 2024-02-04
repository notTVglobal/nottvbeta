<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowCategorySub extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'show_categories_id'
    ];

    public function show()
    {
        return $this->hasMany(Show::class);
    }
    public function showCategory()
    {
        return $this->belongsTo(ShowCategory::class, 'show_categories_id', 'id')->withDefault([
          'name' => 'sub category',
          'description' => 'sub category description'
      ]);
    }

}
