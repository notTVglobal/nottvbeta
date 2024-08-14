<?php

namespace App\Jobs;

use App\Models\Schedule;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateSchedulesAndIndexes implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Execute the job.
   * @throws Throwable
   */
  public function handle(): void {

    // Define the cutoff time
    $cutoffTime = now()->subMinutes(30);

    // Retrieve all broadcast dates from Redis and update the database
    $schedules = Schedule::with('content', 'scheduleRecurrenceDetails', 'scheduleIndexes')
        ->where('end_dateTime_utc', '>', $cutoffTime)
        ->orderBy('start_dateTime_utc')
        ->get();

    // Process schedules in chunks to reduce memory usage and enable batching
    $schedules->chunk(100)->each(function ($chunk) {
      $jobs = [];


      foreach ($chunk as $schedule) {
        // Check if the schedule should be deleted
        if ($schedule->end_dateTime_utc->lt(now()->subMinutes(30))) {
          // Delete associated schedules_index and schedule_recurrence_details records
          $schedule->scheduleIndexes()->delete();
          $schedule->scheduleRecurrenceDetails()->delete();

          // Delete the schedule itself
          $schedule->delete();

          Log::info("Schedule ID {$schedule->id} and its associated records were deleted.");
        } else {
          $jobs[] = new ProcessSingleScheduleAndIndex($schedule);
        }
      }

      // Dispatch the batch job
      if (!empty($jobs)) {
        Bus::batch($jobs)
            ->then(function (Batch $batch) {
              Log::info('Batch to cache schedules processed successfully. Batch ID: ' . $batch->id);
            })
            ->catch(function (Batch $batch, Throwable $e) {
              Log::error('Batch job failure detected: ' . $e->getMessage());
            })
            ->dispatch();
      }
    });
  }
}
