<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InviteCode extends Model {
  use HasFactory;

  protected $fillable = [
      'code',
      'user_role_id',
      'created_by',
      'volume',
      'expiry_date',
      'notes',
  ];

  public function createdBy(): BelongsTo {
    return $this->belongsTo(User::class, 'created_by')->withDefault();
  }

  public function role() {
    return $this->belongsTo(Role::class, 'user_role_id');
  }
}
