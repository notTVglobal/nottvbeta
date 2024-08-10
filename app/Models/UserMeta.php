<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model {
  use HasFactory;

  protected $table = 'user_meta';

  protected $fillable = ['user_id', 'changelog_seen'];

  protected $casts = [
      'changelog_seen' => 'json', // Cast the changelog_seen column to a json
  ];

  public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(User::class);
  }
}
