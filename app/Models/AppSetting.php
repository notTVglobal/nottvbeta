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
    public function movie()
    {
        return $this->hasMany(Movie::class);
    }

    public function team()
    {
        return $this->hasMany(Team::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class)->withDefault([
            'name' => 'channel name',
        ]);
    }
}
