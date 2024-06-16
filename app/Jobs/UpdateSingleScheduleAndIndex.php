<?php

namespace App\Jobs;

use App\Models\Schedule;
use App\Models\SchedulesIndex;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class UpdateSingleScheduleAndIndex implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected int $scheduleId;

  public function __construct(int $scheduleId) {
    $this->scheduleId = $scheduleId;
  }

  public function handle(): void {
    $schedule = Schedule::with('content', 'scheduleRecurrenceDetails', 'scheduleIndexes')->find($this->scheduleId);

    if (!$schedule) {
      Log::error('Schedule not found', ['schedule_id' => $this->scheduleId]);
      return;
    }

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

  protected function updateScheduleAndIndex($schedule, array $broadcastDates, $closestBroadcastDate): void {
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
        // Update or create the schedule index
        $scheduleIndex = SchedulesIndex::updateOrCreate(
            ['schedule_id' => $schedule->id],
            [
                'next_broadcast' => $closestBroadcastDate,
                'content_type'   => $contentType,
                'content_id'     => $contentId,
                'team_id'        => $teamId ?? null
            ]
        );

        // Initialize next_broadcast_details if null
        if (is_null($scheduleIndex->next_broadcast_details)) {
          $scheduleIndex->next_broadcast_details = [];
        }

        $nextBroadcastDetails = $scheduleIndex->next_broadcast_details; // Get the current value
        $nextBroadcastDetails['duration_minutes'] = $schedule->duration_minutes; // Modify it
        $scheduleIndex->next_broadcast_details = $nextBroadcastDetails; // Set the entire property

        $scheduleIndex->save();
      } catch (\Exception $e) {
        Log::error('Failed to update schedule index', [
            'schedule_id' => $schedule->id,
            'error'       => $e->getMessage(),
            'team_id'     => $teamId
        ]);
      }
    }
  }
}
