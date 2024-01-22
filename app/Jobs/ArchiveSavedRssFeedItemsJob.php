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
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Storage;

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
        //
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
          $archivedItem->fill($item->toArray());
          $archivedItem->image_id = $this->processImage($item->image_url);
          $archivedItem->save();
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
      return $imageController->storeImage($imageUrl, $folder);
    }

    return null;
  }
}
