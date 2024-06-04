<?php

namespace App\Services;

use App\Events\ChangeFirstPlayVideo;
use App\Models\AppSetting;
use App\Models\Channel;
use App\Models\SchedulesIndex;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FirstPlayService
{
  public function checkAndUpdateChannels(): void {
    // Get the current time and round to the nearest half-hour
    $now = Carbon::now();
    $roundedNow = $now->copy()->minute(0)->second(0)->subMinutes($now->minute % 30);

    // Get all active channels
    $channels = Channel::where('active', true)->get();

    foreach ($channels as $channel) {
      $videoDetails = $this->getVideoDetailsForChannel($channel, $roundedNow);
      if ($videoDetails) {
        // Update the AppSetting with the new first play settings
        $appSetting = AppSetting::first();
        $appSetting->first_play_settings = json_encode($videoDetails);
        $appSetting->save();

        // Broadcast the change event
        event(new ChangeFirstPlayVideo($videoDetails));
      } else {
        Log::info('No suitable video found for channel: ' . $channel->id);
      }


      // Check for the next broadcast within the 1-minute window
      $scheduleIndex = SchedulesIndex::whereBetween('next_broadcast', [
          $roundedNow->subMinute(),
          $roundedNow->addMinute(),
      ])->first();

      if ($scheduleIndex) {
        $model = $scheduleIndex->model; // Retrieve the related model

        $videoDetails = $this->constructVideoDetails($model);

        // Update the AppSetting with the new first play settings
        $appSetting = AppSetting::first();
        $appSetting->first_play_settings = json_encode($videoDetails);
        $appSetting->save();

        // Broadcast the change event
        event(new ChangeFirstPlayVideo($videoDetails));
      } else {
        Log::info('No broadcast found within the time window.');
      }
    }
  }

  protected function getVideoDetailsForChannel($channel, $roundedNow)
  {
    return match ($channel->playback_priority_type) {
      'MistStream' => $this->getMistStreamDetails($channel),
      'ExternalSource' => $this->getExternalSourceDetails($channel),
      'ChannelPlaylist' => $this->getChannelPlaylistDetails($channel, $roundedNow),
      default => $this->getDefaultMistStreamDetails(),
    };
  }

  protected function getMistStreamDetails($channel)
  {
    // Return MistStream details
    return [
        'type' => 'mist_stream',
        'id' => $channel->mist_stream_id,
        'title' => $channel->mist_stream_title,
      // Add other relevant fields
    ];
  }

  protected function getExternalSourceDetails($channel)
  {
    // Return ExternalSource details
    return [
        'type' => 'external_source',
        'url' => $channel->external_source_url,
        'title' => $channel->external_source_title,
      // Add other relevant fields
    ];
  }

  protected function getChannelPlaylistDetails($channel, $roundedNow)
  {
    if ($channel->channelPlaylist->useSchedule) {
      // Check the ScheduleIndex for the current item
      $scheduleIndex = ScheduleIndex::where('channel_id', $channel->id)
          ->whereBetween('next_broadcast', [
              $roundedNow->subMinute(),
              $roundedNow->addMinute(),
          ])->first();

      if ($scheduleIndex) {
        return $this->constructVideoDetails($scheduleIndex->model);
      }
    }

    // Get the next item from the ChannelPlaylist
    $nextItem = $channel->channelPlaylist->nextItem();
    if ($nextItem) {
      return $this->constructVideoDetails($nextItem);
    }

    return null;
  }

  protected function getDefaultMistStreamDetails()
  {
    // Return default MistStream details
    return [
        'type' => 'mist_stream',
        'id' => 'test',
        'title' => 'Default Test Stream',
      // Add other relevant fields
    ];
  }

  protected function constructVideoDetails($model): array {
    // Construct the video details based on the type of the model
    if ($model instanceof \App\Models\Video) {
      return [
          'type' => 'video',
          'id' => $model->id,
          'title' => $model->title,
          'url' => $model->url,
        // Add other relevant fields
      ];
    } elseif ($model instanceof \App\Models\MistStreamWildcard) {
      return [
          'type' => 'mist_stream_wildcard',
          'id' => $model->id,
          'stream_key' => $model->stream_key,
          'title' => $model->title,
        // Add other relevant fields
      ];
    }

    return [];
  }
}
