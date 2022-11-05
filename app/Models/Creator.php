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

    public function team()
    {
        return $this->hasMany(Teams::class);
    }

    public function show()
    {
        return $this->hasMany(Shows::class);
    }

    public function status()
    {
        return $this->belongsTo(CreatorStatus::class);
    }
}
