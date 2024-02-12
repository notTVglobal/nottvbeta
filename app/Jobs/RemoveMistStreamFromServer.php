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

class RemoveMistStreamFromServer implements ShouldQueue {
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
        "deletestream" => [
            0 => $this->mistStream->name
        ]
    ];

    try {
      $response = $mistServerService->send($data);

      // Assuming the MistServerService returns null or a different structure in case of connection issues
      if (!$response || isset($response['error'])) {
        $errorMessage = $response['error'] ?? 'Unknown error occurred';
        Log::error("Failed to remove MistStream '{$this->mistStream->name}' from server: {$errorMessage}");
      } else {
        // Check for specific response indicating success or lack of the stream
        if (isset($response['status']) && $response['status'] === 'OK') {
          Log::info("Successfully removed MistStream '{$this->mistStream->name}' from server.");
        } else {
          // Handle other statuses or missing stream name cases
          $statusMessage = $response['status'] ?? 'No specific status received';
          Log::error("Failed to remove MistStream '{$this->mistStream->name}' from server due to: {$statusMessage}");
        }
      }
    } catch (\Exception $e) {
      Log::error("Exception when trying to remove MistStream '{$this->mistStream->name}' from server: {$e->getMessage()}");
    }
  }
}
