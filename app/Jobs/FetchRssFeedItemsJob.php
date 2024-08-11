<?php

namespace App\Jobs;

use App\Models\NewsRssFeed;
use App\Services\RssFeedService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchRssFeedItemsJob implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

  protected NewsRssFeed $newsRssFeed;

  public function __construct(NewsRssFeed $newsRssFeed) {
    $this->newsRssFeed = $newsRssFeed;
  }

  public function handle(RssFeedService $rssFeedService): void {
    $rssFeedService->fetchAndProcessRssFeed($this->newsRssFeed);
  }
}
