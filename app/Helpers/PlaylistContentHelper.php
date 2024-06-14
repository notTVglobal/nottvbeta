<?php

namespace App\Helpers;

use App\Http\Resources\SimpleMovieResource;
use App\Http\Resources\SimpleNewsStoryResource;
use App\Http\Resources\SimpleShowEpisodeResource;
use App\Http\Resources\SimpleShowResource;
use Exception;

class PlaylistContentHelper
{
  /**
   * @throws Exception
   */
  public static function getFormattedContent($item, $type): mixed {
    // Ensure the type is fully qualified
    if (!str_starts_with($type, 'App\\Models\\')) {
      $type = 'App\\Models\\' . $type;
    }

    return match ($type) {
      'App\Models\ShowEpisode' => new SimpleShowEpisodeResource($item),
      'App\Models\Show' => new SimpleShowResource($item),
      'App\Models\MistStream' => $item,
      'App\Models\Movie' => new SimpleMovieResource($item),
      'App\Models\MovieTrailer' => $item, // Return the item as it is
      'App\Models\NewsStory' => new SimpleNewsStoryResource($item),
      'App\Models\OtherContent' => $item, // Return the item as it is
      default => throw new Exception('Invalid content type'),
    };
  }
}