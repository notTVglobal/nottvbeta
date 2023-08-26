<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_name',
        'extension',
        'size',
        'type',
        'full_url',
        'poster',
        'sprite_path',
        'sprite_full_url',
        'live_count',
        'is_live',
        'profile_photo_path',
        'video_status',
        'category',
        'commons_license',
        'access_level'

    ];

    protected $hidden = [
        'encryption_key',
        'hash',
        'nft_address'

    ];

    public function newsPost()
    {
        return $this->hasMany(NewsPost::class);
    }

    public function showEpisode()
    {
        return $this->hasMany(ShowEpisode::class);
    }

    public function movie()
    {
        return $this->hasMany(Movie::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function appSetting()
    {
        return $this->belongsTo(AppSetting::class);
    }

    public function channels()
    {
        return $this->hasMany(Channel::class);
    }
}
