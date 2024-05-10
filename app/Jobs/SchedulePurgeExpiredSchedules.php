<?php

namespace App\Jobs;

use App\Models\Schedule;
use App\Models\SchedulesIndex;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SchedulePurgeExpiredSchedules implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // TODO: Removes items from the show_schedule_index where
    //  1. The show_schedule->end_date is in the past.





    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

  /**
   * Execute the job.
   *
   * @return void
   * @throws Exception
   */
    public function handle(): void {

//      Log::debug('Starting to purge expired schedules.');

      try {
        // Use chunkById to efficiently process large sets of expired schedules
        Schedule::where('end_time', '<', now())
            ->chunkById(100, function ($schedules) {
              foreach ($schedules as $schedule) {
                // Delete related index entries first to maintain referential integrity
                $schedule->scheduleIndexes()->delete();

                // Then delete the schedule
                $schedule->delete();

                // Log the deletion of each schedule
//                Log::debug('Processed and deleted schedule', ['schedule_id' => $schedule->id]);
              }
            });

        // Optionally log the completion of the operation
        Log::info('Purged all expired schedules and their corresponding indexes.');

      } catch (Exception $e) {
        Log::error('Error purging schedules', [
            'message' => $e->getMessage(),
            'stackTrace' => $e->getTraceAsString()
        ]);
        // Optionally rethrow if you want the job to be attempted again
        throw $e;
      }
    }
}
