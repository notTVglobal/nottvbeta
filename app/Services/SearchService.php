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

    Log::info('Episodes fetched', ['Model Type' => $modelType, 'ID' => $id, 'Count' => $filteredEpisodes->count()]);
    return ShowEpisodeResource::collection($filteredEpisodes->values()->all());
  }

//  public function searchShowEpisodes(string $modelType, $id, string $query): AnonymousResourceCollection {
//    Log::debug('Search Episodes Initiated', ['Model Type' => $modelType, 'ID' => $id, 'Query' => $query]);
//    if ($modelType === 'team') {
//      $team = Team::with('shows.category', 'shows.subCategory')->find($id);
//      Log::debug('Team Loaded', ['Exists' => $team ? 'Yes' : 'No']);
//      if (!$team) {
//        return ShowEpisodeResource::collection(collect());
//      }
//      $episodes = $team->shows()
//          ->whereHas('showEpisodes', function ($q) use ($query) {
//            $q->where('name', 'like', '%' . $query . '%')
//                ->orWhere('description', 'like', '%' . $query . '%');
//          })
//          ->with('showEpisodes.image.appSetting', 'showEpisodes.video.appSetting')
//          ->paginate(10);
//
//
//      Log::debug('Episodes fetched for Team', ['Count' => $episodes->count()]);
//
//      return ShowEpisodeResource::collection($episodes);
//    } elseif ($modelType === 'show') {
//      $show = Show::with('category', 'subCategory', 'image.appSetting')->find($id);
//
//      Log::debug('Show Loaded', ['Exists' => $show ? 'Yes' : 'No']);
//
//      if (!$show) {
//        return ShowEpisodeResource::collection(collect());
//      }
//      $episodes = $show->showEpisodes()
//          ->where('name', 'like', '%' . $query . '%')
//          ->orWhere('description', 'like', '%' . $query . '%')
//          ->with('image.appSetting')
//          ->paginate(10);
//
//      Log::debug('Episodes fetched for Show', ['Count' => $episodes->count()]);
//
//      return ShowEpisodeResource::collection($episodes);
//    }
//
//    Log::debug('No valid model type provided');
//
//    return ShowEpisodeResource::collection(collect());
//  }


//  public function searchShowEpisodes(Model $model, string $query): AnonymousResourceCollection {
//    // Check if the model is a Team and adjust the query accordingly
//
//    if ($model instanceof Team) {
//      $episodes = $model->shows()
//          ->whereHas('showEpisodes', function ($q) use ($query) {
//            $q->where('name', 'like', '%' . $query . '%')
//                ->orWhere('description', 'like', '%' . $query . '%');
//          })
//          ->select('show_episodes.*') // Make sure to select show episodes fields
//          ->with('image.appSetting') // Assuming each episode has an associated image
//          ->paginate(10); // Apply pagination
//
//      // Return a collection of resources
//      return ShowEpisodeResource::collection($episodes);
//    }
//
//    // Otherwise, handle it as a Show directly
//    $episodes = $model->showEpisodes()
//        ->with('image.appSetting')
//        ->where('name', 'like', '%' . $query . '%')
//        ->orWhere('description', 'like', '%' . $query . '%')
//        ->orderBy('release_dateTime', 'desc')
//        ->paginate(10);
//
//    // Return a collection of resources
//    return ShowEpisodeResource::collection($episodes);
//  }

  private function transformImage($image, $appSetting): array {
    return [
        'id'              => $image->id,
        'name'            => $image->name,
        'folder'          => $image->folder,
        'cdn_endpoint'    => $appSetting->cdn_endpoint,
        'cloud_folder'    => $image->cloud_folder,
        'placeholder_url' => $image->placeholder_url,
    ];
  }


























