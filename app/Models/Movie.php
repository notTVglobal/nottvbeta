<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
//    protected $fillable = [
//        'name',
//        'description',
//        'file_path',
//        'file_url',
//    ];
    protected $guarded = [];

    public function getRouteKeyName() {
        return 'slug';
    }

    public function newsPerson()
    {
        return $this->hasMany(MovieTrailer::class);
    }
}


