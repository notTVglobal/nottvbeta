<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsRssFeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'url'
    ];

    public function getRouteKeyName() {
        return 'slug';
    }
}
