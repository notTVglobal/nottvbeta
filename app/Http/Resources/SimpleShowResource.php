<?php

namespace App\Http\Resources;

use App\Http\Resources\ImageResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\VideoResource;

class SimpleShowResource extends JsonResource {

  public static function requiredRelationships(): array {
    return ['team.image.appSetting', 'image.appSetting'];
  }

  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray(Request $request): array {
    return [
        'id'          => $this->id,
        'name'        => $this->name,
        'slug'        => $this->slug,
        'team'        => [
            'id'    => optional($this->team)->id,
            'name'  => optional($this->team)->name,
            'slug'  => optional($this->team)->slug,
            'image' => optional(new ImageResource($this->team->image))->resolve() ?? null,
        ],
        'description' => strlen($this->description) > 300 ? substr($this->description, 0, 300) . '...' : $this->description,
        'image'       => $this->whenLoaded('image', function () {
          // Directly return the array representation of the ImageResource
          return (new ImageResource($this->image))->resolve() ?? null;
        }),
        'category'    => $this->resource->getCachedCategory() ? [
            'name'        => $this->resource->getCachedCategory()->name,
            'description' => $this->resource->getCachedCategory()->description,
        ] : null,
        'subCategory' => $this->resource->getCachedSubCategory() ? [
            'name'        => $this->resource->getCachedSubCategory()->name,
            'description' => $this->resource->getCachedSubCategory()->description,
        ] : null,
    ];
  }
}
