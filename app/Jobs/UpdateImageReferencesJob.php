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
    try {
      ImageHash::where('is_duplicate', 1)
          ->chunkById(100, function ($duplicateImages) {
            foreach ($duplicateImages as $duplicateImage) {
              $originalImageId = ImageHash::where('hash', $duplicateImage->hash)
                  ->where('is_duplicate', 0)
                  ->value('image_id'); // More efficient than fetching the entire model

              if ($originalImageId) {
                // Update the table's image_id to the original image_id
                DB::table($this->table)
                    ->where('image_id', $duplicateImage->image_id)
                    ->update(['image_id' => $originalImageId]);

                DB::transaction(function () use ($duplicateImage) {
                  // Attempt to acquire a lock for the specific duplicate image ID
                  $existingQueueEntry = DB::table('image_deletion_queue')
                      ->where('duplicate_image_id', $duplicateImage->image_id)
                      ->lockForUpdate()
                      ->exists();

                  if (!$existingQueueEntry) {
                    // If not exists, insert the duplicate image ID into the image_deletion_queue table
                    DB::table('image_deletion_queue')->insert([
                        'duplicate_image_id' => $duplicateImage->image_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                  }
                });
              }
            }
          });

    } catch (\Exception $e) {
      Log::error("Error processing UpdateImageReferencesJob: {$e->getMessage()}");
      // Optionally, decide how you want to handle the error.
      // For example, you might want to release the job back into the queue with a delay.
      // $this->release(120);
    }
  }


}
