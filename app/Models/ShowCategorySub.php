<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowCategorySub extends Model
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
    public function showCategory()
    {
        return $this->hasOne(ShowCategory::class);
    }

}
