<?php

namespace App\Http\Resources;

use App\Http\Resources\ImageResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\VideoResource;
use JsonSerializable;

class SimpleMovieResource extends JsonResource {

  public static function requiredRelationships(): array {
    return ['image.appSetting', 'trailers', 'team.image.appSetting'];
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
        'logline'     => $this->logline,
        'duration'    => $this->duration,
        'image'       => $this->whenLoaded('image') ? new ImageResource($this->image) : null,
        'category'    => $this->resource->getCachedCategory() ? [
            'name'        => $this->resource->getCachedCategory()->name,
            'description' => $this->resource->getCachedCategory()->description,
        ] : null,
        'subCategory' => $this->resource->getCachedSubCategory() ? [
            'name'        => $this->resource->getCachedSubCategory()->name,
            'description' => $this->resource->getCachedSubCategory()->description,
        ] : null,
//          'team' => $this->whenLoaded('team') ? $this->transformTeam($this->team) : null,
      // Add other fields as necessary
    ];
  }

  private function transformTeam($team) {
    // Ensure team is a collection before mapping
    if ($team instanceof \Illuminate\Support\Collection) {
      return $team->map(function ($member) {
        return [
            'id'    => $member->id,
            'name'  => $member->name,
            'slug'  => $member->slug,
            'image' => optional($member->image)->map(function ($image) {
              return new ImageResource($image);
            }),
        ];
      })->all();
    }

    // If team is not a collection, handle the single member case
    return [
        'id'    => $team->id ?? null,
        'name'  => $team->name ?? null,
        'slug'  => $team->slug ?? null,
        'image' => $team->image ? new ImageResource($team->image) : null,
    ];
  }
}