//
//  protected TeamShowSearchService $teamShowSearchService;
//
//  public function __construct(TeamShowSearchService $teamShowSearchService)
//  {
//    $this->teamShowSearchService = $teamShowSearchService;
//  }


  public function searchTeamShowEpisodes(Request $request, $teamId): \Illuminate\Http\JsonResponse {
    $team = Team::findOrFail($teamId);
    $query = $request->query('query', '');
    $results = $this->teamShowSearchService->searchEpisodes($team, $query);
    return response()->json($results);
  }





  /**
   * Perform a dynamic search on any model that supports it.
   *
   * @param Model $model The model instance equipped with a search method.
   * @param string $query The search query.
   * @param string $type The type of search to perform, if applicable.
   * @return Collection The search results.
   */
  public function search(Model $model, string $query, string $type = 'default'): Collection {
    Log::debug('Initiating search', ['model' => get_class($model), 'type' => $type, 'query' => $query]);

    if (method_exists($model, 'search')) {
      return $model->search($query, $type);
    }

    Log::error('Search method not implemented on the model', ['model' => get_class($model)]);
    return new Collection();  // Return an empty collection if the model doesn't support searching
  }


  /**
   * Search for show episodes based on provided model (Team or Show) and query string.
   *
   * @param Model $model Either a Team or Show instance
   * @param string $query Search query
   * @return LengthAwarePaginator
   */
  protected function searchEpisodes(Model $model, string $query): LengthAwarePaginator {

//    Log::debug('Searching episodes for team', ['team_id' => $team->id, 'query' => $query]);

// Execute a query on the ShowEpisode model
    $results = ShowEpisode::query()
        // Select relevant fields from the show_episodes table and the slug from the shows table.
        ->select('show_episodes.id', 'show_episodes.name', 'show_episodes.description',
            'show_episodes.release_dateTime', 'show_episodes.slug', 'shows.slug as show_slug')
        ->join('shows', 'show_episodes.show_id', '=', 'shows.id') // Join with the shows table to access show-specific fields.
        ->with([
            'image.appSetting', // Eagerly load the image relationship and its related appSetting for each episode.
        ])
        ->where('shows.team_id', $this->id) // Filter to include only episodes from shows of this team.
        ->whereIn('shows.show_status_id', [1, 2]) // Filter shows that are either new (1) or active (2).
        ->where('show_episodes.show_episode_status_id', 7) // Include only episodes that are published.
        ->where(function ($queryBuilder) use ($query) {
          // Add filters for matching episode titles or descriptions
          $queryBuilder->where('show_episodes.name', 'like', '%' . $query . '%')
              ->orWhere('show_episodes.description', 'like', '%' . $query . '%')
              ->orWhere(function ($dateQuery) use ($query) {
                // Additional nested conditionals for date searching.
                // Check different formats of the date string provided by the user.
                if ($date = $this->tryCarbonParse($query, 'Y-m')) {
                  $dateQuery->whereYear('show_episodes.release_dateTime', $date->year)
                      ->whereMonth('show_episodes.release_dateTime', $date->month);
                } elseif ($date = $this->tryCarbonParse($query)) {
                  $dateQuery->whereDate('show_episodes.release_dateTime', $date->toDateString());
                }
              });
        })
        ->orderBy('show_episodes.release_dateTime', 'desc') // Order the results by release date in descending order.
        ->paginate(10) // Paginate the results to show 10 episodes per page.
        ->get() // Get the results without pagination
        ->map(fn($showEpisode) => $this->transformShowEpisode($showEpisode));

    Log::debug('Episodes found', ['count' => $results->count()]);
  }

  private function transformShowEpisode($showEpisode): array {
    return [
        'id'              => $showEpisode->id,
        'name'            => $showEpisode->name,
        'description'     => $showEpisode->description,
        'slug'            => $showEpisode->team_id,
        'category'        => [
            'name'        => $showEpisode->show->category->name,
            'description' => $showEpisode->show->category->description,
        ],
        'subCategory'     => [
            'name'        => $showEpisode->show->subCategory->name,
            'description' => $showEpisode->show->subCategory->description,
        ],
        'image'           => $this->transformImage($showEpisode->image, $showEpisode->appSetting),
        'showName'        => $showEpisode->show->name,
        'showSlug'        => $showEpisode->show->slug,
        'releaseDateTime' => $showEpisode->release_dateTime,
    ];
  }


  /**
   * Attempts to parse a given date string into a Carbon date object.
   * Handles different formats by attempting to parse using both specified format and general parsing.
   *
   * @param string $date The date string to parse.
   * @param string|null $format An optional date format to try before falling back to general parsing.
   * @return Carbon|null Returns a Carbon instance if parsing succeeds, or null if it fails.
   */
  protected function tryCarbonParse(string $date, string $format = null): ?Carbon {
    try {
      // Attempt to create a date with a specific format or parse normally if no format is provided.
      return $format ? Carbon::createFromFormat($format, $date) : Carbon::parse($date);
    } catch (\Exception $e) {
      Log::debug('Date parsing failed', ['date' => $date, 'format' => $format, 'error' => $e->getMessage()]);

      // Return null if parsing fails, allowing the calling function to handle the failure appropriately.
      return null;
    }
  }

}
