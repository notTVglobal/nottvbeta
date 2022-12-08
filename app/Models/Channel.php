<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    public function messages() {
        return $this->hasMany('App\Models\ChatMessage');
    }

    public function user()
    {
        return $this->hasMany('App\Models\User', 'id', 'user_id');
//        return $this->belongsTo(User::class);
    }

    public function playlist() {
        return $this->hasMany('App\Models\ChannelPlaylist');
    }
}
