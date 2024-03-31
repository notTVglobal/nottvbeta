<?php

namespace App\Console\Commands;

use App\Factories\MistServerServiceFactory;
use App\Models\MistServerActivePush;
use App\Models\MistStreamPushDestination;
use App\Models\MistStreamWildcard;
use App\Services\MistServer\MistServerService;
use Exception;
use Illuminate\Console\Command;
use App\Services\MistServer\PushService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MistPushUpdatePushDataCommand extends Command {
  protected $signature = 'mistPush:updatePushData';
  protected $description = 'Update push data from MistServer Push Server';

  protected MistServerService $pushService;
  protected array $insertUpdateActivePushes = [];
  protected array $updatePushDestinations = [];
  protected array $updateStreamWildcards = [];

  public function __construct() {
    parent::__construct();
    $this->pushService = MistServerServiceFactory::make('push');
  }

  /**
   * Execute the console command.
   *
   * @return void
   * @throws Exception
   */
  public function handle(): void {

    try {

      // 1. Delete all existing records in the table
      MistServerActivePush::query()->delete();
// If you need to reset auto-increment values
      DB::statement('ALTER TABLE mist_server_active_pushes AUTO_INCREMENT = 1;');

// 2. Fetch the new push list from the MistServer
      $newPushList = $this->pushService->fetchPushList();
      $newPushListItems = collect($newPushList['push_list'] ?? []);

// 3. Repopulate the table with the new data
      foreach ($newPushListItems as $newPushListItem) {
        // Assuming $newPushListItem is an array with the required information
        $streamName = $newPushListItem[1];
        $originalUri = $newPushListItem[2];
        $processedUri = $newPushListItem[3];
        $logs = $newPushListItem[4];
        $pushStatus = $newPushListItem[5];

        // Create a new record in MistServerActivePush
        MistServerActivePush::create([
            'push_id'       => $newPushListItem[0], // Assuming the ID is the first element
            'stream_name'   => $streamName,
            'original_uri'  => $originalUri,
            'processed_uri' => $processedUri,
            'logs'          => $logs,
            'push_status'   => $pushStatus,
        ]);
      }
      // Fetch all records from the table
      $allActivePushes = MistServerActivePush::all();
      // Save the data to the cache. You can specify a desired cache duration.
      Cache::put('all_active_pushes', $allActivePushes, now()->addMinutes(10));

      // 2. Update Auto Push List -> mist_server_auto_pushes
           // refresh the whole table every minute
           // save the whole list in the cache every 10 seconds
      // 3. Get the MistStreamPushDestinations::all()
      // 3. Update MistStreamPushDestinations where:
              // stream_name = pushList->streamName
              // full_push_uri = pushList->originalUri
           // update...
              // mist_push_id
              // push_is_started
              // has_auto_push
      // 4. Cache the MistStreamPushDestinations



      // Fetch the new push list from MistServer

//      $newPushListIds = collect($newPushList['push_list'])->pluck('0')->toArray();

      // Retrieve cached push list to compare
      $cachedPushList = Cache::get('pushList', []);
      $cachedPushListItems = collect($cachedPushList['push_list'] ?? []);
//      Log::debug('Cached Push List', [$cachedPushList]);

      // Extract IDs for comparison
      $newPushListIds = $newPushListItems->pluck('0');
      $cachedPushListIds = $cachedPushListItems->pluck('0');

      // Determine new and removed IDs
      $newIds = $newPushListIds->diff($cachedPushListIds);
      $removedIds = $cachedPushListIds->diff($newPushListIds);

      // Cache the new push list
      Cache::put('pushList', $newPushList, now()->addMinutes(10));

      // Cache the necessary data from each table
//      $activePushes = MistServerActivePush::all();
//      $pushDestinations = MistStreamPushDestination::all();
//      $streamWildcards = MistStreamWildcard::all();
//
//      Cache::put('active_pushes', $activePushes, now()->addMinutes(10));
//      Cache::put('push_destinations', $pushDestinations, now()->addMinutes(10));
//      Cache::put('stream_wildcards', $streamWildcards, now()->addMinutes(10));
//
//


      // Log IDs for debugging
//      Log::debug('Cached Push List IDs.', ['IDs' => $cachedPushListIds]);
//      Log::debug('New Push IDs', ['New IDs' => $newIds]);
//      Log::debug('Removed Push IDs', ['Removed IDs' => $removedIds]);


      // Process removed IDs: Mark records as removed or delete in the database
      foreach ($removedIds as $removedId) {
        $itemDetails = $cachedPushListItems->firstWhere('0', $removedId);
        if ($itemDetails) {
          $streamName = $itemDetails[1];
          $originalUri = $itemDetails[2];

          // tec21: we don't really need this
          // Find the corresponding MistServerActivePush record and delete or update
          $activePush = MistServerActivePush::where('push_id', $removedId)->first();
          if ($activePush) {
            $activePush->delete();
          }

          // Similarly, find and update/delete the MistStreamPushDestination record
          $pushDestination = MistStreamPushDestination::where('stream_name', $streamName)
              ->where('full_push_uri', $originalUri)
              ->first();

          if ($pushDestination) {
            $pushDestination->update([
                'push_is_started' => 0,
                'mist_push_id' => null,
            ]);
          }

          // TODO: Fix how we flag active recodings and save them in the database and broadcast them.
//          // tec21: this isn't working properly,
          // it is being filtered above and won't include any recordings...
          // this needs to be overhauled.
          //
//          // Check if originalUri contains 'recording'
//          if (Str::contains($originalUri, 'recordings')) {
//            $mistStreamWildcard = MistStreamWildcard::find($pushDestination->mist_stream_wildcard_id);
//            if ($mistStreamWildcard) {
//              $mistStreamWildcard->is_recording = 0;
//              $mistStreamWildcard->save();
//            }
//          }


        }
      }
      // Process new IDs: Fetch detailed data and update the database
      foreach ($newIds as $newId) {
        $itemDetails = $newPushListItems->firstWhere('0', $newId);
        if ($itemDetails) {
          $streamName = $itemDetails[1];
          $originalUri = $itemDetails[2];
          $processedUri = $itemDetails[3];
          $logs = $itemDetails[4];
          $pushStatus = $itemDetails[5];

//          Log::info('Item details:', ['details' => $itemDetails]);

          // Update or create a record in MistServerActivePush
          $activePush = MistServerActivePush::updateOrCreate(
              ['push_id' => $newId],
              [
                  'stream_name'   => $streamName,
                  'original_uri'  => $originalUri,
                  'processed_uri' => $processedUri,
                  'logs'          => $logs,
                  'push_status'   => $pushStatus,
              ]
          );

          // Find the corresponding MistStreamPushDestination record and update it
          $pushDestination = MistStreamPushDestination::where('stream_name', $streamName)
              ->where('full_push_uri', $originalUri)
              ->first();

          if ($pushDestination) {
            $pushDestination->update([
              // Update fields as necessary, for example:
                'push_is_started' => 1,
                'mist_push_id' => $newId,
            ]);

            // Check if originalUri contains 'recordings'
            if (Str::contains($originalUri, 'recordings')) {
              $mistStreamWildcard = MistStreamWildcard::find($pushDestination->mist_stream_wildcard_id);
              if ($mistStreamWildcard) {
                $mistStreamWildcard->is_recording = 1;
                $mistStreamWildcard->save();
              }
              Log::warning("Recording found for stream name {$streamName} and URI {$originalUri}.");
            }

          } else {
            Log::warning("Push destination not found for stream name {$streamName} and URI {$originalUri}.");
          }
        }
      }

      $this->info('Push data updated and cached successfully.');
    } catch (Exception $e) {
      $this->error('Failed to update push data: ' . $e->getMessage());
    }

  }
//
//
//  public function processPushListAndUpdateDatabase($newPushList)
//  {
//    // Retrieve cached data
//    $cachedActivePushes = Cache::get('active_pushes');
//    $cachedPushDestinations = Cache::get('push_destinations');
//    $cachedStreamWildcards = Cache::get('stream_wildcards');
//
//    // Convert to collections for easier manipulation
//    $newPushListItems = collect($newPushList['push_list']);
//    $newPushListIds = $newPushListItems->pluck(0)->toArray();
//
//    // Extract IDs for comparison from cached data
//    $cachedPushListIds = $cachedActivePushes->pluck('push_id')->toArray();
//
//    // Determine new and removed IDs
//    $newIds = array_diff($newPushListIds, $cachedPushListIds);
//    $removedIds = array_diff($cachedPushListIds, $newPushListIds);
//
//    // Prepare batch operations
//    $insertUpdateActivePushes = [];
//    $updatePushDestinations = [];
//    $updateStreamWildcards = [];
//
//    // Process new IDs
//    foreach ($newIds as $newId) {
//      $itemDetails = $newPushListItems->firstWhere(0, $newId);
//      $streamName = $itemDetails[1];
//      $originalUri = $itemDetails[2];
//
//      // Prepare for upsert in MistServerActivePush
//      $insertUpdateActivePushes[] = [
//          'push_id' => $newId,
//          'stream_name' => $streamName,
//          'original_uri' => $originalUri,
//        // Include other fields as necessary
//      ];
//
//      // Prepare updates for MistStreamPushDestination and MistStreamWildcard
//      $pushDestination = $cachedPushDestinations->firstWhere('stream_name', $streamName);
//      if ($pushDestination) {
//        $updatePushDestinations[] = [
//            'id' => $pushDestination->id,
//            'full_push_uri' => $originalUri,
//          // Other fields to update
//        ];
//      }
//
//      $streamWildcard = $cachedStreamWildcards->firstWhere('name', $streamName);
//      if ($streamWildcard) {
//        $updateStreamWildcards[] = [
//            'id' => $streamWildcard->id,
//          // Fields to update
//        ];
//      }
//    }
//
//    // Process removed IDs similarly...
//
//    // Execute batch operations
//    // Upsert active pushes
//    MistServerActivePush::upsert($insertUpdateActivePushes, ['push_id'], ['stream_name', 'original_uri']);
//
//    // Batch update push destinations and stream wildcards if necessary
//
//    // Note: Laravel doesn't have a built-in method for batch updating by ID,
//    // so you might need to loop through each record or use a custom query.
//
//    // Cache the updated lists again for future comparisons
//    Cache::put('active_pushes', MistServerActivePush::all(), now()->addMinutes(10));
//    // Repeat for other cached data
//  }
//
//  public function executeBatchOperations()
//  {
//    // Assuming $insertUpdateActivePushes, $updatePushDestinations, and $updateStreamWildcards are already prepared
//    // For demonstration, let's say they're class properties or retrieved from somewhere
//
//    // Execute Batch Inserts/Updates for MistServerActivePush
//    MistServerActivePush::upsert($this->insertUpdateActivePushes, 'push_id', ['stream_name', 'original_uri']);
//
//    // Execute Batch Updates for MistStreamPushDestination
//    foreach ($this->updatePushDestinations as $destinationUpdate) {
//      MistStreamPushDestination::where('id', $destinationUpdate['id'])
//          ->update($destinationUpdate);
//    }
//
//    // Execute Batch Updates for MistStreamWildcard
//    foreach ($this->updateStreamWildcards as $wildcardUpdate) {
//      MistStreamWildcard::where('id', $wildcardUpdate['id'])
//          ->update($wildcardUpdate);
//    }
//
//    // Execute Deletions if necessary
//    // Assuming $removedIds is an array of IDs to delete
//    MistServerActivePush::whereIn('push_id', $this->removedIds)->delete();
//    // Repeat for other models as necessary
//
//    // Update Cache (Optional)
//    // If your application heavily relies on these data, consider updating the cache after operations
//    $this->updateCache();
//  }
//
//  protected function updateCache()
//  {
//    // Update cached lists of active pushes, push destinations, and stream wildcards
//    Cache::put('active_pushes', MistServerActivePush::all(), now()->addMinutes(10));
//    // Repeat for other cached data as needed
//  }
//




















  // TODO: Make MistPushUpdatePushDataCommand more efficient.

  //  public function handle(): void {
//    try {
//      // Step 1: Fetch and Compare Push List Data
//      $pushList = $this->fetchAndComparePushList();
//
//      // Step 2: Prepare Batch Operations
//      $this->prepareBatchOperations($pushList);
//
//      // Step 3: Execute Batch Operations
//      $this->executeBatchOperations();
//
//      $this->info('Push data updated and cached successfully.');
//    } catch (Exception $e) {
//      $this->error('Failed to update push data: ' . $e->getMessage());
//    }
//  }
//
//  private function fetchAndComparePushList(): array {
//    $newPushList = $this->pushService->fetchPushList();
//    $cachedPushList = Cache::get('pushList', []);
//
//    // Logic to compare $newPushList with $cachedPushList and determine new, existing, and removed IDs
//    // Example:
//    // $newIds = array_diff($newPushListIds, $cachedPushListIds);
//    // $removedIds = array_diff($cachedPushListIds, $newPushListIds);
//
//    try {
//
//      // Fetch the new push list from MistServer
//      $pushList = $this->pushService->fetchPushList();
//      $newPushListItems = collect($pushList['push_list']);
//      $newPushListIds = collect($pushList['push_list'])->pluck('0')->toArray();
//
//      // Retrieve cached push list to compare
//      $cachedPushList = Cache::get('pushList', []);
//      Log::debug('Cached Push List', [$cachedPushList]);
//      $cachedPushListItems = collect($cachedPushList['push_list'] ?? []);
//
//      // Extract IDs for comparison
//      $newPushListIds = $newPushListItems->pluck('0');
//      $cachedPushListIds = $cachedPushListItems->pluck('0');
//
//      // Retrieve cached push list to compare
//      $cachedPushList = Cache::get('pushList', []);
//      $cachedPushListItems = collect($cachedPushList['push_list'] ?? []);
//
//      // Determine new and removed IDs
//      $newIds = $newPushListIds->diff($cachedPushListIds);
//      $removedIds = $cachedPushListIds->diff($newPushListIds);
//
//      // Cache the new push list
//      Cache::put('pushList', $pushList, now()->addMinutes(10));
//
//      // Cache the necessary data from each table
//      $activePushes = MistServerActivePush::all();
//      $pushDestinations = MistStreamPushDestination::all();
//      $streamWildcards = MistStreamWildcard::all();
//
//      Cache::put('active_pushes', $activePushes, now()->addMinutes(10));
//      Cache::put('push_destinations', $pushDestinations, now()->addMinutes(10));
//      Cache::put('stream_wildcards', $streamWildcards, now()->addMinutes(10));
//
//      $this->info('Fetch push list and cache all data.');
//    } catch (Exception $e) {
//      $this->error('Failed to fetch push list and cache all data: ' . $e->getMessage());
//    }
//
//    // For simplicity, returning $newPushList
//    return $newPushList;
//  }
//
//  private function prepareBatchOperations(array $pushList): void {
//    // Based on comparison, prepare data for batch inserts/updates/deletes
//    // Example:
//    // $this->insertUpdateActivePushes = [...];
//    // $this->updatePushDestinations = [...];
//    // $this->removedIds = [...];
//  }
//
//  private function executeBatchOperations(): void {
//    // Execute prepared batch operations
//    // Example:
//    // MistServerActivePush::upsert($this->insertUpdateActivePushes, 'push_id', [...]);
//    // MistStreamPushDestination::whereIn('id', [...])->update([...]);
//    // MistServerActivePush::whereIn('push_id', $this->removedIds)->delete();
//
//    // Optionally, update the cache
//    Cache::put('pushList', $this->pushService->fetchPushList(), now()->addMinutes(10));
////  }
//
//
//
//
//
//
//
//
//
//
//
//
//
//    // Compare and update based on new push list
////    $this->processPushListAndUpdateDatabase($pushList);
//
//    try {
////
////      // Fetch the new push list from MistServer
////      $pushList = $this->pushService->fetchPushList();
////      $newPushListItems = collect($pushList['push_list']);
////      $newPushListIds = collect($pushList['push_list'])->pluck('0')->toArray();
////
////      // Retrieve cached push list to compare
////      $cachedPushList = Cache::get('pushList', []);
////      Log::debug('Cached Push List', [$cachedPushList]);
////      $cachedPushListItems = collect($cachedPushList['push_list'] ?? []);
////
////      // Extract IDs for comparison
////      $newPushListIds = $newPushListItems->pluck('0');
////      $cachedPushListIds = $cachedPushListItems->pluck('0');
////
////      // Retrieve cached push list to compare
////      $cachedPushList = Cache::get('pushList', []);
////      $cachedPushListItems = collect($cachedPushList['push_list'] ?? []);
////
////      // Determine new and removed IDs
////      $newIds = $newPushListIds->diff($cachedPushListIds);
////      $removedIds = $cachedPushListIds->diff($newPushListIds);
////
////      // Cache the new push list
////      Cache::put('pushList', $pushList, now()->addMinutes(10));
////
////      // Cache the necessary data from each table
////      $activePushes = MistServerActivePush::all();
////      $pushDestinations = MistStreamPushDestination::all();
////      $streamWildcards = MistStreamWildcard::all();
////
////      Cache::put('active_pushes', $activePushes, now()->addMinutes(10));
////      Cache::put('push_destinations', $pushDestinations, now()->addMinutes(10));
////      Cache::put('stream_wildcards', $streamWildcards, now()->addMinutes(10));
//
//
//
//
//
//
//      // Log IDs for debugging
//      Log::debug('Cached Push List IDs.', ['IDs' => $cachedPushListIds]);
//      Log::debug('New Push IDs', ['New IDs' => $newIds]);
//      Log::debug('Removed Push IDs', ['Removed IDs' => $removedIds]);
//
//      // Process new IDs: Fetch detailed data and update the database
//      foreach ($newIds as $newId) {
//        $itemDetails = $newPushListItems->firstWhere('0', $newId);
//        if ($itemDetails) {
//          $streamName = $itemDetails[1];
//          $originalUri = $itemDetails[2];
//          $processedUri = $itemDetails[3];
//          $logs = $itemDetails[4];
//          $pushStatus = $itemDetails[5];
//
//          // Update or create a record in MistServerActivePush
//          $activePush = MistServerActivePush::updateOrCreate(
//              ['push_id' => $newId],
//              [
//                  'stream_name'   => $streamName,
//                  'original_uri'  => $originalUri,
//                  'processed_uri' => $processedUri,
//                  'logs'          => $logs,
//                  'push_status'   => $pushStatus,
//              ]
//          );
//
//          // Find the corresponding MistStreamPushDestination record and update it
//          $pushDestination = MistStreamPushDestination::where('stream_name', $streamName)
//              ->where('full_push_uri', $originalUri)
//              ->first();
//
//          if ($pushDestination) {
//            $pushDestination->update([
//              // Update fields as necessary, for example:
//                'push_is_started' => true,
//            ]);
//
//            // Check if originalUri contains 'recording'
//            if (Str::contains($originalUri, 'recording')) {
//              $mistStreamWildcard = MistStreamWildcard::find($pushDestination->mist_stream_wildcard_id);
//              if ($mistStreamWildcard) {
//                $mistStreamWildcard->is_recording = true;
//                $mistStreamWildcard->save();
//              }
//            }
//
//          } else {
//            Log::warning("Push destination not found for stream name {$streamName} and URI {$originalUri}.");
//          }
//        }
//      }
//
//      // Process removed IDs: Mark records as removed or delete in the database
//      foreach ($removedIds as $removedId) {
//        $itemDetails = $cachedPushListItems->firstWhere('0', $removedId);
//        if ($itemDetails) {
//          $streamName = $itemDetails[1];
//          $originalUri = $itemDetails[2];
//
//          // Find the corresponding MistServerActivePush record and delete or update
//          $activePush = MistServerActivePush::where('push_id', $removedId)->first();
//          if ($activePush) {
//             $activePush->delete();
//          }
//
//          // Similarly, find and update/delete the MistStreamPushDestination record
//          $pushDestination = MistStreamPushDestination::where('stream_name', $streamName)
//              ->where('full_push_uri', $originalUri)
//              ->first();
//
//          if ($pushDestination) {
//            $pushDestination->update([
//                'push_is_started' => false,
//            ]);
//          }
//          // Check if originalUri contains 'recording'
//          if (Str::contains($originalUri, 'recording')) {
//            $mistStreamWildcard = MistStreamWildcard::find($pushDestination->mist_stream_wildcard_id);
//            if ($mistStreamWildcard) {
//              $mistStreamWildcard->is_recording = false;
//              $mistStreamWildcard->save();
//            }
//          }
//        }
//      }
//      $this->info('Push data updated and cached successfully.');
//    } catch (Exception $e) {
//      $this->error('Failed to update push data: ' . $e->getMessage());
//    }
//
//  }
//
//
//  public function processPushListAndUpdateDatabase($newPushList)
//  {
//    // Retrieve cached data
//    $cachedActivePushes = Cache::get('active_pushes');
//    $cachedPushDestinations = Cache::get('push_destinations');
//    $cachedStreamWildcards = Cache::get('stream_wildcards');
//
//    // Convert to collections for easier manipulation
//    $newPushListItems = collect($newPushList['push_list']);
//    $newPushListIds = $newPushListItems->pluck(0)->toArray();
//
//    // Extract IDs for comparison from cached data
//    $cachedPushListIds = $cachedActivePushes->pluck('push_id')->toArray();
//
//    // Determine new and removed IDs
//    $newIds = array_diff($newPushListIds, $cachedPushListIds);
//    $removedIds = array_diff($cachedPushListIds, $newPushListIds);
//
//    // Prepare batch operations
//    $insertUpdateActivePushes = [];
//    $updatePushDestinations = [];
//    $updateStreamWildcards = [];
//
//    // Process new IDs
//    foreach ($newIds as $newId) {
//      $itemDetails = $newPushListItems->firstWhere(0, $newId);
//      $streamName = $itemDetails[1];
//      $originalUri = $itemDetails[2];
//
//      // Prepare for upsert in MistServerActivePush
//      $insertUpdateActivePushes[] = [
//          'push_id' => $newId,
//          'stream_name' => $streamName,
//          'original_uri' => $originalUri,
//        // Include other fields as necessary
//      ];
//
//      // Prepare updates for MistStreamPushDestination and MistStreamWildcard
//      $pushDestination = $cachedPushDestinations->firstWhere('stream_name', $streamName);
//      if ($pushDestination) {
//        $updatePushDestinations[] = [
//            'id' => $pushDestination->id,
//            'full_push_uri' => $originalUri,
//          // Other fields to update
//        ];
//      }
//
//      $streamWildcard = $cachedStreamWildcards->firstWhere('name', $streamName);
//      if ($streamWildcard) {
//        $updateStreamWildcards[] = [
//            'id' => $streamWildcard->id,
//          // Fields to update
//        ];
//      }
//    }
//
//    // Process removed IDs similarly...
//
//    // Execute batch operations
//    // Upsert active pushes
//    MistServerActivePush::upsert($insertUpdateActivePushes, ['push_id'], ['stream_name', 'original_uri']);
//
//    // Batch update push destinations and stream wildcards if necessary
//
//    // Note: Laravel doesn't have a built-in method for batch updating by ID,
//    // so you might need to loop through each record or use a custom query.
//
//    // Cache the updated lists again for future comparisons
//    Cache::put('active_pushes', MistServerActivePush::all(), now()->addMinutes(10));
//    // Repeat for other cached data
//  }
//
//  public function executeBatchOperations()
//  {
//    // Assuming $insertUpdateActivePushes, $updatePushDestinations, and $updateStreamWildcards are already prepared
//    // For demonstration, let's say they're class properties or retrieved from somewhere
//
//    // Execute Batch Inserts/Updates for MistServerActivePush
//    MistServerActivePush::upsert($this->insertUpdateActivePushes, 'push_id', ['stream_name', 'original_uri']);
//
//    // Execute Batch Updates for MistStreamPushDestination
//    foreach ($this->updatePushDestinations as $destinationUpdate) {
//      MistStreamPushDestination::where('id', $destinationUpdate['id'])
//          ->update($destinationUpdate);
//    }
//
//    // Execute Batch Updates for MistStreamWildcard
//    foreach ($this->updateStreamWildcards as $wildcardUpdate) {
//      MistStreamWildcard::where('id', $wildcardUpdate['id'])
//          ->update($wildcardUpdate);
//    }
//
//    // Execute Deletions if necessary
//    // Assuming $removedIds is an array of IDs to delete
//    MistServerActivePush::whereIn('push_id', $this->removedIds)->delete();
//    // Repeat for other models as necessary
//
//    // Update Cache (Optional)
//    // If your application heavily relies on these data, consider updating the cache after operations
//    $this->updateCache();
//  }
//
//  protected function updateCache()
//  {
//    // Update cached lists of active pushes, push destinations, and stream wildcards
//    Cache::put('active_pushes', MistServerActivePush::all(), now()->addMinutes(10));
//    // Repeat for other cached data as needed
//  }

}


