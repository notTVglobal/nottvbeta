<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ShowEpisode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'show_id',
        'slug',
        'notes',
        'video_url',
        'video_embed_code',
        'isPublished',
        'isBeingEditedByUser_id',
        'image_id',
        'release_year',
        'release_dateTime'
    ];

    protected $with = ['show', 'image'];

    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    public function showEpisodeStatus()
    {
        return $this->belongsTo(ShowEpisodeStatus::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function appSetting(): BelongsTo
    {
        return $this->belongsTo(AppSetting::class)->withDefault([
//            'cdn_endpoint' => 'https://development-nottv.sfo3.cdn.digitaloceanspaces.com',
        ]);
    }

//    public function showCategory(): HasOneThrough
//    {
//        return $this->hasOneThrough(Show::class, ShowCategory::class);
//    }
//
//    public function showCategorySub(): HasOneThrough
//    {
//        return $this->hasOneThrough(ShowCategorySub::class, Show::class);
//    }


    public function getRouteKeyName() {
        return 'slug';
    }

}
