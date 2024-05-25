<?php

namespace App\Jobs;

use App\Models\Schedule;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateAllScheduleBroadcastDates implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;


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
   * @return string
   * @throws Throwable
   */
  public function handle(): string {
    $jobs = [];

    Schedule::with(['content', 'scheduleRecurrenceDetails', 'scheduleIndexes'])
        ->chunk(100, function ($schedules) use (&$jobs) {
          foreach ($schedules as $schedule) {
            $jobs[] = new UpdateBroadcastDates($schedule);
          }
        });

    $batch = Bus::batch($jobs)
        ->before(function (Batch $batch) {
//          Log::info('Batch created: '.$batch->id);
        })
        ->progress(function (Batch $batch) {
//          Log::info('Batch progress: '.$batch->processedJobs().'/'.$batch->totalJobs);
        })
        ->then(function (Batch $batch) {
//          Log::info('All jobs in batch completed successfully.');
        })
        ->catch(function (Batch $batch, Throwable $e) {
//          Log::error('Batch job failure detected: '.$e->getMessage());
        })
        ->finally(function (Batch $batch) {
          Log::info('All schedule broadcast dates updated successfully.');
          // Chain the CacheAllSchedules job
          CacheAllSchedules::dispatch();
        })
        ->onQueue('schedules')->name('Update All Scheduled Broadcast Dates')->dispatch();

    return $batch->id;
  }
}
