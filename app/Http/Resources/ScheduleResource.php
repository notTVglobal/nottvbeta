<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray($request): array {
    return [
        'id'                 => $this->id,
        'created_at'         => $this->created_at,
        'type'               => $this->type,
        'start_dateTime'     => $this->start_dateTime->setTimezone($this->timezone)->format('c'),
        'end_dateTime'       => $this->end_dateTime->setTimezone($this->timezone)->format('c'),
        'timezone'           => $this->timezone,
        'start_dateTime_utc' => $this->start_dateTime_utc->format('c'),
        'end_dateTime_utc'   => $this->end_dateTime_utc->format('c'),
        'priority'           => $this->priority,
        'duration_minutes'   => $this->duration_minutes,
        'status'             => $this->status,
        'recurrence_flag'    => $this->recurrence_flag,
        'broadcast_dates'    => $this->getBroadcastDates(),
        'content'            => $this->transformContent($this->content),
    ];
  }

  private function getBroadcastDates(): array {
    $broadcastDates = json_decode($this->broadcast_dates, true);

    return $broadcastDates['broadcastDates'] ?? [];
  }

  private function transformContent($content): array {
    if (!$content) {
      return []; // Return empty array if no content is associated
    }

    // Direct transformation using appropriate resource based on content type
    return match ($content->getMorphClass()) {
      'App\Models\ShowEpisode' => (new SimpleShowEpisodeResource($content))->resolve(),
      'App\Models\Movie' => (new SimpleMovieResource($content))->resolve(),
      'App\Models\Show' => (new SimpleShowResource($content))->resolve(),
      default => [],
    };
  }
}
