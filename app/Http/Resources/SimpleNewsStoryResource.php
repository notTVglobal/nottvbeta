<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class SimpleNewsStoryResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param \Illuminate\Http\Request $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request) {
    $user = Auth::user();

    return [
        'id'                           => $this->id,
        'slug'                         => $this->slug,
        'title'                        => $this->title,
        'duration'                     => $this->duration,
        'category'                     => $this->getCachedCategory() ?? [
                'id'          => null,
                'slug'        => null,
                'name'        => null,
                'description' => null,
            ],
        'subCategory'                  => $this->getCachedSubCategory() ?? [
                'id'          => null,
                'slug'        => null,
                'name'        => null,
                'description' => null,
            ],
        'city'                         => $this->getCachedCity() ?? [
                'id'   => null,
                'slug' => null,
                'name' => null,
            ],
        'province'                     => $this->getCachedProvince() ?? [
                'id'   => null,
                'slug' => null,
                'name' => null,
            ],
        'federalElectoralDistrict'     => $this->getCachedFederalElectoralDistrict() ?? [
                'id'   => null,
                'slug' => null,
                'name' => null,
            ],
        'subnationalElectoralDistrict' => $this->getCachedSubnationalElectoralDistrict() ?? [
                'id'   => null,
                'slug' => null,
                'name' => null,
            ],
        'image'                        => $this->image ? (new ImageResource($this->image))->toArray($request) : null,
        'published_at'                 => $this->published_at,
    ];
  }
}
