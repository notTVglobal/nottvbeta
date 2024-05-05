<?php

namespace App\Jobs;

use App\Models\Schedule;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

class ScheduleUpdateAllScheduleBroadcastDates implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


  // TODO: Brand new. This will run "after" we Purge Expired Shows.
  //  This runs a batch of `UpdateBroadcastScheduleForShow` Jobs.


  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct() {
    //
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle() {
    // Fetch schedules in chunks to manage memory usage and eager load related models
    Schedule::with(['content', 'scheduleRecurrenceDetails', 'scheduleIndexes'])
        ->where('end_time', '>=', now())  // Assuming you want to update only active/future schedules
        ->chunk(100, function ($schedules) {
          foreach ($schedules as $schedule) {
            // Directly dispatch the job for each schedule
            ScheduleUpdateShowBroadcastDates::dispatch($schedule);
          }
        });
  }
}
