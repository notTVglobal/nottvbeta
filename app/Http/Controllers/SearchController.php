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

class SearchController extends Controller {
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

  private function getModel($type, $id) {
    return $type === 'team' ? Team::with('shows.showEpisodes')->find($id) : Show::find($id);
  }

}
