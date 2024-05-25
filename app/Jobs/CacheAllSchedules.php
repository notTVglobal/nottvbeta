<?php

namespace App\Jobs;

use App\Models\Schedule;
use App\Traits\PreloadScheduleContentRelationships;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheAllSchedules implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, PreloadScheduleContentRelationships;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
  public function handle(): string {
    // Fetch all schedules with necessary relationships
    $schedules = Schedule::with(['content.image.appSetting', 'scheduleRecurrenceDetails', 'scheduleIndexes'])
        ->orderBy('start_dateTime')
        ->get();

    // Preload additional relationships for each schedule
    foreach ($schedules as $schedule) {
      $this->preloadContentRelationships($schedule);
    }

    // Cache the schedules
    $cacheKey = $this->getCacheKey('all_schedules');
    Cache::put($cacheKey, $schedules, now()->addMinutes(30)); // Cache for 30 minutes
    Log::info('Schedules cached successfully.');

    // Return success message for Horizon
    return 'Schedules cached successfully.';
  }

  protected function getCacheKey($key): string {
    return "schedule_cache_{$key}";
  }
}
