<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\NewsStory;
use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
  /**
   * Perform a search based on a model and slug provided through the URL,
   * and optionally handle different types of searches within those models.
   *
   * @param Request $request
   * @param string $model  The model name derived from the URL.
   * @param string $slug  The slug used to identify the model instance.
   * @return JsonResponse
   */
  public function search(Request $request, string $model, string $slug): JsonResponse {

    // Validate the incoming request parameters and query
    $validator = Validator::make($request->all() + compact('model', 'slug'), [
        'model' => 'required|string|in:teams,shows,showEpisodes,newsStories,creators', // Adjust the allowed models as necessary
        'slug' => 'required|string',
        'query' => 'required|string|min:1', // Ensure there's at least one character in the query
        'type' => 'sometimes|string' // Validate type if provided
    ]);

    // Handle validation failure
    if ($validator->fails()) {
      return response()->json(['message' => 'Invalid input parameters', 'errors' => $validator->errors()], 422);
    }

    // Translate URL model name to actual model class
    $modelClass = $this->getModelClass($model);
    if (!$modelClass) {
      return response()->json(['message' => 'Invalid model type'], 404);
    }

    try {
      $modelInstance = $modelClass::where('slug', $slug)->firstOrFail();
    } catch (ModelNotFoundException $e) {
      return response()->json(['message' => 'Model not found'], 404);
    }

    // Retrieve the type of search from the request, defaulting to 'default' if not provided
    $type = $request->query('type', 'default');

    // Perform the search with the provided query string and type
    $searchResults = $modelInstance->search($request->query('query'), $type);

    return response()->json($searchResults);
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
}
