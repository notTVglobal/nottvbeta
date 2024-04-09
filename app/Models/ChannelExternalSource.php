<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelExternalSource extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function channel()
    {
        return $this->hasMany(Channel::class, 'channel_external_source_id');
    }
}
