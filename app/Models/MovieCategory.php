<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function movie()
    {
        return $this->hasMany(Movie::class);
    }

    public function movieCategorySub()
    {
        return $this->belongsTo(MovieCategorySub::class);
    }
}
