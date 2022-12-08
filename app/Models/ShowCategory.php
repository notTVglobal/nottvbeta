<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function show()
    {
        return $this->hasMany(Show::class);
    }

    public function showCategorySub()
    {
        return $this->belongsTo(ShowCategorySub::class);
    }
}
