<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function image()
    {
        return $this->hasMany(Image::class);
    }

    public function show()
    {
        return $this->hasMany(Show::class);
    }
}
