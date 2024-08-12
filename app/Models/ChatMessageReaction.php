<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessageReaction extends Model {
  use HasFactory;

  protected $fillable = [
      'chat_message_id',
      'user_id',
      'reaction_type',
  ];

  public function chatMessage(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(ChatMessage::class);
  }

  public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(User::class);
  }
}
