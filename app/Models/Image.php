<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function show()
    {
        return $this->hasMany(Shows::class);
    }

    public function team()
    {
        return $this->hasMany(Teams::class);
    }

    public function showEpisodes()
    {
        return $this->hasMany(ShowEpisode::class);
    }

    public function movie()
    {
        return $this->hasMany(Movie::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function newsPost()
    {
        return $this->hasMany(NewsPost::class);
    }

    public function video()
    {
        return $this->hasMany(Video::class);
    }

    public function appSetting()
    {
        return $this->belongsTo(AppSetting::class);
    }

//    public function appSetting(): BelongsTo
//    {
//        return $this->belongsTo(AppSetting::class)->withDefault([
//            'cdn_endpoint' => 'https://development-nottv.sfo3.cdn.digitaloceanspaces.com',
//        ]);
//    }

    public function showImage(): \Illuminate\Database\Eloquent\Relations\HasManyThrough {
        return $this->hasManyThrough(Image::class, Show::class);
    }
}
