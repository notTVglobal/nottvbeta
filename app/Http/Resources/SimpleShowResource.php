<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\VideoResource;

class SimpleShowResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param \Illuminate\Http\Request $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request) {
    return [
        'id'               => $this->id,
        'name'             => $this->name,
        'slug'             => $this->slug,
        'description'      => substr($this->description, 0, 300),
        'image'            => $this->whenLoaded('image', function () {
          // Directly return the array representation of the ImageResource
          return (new ImageResource($this->image))->resolve();
        }),
        'category'         => $this->resource->getCachedCategory() ? [
            'name'        => $this->resource->getCachedCategory()->name,
            'description' => $this->resource->getCachedCategory()->description,
        ] : null,
        'subCategory'      => $this->resource->getCachedSubCategory() ? [
            'name'        => $this->resource->getCachedSubCategory()->name,
            'description' => $this->resource->getCachedSubCategory()->description,
        ] : null,
    ];
  }
}
