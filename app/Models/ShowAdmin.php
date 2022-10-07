<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowAdmin extends Model
{
    use HasFactory;

    protected $fillable = [
        'show_id',
        'user_id',
    ];

}
