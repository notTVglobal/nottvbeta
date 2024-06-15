<?php

namespace App\Jobs;

use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSingleScheduleAndIndex implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected Schedule $schedule;

  public function __construct(Schedule $schedule) {
    $this->schedule = $schedule;
  }

  public function handle(): void {
    $updateJob = new UpdateSchedulesAndIndexes();
    $updateJob->updateSingleScheduleAndIndex($this->schedule);
  }
}
