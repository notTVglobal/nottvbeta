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

    protected $casts = [
        'content_json' => 'array'
    ];

    public function getRouteKeyName() {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(NewsCategory::class);
    }

    public function status()
    {
        return $this->belongsTo(NewsStatus::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
