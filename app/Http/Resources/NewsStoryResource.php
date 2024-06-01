<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class NewsStoryResource extends JsonResource {
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
        'content'                      => $this->content,
        'newsPerson'                   => [
            'id'   => $this->newsPerson->id ?? null,
            'name' => $this->newsPerson->user->name ?? 'Unknown',
        ],
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
        'status'                       => $this->getCachedStatus() ?? [
                'id'   => null,
                'name' => null,
            ],
        'video'                        => $this->video ? (new ImageResource($this->video))->toArray($request) : null,
        'created_at'                   => $this->created_at,
        'published_at'                 => $this->published_at,
        'updated_at'                   => $this->updated_at,
        'can'                          => $user ? [
            'editNewsStory'    => $user->can('edit', $this->resource),
            'deleteNewsStory'  => $user->can('delete', $this->resource),
            'publishNewsStory' => $user->can('canPublish', $this->resource),
        ] : [
            'editNewsStory'    => false,
            'deleteNewsStory'  => false,
            'publishNewsStory' => false,
        ],
    ];
  }
}
