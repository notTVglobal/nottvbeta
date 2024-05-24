<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NewsRssFeed;
use App\Jobs\FetchRssFeedItemsJob;
use Illuminate\Support\Facades\Log;

class FetchRssFeeds extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
  protected $signature = 'fetch:rss-feeds';

    /**
     * The console command description.
     *
     * @var string
     */
  protected $description = 'Fetch items for all RSS feeds';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $feeds = NewsRssFeed::all();
      foreach ($feeds as $feed) {
        FetchRssFeedItemsJob::dispatch($feed);
      }
      Log::info('Dispatched jobs to fetch RSS feed items.');
      $this->info('Dispatched jobs to fetch RSS feed items.');
    }
}
