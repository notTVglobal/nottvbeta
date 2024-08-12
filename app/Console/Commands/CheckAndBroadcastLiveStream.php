<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Models\SchedulesIndex;
use App\Services\MistServer\PlaybackService;
use App\Services\BroadcastAutomationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckAndBroadcastLiveStream extends Command {

  protected $signature = 'broadcast:check-and-broadcast';
  protected $description = 'Check scheduled shows and broadcast if the live stream is connected';

  protected PlaybackService $playbackService;
  protected BroadcastAutomationService $broadcastService;

  public function __construct() {
    parent::__construct();
    $this->playbackService = app(PlaybackService::class);
    $this->broadcastService = app(BroadcastAutomationService::class);
  }


  public function handle(): void {

    try {

      $now = Carbon::now()->utc()->startOfMinute();
      $nextMinute = $now->copy()->addMinute();

//      Log::debug('CheckAndBroadcastLiveStream: Checking for scheduled broadcasts', [
//          'current_time' => $now->toDateTimeString(),
//      ]);

      // Get schedules that are supposed to start within the next minute
      $schedules = SchedulesIndex::whereBetween('next_broadcast', [$now, $nextMinute])
          ->with(['content', 'content.mistStreamWildcard'])
          ->get();

      if ($schedules->isEmpty()) {
//        Log::debug('CheckAndBroadcastLiveStream: No scheduled broadcasts found for the next minute');

        return;
      }

      // If there are multiple schedules, sort by priority from the schedules table
      $schedules = $schedules->sortBy('priority');

//      Log::debug('CheckAndBroadcastLiveStream: Found scheduled broadcasts', [
//          'schedule_count' => $schedules->count(),
//      ]);

      // Process the schedule with the highest priority (lowest number)
      $this->processSchedule($schedules->first());

    } catch (Exception $e) {
      Log::error('CheckAndBroadcastLiveStream: Error during scheduled broadcast check', [
          'error' => $e->getMessage(),
      ]);
    }
  }


  protected function processSchedule($scheduleIndex): void {

    try {

      $mistStreamName = $scheduleIndex->content->mistStreamWildcard->name;

//      Log::debug('CheckAndBroadcastLiveStream: Processing schedule', [
//          'schedule_id'      => $scheduleIndex->id,
//          'mist_stream_name' => $mistStreamName,
//      ]);

      $activeStreams = $this->playbackService->activeStreams();

      if (in_array($mistStreamName, $activeStreams)) {
//        Log::debug('CheckAndBroadcastLiveStream: Stream is active, proceeding with broadcast', [
//            'mist_stream_name' => $mistStreamName,
//        ]);

        // Live stream is connected, broadcast the event
        $this->broadcastLiveStream($scheduleIndex);

      } else {
//        Log::debug('CheckAndBroadcastLiveStream: Stream is not active, initiating fallback mechanism', [
//            'mist_stream_name' => $mistStreamName,
//        ]);

        // Live stream not connected, start fallback mechanism
        $this->startFallback($scheduleIndex);
      }
    } catch (Exception $e) {
      Log::error('CheckAndBroadcastLiveStream: Error processing schedule', [
          'error'       => $e->getMessage(),
          'schedule_id' => $scheduleIndex->id ?? null,
      ]);
    }
  }


  protected function broadcastLiveStream($scheduleIndex): void {
    $this->broadcastService->updateAndBroadcast($scheduleIndex);

//    Log::debug('CheckAndBroadcastLiveStream: Stream broadcasted', [
//        'schedule_id' => $scheduleIndex->id,
//    ]);
  }


  protected function startFallback($scheduleIndex): void {
    $retryInterval = 30; // seconds
    $maxRetries = 10; // 5 minutes / 30 seconds = 10 retries

    try {

//      Log::debug('CheckAndBroadcastLiveStream: Starting fallback mechanism', [
//          'schedule_id'    => $scheduleIndex->id,
//          'retry_interval' => $retryInterval,
//          'max_retries'    => $maxRetries,
//      ]);

      // TODO: Set the firstPlay stream to 'standby'. Use the scheduled show name as firstPlayVideoName/customVideoName.
      //   Create the standby stream and implement it here.

      // Begin fallback loop
      for ($retry = 0; $retry < $maxRetries; $retry++) {
        sleep($retryInterval);

        $activeStreams = $this->playbackService->activeStreams()['active_streams'] ?? [];
        $mistStreamName = $scheduleIndex->content->mistStreamWildcard->name;

//        Log::debug('CheckAndBroadcastLiveStream: Fallback retry', [
//            'retry_number'     => $retry + 1,
//            'mist_stream_name' => $mistStreamName,
//        ]);

        if (in_array($mistStreamName, $activeStreams)) {
//          Log::debug('CheckAndBroadcastLiveStream: Stream became active during fallback, broadcasting', [
//              'mist_stream_name' => $mistStreamName,
//          ]);

          $this->broadcastLiveStream($scheduleIndex);

          return;
        }
      }

      // After 5 minutes, if the stream is still not connected
//      Log::debug('CheckAndBroadcastLiveStream: Stream did not become active after retries, setting to test', [
//          'schedule_id' => $scheduleIndex->id,
//      ]);

      $this->setStreamToTest($scheduleIndex);

    } catch (Exception $e) {
      Log::error('CheckAndBroadcastLiveStream: Error during fallback process', [
          'error'       => $e->getMessage(),
          'schedule_id' => $scheduleIndex->id ?? null,
      ]);
    }
  }


  protected function setStreamToTest($scheduleIndex): void {
    // TODO: Set the firstPlay stream to "test"
    //   Copy the standby stream implementation here but make it the 'test' stream.

    try {
      // Log that the stream was set to test
//      Log::debug('CheckAndBroadcastLiveStream: Stream set to test', [
//          'schedule_id'     => $scheduleIndex->id,
//          'new_stream_name' => 'test',
//      ]);
    } catch (Exception $e) {
      Log::error('CheckAndBroadcastLiveStream: Error while setting stream to Test', [
          'error'       => $e->getMessage(),
          'schedule_id' => $scheduleIndex->id ?? null,
      ]);
    }
  }

}
