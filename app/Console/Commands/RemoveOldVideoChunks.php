<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RemoveOldVideoChunks extends Command {
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'video-chunks:remove-old';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Remove video chunks that are 2 days old or older';

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle(): int {
    $disk = Storage::disk('local-video-chunks');
    $files = $disk->allFiles();
    $now = Carbon::now();

    foreach ($files as $file) {
      $lastModified = Carbon::createFromTimestamp($disk->lastModified($file));
      if ($now->diffInDays($lastModified) >= 2) {
        $disk->delete($file);
        $this->info("Deleted: $file");
      }
    }

    return 0;
  }
}
