<?php

namespace App\Jobs;

use App\Services\ScheduleService;
use App\Traits\PreloadScheduleContentRelationships;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class CacheAllSchedules implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, PreloadScheduleContentRelationships;

  /**
   * Execute the job.
   */
  public function handle(): void {

    // Resolve ScheduleService using the service container
    $scheduleService = App::make(ScheduleService::class);

    // Fetch, transform, and cache the schedules
    $scheduleService->fetchAndCacheSchedules();

  }

}
