<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image_id',
        'name',
        'description',
        'memberSpots',
        'totalSpots',
        'slug',
        'isBeingEditedByUser_id',
    ];

    public function getRouteKeyName() {
        return 'slug';
    }

    public function shows()
    {
        return $this->hasMany(Show::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }



}
