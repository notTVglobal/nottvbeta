<?php

namespace App\Traits;

use App\Models\Schedule;

trait PreloadScheduleContentRelationships
{
  /**
   * Preload additional relationships based on content type.
   *
   * @param Schedule $schedule
   * @return void
   */
  public function preloadContentRelationships(Schedule $schedule): void
  {
    if (is_null($schedule->content)) {
      return; // Exit if content is null
    }

    switch ($schedule->content_type) {
      case 'App\Models\Show':
        $schedule->content->loadMissing([
            'image.appSetting', 'team'
        ]);
        break;

      case 'App\Models\ShowEpisode':
        $schedule->content->loadMissing([
            'show.image.appSetting', 'image.appSetting',
        ]);
        break;

      case 'App\Models\Movie':
        $schedule->content->loadMissing([
            'trailers', 'image.appSetting'
        ]);
        break;

      default:
        // Handle other content types or log an error if necessary
        break;
    }
  }
}
