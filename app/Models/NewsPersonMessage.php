<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsPersonMessage extends Model {

  use SoftDeletes;

  protected $fillable = [
      'sender_id',
      'recipient_id',
      'subject',
      'message',
      'read_at'
  ];

  public function sender()
  {
    return $this->belongsTo(User::class, 'sender_id');
  }


  public function recipient() {
    return $this->belongsTo(NewsPerson::class, 'recipient_id');
  }
}
