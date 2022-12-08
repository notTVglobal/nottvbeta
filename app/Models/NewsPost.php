<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_categories_id',
        'title',
        'slug',
        'content',
        'city',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(NewsCategory::class);
    }

    public function status()
    {
        return $this->hasOne(NewsStatus::class);
    }

    public function image()
    {
        return $this->hasOne(Image::class);
    }
}
