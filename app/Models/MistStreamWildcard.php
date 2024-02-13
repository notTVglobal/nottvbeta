<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MistStreamWildcard extends Model
{
  use HasUlids, HasFactory;

  protected $fillable = [
      'mist_stream_id',
      'name',
      'comment',
      'source',
      'mime_type',
      'active',
      'metadata'
  ];

  public function mistStream()
  {
    return $this->belongsTo(MistStream::class);
  }

    public function shows()
  {
    return $this->hasMany(Show::class, 'mist_stream_wildcard_id');
  }

  // Define the inverse relationship to ShowEpisode
  public function showEpisodes()
  {
    return $this->hasMany(ShowEpisode::class, 'mist_stream_wildcard_id');
  }
}
