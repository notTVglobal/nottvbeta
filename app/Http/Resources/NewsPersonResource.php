<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsPersonResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray(Request $request): array {

    return [
        'id'                 => $this->id,
        'name'               => $this->user->name,
        'slug'               => $this->slug,
        'image'              => $this->whenLoaded('image', function () {
          // Directly return the array representation of the ImageResource
          return (new ImageResource($this->image))->resolve() ?? null;
        }),
        'profile_photo_url'  => $this->user->profile_photo_url,
        'profile_photo_path' => $this->user->profile_photo_path,
        'biography'          => $this->biography,
        'contact_info'       => $this->contact_info,
        'other_details'      => $this->other_details,
        'social_media'       => $this->social_media,
        'roles'              => $this->roles->map(function ($role) {
          return [
              'id'   => $role->id,
              'name' => $role->name,
          ];
        }),
    ];
  }
}
