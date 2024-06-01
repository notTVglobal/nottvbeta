<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsTip extends Model {
  use HasFactory, SoftDeletes;

  protected $fillable = [
      'name',
      'email',
      'postalCode',
      'message',
      'news_person_id',
      'read_at',
      'read_by',
  ];

  protected $casts = [
      'read_at' => 'datetime',
  ];

  public function newsPerson() {
    return $this->belongsTo(NewsPerson::class);
  }
}