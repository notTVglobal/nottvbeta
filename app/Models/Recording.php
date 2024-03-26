<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recording extends Model {
  use HasUlids, HasFactory;

  protected $fillable = [
      'stream_name',
      'path',
      'file_extension',
      'mime_type',
      'start_time',
      'end_time',
      'bytes_recorded',
      'seconds_spent_recording',
      'total_milliseconds_recorded',
      'milliseconds_first_packet',
      'milliseconds_last_packet',
      'reason_for_exit',
      'human_readable_reason_for_exit',
      'mist_stream_wildcard_id',
      'model_type',
      'model_id',
      'download_url',
  ];

  protected $dates = [
      'start_time',
      'end_time',
  ];

  // Cast attributes to appropriate data types (optional)
  protected $casts = [
      'bytes_recorded' => 'integer',
      'seconds_spent_recording' => 'integer',
      'total_milliseconds_recorded' => 'integer',
      'milliseconds_first_packet' => 'integer',
      'milliseconds_last_packet' => 'integer',
    // any other fields you wish to cast
  ];

  public function model() {
    return $this->morphTo(null, 'model_type', 'model_id');
  }

  public function mistStreamWildcard() {
    return $this->belongsTo(MistStreamWildcard::class, 'mist_stream_wildcard_id');
  }

}
