<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MistStream extends Model {
  use HasUlids, HasFactory;

  protected $fillable = [
      'name',
      'comment',
      'source'
  ];

  protected static function boot() {
    parent::boot();

    static::creating(function ($model) {
      // Only set a unique name if it's not already set
      if (empty($model->name)) {
        $model->name = Str::uuid();
      }
    });
  }

  public function videos() {
    return $this->hasMany(Video::class, 'mist_stream_id');
  }

}
