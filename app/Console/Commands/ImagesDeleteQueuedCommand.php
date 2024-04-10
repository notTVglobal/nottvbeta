<?php

namespace App\Console\Commands;

use App\Jobs\DeleteImageJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class ImagesDeleteQueuedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:delete-queued';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete images that are queued for deletion.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $queuedImages = DB::table('image_deletion_queue')->get();

//      foreach ($queuedImages as $queuedImage) {
//        DeleteImageJob::dispatch($queuedImage->duplicate_image_id);
//      }
//
      // Map each queued image to a DeleteImageJob
      $jobs = $queuedImages->map(function ($queuedImage) {
        return new DeleteImageJob($queuedImage->duplicate_image_id);
      })->all();

      // Dispatch the jobs as a batch
      Bus::batch($jobs)->dispatch();

      $this->info('Batch deletion of images dispatched.');

    }
}
