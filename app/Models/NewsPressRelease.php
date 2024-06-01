<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPressRelease extends Model
{
    use HasFactory, SoftDeletes;

  protected $fillable = [
      'name',
      'email',
      'file_path',
  ];

}
