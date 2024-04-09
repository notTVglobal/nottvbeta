<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MistServerActivePush extends Model {
  use HasUlids, HasFactory;

  protected $fillable = [
      'push_id',
      'stream_name',
      'original_uri',
      'processed_uri',
      'logs',
      'push_status',
  ];

  public $timestamps = true;

  protected $casts = [
      'logs'        => 'json',
      'push_status' => 'json',
  ];

  public function mistStreamPushDestinations(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(MistStreamPushDestination::class, 'mist_push_id', 'push_id');
  }
}
