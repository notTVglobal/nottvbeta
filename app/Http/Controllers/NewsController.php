<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsStoryResource;
use App\Models\NewsCategory;
use App\Models\NewsCity;
use App\Models\NewsPerson;
use App\Models\NewsCountry;
use App\Models\NewsProvince;
use App\Models\NewsStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\SearchableNewsTrait;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{

  use SearchableNewsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response {
      $user = Auth::user();

      return Inertia::render('News/Index', [
          'can' => [
              'viewNewsroom' => optional($user)->can('viewAny', NewsPerson::class) ?: false,
          ],
      ]);
    }

    public function getNewsCountriesSimpleList(): \Illuminate\Http\JsonResponse {
      $countries = NewsCountry::orderBy('name', 'asc')->get(['id', 'name']);
      return response()->json($countries);
    }

  public function indexCities(Request $request): Response {
    $user = Auth::user();
    $component = $user ? 'News/City/Index' : 'LoggedOut/News/City/Index';

    // Eager load the provinces with their cities, selecting only the required fields
    $provinces = NewsProvince::select('id', 'name', 'slug')
        ->with(['cities' => function ($query) {
          $query->select('id', 'name', 'slug', 'province_id');
        }])
        ->get();

    return Inertia::render($component, [
        'newsProvinces' => $provinces,
    ]);
  }

  public function showCity(Request $request, NewsCity $newsCity): Response {
    $user = Auth::user();
    $component = $user ? 'News/City/{$id}/Index' : 'LoggedOut/News/City/{$id}/Index';

    $newsStoriesQuery = NewsStory::with([
        'image.appSetting',
        'video.appSetting',
        'newsPerson.user',
    ])->where('city_id', $newsCity->id);

//    $this->applySearch($newsStoriesQuery);

    $paginatedNewsStories = $newsStoriesQuery->paginate(10)->withQueryString();
    $newsStories = NewsStoryResource::collection($paginatedNewsStories);

    return Inertia::render($component, [
        'newsCity' => $newsCity,
        'newsStories' => $newsStories,
    ]);
  }

  public function indexCategories(Request $request): Response {
    $user = Auth::user();
    $component = $user ? 'News/Category/Index' : 'LoggedOut/News/Category/Index';

    $categories = NewsCategory::all();
    return Inertia::render($component, [
        'newsCategories' => $categories,
    ]);
  }

  public function showCategory(Request $request, NewsCategory $newsCategory): Response {
    $user = Auth::user();
    $component = $user ? 'News/Category/{$id}/Index' : 'LoggedOut/News/Category/{$id}/Index';

    $newsStoriesQuery = NewsStory::with([
        'image.appSetting',
        'video.appSetting',
        'newsPerson.user',
    ])->where('news_category_id', $newsCategory->id);

//    $this->applySearch($newsStoriesQuery);

    $paginatedNewsStories = $newsStoriesQuery->paginate(10)->withQueryString();
    $newsStories = NewsStoryResource::collection($paginatedNewsStories);

    return Inertia::render($component, [
        'newsCategory' => $newsCategory,
        'newsStories' => $newsStories,
    ]);
  }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        //
    }
}
