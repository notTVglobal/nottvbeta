<?php

namespace App\Jobs;

use App\Models\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CheckImageHashAndHandleDuplicate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $newImage;
  protected $modelType;
  protected $modelId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Image $newImage, $modelType, $modelId)
    {
      $this->newImage = $newImage;
      $this->modelType = $modelType;
      $this->modelId = $modelId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
  public function handle()
  {
    $path = $this->newImage->cloud_folder . $this->newImage->folder . '/' . $this->newImage->name;

    try {
      $stream = Storage::disk('spaces')->readStream($path);

      if ($stream) {
        $ctx = hash_init('sha256');
        while (!feof($stream)) {
          hash_update($ctx, fread($stream, 8192));
        }
        $hash = hash_final($ctx);
        fclose($stream);

        DB::transaction(function () use ($hash) {
          $existingHash = DB::table('image_hashes')->where('hash', $hash)->first();

          if ($existingHash) {
            // Check if there's a model to update
            if ($this->modelType && $this->modelId) {
              // Update the associated model with the existing image's ID
              $this->updateModelWithExistingImageId($existingHash->image_id);
            }

            // Since the image is a duplicate, queue the new image for deletion
            DB::table('image_deletion_queue')->insert([
                'duplicate_image_id' => $this->newImage->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
          } else {
            // If it's a unique image, save the new hash
            DB::table('image_hashes')->insert([
                'image_id' => $this->newImage->id,
                'hash' => $hash,
                'is_duplicate' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
          }
        });
      } else {
        Log::error("Image file does not exist in DO Spaces: {$path}");
      }
    } catch (\Exception $e) {
      Log::error("Failed to process image hash: " . $e->getMessage());
    }
  }


  protected function updateModelWithExistingImageId($existingImageId)
  {
    // Determine the correct model based on $this->modelType and update it
    // This is a simplified example, adjust according to your application's architecture
    $model = $this->getModelInstance($this->modelType, $this->modelId);
    if ($model) {
      $model->update(['image_id' => $existingImageId]);
    }
  }

  protected function getModelInstance($modelType, $modelId)
  {
    switch ($modelType) {
      case 'team':
        return \App\Models\Team::find($modelId);
      case 'show':
        return \App\Models\Show::find($modelId);
      case 'showEpisode':
        return \App\Models\ShowEpisode::find($modelId);
      default:
        return null; // Or handle invalid type as needed
    }
  }
}
