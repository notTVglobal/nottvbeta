<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\NewsRssFeedItemTemp;
use App\Models\NewsRssFeedItemArchive;
use App\Jobs\DownloadAndStoreImageJob;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;

class ArchiveSavedRssFeedItemsJob implements ShouldQueue
{
      use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      // Get all 'is_saved' items from NewsRssFeedItemTemp
      $savedItems = NewsRssFeedItemTemp::where('is_saved', true)->get();

      foreach ($savedItems as $item) {
        // Check if the item is already in the archive
        $existingArchiveItem = NewsRssFeedItemArchive::where('link', $item->link)->first();

        if (!$existingArchiveItem) {
          // Archive the item
          $archivedItem = new NewsRssFeedItemArchive();
          $archivedItem->news_rss_feed_id = $item->news_rss_feed_id;
          $archivedItem->title = $item->title;
          $archivedItem->description = $item->description;
          $archivedItem->link = $item->link;
          $archivedItem->pubDate = $item->pubDate;
          $archivedItem->image_url = $item->image_url;
          $archivedItem->extra_metadata = $item->extra_metadata;
          $archivedItem->save();

          if ($item->image_url) {
            // Dispatch job to download and store image
            DownloadAndStoreImageJob::dispatch($archivedItem->id, $item->image_url);
          }
        }
      }
    }

  private function processImage($imageUrl)
  {
    // Use ImageController to handle image storage
    $imageController = new ImageController();
    $folder = 'your_folder_name'; // Specify the folder name

    if ($imageUrl) {
      // Save image and get stored image ID
//      return $imageController->storeImage($imageUrl, $folder);
      return $imageId = $this->imageService->storeImage($imageUrl, $folder);
    }

    return null;
  }
}
