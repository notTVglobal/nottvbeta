<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ImageService;
use App\Models\NewsRssFeedItemArchive;

class DownloadAndStoreImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $rssFeedItemArchiveId;
    protected $imageUrl;
    protected $savedByUserId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($rssFeedItemArchiveId, $imageUrl, $savedByUserId)
    {
      $this->rssFeedItemArchiveId = $rssFeedItemArchiveId;
      $this->imageUrl = $imageUrl;
      $this->savedByUserId = $savedByUserId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ImageService $imageService)
    {
      $imageId = $imageService->storeImage($this->imageUrl, $this->savedByUserId);

      if ($imageId) {
        // Update the NewsRssFeedItemArchive record with the image ID
        $rssFeedItemArchive = NewsRssFeedItemArchive::find($this->rssFeedItemArchiveId);
        if ($rssFeedItemArchive) {
          $rssFeedItemArchive->image_id = $imageId;
          $rssFeedItemArchive->save();
        }
      }
    }
}
