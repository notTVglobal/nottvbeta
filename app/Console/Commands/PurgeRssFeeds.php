<?php

namespace App\Console\Commands;

use App\Models\NewsRssFeedItemTemp;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Command\Command as CommandAlias;
use App\Services\ImageService;

class PurgeRssFeeds extends Command
{
    protected $signature = 'purge:rss-feeds';
    protected $description = 'Purge RSS feed items from the temp table that are older than 30 days';

    public function handle()
    {
      // Set the threshold to 30 days before now
      $threshold = Carbon::now()->subDays(180);
      NewsRssFeedItemTemp::where('created_at', '<', $threshold)->delete();

      $this->info('Old RSS feed items purged successfully.');
      Log::info('Old RSS feed items purged successfully.');

    }
}
