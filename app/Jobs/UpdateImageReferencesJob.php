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
      $duplicateImages = ImageHash::where('is_duplicate', 1)->get();

      foreach ($duplicateImages as $duplicateImage) {
        $originalImageId = ImageHash::where('hash', $duplicateImage->hash)
            ->where('is_duplicate', 0)
            ->first()
            ->image_id ?? null;

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
    }
}
