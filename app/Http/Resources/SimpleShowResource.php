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
        'category'         => $this->category ? [
            'name'        => $this->category->name,
            'description' => $this->category->description,
        ] : null,
        'subCategory'      => $this->subCategory ? [
            'name'        => $this->subCategory->name,
            'description' => $this->subCategory->description,
        ] : null,
    ];
  }
}
