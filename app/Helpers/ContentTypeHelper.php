<?php
// app/Helpers/ContentTypeHelper.php
namespace App\Helpers;

class ContentTypeHelper {
  public static function normalizeContentType($type): ?string {
    $contentTypeMap = [
        'showepisode'  => 'App\Models\ShowEpisode',
        'show'         => 'App\Models\Show',
        'movie'        => 'App\Models\Movie',
        'movietrailer' => 'App\Models\MovieTrailer',
        'newsstory'    => 'App\Models\NewsStory',
        'othercontent' => 'App\Models\OtherContent',
    ];

    $normalizedType = strtolower(str_replace('App\\Models\\', '', $type));

    return $contentTypeMap[$normalizedType] ?? null;
  }
}
