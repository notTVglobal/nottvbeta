<?php

namespace App\Console\Commands;

use App\Events\PushDataFetched;
use App\Services\MistServerService;
use App\Services\PushDestinationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MistServerFetchPushData extends Command
{

  private MistServerService $mistServerService;
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'mistServer:fetchPushData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch push data from MistServer and update the database';


  public function __construct(MistServerService $mistServerService) {
        parent::__construct();
    $this->mistServerService = $mistServerService;

  }


  /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(PushDestinationService $pushDestinationService): int {
      $activePushes = $this->mistServerService->getActivePushList();
//      $pushDestinationService->fetchPushAutoList();
      // Log the output or dump to the console for inspection
//      Log::debug('Active pushes fetched:', $activePushes);
//      $this->line('Active pushes fetched: ' . print_r($activePushes, true));

//      foreach ($activePushes as $push) {
//        $pushId = $push[0];
//        $streamName = $push[1];
//        $originalURI = $push[2];
//        $processedURI = $push[3]; // After triggers/substitutions
//        $logs = $push[4]; // Consider how to use or store these for debugging
//        $pushStatus = $push[5]; // Contains status details like 'bytes', 'active_seconds', etc.


        // TODO: Check this command to ensure its efficient.
        // How many Active Pushes are there?
        // Do we need to optimize this?
        // Could the updateDestinationRecord be moved into a Listener and run as a Job?
        // we do have a Listener associated with this process: UpdatePushDestinations


        // Check if a record already exists for this push ID
        // If the record exists, update it with the latest data
//        $activePush = \App\Models\MistServerActivePush::updateOrCreate(
//            ['push_id' => $pushId],
//            [
//                'stream_name' => $streamName,
//                'original_uri' => $originalURI,
//                'processed_uri' => $processedURI,
//                'logs' => $logs,
//                'push_status' => $pushStatus,
//            ]
//        );

        // Process each push
//        $pushDestinationService->updateDestinationRecord($activePush);

        // Dispatch an event with the fetched data
//      event(new PushDataFetched($activePush));
//      }


      // Dispatch an event with the fetched data
//      event(new PushDataFetched($activePushes));

      return 0; // Indicates successful execution

    }
}
