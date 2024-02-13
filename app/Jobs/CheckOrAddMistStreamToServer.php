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

class CheckOrAddMistStreamToServer implements ShouldQueue {
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

    $data = [
        "addstream" => [
            $this->mistStream->name => [
                "source" => $this->mistStream->source
              // Add additional options here if necessary
            ]
        ]
    ];

    try {
      $response = $mistServerService->send($data);

      if (isset($response['error'])) {
        Log::error("Failed to add MistStream '{$this->mistStream->name}' to server: " . $response['error']);
      } else {
        // Optionally update the $mistStream object or log success
        // For example, log the success or update the database accordingly
        Log::info("Successfully added MistStream '{$this->mistStream->name}' to server.");
      }
    } catch (\Exception $e) {
      Log::error("Failed to add MistStream '{$this->mistStream->name}' to server: {$e->getMessage()}");
    }

  }
}
