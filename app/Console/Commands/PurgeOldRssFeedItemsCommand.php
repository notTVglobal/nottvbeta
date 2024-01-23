<?php

namespace App\Console\Commands;

use App\Models\NewsRssFeedItemTemp;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Command\Command as CommandAlias;
use App\Services\ImageService;

class PurgeOldRssFeedItemsCommand extends Command
{
    protected $signature = 'purge:oldRssFeedItems';
    protected $description = 'Purge RSS feed items from the temp table that are older than 7 days';

    public function handle()
    {
      $threshold = Carbon::now()->subDays(7);
      NewsRssFeedItemTemp::where('created_at', '<', $threshold)->delete();

      $this->info('Old RSS feed items purged successfully.');
    }
}
