<?php

namespace App\Services;

use App\Http\Resources\ShowEpisodeResource;
use App\Models\Show;
use App\Models\Team;
use App\Models\ShowEpisode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SearchService {

  /**
   * Search for show episodes based on provided model (Team or Show) and query string.
   *
   * @param string $modelType
   * @param $id
   * @param string $query Search query
   * @return AnonymousResourceCollection
   */

  public function searchShowEpisodes(string $modelType, $id, string $query): AnonymousResourceCollection {
    // Define cache keys based on the model type and ID
    $cacheKey = "{$modelType}_episodes_{$id}";

    // Attempt to get the data from cache
    $episodes = Cache::remember($cacheKey, 300, function () use ($modelType, $id) {
      if ($modelType === 'team') {
        $team = Team::with(['shows.showEpisodes' => function ($query) {
          $query->where('show_episode_status_id', 7)  // Filter for published episodes
          ->with('image.appSetting');
        },
            'shows.category', 'shows.subCategory', 'shows.image.appSetting'])->find($id);
        return $team ? $team->shows->flatMap->showEpisodes : collect();
      } elseif ($modelType === 'show') {
        $show = Show::with(['showEpisodes' => function ($query) {
          $query->where('show_episode_status_id', 7)  // Filter for published episodes
          ->with('image.appSetting');
        },
            'category', 'subCategory', 'image.appSetting'])->find($id);
        return $show ? $show->showEpisodes : collect();
      }
      return collect();
    });

    // Apply query filtering on the cached data
    $filteredEpisodes = $episodes->filter(function ($episode) use ($query) {
      return str_contains(strtolower($episode->name), strtolower($query)) ||
          str_contains(strtolower($episode->description), strtolower($query));
    });

    // Sort globally by date across all fetched episodes
    $sortedEpisodes = $filteredEpisodes->sortByDesc('release_dateTime');
//    Log::debug('Episodes fetched', ['Model Type' => $modelType, 'ID' => $id, 'Count' => $filteredEpisodes->count()]);
    return ShowEpisodeResource::collection($sortedEpisodes->values()->all());
  }

}
