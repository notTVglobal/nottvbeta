<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AssignUlidToVideos extends Command {
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'videos:assign-ulid';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Assign ULIDs to videos that are missing them';

  /**
   * Execute the console command.
   */
  public function handle(): int {
    // Find all videos that are missing a ULID
    $videosWithoutUlid = Video::whereNull('ulid')->get();

    // Loop through each video and assign a ULID
    foreach ($videosWithoutUlid as $video) {
      $video->ulid = Str::ulid();
      $video->save();
      $this->info("Assigned ULID to video ID: {$video->id}");
    }

    $this->info('All missing ULIDs have been assigned.');

    return 0;
  }
}
