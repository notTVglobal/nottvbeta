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
        'isPublished',
        'isBeingEditedByUser_id',
        'image_id',

    ];

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
