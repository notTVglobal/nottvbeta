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
//    $schedule->command('inspire')->hourly();
    $schedule->command('horizon:snapshot')->everyFiveMinutes();
//        $schedule->job(new CheckSubscriptionStatuses)->everySixHours();
    $schedule->job(new CheckSubscriptionStatuses, 'default')->daily();
    $schedule->command('expire:inviteCodes')->daily();

//    $schedule->call(function () use ($schedule) {
//      $feeds = NewsRssFeed::all(); // Get all feeds
//      foreach ($feeds as $feed) {
//        $schedule->job(new FetchRssFeedItemsJob($feed))->hourly();
//      }
//    })->after(function () {
//      // This closure will be executed after the application is fully booted.
//      // This means it will run after migrations have completed during an artisan command.
//    });

    $schedule->command('fetch:rssFeeds')->hourly();
    $schedule->command('newsRssFeed:archive')->hourly();
    $schedule->command('purge:oldRssFeedItems')->monthly();
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
      Commands\ArchiveRssFeedItemsCommand::class,
  ];
}
