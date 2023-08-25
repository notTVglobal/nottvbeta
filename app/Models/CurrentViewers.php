<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentViewers extends Model
{
    use HasFactory;

    public mixed $channel_id;
}
