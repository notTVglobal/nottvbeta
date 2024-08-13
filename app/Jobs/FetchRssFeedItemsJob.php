<?php

namespace App\Jobs;

use App\Models\NewsRssFeed;
use App\Services\RssFeedService;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchRssFeedItemsJob implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

  protected NewsRssFeed $newsRssFeed;

  public function __construct(NewsRssFeed $newsRssFeed) {
    $this->newsRssFeed = $newsRssFeed;
  }

  /**
   * @throws Exception
   */
  public function handle(RssFeedService $rssFeedService): void {
    try {
      $rssFeedService->fetchAndProcessRssFeed($this->newsRssFeed);
    } catch (Exception $e) {
      // Log the feed details along with the exception
      Log::error('Failed to fetch RSS feed item.', [
          'feed_id' => $this->newsRssFeed->id,
          'feed_name' => $this->newsRssFeed->name,
          'feed_url' => $this->newsRssFeed->url,
          'exception' => $e->getMessage(),
      ]);

      throw $e;  // Rethrow the exception to be caught in the batch handler
    }
  }
}
