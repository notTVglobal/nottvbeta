<?php

namespace App\Console\Commands;

use App\Models\MistStreamPushDestination;
use Illuminate\Console\Command;

class MistPushOneTimeUpdateMistStreamPushDestinationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mistPush:updatePushDestinations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update show_id, stream_name, and full_push_uri for MistStreamPushDestinations.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
      $destinations = MistStreamPushDestination::with('mistStreamWildcard.show')->get();

      foreach ($destinations as $destination) {
        // Ensure related models are loaded
        if ($destination->mistStreamWildcard && $destination->mistStreamWildcard->show) {
          $showId = $destination->mistStreamWildcard->show->id;
          $streamName = $destination->mistStreamWildcard->name;
          // Assuming you want to concatenate rtmp_url and rtmp_key
          $fullPushUri = $destination->rtmp_url . $destination->rtmp_key;

          // Update this destination with new values
          $destination->update([
              'show_id' => $showId,
              'stream_name' => $streamName,
              'full_push_uri' => $fullPushUri,
          ]);
        }
      }

      $this->info('MistStreamPushDestinations updated successfully.');
    }
}
