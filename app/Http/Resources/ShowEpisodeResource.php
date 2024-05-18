<?php

namespace App\Http\Resources;

use App\Http\Resources\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ShowResource;
use App\Http\Resources\VideoResource;

class ShowEpisodeResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param \Illuminate\Http\Request $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request) {
    return [
        'id'          => $this->id,
        'name'        => $this->name,
        'slug'        => $this->slug,
        'description' => $this->description,
        'image'       => $this->whenLoaded('image') ? new ImageResource($this->image) : null,
      // Include video using VideoResource
        'video'       => $this->whenLoaded('video') ? new VideoResource($this->video) : null,
        'category'        => [
            'name'        => $this->resource->show->getCachedCategory()->name ?? null,
            'description' => $this->resource->show->getCachedCategory()->description ?? null,
        ],
        'subCategory'     => [
            'name'        => $this->resource->show->getCachedSubCategory()->name ?? null,
            'description' => $this->resource->show->getCachedSubCategory()->description ?? null,
        ],
        'show'          => [
          'name'        => $this->show->name ?? null,
          'slug'        => $this->show->slug ?? null,
          'description' => $this->show->description ?? null,
          'image'       => $this->whenLoaded('image') ? new ImageResource($this->image) : null,
        ],
        'releaseDateTime' => $this->release_dateTime ?? null,

      // Add other fields as necessary
    ];
  }
}
