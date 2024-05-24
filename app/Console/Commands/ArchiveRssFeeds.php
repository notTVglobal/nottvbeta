<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ArchiveSavedRssFeedItemsJob;
use Illuminate\Support\Facades\Log;

class ArchiveRssFeeds extends Command
{

  protected $signature = 'archive:rss-feeds';
  protected $description = 'Archives saved RSS feed items to the NewsRssFeedItemArchive table';

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    $this->info('Starting the archiving process...');

    ArchiveSavedRssFeedItemsJob::dispatch();

    $this->info('Archiving job dispatched.');
    Log::info('Archived RSS feed items.');
  }
}
