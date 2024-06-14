<?php

namespace App\Jobs;

use App\Models\Schedule;
use App\Models\SchedulesIndex;
use App\Services\ScheduleService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class UpdateSchedulesAndIndexes implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Execute the job.
   */
  public function handle(): string {

    // Retrieve all broadcast dates from Redis and update the database
    $schedules = Schedule::with('content', 'scheduleRecurrenceDetails', 'scheduleIndexes')
        ->orderBy('start_dateTime_utc')
        ->get();

    foreach ($schedules as $schedule) {
      if ($schedule->content_type === 'App\Models\ShowEpisode') {
        $schedule->load('content.show');
      }

      $redisKey = 'schedule_broadcast_dates_' . $schedule->id;
      $cacheData = json_decode(Redis::get($redisKey), true);

      if ($cacheData) {
        // Format for storage
        $broadcastDatesData = [
            'modelType'       => $schedule->content_type,
            'modelId'         => $schedule->content_id,
            'priority'        => $schedule->priority, // Assuming the same priority for all schedules for simplicity
            'timezone'        => 'UTC',
            'durationMinutes' => $schedule->duration_minutes,
            'broadcastDates'  => $cacheData['dates']
        ];

        $closestBroadcastDate = $cacheData['closestBroadcastDate'];

        $this->updateScheduleAndIndex($schedule, $broadcastDatesData, $closestBroadcastDate);
        Redis::del($redisKey); // Clear the data from Redis after updating
      }


    }

//    Log::debug('UpdateSchedulesAndIndexes completed successfully.');
    return "UpdateSchedulesAndIndexes completed successfully.";
  }

  protected function updateScheduleAndIndex($schedule, $broadcastDates, $closestBroadcastDate): void {
    $schedule->broadcast_dates = json_encode($broadcastDates);
    $schedule->save();

    if ($closestBroadcastDate) {
      $contentType = $schedule->content_type;
      $contentId = $schedule->content_id;
      $teamId = match ($contentType) {
        'App\Models\Show', 'App\Models\Movie' => $schedule->content->team_id ?? null,
        'App\Models\ShowEpisode' => $schedule->content->show->team_id ?? null,
        default => null,
      };

      try {
        SchedulesIndex::updateOrCreate(
            ['schedule_id' => $schedule->id],
            [
                'next_broadcast' => $closestBroadcastDate,
                'content_type'   => $contentType,
                'content_id'     => $contentId,
                'team_id'        => $teamId ?? null
            ]
        );
      } catch (\Exception $e) {
        Log::error('Failed to update schedule index', [
            'schedule_id'        => $schedule->id,
            'error'              => $e->getMessage(),
            'team_id'            => $teamId
        ]);
      }
    }
  }
}
