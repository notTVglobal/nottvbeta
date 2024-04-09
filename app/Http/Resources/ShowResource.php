<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\VideoResource;

class ShowResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param \Illuminate\Http\Request $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request) {
    return [
        'id'             => $this->id,
        'ulid'           => $this->ulid,
        'name'           => $this->name,
        'slug'           => $this->slug,
        'description'    => $this->description,
        'image'          => $this->whenLoaded('image', function () {
          // Directly return the array representation of the ImageResource
          return (new ImageResource($this->image))->resolve();
        }),
        'category'       => $this->category ? [
            'id'          => $this->category->id,
            'name'        => $this->category->name,
            'description' => $this->category->description,
        ] : null,
        'subCategory'    => $this->subCategory ? [
            'id'          => $this->subCategory->id,
            'name'        => $this->subCategory->name,
            'description' => $this->subCategory->description,
        ] : null,
      // Include video using VideoResource
//        'video'       => $this->whenLoaded('video') ? new VideoResource($this->video) : null,
        'showRunner'     => $this->whenLoaded('showRunner', function () {
          return [
              'creator_id' => $this->showRunner->id ?? null, // This should be creator id...
              'name'       => $this->showRunner->user->name ?? null,
          ];
        }),
        'www_url'        => $this->www_url,
        'instagram_name' => $this->instagram_name,
        'telegram_url'   => $this->telegram_url,
        'twitter_handle' => $this->twitter_handle,
    ];
  }
}
