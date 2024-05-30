<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class NewsPersonMessageResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    // Determine the sender type and retrieve appropriate data
    $senderData = $this->getSenderData();

    return [
        'id' => $this->id,
        'sender_id' => $this->sender_id,
        'recipient_id' => $this->recipient_id,
        'subject' => $this->subject ? Crypt::decryptString($this->subject) : null,
        'message' => Crypt::decryptString($this->message),
        'read_at' => $this->read_at,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'sender' => $senderData,
    ];
  }

  /**
   * Get sender data based on the sender type.
   *
   * @return array
   */
  private function getSenderData()
  {
    if ($this->sender) {
      if ($this->sender->newsPerson) {
        return [
            'type' => 'newsPerson',
            'id' => $this->sender->newsPerson->id,
            'name' => $this->sender->name,
            'roles' => $this->sender->newsPerson->roles->pluck('name'),
            'profile_photo_path' => $this->sender->profile_photo_path,
            'profile_photo_url' => $this->sender->profile_photo_url,
            'email' => null,
            'phone' => null,
            'creator_slug' => null,
        ];
      } elseif ($this->sender->creator) {
        return [
            'type' => 'creator',
            'id' => $this->sender->id,
            'name' => $this->sender->name,
            'roles' => [],
            'profile_photo_path' => $this->sender->profile_photo_path,
            'profile_photo_url' => $this->sender->profile_photo_url,
            'email' => null,
            'phone' => null,
            'creator_slug' => $this->sender->creator->slug,
        ];
      } else {
        return [
            'type' => 'user',
            'id' => $this->sender->id,
            'name' => $this->sender->name,
            'roles' => [],
            'profile_photo_path' => $this->sender->profile_photo_path,
            'profile_photo_url' => $this->sender->profile_photo_url,
            'email' => $this->sender->email,
            'phone' => $this->sender->phone,
            'creator_slug' => null,
        ];
      }
    } else {
      return [
          'type' => 'anonymous',
          'id' => null,
          'name' => 'Anonymous',
          'roles' => [],
          'profile_photo_path' => null,
          'profile_photo_url' => null,
          'email' => null,
          'phone' => null,
          'creator_slug' => null,
      ];
    }
  }
}

