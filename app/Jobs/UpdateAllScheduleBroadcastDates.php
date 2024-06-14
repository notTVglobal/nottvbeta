<?php

namespace App\Jobs;

use App\Models\Schedule;
use App\Traits\PreloadContentRelationships;
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
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable, PreloadContentRelationships;

  // This will run "after" we Purge Expired Shows.
  // This runs a batch of `UpdateBroadcastScheduleForShow` Jobs.

  /**
   * Execute the job.
   *
   * @return string
   * @throws Throwable
   */
  public function handle(): string {
    $jobs = [];

    $schedules = Schedule::with('content', 'scheduleRecurrenceDetails', 'scheduleIndexes')
        ->get();

    foreach ($schedules as $schedule) {
      $this->preloadContentRelationships($schedule); // Preload necessary relationships
      $jobs[] = new UpdateBroadcastDates($schedule);
    }

    $batch = Bus::batch($jobs)
        ->then(function (Batch $batch) {
          Log::info('Batch completed successfully.' . PHP_EOL . 'Batch ID: ' . $batch->id);
          UpdateSchedulesAndIndexes::dispatch();
        })
        ->catch(function (Batch $batch, Throwable $e) {
          Log::error('Batch job failure detected: ' . $e->getMessage());
          HandleBatchFailure::dispatch();
        })
        ->finally(function (Batch $batch) {
          Log::info('All schedule broadcast dates updated successfully.' . PHP_EOL . 'Batch ID: ' . $batch->id);
          CacheAllSchedules::dispatch();
        })
        ->name('Update All Scheduled Broadcast Dates')
        ->dispatch();

    Log::info('Update all schedule broadcast dates batch dispatched.' . PHP_EOL . 'Batch ID: ' . $batch->id);

    return $batch->id;
  }
}