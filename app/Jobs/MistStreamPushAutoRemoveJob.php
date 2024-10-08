<?php

namespace App\Jobs;

use App\Models\MistStreamPushDestination;
use App\Services\MistServer\MistServerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MistStreamPushAutoRemoveJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected MistStreamPushDestination $destination;

  /**
   * Create a new job instance.
   *
   * @param MistStreamPushDestination $destination
   */
  public function __construct(MistStreamPushDestination $destination)
  {
    $this->destination = $destination;
  }

  /**
   * Execute the job.
   *
   * @param MistServerService $mistServerService
   * @return void
   */
  public function handle(MistServerService $mistServerService): void {
    $mistServerService->pushAutoRemove($this->destination);
  }
}
