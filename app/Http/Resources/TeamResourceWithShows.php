<?php

namespace App\Http\Resources;

use App\Http\Resources\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResourceWithShows extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param \Illuminate\Http\Request $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request) {

    // Resolve the image if it's loaded
    $resolvedImage = $this->whenLoaded('image', function () {
      return $this->image ? (new ImageResource($this->image))->resolve() : null;
    });

    return [
        'id'               => $this->id,
        'name'             => $this->name,
        'description'      => $this->description,
        'public_message'   => $this->public_message ?? null,
        'slug'             => $this->slug,
        'image'            => $resolvedImage,
        'status_id'        => $this->status_id,
        'nextBroadcast'    => $this->nextBroadcast ?? [],
        'socialMediaLinks' => [
            'www_url'        => $this->www_url ?? null,
            'instagram_name' => $this->instagram_name ?? null,
            'telegram_url'   => $this->telegram_url ?? null,
            'twitter_handle' => $this->twitter_handle ?? null,
        ],
        'shows' => SimpleShowResource::collection($this->whenLoaded('shows'))->resolve() ?? null,
    ];
  }
}
