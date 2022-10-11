<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'name',
        'poster',
        'description',
        'image_id',
        'team_id',
        'user_id',
        'show_id',
        'slug',
        'notes',
        'isPublished',
        'episodeStatus_id',
    ];
}
