<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'channel_id',
        'message',
        'user_name',
        'user_profile_photo_path',
        'user_profile_photo_url',
        'is_visible'
    ];

  protected $casts = [
      'is_visible' => 'boolean',
  ];

    public function channel() {
        return $this->hasOne('App\Models\Channel', 'id', 'channel_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
