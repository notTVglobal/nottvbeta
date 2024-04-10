<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MistStreamWildcard extends Model {
  use HasUlids, HasFactory;

  protected $fillable = [
      'mist_stream_id',
      'name',
      'token',
      'comment',
      'source',
      'mime_type',
      'active',
      'is_recording',
      'metadata'
  ];

  protected $casts = [
      'metadata'     => 'json',
      'active'       => 'boolean',
      'is_recording' => 'boolean'
  ];

  public function mistStream(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(MistStream::class);
  }

  public function show(): \Illuminate\Database\Eloquent\Relations\HasOne {
    return $this->hasOne(Show::class, 'mist_stream_wildcard_id');
  }

  // Define the inverse relationship to ShowEpisode
  public function showEpisode(): \Illuminate\Database\Eloquent\Relations\HasOne {
    return $this->hasOne(ShowEpisode::class, 'mist_stream_wildcard_id');
  }

  public function mistStreamPushDestination(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(MistStreamPushDestination::class);
  }

  public function recording(): \Illuminate\Database\Eloquent\Relations\HasOne {
    return $this->hasOne(Recording::class, 'mist_stream_wildcard_id');
  }
}
