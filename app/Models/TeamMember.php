<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'user_id',
    ];

    public function teams()
    {
        return $this->belongsToMany(Teams::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
