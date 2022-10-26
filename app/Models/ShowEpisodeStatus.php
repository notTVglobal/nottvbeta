<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowEpisodeStatus extends Model
{
    use HasFactory;

    public function showEpisodes()
    {
        return $this->hasMany(ShowEpisodes::class);
    }
}
