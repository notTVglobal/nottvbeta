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
        return $this->hasOne(Shows::class);
    }

    public function teams()
    {
        return $this->hasOne(Teams::class);
    }

    public function showEpisodes()
    {
        return $this->hasOne(ShowEpisode::class);
    }

    public function users()
    {
        return $this->hasOne(User::class);
    }
}
