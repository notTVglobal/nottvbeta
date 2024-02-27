<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MistStreamPushDestination extends Model {
  use HasUlids;

  protected $fillable = [
      'mist_stream_wildcard_id',
      'rtmp_url',
      'rtmp_key',
      'comment',
      'has_auto_push',
      'push_is_started',
      'destination_name',
      'destination_image'
  ];

  public function mistStreamWildcard(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(MistStreamWildcard::class);
  }
}
