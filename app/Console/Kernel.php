<?php

namespace App\Console;

use App\Jobs\CheckSubscriptionStatuses;
use App\Jobs\FetchRssFeedItemsJob;
use App\Jobs\PurgeExpiredSchedules;
use App\Jobs\UpdateAllScheduleBroadcastDates;
use App\Jobs\ScheduleUpdateSchedulesIndexesDELETEME;
use App\Models\NewsRssFeed;
use App\Services\ScheduleService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
  /**
   * Define the application's command schedule.
   *
   * @param Schedule $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule): void
  {
    // Hourly maintenance task: Purges old cache files older than 1 hour
    $schedule->job(new \App\Jobs\PurgeOldCacheFilesJob(1))
        ->hourly()
        ->withoutOverlapping()
        ->onQueue('maintenance');

    // High-priority tasks
    // Runs every minute to update push data
    $schedule->call(function() {
      \Artisan::call('mistPush:updatePushData');
    })->everyMinute()->withoutOverlapping()->onQueue('high-priority');

    // Takes a snapshot of Horizon every five minutes
    $schedule->call(function() {
      \Artisan::call('horizon:snapshot');
    })->everyFiveMinutes()->withoutOverlapping()->onQueue('high-priority');

    // Hourly tasks
    // Deletes queued images every hour
    $schedule->call(function() {
      \Artisan::call('images:delete-queued');
    })->hourly()->withoutOverlapping()->onQueue('hourly');

    // Fetches RSS feeds every hour
    $schedule->call(function() {
      \Artisan::call('fetch:rss-feeds');
    })->hourly()->withoutOverlapping()->onQueue('hourly');

    // Archives RSS feeds every hour
    $schedule->call(function() {
      \Artisan::call('archive:rss-feeds');
    })->hourly()->withoutOverlapping()->onQueue('hourly');

    // Every thirty minutes task
    // Updates the schedule every thirty minutes
    $schedule->call(function() {
      \Artisan::call('update:schedule');
    })->everyThirtyMinutes()->withoutOverlapping()->onQueue('hourly');

    // Daily tasks
    // Purges RSS feeds daily
    $schedule->call(function() {
      \Artisan::call('purge:rss-feeds');
    })->daily()->withoutOverlapping()->onQueue('daily');

    // Expires invite codes daily
    $schedule->call(function() {
      \Artisan::call('expire:inviteCodes');
    })->daily()->withoutOverlapping()->onQueue('daily');

    // Removes old video chunks daily
    $schedule->call(function() {
      \Artisan::call('video-chunks:remove-old');
    })->daily()->withoutOverlapping()->onQueue('daily');

    // Runs ClamAV scan daily at 9:00 UTC (2:00am PDT or 3:00am PST)
    $schedule->call(function() {
      \Artisan::call('clamav:scan');
    })->dailyAt('9:00')->withoutOverlapping()->onQueue('daily');

    // Checks subscription statuses daily
    $schedule->job(new \App\Jobs\CheckSubscriptionStatuses)
        ->daily()
        ->withoutOverlapping()
        ->onQueue('daily');

    // Purges the schedule daily
    $schedule->call(function() {
      \Artisan::call('purge:schedule');
    })->daily()->withoutOverlapping()->onQueue('daily');
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
}
