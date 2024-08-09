<?php

namespace App\Services;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BroadcastAutomationService {
  public function updateAndBroadcast($scheduleIndex): void {

    try {

      // Log the beginning of the broadcast process
      Log::debug('BroadcastAutomationService: Starting broadcast process', [
          'schedule_id'      => $scheduleIndex->id,
          'mist_stream_name' => $scheduleIndex->content->mistStreamWildcard->name,
      ]);

      // Retrieve necessary data
      $mistStreamName = $scheduleIndex->content->mistStreamWildcard->name;
      $contentName = $scheduleIndex->content->name;

      // Create source
      $source = 'https://mist.nottv.io/hls/' . $mistStreamName . '/index.m3u8';
      $sourceType = 'application/x-mpegURL';

      // Prepare the settings
      $appSetting = AppSetting::find(1);

      // Prepare the settings array for updating the first play configuration.
      // This array contains both deprecated fields for legacy support and new fields for current functionality.

      $updateData = [
        // The 'first_play_settings' array holds the main configuration for the first play feature.
        // These settings are nested to organize related data in a single structure.
          'first_play_settings'          => [
            // The 'channelId' indicates the channel associated with the first play.
            // In this implementation, it is statically set to 1, but it could be dynamic in other contexts.
              'channelId'             => 1, // Static value for now... this BroadcastAutomationService is meant to be replaced by the FirstPlayService.

            // The 'useCustomVideo' flag determines whether a custom video source is used
            // instead of the default channel video. This is currently set to true.
              'useCustomVideo'        => true, // Assuming this is the default setting

            // 'customVideoSource' specifies the URL or path to the custom video stream.
              'customVideoSource'     => $source, // Set this if you're using a custom video source

            // 'customVideoSourceType' defines the type of the custom video stream (e.g., 'application/x-mpegURL').
              'customVideoSourceType' => $sourceType, // Set this based on the custom video type

            // 'customVideoName' provides a name or title for the custom video being played.
              'customVideoName'       => $contentName, // Set this to the custom video name

            // 'customMediaType' categorizes the media type for the custom video, such as 'firstPlay'.
              'customMediaType'       => 'firstPlay', // Set this to the custom media type
          ],

        // Deprecated fields (still included for legacy support):
        // These fields were part of the original configuration structure but are now deprecated.
        // They are still populated to ensure compatibility with older parts of the system.
          'first_play_channel_id'        => 1,
          'first_play_video_source'      => $source,
          'first_play_video_source_type' => $sourceType,
          'first_play_video_name'        => $contentName,
          'first_play_media_type'        => 'firstPlay',
      ];

      // Log the update data before updating
      Log::debug('BroadcastAutomationService: Prepared update data', [
          'update_data' => $updateData,
      ]);

      // Update the settings
      $appSetting->update($updateData);

      // Cache the settings
      $this->cacheSettings($appSetting);

      // Log before broadcasting
      Log::debug('BroadcastAutomationService: Broadcasting event for first play video', [
          'mist_stream_name' => $mistStreamName,
          'content_name'     => $contentName ?? 'Unknown',
      ]);

      // Trigger the event
      event(new ChangeFirstPlayVideo((object) [
          'source'    => $updateData['first_play_video_source'],
          'mediaType' => $updateData['first_play_media_type'],
          'name'      => $updateData['first_play_video_name'],
      ]));

      // Log completion of the broadcast process
      Log::debug('BroadcastAutomationService: Broadcast process completed successfully', [
          'schedule_id' => $scheduleIndex->id,
      ]);

    } catch (Exception $e) {
      Log::error('BroadcastAutomationService: Error during broadcast process', [
          'error'       => $e->getMessage(),
          'schedule_id' => $scheduleIndex->id ?? null,
      ]);
    }
  }

  protected function cacheSettings($appSetting): void {

    try {

      $jsonFilePath = 'json/firstPlayData.json';
      Cache::forget('firstPlayData');
      Storage::disk('local')->put($jsonFilePath, json_encode($appSetting->toArray(), JSON_PRETTY_PRINT));

      // Log caching of the settings
      Log::debug('BroadcastAutomationService: Cached first play settings', [
          'file_path' => $jsonFilePath,
      ]);

    } catch (Exception $e) {
      Log::error('BroadcastAutomationService: Error caching first play settings', [
          'error' => $e->getMessage(),
      ]);
    }
  }
}
