<?php

namespace App\Jobs;

use App\Models\MistStreamPushDestination;
use App\Services\MistServer\MistServerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MistStreamPushStartJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected MistStreamPushDestination $mistStreamPushDestination;


  public function __construct(MistStreamPushDestination $mistStreamPushDestination)
  {
    $this->mistStreamPushDestination = $mistStreamPushDestination;
  }

  /**
   * Execute the job.
   *
   * @param MistServerService $mistServerService
   * @return void
   */
  public function handle(MistServerService $mistServerService): void {
    $mistServerService->startPush($this->mistStreamPushDestination);
  }
}
