<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamStatus extends Model
{
    use HasFactory;

    protected $fillable = ['status'];

    public function teams()
    {
        return $this->hasMany(Team::class, 'team_status_id');
    }
}
