<?php

namespace App\Console\Commands;

use App\Jobs\UpdateImageReferencesJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class UpdateImageReferences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:update-references';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update image references for non-duplicate images in a batch.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $tables = [
          'movies',
          'news_rss_feed_item_archives',
          'news_stories',
          'other_contents',
          'show_episodes',
          'shows',
          'subscription_plans',
          'teams',
          'product',
          'video'
      ];
      $jobs = collect($tables)->map(function ($table) {
        return new UpdateImageReferencesJob($table);
      })->all();

      // Dispatch the jobs as a batch to the 'long-running' queue
      Bus::batch($jobs)->onQueue('long-running')->dispatch();

      $this->info('Batch update of image references for non-duplicate images dispatched.');

    }

}
