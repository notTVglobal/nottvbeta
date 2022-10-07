<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'show_id',
        'episode_id',
        'user_id',
        'title',
        'body',
    ];
}
