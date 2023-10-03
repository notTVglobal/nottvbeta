<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamManager extends Pivot
{
    use HasFactory;

    protected $table = 'team_managers';

    protected $fillable = [
        'team_id',
        'user_id',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->select(['id', 'name', 'email', 'phone', 'profile_photo_path']); // Select specific columns from the users table
    }

}
