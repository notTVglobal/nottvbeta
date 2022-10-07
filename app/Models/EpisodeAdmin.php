<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpisodeAdmin extends Model
{
    use HasFactory;

    protected $fillable = [
        'episode_id',
        'user_id',
    ];
}
