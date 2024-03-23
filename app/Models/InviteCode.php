<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;

class InviteCode extends Model {
  use HasFactory;

  protected static function booted()
  {
    static::creating(function ($model) {
      $model->ulid = strtolower((string) Ulid::generate());
    });
  }

  protected $fillable = [
      'code',
      'user_role_id',
      'created_by',
      'volume',
      'expiry_date',
      'notes',
      'ulid',
  ];

  public function getRouteKeyName(): string
  {
    return 'ulid';
  }

  public function createdBy(): BelongsTo {
    return $this->belongsTo(User::class, 'created_by')->withDefault();
  }

  public function claimedBy(): BelongsTo {
    return $this->belongsTo(User::class, 'claimed_by')->withDefault();
  }

  public function role() {
    return $this->belongsTo(Role::class, 'user_role_id');
  }
}
