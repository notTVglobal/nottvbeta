<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NewsRssFeed;
use App\Jobs\FetchRssFeedItemsJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Batch;
use Throwable;

class FetchRssFeeds extends Command {

  protected $signature = 'fetch:rss-feeds';
  protected $description = 'Fetch items for all RSS feeds';

  /**
   * @throws Throwable
   */
  public function handle(): int {
    $feeds = NewsRssFeed::all();
    $jobs = [];

    foreach ($feeds as $feed) {
      $jobs[] = new FetchRssFeedItemsJob($feed);
    }

    $batch = Bus::batch($jobs)
        ->then(function (Batch $batch) {
          Log::info('All RSS feed fetch jobs completed successfully.');
        })
        ->catch(function (Batch $batch, Throwable $e) {
          Log::error('One or more RSS feed fetch jobs failed.', [
              'batch_id' => $batch->id,
              'failed_jobs_count' => $batch->failedJobs,
              'exception' => $e->getMessage(),
          ]);
        })
        ->finally(function (Batch $batch) {
          Log::info('RSS feed fetch job batch finished.');
        })
        ->dispatch();

    Log::info('Dispatched batch jobs to fetch RSS feed items with batch ID: ' . $batch->id);
    $this->info('Dispatched batch jobs to fetch RSS feed items.');
    return 0;
  }
}
