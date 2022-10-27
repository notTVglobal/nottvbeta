<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function shows()
    {
        return $this->hasMany(Shows::class);
    }

    public function teams()
    {
        return $this->hasMany(Teams::class);
    }

    public function showEpisodes()
    {
        return $this->hasMany(ShowEpisode::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
