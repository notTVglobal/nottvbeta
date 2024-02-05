<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
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
    $storageLocation = $this->storage_location ?? null;
    $mediaType = $storageLocation === 'external' ? 'externalVideo' : 'internalVideo';

    return [
        'media_type' => $mediaType,
        'file_name' => $this->file_name ?? '',
        'cdn_endpoint' => $this->appSetting->cdn_endpoint ?? '',
        'folder' => $this->folder ?? '',
        'cloud_folder' => $this->cloud_folder ?? '',
        'upload_status' => $this->upload_status ?? '',
        'video_url' => $this->video_url ?? '',
        'type' => $this->type ?? '',
        'storage_location' => $this->storage_location ?? '',
    ];
  }
}
