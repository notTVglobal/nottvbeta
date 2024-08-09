<?php

namespace App\Jobs;

use App\Models\Schedule;
use App\Models\SchedulesIndex;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ProcessSingleScheduleAndIndex implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

  protected Schedule $schedule;

  /**
   * Create a new job instance.
   *
   * @param Schedule $schedule
   */
  public function __construct(Schedule $schedule) {
    $this->schedule = $schedule;
  }

  /**
   * Execute the job.
   */
  public function handle(): void {

    $schedule = $this->schedule;

    // Check if the job is part of a batch and if the batch has been cancelled
    if ($this->batch() && $this->batch()->cancelled()) {
      Log::info('Batch cancelled, job will not run', ['scheduleId' => $schedule->id]);

      return;
    }

    if ($schedule->content_type === 'App\Models\ShowEpisode') {
      $schedule->load('content.show');
    }

    $redisKey = 'schedule_broadcast_dates_' . $schedule->id;
    $cacheData = json_decode(Redis::connection()->get($redisKey), true);

    if ($cacheData) {
      $broadcastDatesData = [
          'modelType'       => $schedule->content_type,
          'modelId'         => $schedule->content_id,
          'priority'        => $schedule->priority,
          'timezone'        => 'UTC',
          'durationMinutes' => $schedule->duration_minutes,
          'broadcastDates'  => $cacheData['dates'],
      ];

      $closestBroadcastDate = $cacheData['closestBroadcastDate'];

      $this->updateScheduleAndIndex($schedule, $broadcastDatesData, $closestBroadcastDate);
//      Redis::del($redisKey);
      Redis::connection()->command('del', [$redisKey]);

    }
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
        // Check if the closest broadcast date is in the past
        $nextBroadcast = now()->lessThan($closestBroadcastDate) ? $closestBroadcastDate : null;

        $scheduleIndex = SchedulesIndex::updateOrCreate(
            ['schedule_id' => $schedule->id],
            [
                'next_broadcast' => $nextBroadcast,
                'content_type'   => $contentType,
                'content_id'     => $contentId,
                'team_id'        => $teamId ?? null,
            ]
        );

        if (is_null($scheduleIndex->next_broadcast_details)) {
          $scheduleIndex->next_broadcast_details = [];
        }

        $nextBroadcastDetails = $scheduleIndex->next_broadcast_details;
        $nextBroadcastDetails['duration_minutes'] = $schedule->duration_minutes;
        $scheduleIndex->next_broadcast_details = $nextBroadcastDetails;

        $scheduleIndex->save();
      } catch (\Exception $e) {
        Log::error('Failed to update schedule index', [
            'schedule_id' => $schedule->id,
            'error'       => $e->getMessage(),
            'team_id'     => $teamId,
        ]);
      }
    }
  }
}
