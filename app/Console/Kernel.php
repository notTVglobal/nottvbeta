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
  protected function schedule(Schedule $schedule): void {

    // High-priority tasks
    $schedule->command('mistPush:updatePushData')->everyMinute()->onQueue('high-priority');
    $schedule->command('horizon:snapshot')->everyFiveMinutes()->onQueue('high-priority');

    // Hourly tasks
    $schedule->command('images:delete-queued')->hourly()->onQueue('hourly')->withoutOverlapping();
    $schedule->command('fetch:rss-feeds')->hourly()->onQueue('hourly')->withoutOverlapping();
    $schedule->command('archive:rss-feeds')->hourly()->onQueue('hourly')->withoutOverlapping();

    // Every thirty minutes tasks
    $schedule->command('update:schedule')->everyThirtyMinutes()->onQueue('hourly')->withoutOverlapping();

    // Hourly maintenance task
    $schedule->call(function () {
      app(ScheduleService::class)->purgeOldCacheFiles(1);
    })->hourly()->onQueue('maintenance')->withoutOverlapping();

    // Daily tasks
    $schedule->command('purge:rss-feeds')->daily()->onQueue('daily')->withoutOverlapping();
    $schedule->command('expire:inviteCodes')->daily()->onQueue('daily')->withoutOverlapping();
    $schedule->command('video-chunks:remove-old')->daily()->onQueue('daily')->withoutOverlapping();
    $schedule->command('clamav:scan')->dailyAt('9:00')->onQueue('daily')->withoutOverlapping(); // 9am UTC is 2am PDT or 3am PST

    // Daily job
    $schedule->job(new CheckSubscriptionStatuses, 'default')->daily()->onQueue('daily')->withoutOverlapping();
    $schedule->command('purge:schedule')->daily()->onQueue('daily')->withoutOverlapping();
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
