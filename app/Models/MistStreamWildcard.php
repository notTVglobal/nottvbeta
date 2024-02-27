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
  ];

  protected $casts = [
      'metadata' => 'array',
  ];

  public function mistStream(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(MistStream::class);
  }

    public function shows(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(Show::class, 'mist_stream_wildcard_id');
  }

  // Define the inverse relationship to ShowEpisode
  public function showEpisodes(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(ShowEpisode::class, 'mist_stream_wildcard_id');
  }

  public function mistStreamPushDestination(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(MistStreamPushDestination::class);
  }
}
