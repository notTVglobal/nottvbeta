<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreatorStatus extends Model
{
    use HasFactory;

    protected $table = 'creator_statuses';

    public function creator()
    {
        return $this->hasMany(Creator::class);
    }
}
