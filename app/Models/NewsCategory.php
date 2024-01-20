<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function newsPost()
    {
        return $this->hasMany(NewsStory::class);
    }
}
