<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieTrailer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
