<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model {
  use HasFactory;

  public function users() {
    return $this->hasMany(User::class);
  }

  public function inviteCodes() {
    return $this->hasMany(InviteCode::class, 'user_role_id');
  }

}
