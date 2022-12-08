<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelPlaylist extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel_id',
        'name',
        'description',
        'url'
    ];

    public function channel() {
        return $this->belongsTo('App\Models\Channel');
    }


}
