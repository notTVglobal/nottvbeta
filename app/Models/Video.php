<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\Uid\Ulid;

class Video extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->ulid = (string) Ulid::generate();
        });
    }

    protected $fillable = [
        'user_id',
        'file_name',
        'extension',
        'size',
        'type',
        'poster',
        'sprite_path',
        'sprite_full_url',
        'live_count',
        'is_live',
        'profile_photo_path',
        'video_status',
        'category',
        'commons_license',
        'access_level',
        'upload_status',
        'is_processing',
        'url',
        'audio_codec',
        'video_codec',
        'audio_channels',
        'length',
    ];

    protected $hidden = [
        'encryption_key',
        'hash',
        'nft_address'

    ];

    public function newsPost()
    {
        return $this->hasOne(NewsPost::class);
    }

    public function showEpisode()
    {
        return $this->hasOne(ShowEpisode::class);
    }

    public function movie()
    {
        return $this->hasOne(Movie::class);
    }

    public function movieTrailer()
    {
        return $this->hasOne(MovieTrailer::class);
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
