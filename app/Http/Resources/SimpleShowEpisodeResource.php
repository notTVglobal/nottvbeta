<?php

namespace App\Http\Resources;

use App\Http\Resources\ImageResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ShowResource;
use App\Http\Resources\VideoResource;

class SimpleShowEpisodeResource extends JsonResource {

  public static function requiredRelationships(): array {
    return ['show.image.appSetting', 'image.appSetting', 'show.team.image.appSetting'];
  }

  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray(Request $request): array {

    return [
        'id'                       => $this->id,
        'name'                     => $this->name,
        'slug'                     => $this->slug,
        'description'              => strlen($this->description) > 300 ? substr($this->description, 0, 300) . '...' : $this->description,
        'image'                    => $this->whenLoaded('image') ? new ImageResource($this->image) : null,
        'category'                 => $this->getCategory(),
        'subCategory'              => $this->getSubCategory(),
        'show'                     => $this->getShowDetails(),
        'releaseDateTime'          => $this->release_dateTime ?? null,
        'scheduledReleaseDateTime' => $this->scheduled_release_date_time ?? null,
        'team'                     => $this->getTeam(),
    ];
  }

  private function getCategory()
  {
    return $this->whenLoaded('show', function () {
      return [
          'name'        => $this->show->getCachedCategory()->name ?? null,
          'description' => $this->show->getCachedCategory()->description ?? null,
      ];
    });
  }

  private function getSubCategory()
  {
    return $this->whenLoaded('show', function () {
      return [
          'name'        => $this->show->getCachedSubCategory()->name ?? null,
          'description' => $this->show->getCachedSubCategory()->description ?? null,
      ];
    });
  }

  private function getShowDetails()
  {
    return $this->whenLoaded('show', function () {
      return [
          'name'        => $this->show->name ?? null,
          'slug'        => $this->show->slug ?? null,
          'description' => strlen($this->show->description) > 300 ? substr($this->show->description, 0, 300) . '...' : $this->show->description,
          'image'       => $this->show->image ? new ImageResource($this->show->image) : null,
      ];
    });
  }

  private function getTeam()
  {
    return $this->whenLoaded('show', function () {
      if ($this->show->relationLoaded('team')) {
        return [
            'id'    => $this->show->team->id ?? null,
            'name'  => $this->show->team->name ?? null,
            'slug'  => $this->show->team->slug ?? null,
            'image' => $this->show->team->image ? new ImageResource($this->show->team->image) : null,
        ];
      } else {
        return null; // Return null if the 'team' relationship is not loaded
      }
    });
  }
}
