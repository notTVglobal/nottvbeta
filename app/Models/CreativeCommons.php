<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CreativeCommons extends Model
{
    use HasFactory;

  protected static function boot() {
    parent::boot();

    static::saved(function ($cc) {
      Cache::forget('creative_commons');
    });

    static::deleted(function ($cc) {
      Cache::forget('creative_commons');
    });
  }
}
