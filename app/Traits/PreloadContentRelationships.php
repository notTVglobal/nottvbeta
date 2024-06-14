<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait PreloadContentRelationships
{
  /**
   * Preload additional relationships based on content type.
   *
   * @param Model $model
   * @return void
   */
  public function preloadContentRelationships(Model $model): void
  {
    if (is_null($model->content)) {
      return; // Exit if content is null
    }

    switch ($model->content_type) {
      case 'App\Models\Show':
        $model->content->loadMissing([
            'image.appSetting', 'team.image.appSetting'
        ]);
        break;

      case 'App\Models\ShowEpisode':
        $model->content->loadMissing([
            'show.image.appSetting', 'show.team.image.appSetting', 'image.appSetting', 'video.appSetting'
        ]);
        break;

      case 'App\Models\Movie':
        $model->content->loadMissing([
            'trailers', 'image.appSetting', 'team.image.appSetting', 'video.appSetting'
        ]);
        break;

      case 'App\Models\OtherContent':
      case 'App\Models\NewsStory':
        $model->content->loadMissing([
            'image.appSetting', 'video.appSetting'
        ]);
        break;

      default:
        // Handle other content types or log an error if necessary
        break;
    }
  }
}
