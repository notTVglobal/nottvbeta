<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieTrailer extends Model {
  use HasFactory;

  protected $fillable = [
      'movie_id',
      'extension',
      'size',
      'file_path',
      'file_url',
      'video_id',
  ];

  public function movie() {
    return $this->belongsTo(Movie::class);
  }

  public function video() {
    return $this->belongsTo(Video::class);
  }
}
