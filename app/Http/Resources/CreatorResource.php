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

    // Fetch teams and check if user is a manager for each team
    $teams = $this->whenLoaded('user', function() use ($user) {
      return $user->teams->map(function($team) use ($user) {
        return [
            'id' => $team->id,
            'name' => $team->name,
            'is_manager' => $user->teamManager->contains($team),
        ];
      });
    });

    return [
        'id' => $this->user_id,
        'slug' => $this->slug,
        'name' => $user->name ?? '',
        'teams' => $teams,
        'profile_photo_path' => $user->profile_photo_path ?? '',
        'profile_photo_url' => $user->profile_photo_url ?? '',
    ];
  }
}
