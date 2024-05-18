<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CreatorResource extends JsonResource
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
    $teams = $this->whenLoaded('teams');

    return [
        'id' => $this->user_id,
        'slug' => $this->slug,
        'name' => $user->name ?? '',
        'teams' => $teams->pluck('id') ?? [],
        'profile_photo_path' => $user->profile_photo_path ?? '',
        'profile_photo_url' => $user->profile_photo_url ?? '',
    ];
  }
}
