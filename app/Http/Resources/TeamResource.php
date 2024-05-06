<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ImageResource;

class TeamResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param \Illuminate\Http\Request $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request) {
    return [
        'name'             => $this->name,
        'description'      => $this->description,
        'public_message'   => $this->public_message ?? null,
        'slug'             => $this->slug,
        'image'            => $this->whenLoaded('image', function () {
          return new ImageResource($this->image);
        }),
        'nextBroadcast'    => $this->nextBroadcast ?? [],
        'socialMediaLinks' => [
            'www_url'        => $this->www_url ?? null,
            'instagram_name' => $this->instagram_name ?? null,
            'telegram_url'   => $this->telegram_url ?? null,
            'twitter_handle' => $this->twitter_handle ?? null,
        ],
    ];
  }
}
