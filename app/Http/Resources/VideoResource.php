<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray(Request $request) {

    if (is_null($this->resource)) {
      return [
          'id' => '',
          'ulid' => '',
          'isAvailable' => false,
          'mediaType' => '',
          'file_name' => '',
          'cdn_endpoint' => '',
          'folder' => '',
          'cloud_folder' => '',
          'upload_status' => '',
          'video_url' => '',
          'type' => '',
          'storage_location' => '',
          'mist_stream' => null,
          'mist_stream_wildcard' => null,
      ];
    }

    // Ensure appSetting, mistStream, and mistStreamWildcard are loaded and provide default values if they are null
    $appSetting = $this->whenLoaded('appSetting') ?: null;
    $mistStream = $this->whenLoaded('mistStream') ?: null;
    $mistStreamWildcard = $this->whenLoaded('mistStreamWildcard') ?: null;
    $storageLocation = $this->storage_location ?? null;
    $mediaType = $storageLocation === 'external' ? 'externalVideo' : 'internalVideo';
    $videoIsAvailable = $this->video_url ||
        ($this->folder && $this->upload_status !== 'processing');

    return [
        'id'                   => $this->id ?? null,
        'ulid'                 => $this->ulid ?? '',
        'isAvailable'          => $videoIsAvailable,
        'mediaType'            => $mediaType, // New attribute for NowPlayingStore
        'file_name'            => $this->file_name ?? '',
        'cdn_endpoint'         => $appSetting->cdn_endpoint ?? '',
        'folder'               => $this->folder ?? '',
        'cloud_folder'         => $this->cloud_folder ?? '',
        'upload_status'        => $this->upload_status ?? '',
        'video_url'            => $this->video_url ?? '',
        'type'                 => $this->type ?? '',
        'storage_location'     => $storageLocation ?? null,
        'mist_stream'          => $mistStream ?? null,
        'mist_stream_wildcard' => $mistStreamWildcard ?? null,
    ];
  }
}
