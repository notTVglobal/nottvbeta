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
        'image_id',
        'first_release_year',
        'last_release_year',
    ];

    public function getRouteKeyName() {
        return 'slug';
    }

    public function showEpisodes()
    {
        return $this->hasMany(ShowEpisode::class);
    }

    public function showStatus()
    {
        return $this->belongsTo(ShowStatus::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showRunner()
    {
        return $this->belongsTo(Creator::class);
    }

    public function showNotes()
    {
        return $this->hasMany(ShowNote::class);
    }

}

