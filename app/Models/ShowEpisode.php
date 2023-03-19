<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'video_thumbnail',
        'video_file_url',
        'video_file_embed_code',
        'isPublished',
        'isBeingEditedByUser_id',
        'image_id',
        'release_date'
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


    public function getRouteKeyName() {
        return 'slug';
    }

}
