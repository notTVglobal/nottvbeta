<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use App\Models\Creator;
use App\Models\NewsStory;
use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
  protected SearchService $searchService;

  public function __construct(SearchService $searchService) {
    $this->searchService = $searchService;
  }


  public function searchShowEpisodes(Request $request, $modelType, $id): JsonResponse {
    // Validate the request data
    $validated = $request->validate([
        'query' => 'required|string|min:1|max:255', // Ensuring the query is a non-empty string
    ]);

    // Extract the validated query
    $query = $validated['query'];

    // Log the input values
//    Log::info('Starting episode search', [
//        'modelType' => $modelType,
//        'id' => $id,
//        'query' => $query
//    ]);


    $results = $this->searchService->searchShowEpisodes($modelType, $id, $query);

    // Log the results count
//    Log::info('Search results obtained', [
//        'results_count' => count($results),
//        'results' => $results // Be careful with logging large result sets
//    ]);

    return response()->json($results);
  }

  private function getModel($type, $id)
  {
    return $type === 'team' ? Team::with('shows.showEpisodes')->find($id) : Show::find($id);
  }






  public function search(Request $request, string $modelKey, string $slug): JsonResponse {
    $modelClass = $this->getModelClass($modelKey);
    if (!$modelClass) {
      return response()->json(['message' => 'Invalid model type'], 404);
    }

    $modelInstance = $modelClass::where('slug', $slug)->firstOrFail();
    $type = $request->query('type', 'default');
    $query = $request->query('query');

    $results = $this->searchService->search($modelInstance, $query, $type);

    return response()->json($results);
  }

  /**
   * Maps a type string to a model class.
   * Ensures that only valid model types specified in the URL are handled.
   *
   * @param string $type  The model type as specified in the URL.
   * @return string|null  Returns the full namespace of the model class if found, or null if not.
   */
  protected function getModelClass(string $type): ?string {
    $models = [
        'teams' => Team::class,
        'shows' => Show::class,
        'showEpisodes' => ShowEpisode::class,
        'newsStories' => NewsStory::class,
        'creators' => Creator::class,
    ];

    return $models[strtolower($type)] ?? null;
  }



//  /**
//   * Perform a search based on a model and slug provided through the URL,
//   * and optionally handle different types of searches within those models.
//   *
//   * @param Request $request
//   * @param string $model  The model name derived from the URL.
//   * @param string $slug  The slug used to identify the model instance.
//   * @return JsonResponse
//   */
//  public function search(Request $request, string $model, string $slug): JsonResponse {
//
//    Log::debug('Search requested', ['model' => $model, 'slug' => $slug, 'params' => $request->all()]);
//
//    // Validate the incoming request parameters and query
//    $validator = Validator::make($request->all() + compact('model', 'slug'), [
//        'model' => 'required|string|in:teams,shows,showEpisodes,newsStories,creators', // Adjust the allowed models as necessary
//        'slug' => 'required|string',
//        'query' => 'required|string|min:1', // Ensure there's at least one character in the query
//        'type' => 'sometimes|string' // Validate type if provided
//    ]);
//
//    // Handle validation failure
//    if ($validator->fails()) {
//      Log::debug('Validation failed', ['errors' => $validator->errors()]);
//      return response()->json(['message' => 'Invalid input parameters', 'errors' => $validator->errors()], 422);
//    }
//
//    // Translate URL model name to actual model class
//    $modelClass = $this->getModelClass($model);
//    if (!$modelClass) {
//      Log::debug('Invalid model type', ['model' => $model]);
//      return response()->json(['message' => 'Invalid model type'], 404);
//    }
//
//    try {
//      $modelInstance = $modelClass::where('slug', $slug)->firstOrFail();
//    } catch (ModelNotFoundException $e) {
//      Log::debug('Model not found', ['model' => $model, 'slug' => $slug]);
//      return response()->json(['message' => 'Model not found'], 404);
//    }
//
//    // Retrieve the type of search from the request, defaulting to 'default' if not provided
//    $type = $request->query('type', 'default');
//    $query = $request->query('query');
//    Log::debug('Performing search', ['model' => $model, 'slug' => $slug, 'type' => $type, 'query' => $query]);
//
//    // Perform the search with the provided query string and type
//    $searchResults = $modelInstance->search($query, $type);
//    Log::debug('Search results', ['results' => $searchResults]);
//
//    return response()->json($searchResults);
//  }


}
