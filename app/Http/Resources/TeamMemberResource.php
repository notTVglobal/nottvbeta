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
    $user = $this->whenLoaded('user');
    return [
        'id' => $this->id,
        'name' => $user->name ?? '',
        'email' => $user->email ?? '',
        'phone' => $user->phone ?? '',
        'profile_photo_path' => $user->profile_photo_path ?? '',
        'profile_photo_url' => $user->profile_photo_url ?? '',
        'active' => $this->pivot->active ?? null,
        'team_profile_is_public' => $this->pivot->team_profile_is_public ?? null,
        'created_at' => $this->pivot->created_at ?? null,
        'updated_at' => $this->pivot->updated_at ?? null,
    ];
  }
}
