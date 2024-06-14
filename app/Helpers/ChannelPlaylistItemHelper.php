<?php
// app/Helpers/PlaylistItemCreator.php
namespace App\Helpers;

use App\Models\ChannelPlaylistItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class ChannelPlaylistItemHelper {

  protected static array $contentTypeMap = [
      'showepisode'  => 'App\Models\ShowEpisode',
      'show'         => 'App\Models\Show',
      'miststream'   => 'App\Models\MistStream',
      'movie'        => 'App\Models\Movie',
      'movietrailer' => 'App\Models\MovieTrailer',
      'newsstory'    => 'App\Models\NewsStory',
      'othercontent' => 'App\Models\OtherContent',
  ];

  /**
   * @throws Exception
   */
  public static function createOrUpdateItems($scheduleItems, $channelPlaylistId): array {

    Log::debug('Processing schedule items', ['scheduleItems' => $scheduleItems]);

    $channelPlaylistItems = [];
    foreach ($scheduleItems as $index => $item) {
      $itemStartDateTime = Carbon::parse($item['start_dateTime'])->format('Y-m-d H:i:s');
      $itemEndDateTime = Carbon::parse($item['end_dateTime'])->format('Y-m-d H:i:s');

      // Normalize the content type key
      $contentTypeKey = strtolower(class_basename($item['type']));
      Log::debug('Processing item', ['contentTypeKey' => $contentTypeKey]);
      $contentType = self::$contentTypeMap[$contentTypeKey] ?? null;

      if (!$contentType) {
        throw new Exception('Invalid content type: ' . $item['type']);
      }

      // Eager load related models except for MistStream
      if ($contentTypeKey === 'miststream') {
        $content = $contentType::find($item['content']['id']);
      } else {
        $content = $contentType::with(['video.appSetting', 'mistStreamWildcard'])->find($item['content']['id']);
      }
      if (!$content) {
        throw new Exception('Content not found for id: ' . $item['content']['id']);
      }
      $item['content'] = $content;

      // Ensure 'is_scheduled' key exists
      $item['is_scheduled'] = $item['is_scheduled'] ?? false;

      // Set media properties
      $item = self::setMediaProperties($item);

      $channelPlaylistItem = ChannelPlaylistItem::updateOrCreate(
          ['id' => $item['id'] ?? null],
          [
              'playlist_id'             => $channelPlaylistId,
              'content_id'              => $item['content']['id'],
              'content_type'            => $contentType,
              'order'                   => $index + 1,  // Assuming the order is based on the index
              'media_type'              => $item['media_type'],
              'source_path'             => $item['source_path'],
              'source_type'             => $item['source_type'],
              'is_live'                 => false, // Adjust based on your requirements
              'is_scheduled'            => $item['is_scheduled'] ?? false,
              'current_viewers_count'   => 0,
              'max_viewers_count'       => 0,
              'additional_sources'      => null, // Adjust based on your requirements
              'custom_playback_options' => null, // Adjust based on your requirements
              'metadata'                => null, // Adjust based on your requirements
              'has_played'              => false,
              'start_dateTime'          => $itemStartDateTime,
              'end_dateTime'            => $itemEndDateTime,
              'duration_minutes'        => $item['duration_minutes'],
          ]
      );

      $channelPlaylistItems[] = $channelPlaylistItem;
    }

    return $channelPlaylistItems;
  }


  /**
   * @throws Exception
   */
  private static function setMediaProperties($item) {
    $content = $item['content'];

    if ($content instanceof \App\Models\MistStream) {
      $item['source_path'] = $content->name;
      $item['source_type'] = "application/vnd.apple.mpegURL";
      $item['media_type'] = "mistStream";
    } else {
      $isScheduled = $item['is_scheduled'];

      if ($isScheduled) {
        if ($content->mistStreamWildcard) {
          $item['source_path'] = $content->mistStreamWildcard->name;
          $item['source_type'] = "application/vnd.apple.mpegURL";
          $item['media_type'] = "mistStream";
        }
      } else {
        $video = $content->video;
        if ($video && $video->appSetting) {
          $item['source_path'] = $video->appSetting->cdn_endpoint . $video->cloud_folder . $video->folder . '/' . $video->file_name;
          $item['source_type'] = $video->type;
          $item['media_type'] = "vod";
        } else {
          throw new Exception('Video or appSetting not found for content id: ' . $content->id . ' of type ' . get_class($content));
        }
      }
    }

    return $item;
  }

  public static function removeUnusedItems($channelPlaylistId, $currentItemIds): void {
    ChannelPlaylistItem::where('playlist_id', $channelPlaylistId)
        ->whereNotIn('id', $currentItemIds)
        ->delete();
  }
}
