<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecureNote extends Model {
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */

  protected $fillable = [
      'user_id',
      'content',
  ];

  // Encrypt content before saving
  public function setContentAttribute($value)
  {
    $this->attributes['content'] = encrypt($value);
  }

  // Decrypt content when accessing
  public function getContentAttribute($value)
  {
    return decrypt($value);
  }

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    // You might want to hide sensitive fields here, depending on your application's needs
  ];

  /**
   * The user that the note belongs to.
   */
  public function user() {
    return $this->belongsTo(User::class);
  }

  // You can add custom methods here related to your secure notes logic

}
