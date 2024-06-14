<?php
// app/Helpers/RequestValidator.php
namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChannelPlaylistRequestValidator {
  protected static array $contentTypeMap = [
      'showepisode'  => 'App\Models\ShowEpisode',
      'show'         => 'App\Models\Show',
      'miststream'   => 'App\Models\MistStream',
      'movie'        => 'App\Models\Movie',
      'movietrailer' => 'App\Models\MovieTrailer',
      'newsstory'    => 'App\Models\NewsStory',
      'othercontent' => 'App\Models\OtherContent',
  ];

  public static function validatePlaylistRequest(Request $request, $channelPlaylistId = null): \Illuminate\Validation\Validator {
    $uniqueNameRule = $channelPlaylistId ? 'unique:channel_playlists,name,' . $channelPlaylistId : 'unique:channel_playlists,name';

    $validator = Validator::make($request->all(), [
        'name'                             => 'required|string|max:255|' . $uniqueNameRule,
        'description'                      => 'nullable|string',
        'url'                              => 'nullable|url',
        'type'                             => 'required|string|in:regular,event,special',
        'start_dateTime'                   => 'required|date',
        'end_dateTime'                     => 'required|date|after_or_equal:start_dateTime',
        'priority'                         => 'required|integer',
        'repeat_mode'                      => 'required|string|in:repeat_all,repeat_last,shuffle,stop,next_playlist',
        'next_playlist_id'                 => 'nullable|exists:channel_playlists,id',
        'scheduleItems.*.content.id'       => 'required|string', // Accepting both integers and UUIDs as strings
        'scheduleItems.*.type'             => 'required|string',
        'scheduleItems.*.start_dateTime'   => 'required|date',
        'scheduleItems.*.end_dateTime'     => 'required|date|after_or_equal:scheduleItems.*.start_dateTime',
        'scheduleItems.*.duration_minutes' => 'required|integer',
    ]);

    $validator->after(function ($validator) use ($request) {
      foreach ($request->scheduleItems as $index => $item) {
        $contentTypeKey = strtolower(class_basename($item['type']));
        $contentType = self::$contentTypeMap[$contentTypeKey] ?? null;

        if ($contentType) {
          if ($contentTypeKey !== 'miststream') { // Skip MistStream type
            $content = $contentType::with(['video', 'mistStreamWildcard'])->find($item['content']['id']);
            if (!$content || (!$content->video && !$content->mistStreamWildcard)) {
              $contentId = $item['content']['id'];
              $contentName = $content->name ?? 'Unnamed Content';
              $contentTypeName = class_basename($contentType);
              $validator->errors()->add(
                  'scheduleItems.' . $index . '.content',
                  'The content "' . $contentName . '" (ID: ' . $contentId . ') of type "' . $contentTypeName . '" must have either a mistStreamWildcard or a video.'
              );
            }
          }
        } else {
          $validator->errors()->add('scheduleItems.' . $index . '.type', 'Invalid content type: ' . $item['type']);
        }
      }
    });

    return $validator;
  }
}
