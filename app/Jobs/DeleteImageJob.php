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
use Illuminate\Support\Facades\Storage;

class DeleteImageJob implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

  public $imageId;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($imageId) {
    $this->imageId = $imageId;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $image = Image::find($this->imageId);
    if ($image) {
      // Delete the image from Spaces
      $path = $image->cloud_folder . $image->folder . '/' . $image->name;
      Storage::disk('spaces')->delete($path);

      // Remove the image record from your database
       $image->delete();

      // Remove the record from the image_deletion_queue
      DB::table('image_deletion_queue')->where('duplicate_image_id', $this->imageId)->delete();
    }
  }
}
