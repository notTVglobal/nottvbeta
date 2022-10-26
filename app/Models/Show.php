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
        'user_id',
        'team_id',
        'slug',
        'isBeingEditedByUser_id',
        'image_id'
    ];

    public function showEpisodes()
    {
        return $this->hasMany(ShowEpisode::class);
    }

    public function getRouteKeyName() {
        return 'slug';
    }

}

