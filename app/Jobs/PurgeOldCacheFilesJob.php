<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PurgeOldCacheFilesJob implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $hours;

  /**
   * Create a new job instance.
   */
  public function __construct($hours) {
    $this->hours = $hours;
  }

  /**
   * Execute the job.
   */
  public function handle(): void {
    app(ScheduleService::class)->purgeOldCacheFiles($this->hours);
  }
}
