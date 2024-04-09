<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ShowResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\VideoResource;

class ShowEpisodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
  public function toArray($request)
  {
    return [
        'name' => $this->name,
        'slug' => $this->slug,
        'show' => new ShowResource($this->whenLoaded('show')),
        'description' => $this->description,
        'image' => $this->whenLoaded('image') ? new ImageResource($this->image) : null,
      // Include video using VideoResource
        'video' => $this->whenLoaded('video') ? new VideoResource($this->video) : null,

      // Add other fields as necessary
    ];
  }
}
