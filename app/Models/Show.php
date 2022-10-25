<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
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
        'poster',
        'user_id',
        'team_id',
        'slug',
    ];

    protected $attributes = [
        'isBeingEditedByUser_id' => null,
    ];

}

