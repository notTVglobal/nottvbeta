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

    $schedule->command('mistPush:updatePushData')->everyMinute();
    $schedule->command('horizon:snapshot')->everyFiveMinutes();

    $schedule->command('images:delete-queued')->hourly();
    $schedule->command('fetch:rssFeeds')->hourly();
    $schedule->command('archive:rssFeeds')->hourly();
//    $schedule->command('schedule:check-and-update-channels')->everyThirtyMinutes();
    $schedule->command('schedule:update')->everyThirtyMinutes();

    // Purge cache files older than 1 hour every hour
    $schedule->call(function () {
      app(ScheduleService::class)->purgeOldCacheFiles(1);
    })->hourly();

    $schedule->command('purge:rssFeed')->daily();
    $schedule->command('expire:inviteCodes')->daily();
    $schedule->command('clamav:scan')->dailyAt('9:00'); // 9am UTC is 2am PDT or 3am PST

    $schedule->job(new CheckSubscriptionStatuses, 'default')->daily();
    $schedule->command('schedule:purge')->daily();
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
