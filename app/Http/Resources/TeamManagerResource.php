<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamManagerResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray(Request $request): array {
    return [
        'id' => $this->id,
        'name' => $this->name ?? '',
        'email' => $this->email ?? '',
        'phone' => $this->phone ?? '',
        'profile_photo_path' => $this->profile_photo_path ?? '',
        'profile_photo_url' => $this->profile_photo_url ?? '',
      // Include any additional manager-specific fields if needed
    ];
  }
}
