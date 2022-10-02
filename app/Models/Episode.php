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
        'name' => 'required',
        'description' => 'required',
        'team_id' => 'required',
        'user_id' => 'required',
        'show_id' => 'required',
        'notes' => 'array',
        'isPublished' => 'boolean',
        'episodeStatus_id' => 'required',
    ];
}
