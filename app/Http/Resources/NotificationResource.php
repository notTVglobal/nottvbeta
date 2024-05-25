<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray($request) {
    return [
        'id'         => $this->id,
        'title'      => $this->title,
        'message'    => $this->message,
        'url'        => $this->url ?? null,
        'read'       => (bool) $this->read ?? false,
        'created_at' => $this->created_at,
        'image'      => $this->whenLoaded('image', function () {
          return (new ImageResource($this->image))->resolve();
        }),
    ];
  }
}
