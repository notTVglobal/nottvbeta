<?php

namespace App\Jobs;

use App\Models\MistStream;
use App\Services\MistServerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RemoveMistStreamJob implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  // for production
//  public int $timeout = 60;
//  public int $tries = 10;
//  public int $backoff = 60;

  // for testing
  public int $timeout = 60;
  public int $tries = 3;
  public int $backoff = 2;

  protected $mistStream;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(MistStream $mistStream) {
    $this->mistStream = $mistStream;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle(MistServerService $mistServerService) {

    try {

      // Removing a single stream
      $streamName = $this->mistStream->name;
      $success = $mistServerService->removeStream($streamName);

      // Removing multiple streams
      // tec21: not currently implemented.
//      $streamNames = ["streamname_here", "another_streamname"];
//      $mistServerService->removeStream($streamNames);

      if (!$success) {
        // Handle the failure case
        Log::error("Failed to remove MistStream '{$streamName}' from server.");
        // Consider re-throwing an exception or marking the job for retry depending on your use case
      } else {
        // Handle the success case
        Log::info("Successfully removed MistStream '{$streamName}' from server.");
        // Perform any additional cleanup or follow-up actions as needed
      }
    } catch (\Exception $e) {
      Log::error("Exception when trying to remove MistStream '{$streamName}' from server: {$e->getMessage()}");
      // Optionally retry the job or handle the exception as needed
    }
  }
}
