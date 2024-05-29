<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
  public function toArray($request)
  {
//    $user = $this->whenLoaded('members');
//    $user = $this->user;
//    dd($this);
    return [
        'id' => $this->id,
        'name' => $this->name ?? '',
        'email' => $this->email ?? '',
        'phone' => $this->phone ?? '',
        'profile_photo_path' => $this->profile_photo_path ?? '',
        'profile_photo_url' => $this->profile_photo_url ?? '',
        'active' => $this->pivot->active ?? null,
        'team_profile_is_public' => $this->pivot->team_profile_is_public ?? null,
        'created_at' => $this->pivot->created_at ?? null,
        'updated_at' => $this->pivot->updated_at ?? null,
    ];
  }
}
