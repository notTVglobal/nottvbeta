<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'channel_id', 'message'];

    public function channel() {
        return $this->hasOne('App\Models\Channel', 'id', 'channel_id');
    }

    public function user()
    {
//        return $this->hasOne('App\Models\User', 'id', 'user_id');
        return $this->belongsTo(User::class);
    }
}
