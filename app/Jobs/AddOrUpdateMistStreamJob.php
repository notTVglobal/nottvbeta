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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class AddOrUpdateMistStreamJob implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  // for production
//  public int $timeout = 60;
//  public int $tries = 10;
//  public int $backoff = 60;

  // for testing
  public int $timeout = 60;
  public int $tries = 3;
  public int $backoff = 2;

  private array $mistStreamData;
  private ?string $originalName;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(array $mistStreamData, ?string $originalName) {
    $this->mistStreamData = $mistStreamData;
    $this->originalName = $originalName;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle(MistServerService $mistServerService) {
    // Initialize or ensure $this->mistStreamData is an array to prevent potential null access issues
    $this->mistStreamData = $this->mistStreamData ?? [];

    // Safely determine the correct name to use, avoiding null access
    $streamNameForService = $this->originalName ?? $this->mistStreamData['name'] ?? '';

    // Use where() to get a query builder instance, then use firstOrNew to find or create the model
    $stream = MistStream::where('name', $streamNameForService)->firstOrNew();

    // Update or set attributes directly, safely excluding 'metadata' from null access
    $stream->fill(Arr::except($this->mistStreamData, ['metadata']));

    Log::debug('MistStreamData before accessing', ['mistStreamData' => $this->mistStreamData]);

    $metadata = collect($this->mistStreamData['metadata'] ?? [])->mapWithKeys(function ($item) {
      // Check if both 'key' and 'value' exist in the $item array to prevent errors
      if (isset($item['key'], $item['value'])) {
        return [$item['key'] => $item['value']];
      } else {
        // Optionally log the issue or handle the case where 'key' or 'value' are missing
        Log::warning('Missing key or value in metadata item.', ['item' => $item]);
        // Return an empty array if the structure is not as expected
        return [];
      }
    })->toArray();

    // Assign processed metadata to the stream object
    $stream->metadata = $metadata;

    // Save changes to the database
    $stream->save();

    // Prepare the stream details for the MistServer API call,
    // ensuring metadata is correctly included even if it was initially null or not an array
    $streamDetails = [
            'name' => $stream->name,
            'source' => $stream->source,
          // Potentially other attributes here...
        ] + $metadata; // Ensure metadata updates are included

    // Call the service to add or update the stream on the MistServer with the prepared details
    $response = $mistServerService->addOrUpdateStream($streamNameForService, $streamDetails);

    // Check the response to determine if the operation was successful
    if ($response) {
      Log::info("Successfully added/updated MistStream '{$stream->name}' on MistServer.");
    } else {
      Log::error("Failed to add/update MistStream '{$stream->name}' on MistServer.");
    }
  }




}
