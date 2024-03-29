<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MistServerAutoPush extends Model {
  use HasFactory;

  protected $fillable = [
      'stream_name',
      'uri',
      'col_3',
      'col_4',
      'col_5',
      'col_6',
      'col_7',
      'col_8',
      'col_9',
      'col_10',
      'scheduletime',
      'completetime',
      'auto_push_entry'
    // Include other fields here as necessary
  ];

  protected $casts = [
    'auto_push_entry' => 'json',
  ];
}
