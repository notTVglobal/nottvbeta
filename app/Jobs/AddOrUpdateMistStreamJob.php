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

    // Determine the correct name to use
    $streamNameForService = $this->originalName ?? $this->mistStreamData['name'];

    // Find the stream by its original name or another stable identifier
//    $stream = MistStream::firstWhere('name', $this->originalName)->firstOrNew([
//        'name' => $this->streamData['name'],
//        Arr::except($this->streamData, 'metadata') // We handle 'metadata' separately
//    ]);

    // Use where() to get a query builder instance, then use firstOrNew to find or create the model
    $stream = MistStream::where('name', $streamNameForService)->firstOrNew();
    // Now update or set attributes directly
    $stream->fill(Arr::except($this->mistStreamData, ['metadata']));
    // Handle 'metadata' if it's part of your streamData
//    if (isset($this->streamData['metadata'])) {
//      $metadata = collect($this->streamData['metadata'])->mapWithKeys(function ($item) {
//        return [$item['key'] => $item['value']];
//      })->toArray();
//      $stream->metadata = $metadata;
//    }
    // Transform 'metadata' from the submitted array of key-value pairs to an associative array
    $metadata = collect($this->mistStreamData['metadata'] ?? [])->mapWithKeys(function ($item) {
      return [$item['key'] => $item['value']];
    })->toArray();

    $stream->metadata = $metadata;
    // Save changes to the database
    $stream->save();


//    if (!$stream) {
//      // Handle the case where the stream doesn't exist possibly due to a name change or other reasons
//      $stream = new MistStream;
//    }

//    // Update the stream's attributes except 'metadata'
//    $stream->fill(Arr::except($this->streamData, ['metadata']));


    // Attempt to update an existing stream by name or create a new instance
//    $stream = MistStream::updateOrCreate(
//        ['name' => $this->streamData['name']],
//        Arr::except($this->streamData, 'metadata') // We handle 'metadata' separately
//    );

    // Transform 'metadata' from the submitted array of key-value pairs to an associative array
//    $metadata = collect($this->streamData['metadata'] ?? [])->mapWithKeys(function ($item) {
//      return [$item['key'] => $item['value']];
//    })->toArray();

    // Update the stream's metadata with the transformed array and save it
//    $stream->metadata = $metadata;

    // Save changes to the model
//    $stream->save();

    // Prepare the stream details for the MistServer API call,
    // including the potentially new name and the updated metadata
    // Since $metadata is already an associative array, it can be directly merged with other details
    $streamDetails = [
            'name' => $stream->name,
            'source' => $stream->source,
        ] + $metadata; // Ensure metadata updates are included


    // Optionally filter out null values if explicitly unsetting parameters is not intended
    // This is especially important if your metadata transformation could introduce null values
    // that you don't want to send to the MistServer
//    $streamDetails = array_filter($streamDetails, function ($value) {
//      return !is_null($value);
//    });

    // Call the service to add or update the stream on the MistServer with the prepared details
    $response = $mistServerService->addOrUpdateStream($streamNameForService, $streamDetails);

    // Check the response to determine if the operation was successful
    if ($response) {
      Log::info("Successfully added/updated MistStream '{$stream->name}' on MistServer.");
    } else {
      Log::error("Failed to add/update MistStream '{$stream->name}' on MistServer.");
    }
//
//    // Check if we are updating an existing stream or need to create a new one
//    if (!empty($this->mistStream->id)) {
//      $stream = MistStream::find($this->mistStream->id);
//      // Assuming the stream exists since we have an ID
//    } else {
//      // Create a new MistStream instance if we're adding rather than updating
//      $stream = new MistStream();
//    }
//
//    // Set the name and source from the job's mistStream property
//    $stream->name = $this->mistStream->name;
//    $stream->source = $this->mistStream->source;
//
//    // Merge new metadata with existing metadata if we're updating
//    if (!empty($this->mistStream->metadata)) {
//      $stream->metadata = !empty($stream->metadata) ?
//          array_merge($stream->metadata, $this->mistStream->metadata) :
//          $this->mistStream->metadata;
//    }
//
//    // Save changes to the database
//    $stream->save();
//
//    // Prepare the stream details for the MistServer API call
//    $streamDetails = [
//        "name" => $stream->name,
//        "source" => $stream->source,
//      // Additional metadata will be merged below
//    ];
//
//    // Merge optional metadata if available
//    if (!empty($stream->metadata)) {
//      $streamDetails = array_merge($streamDetails, $stream->metadata);
//    }
//
//    // Optionally filter out null values if explicitly unsetting parameters is not intended
//    $streamDetails = array_filter($streamDetails, function ($value) {
//      return !is_null($value);
//    });
//
//    // Call the service to add or update the stream on the MistServer
//    $response = $mistServerService->addOrUpdateStream($stream->name, $streamDetails);
//
//    // Handle the response from the MistServerService
//    if (!$response) {
//      Log::error("Failed to add/update MistStream '{$stream->name}' on server.");
//    } else {
//      Log::info("Successfully added/updated MistStream '{$stream->name}' on server.");
//    }

  }
}
