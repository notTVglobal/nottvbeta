<?php

namespace App\Traits;

trait ImageTransformable {
  protected function transformImage($image): array {
    return [
        'id'              => $image->id,
        'name'            => $image->name,
        'folder'          => $image->folder,
        'cdn_endpoint'    => $image->appSetting->cdn_endpoint,
        'cloud_folder'    => $image->cloud_folder,
        'placeholder_url' => $image->placeholder_url,
    ];
  }
}