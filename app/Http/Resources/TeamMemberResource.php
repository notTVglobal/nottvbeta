<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class TeamMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
  public function toArray(Request $request): array|JsonSerializable|Arrayable {
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
