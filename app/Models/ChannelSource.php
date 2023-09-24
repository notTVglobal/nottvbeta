<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelSource extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function channel()
    {
        return $this->hasMany(Channel::class);
    }
}
