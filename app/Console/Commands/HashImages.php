<?php

namespace App\Console\Commands;

use App\Jobs\ProcessImageHash;
use App\Models\Image;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Throwable;

class HashImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:hash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process image hashes and check for duplicates.';

  /**
   * Execute the console command.
   *
   * @return void
   * @throws Throwable
   */
    public function handle(): void {
      $images = Image::all();

//      foreach ($images as $image) {
//        ProcessImageHash::dispatch($image);
//      }

      // Prepare an array to hold the jobs
      $jobs = $images->map(function ($image) {
        return new ProcessImageHash($image);
      })->all();

      // Dispatch the jobs as a batch
      Bus::batch($jobs)->dispatch();
      $this->info('Batch process image hashes and check for duplicates dispatched.');
    }
}
