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
      Log::debug("MistServer response", ['response' => $response]);

      // Assuming the MistServerService returns null or a different structure in case of    connection issues
      if (!$response || isset($response['error'])) {
        // Handle case where there's an error or no response
        $errorMessage = $response['error'] ?? 'Unknown error occurred';
        Log::error("Failed to remove MistStream '{$this->mistStream->name}' from server: {$errorMessage}");
      } else {
        // Since MistServer does not include deleted streams in the response, check for the "incomplete list" indicator
        if (isset($response["incomplete list"]) && $response["incomplete list"] == 1) {
          // Assuming successful deletion if "incomplete list" is present. You might want to check if the stream specifically is absent if possible.
          Log::info("Successfully removed MistStream '{$this->mistStream->name}' from server.");
        } else {
          // Log a generic error or a more specific message if you have additional logic to determine failure reasons
          $statusMessage = $response['status'] ?? 'No specific status received';
          Log::error("Failed to remove MistStream '{$this->mistStream->name}' from server due to: {$statusMessage}");
        }
      }
    } catch (\Exception $e) {
      Log::error("Exception when trying to remove MistStream '{$this->mistStream->name}' from server: {$e->getMessage()}");
    }
  }
}
