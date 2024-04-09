<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
  public function toArray($request)
  {
    $appSetting = $this->whenLoaded('appSetting');

    return [
        'id' => $this->id,
        'name' => $this->name ?? '',
        'folder' => $this->folder ?? '',
        'cdn_endpoint' => $appSetting->cdn_endpoint ?? '',
        'cloud_folder' => $this->cloud_folder ?? '',
        'placeholder_url' => $this->placeholder_url ?? '',
    ];
  }
}
