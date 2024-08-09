<?php

namespace App\Console;

use App\Jobs\ArchiveSavedRssFeedItemsJob;
use App\Jobs\CheckSubscriptionStatuses;
use App\Jobs\FetchRssFeedItemsJob;
use App\Jobs\PurgeExpiredSchedules;
use App\Jobs\PurgeOldCacheFilesJob;
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
