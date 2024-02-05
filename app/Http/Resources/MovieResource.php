<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\VideoResource;

class MovieResource extends JsonResource
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
          'logline' => $this->logline,
          'image' => $this->whenLoaded('image') ? new ImageResource($this->image) : null,
          'category' => $this->category ? [
              'name' => $this->category->name,
              'description' => $this->category->description,
          ] : null,
          'subCategory' => $this->subCategory ? [
              'name' => $this->subCategory->name,
              'description' => $this->subCategory->description,
          ] : null,
        // Include video using VideoResource
          'video' => $this->whenLoaded('video') ? new VideoResource($this->video) : null,

        // Add other fields as necessary
      ];
    }
}
