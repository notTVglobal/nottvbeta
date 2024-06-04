<?php

namespace App\Traits;

use App\Models\Video;
use App\Services\DigitalOceanService;

trait HandlesDigitalOceanUrls
{
  /**
   * Get pre-signed URL for a video.
   *
   * @param Video $video
   * @return string
   */
  protected function getPreSignedUrl(Video $video): string {
    $digitalOceanService = app(DigitalOceanService::class);

    return $digitalOceanService->getPreSignedUrl(
        config('filesystems.disks.spaces.bucket'),
//        $video->appSetting->cdn_endpoint,
        $video->cloud_private_folder . $video->folder . '/' . $video->file_name
    );
  }
}
