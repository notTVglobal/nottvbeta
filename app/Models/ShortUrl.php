<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model {
  use HasFactory;

  protected $fillable = [
      'identifier',
      'custom_name',
      'original_url',
      'user_id',
      'show_id',
      'clicks',
      'is_active',
  ];

  // Relationship to the User model
  public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(User::class);
  }

  // Relationship to the Show model
  public function show(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(Show::class);
  }

  // Increment the click count when the URL is accessed
  public function incrementClicks(): void {
    $this->increment('clicks');
  }
}
