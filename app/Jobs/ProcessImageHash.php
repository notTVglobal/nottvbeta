<?php

namespace App\Jobs;

use App\Models\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessImageHash implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected Image $image;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Image $image)
    {
      $this->image = $image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
  public function handle()
  {
    if ($this->image->storage_location === 'do_spaces') {
      $path = $this->image->cloud_folder . $this->image->folder . '/' . $this->image->name;

      try {
        $stream = Storage::disk('spaces')->readStream($path);

        if ($stream) {
          $ctx = hash_init('sha256');
          while (!feof($stream)) {
            hash_update($ctx, fread($stream, 8192));
          }
          $hash = hash_final($ctx);
          fclose($stream);

          // Check if a record with the same image_id already exists
          $existingImageId = DB::table('image_hashes')->where('image_id', $this->image->id)->first();

          if (!$existingImageId) {
            // No existing record with the same image_id, proceed to insert
            $isDuplicate = DB::table('image_hashes')->where('hash', $hash)->exists();

            DB::table('image_hashes')->insert([
                'image_id' => $this->image->id,
                'hash' => $hash,
                'is_duplicate' => $isDuplicate, // This will be true if the hash already exists, indicating a duplicate
            ]);
          }
        } else {
          Log::error("Image file does not exist in DO Spaces: {$path}");
        }
      } catch (\Exception $e) {
        Log::error("Failed to process image hash: " . $e->getMessage());
      }
    }
  }

}
