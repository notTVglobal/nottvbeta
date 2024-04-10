<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MistStreamPushDestination extends Model {
  use HasUlids;

  protected $fillable = [
      'mist_stream_wildcard_id',
      'show_id',
      'mist_push_id',
      'stream_name',
      'full_push_uri',
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

  public function show() {
    return $this->belongsTo(Show::class, 'show_id', 'show_id');
  }

  public function mistServerActivePush(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(MistServerActivePush::class, 'mist_push_id', 'push_id');
  }
}
