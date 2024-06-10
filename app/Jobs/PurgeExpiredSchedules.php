<?php

namespace App\Jobs;

use App\Models\Schedule;
use App\Models\SchedulesIndex;
use App\Models\Show;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurgeExpiredSchedules implements ShouldQueue
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
      try {
        // Use chunkById to efficiently process large sets of expired schedules
        Schedule::where('end_dateTime', '<', now())
            ->chunkById(100, function ($schedules) {
              foreach ($schedules as $schedule) {
                DB::beginTransaction();
                try {
                  // Delete related index entries first to maintain referential integrity
                  SchedulesIndex::where('schedule_id', $schedule->id)->delete();

                  // Then delete the schedule
                  $schedule->delete();

                  // Check if the related content is a Show
                  if ($schedule->content_type === Show::class) {
                    $show = $schedule->content;

                    // Check if the show has any remaining schedules
                    if ($show->schedules()->count() === 0) {
                      // Update the show meta to set isScheduled to false
                      $meta = json_decode($show->meta, true);
                      if (!is_array($meta)) {
                        $meta = [];
                      }
                      $meta['isScheduled'] = false;
                      $show->meta = json_encode($meta);
                      $show->save();

//                      Log::debug('Updated show meta isScheduled to false', ['show_id' => $show->id]);
                    }
                  }

                  DB::commit();
                } catch (Exception $e) {
                  DB::rollBack();
                  Log::error('Error processing schedule', [
                      'schedule_id' => $schedule->id,
                      'error' => $e->getMessage(),
                      'trace' => $e->getTraceAsString()
                  ]);
                  throw $e;
                }

                // Log the deletion of each schedule
//                Log::debug('Processed and deleted schedule', ['schedule_id' => $schedule->id]);
              }
            });
        // Optionally log the completion of the operation
//        Log::debug('Purged all expired schedules and their corresponding indexes.');

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
