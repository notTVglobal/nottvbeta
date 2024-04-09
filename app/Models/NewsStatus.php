<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsStatus extends Model
{
    use HasFactory;

    public function newsStories()
    {
        return $this->hasMany(NewsStory::class);
    }
}
