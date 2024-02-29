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
   * @param \Illuminate\Console\Scheduling\Schedule $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule) {
//    $schedule->command('inspire')->hourly();
    $schedule->command('horizon:snapshot')->everyFiveMinutes();
//        $schedule->job(new CheckSubscriptionStatuses)->everySixHours();
    $schedule->job(new CheckSubscriptionStatuses, 'default')->daily();
    $schedule->command('expire:invitecodes')->daily();

    $schedule->call(function () use ($schedule) {
      $feeds = NewsRssFeed::all(); // Get all feeds
      foreach ($feeds as $feed) {
        $schedule->job(new FetchRssFeedItemsJob($feed))->hourly();
      }
    })->after(function () {
      // This closure will be executed after the application is fully booted.
      // This means it will run after migrations have completed during an artisan command.
    });

    $schedule->command('purge:oldRssFeedItems')->daily();
    $schedule->command('newsRssFeed:archive')->dailyAt('08:00');
  }

  /**
   * Register the commands for the application.
   *
   * @return void
   */
  protected function commands() {
    $this->load(__DIR__ . '/Commands');

    require base_path('routes/console.php');
  }

  protected $commands = [
      Commands\ArchiveRssFeedItemsCommand::class,
  ];
}
