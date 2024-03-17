<?php

namespace App\Jobs;

use App\Models\ImageHash;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateImageReferencesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

  public $table;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($table)
    {
      $this->table = $table;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
  public function handle()
  {
    // Use chunking to manage memory usage better and handle large numbers of images efficiently
    ImageHash::where('is_duplicate', 1)->chunk(100, function ($duplicateImages) {
      foreach ($duplicateImages as $duplicateImage) {
        try {
          // Retrieve the original image ID based on the hash
          $originalImage = ImageHash::where('hash', $duplicateImage->hash)
              ->where('is_duplicate', 0)
              ->first();

          if ($originalImage) {
            // Perform the update operation within a transaction
            DB::transaction(function () use ($duplicateImage, $originalImage) {
              // Update references in the target table to the original image ID
              DB::table($this->table)
                  ->where('image_id', $duplicateImage->image_id)
                  ->update(['image_id' => $originalImage->image_id]);

              // Check if this duplicate image ID is already in the deletion queue
              $exists = DB::table('image_deletion_queue')
                  ->where('duplicate_image_id', $duplicateImage->image_id)
                  ->exists();

              if (!$exists) {
                // If not, add it to the queue for deletion
                DB::table('image_deletion_queue')->insert([
                    'duplicate_image_id' => $duplicateImage->image_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
              }
            });
          }
        } catch (\Exception $e) {
          // Log any exceptions and continue processing the next images
          Log::error("Failed to process duplicate image {$duplicateImage->image_id}: " . $e->getMessage());
        }
      }
    });
  }

}
