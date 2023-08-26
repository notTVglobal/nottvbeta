<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelSource extends Model
{
    use HasFactory;

    public function channels()
    {
        return $this->hasMany(Channel::class);
    }
}
