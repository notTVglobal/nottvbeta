<?php

namespace Tests\Feature;

use App\Jobs\ArchiveSavedRssFeedItemsJob;
use App\Jobs\DownloadAndStoreImageJob;
use App\Models\NewsRssFeedItemTemp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;

class DownloadAndStoreImageJobTest extends TestCase
{

  use RefreshDatabase;

  #[Test]
  public function it_correctly_archives_saved_rss_feed_items()
  {
    Queue::fake();

    // Arrange: Create test data
    $tempItem = NewsRssFeedItemTemp::factory()->create(['is_saved' => true]);

    // Act: Dispatch the job
    ArchiveSavedRssFeedItemsJob::dispatchSync();

    // Assert: Check if the item is archived
    $this->assertDatabaseHas('news_rss_feed_item_archives', [
        'link' => $tempItem->link
    ]);

    // Optional: Assert that the DownloadAndStoreImageJob was dispatched
    Queue::assertPushed(DownloadAndStoreImageJob::class);
  }

}
