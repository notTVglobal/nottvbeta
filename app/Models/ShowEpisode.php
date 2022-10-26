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
        'image_id',
        'user_id',
        'show_id',
        'slug',
        'notes',
        'isPublished',

    ];

    protected $attributes = [
        'isBeingEditedByUser_id' => null,
    ];

}
