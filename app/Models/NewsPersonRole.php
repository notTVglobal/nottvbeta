<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPersonRole extends Model
{
    use HasFactory;

    public function newsPerson()
    {
        return $this->hasMany(NewsPerson::class);
    }
}
