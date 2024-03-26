<?php

namespace App\Console;

use App\Jobs\CheckSubscriptionStatuses;
use App\Jobs\FetchRssFeedItemsJob;
use App\Models\NewsRssFeed;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
  /**
   * Define the application's command schedule.
   *
   * @param Schedule $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule): void {
    $schedule->command('horizon:snapshot')->everyFiveMinutes();

    $schedule->command('images:delete-queued')->hourly();
    $schedule->command('fetch:rssFeeds')->hourly();
    $schedule->command('archive:rssFeeds')->hourly();
    $schedule->command('purge:rssFeed')->daily();

    $schedule->job(new CheckSubscriptionStatuses, 'default')->daily();
    $schedule->command('expire:inviteCodes')->daily();
  }

  /**
   * Register the commands for the application.
   *
   * @return void
   */
  protected function commands(): void {
    $this->load(__DIR__ . '/Commands');

    require base_path('routes/console.php');
  }

  protected $commands = [
      Commands\RssFeedsArchiveCommand::class,
  ];
}
