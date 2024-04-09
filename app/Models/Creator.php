<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creator extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // team leader
    public function teamsLed()
    {
        return $this->hasMany(Team::class, 'team_leader');
    }

    // show runner
    public function show()
    {
        return $this->hasMany(Show::class);
    }

    public function status()
    {
        return $this->belongsTo(CreatorStatus::class, 'status_id');
    }
}
