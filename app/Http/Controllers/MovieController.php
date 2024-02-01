<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Movie;
use App\Models\MovieCategory;
use App\Models\MovieCategorySub;
use App\Models\MovieStatus;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class MovieController extends Controller {

  public function __construct() {

    $this->middleware('can:view,movie')->only(['show']);
    $this->middleware('can:create,' . \App\Models\Movie::class)->only(['create']);
    $this->middleware('can:create,movie')->only(['store']);
    $this->middleware('can:edit,movie')->only(['edit']);
    $this->middleware('can:edit,movie')->only(['update']);
    $this->middleware('can:destroy,movie')->only(['destroy']);

  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

  public function index(): Response
  {
    return Inertia::render('Movies/Index', [
        'movies'           => $this->fetchMovies(),
        'recentlyReviewed' => $this->fetchRecentlyReviewed(),
        'mostAnticipated'  => $this->fetchMostAnticipated(),
        'comingSoon'       => $this->fetchComingSoon(),
        'filters'          => Request::only(['search']),
    ]);
  }

  // The goal is to fetch movies, apply a search filter if necessary,
  // paginate the results, and transform each movie with the required data.
  private function fetchMovies()
  {
    return Movie::with(['image', 'appSetting', 'category', 'subCategory', 'status'])
        ->when(Request::input('search'), function ($query, $search) {
          $query->where('name', 'like', "%{$search}%");
        })
        ->where($this->applyStatusFilter())
        ->latest()
        ->paginate(10, ['*'], 'movies')
        ->withQueryString()
        ->through(fn($movie) => $this->transformMovie($movie));
  }

  // This method should fetch a list of recently reviewed movies,
  // apply any necessary transformations, and paginate the results.
  // We'll assume that "recently reviewed" can be determined by a
  // specific attribute in your Movie model, such as a reviewed_at
  // timestamp, or simply by the created_at or updated_at timestamps
  // if reviewed_at is not available.
  private function fetchRecentlyReviewed()
  {
    return Movie::with(['image', 'appSetting', 'category', 'subCategory', 'status'])
        // Optionally, you can filter by a 'reviewed_at' field if available
        // ->whereNotNull('reviewed_at')
        ->where($this->applyStatusFilter())
        ->latest('updated_at') // Assuming 'updated_at' indicates a recent review
        ->paginate(3, ['*'], 'recentlyReviewed')
        ->withQueryString()
        ->through(fn($movie) => $this->transformMovie($movie));
  }

  // The definition of "most anticipated" can vary based on your application's context.
  // It might be determined by user ratings, the number of pre-orders, a specific flag
  // in the database, or upcoming release dates.
  private function fetchMostAnticipated()
  {
    return Movie::with(['image', 'appSetting', 'category', 'subCategory', 'status'])
        ->where($this->applyStatusFilter())
        ->where('release_year', '>', now()->year) // Assuming future release years indicate anticipation
        ->orderBy('release_year', 'asc') // Order by soonest release
        ->paginate(3, ['*'], 'anticipated')
        ->withQueryString()
        ->through(fn($movie) => $this->transformMovie($movie));
  }

  // For the comingSoon section, similar to mostAnticipated, we will define a query that
  // fetches movies based on their upcoming status or release dates. The exact criteria
  // will depend on your application's specific needs. In this case, let's assume that
  // "coming soon" movies are determined by their release dates being at least 24 hours in the future.
  private function fetchComingSoon()
  {
    $tomorrow = now()->addDay(); // Get the date and time for 24 hours in the future

    return Movie::with(['image', 'appSetting', 'category', 'subCategory', 'status'])
        ->where($this->applyStatusFilter())
        ->where('release_date', '>', $tomorrow) // Fetch movies with a release date more than 24 hours away
        ->orderBy('release_date', 'asc') // Order by soonest release date
        ->paginate(3, ['*'], 'comingSoon')
        ->withQueryString()
        ->through(fn($movie) => $this->transformMovie($movie));
  }

  // The through(fn($movie) => $this->transformMovie($movie)) method
  // transforms each movie using the transformMovie method. This method
  // structures the movie data as needed by your frontend.
  private function transformMovie($movie)
  {
    return [
        'id'            => $movie->id,
        'name'          => $movie->name,
        'description'   => $movie->description,
        'logline'       => $movie->logline,
        'teamId'        => $movie->team_id,
      // 'teamSlug'   => $movie->team->slug, // Uncomment if 'team' relationship is loaded
        'userId'        => $movie->user_id,
      // 'userName'   => $movie->user->name, // Uncomment if 'user' relationship is loaded
        'image'         => $this->transformImage($movie->image, $movie->appSetting),
        'slug'          => $movie->slug,
        'copyrightYear' => $movie->created_at->format('Y'),
        'release_year'  => $movie->release_year,
        'category'      => $movie->category ? $movie->category->toArray() : null,
        'subCategory'   => $movie->subCategory ? $movie->subCategory->toArray() : null,
        'statusId'      => $movie->status->id,
    ];
  }

  // transformImage is a helper method to structure the image data.
  // It's called within transformMovie
  private function transformImage($image, $appSetting)
  {
    return [
        'id'           => $image->id,
        'name'         => $image->name,
        'folder'       => $image->folder,
        'cdn_endpoint' => $appSetting->cdn_endpoint,
        'cloud_folder' => $image->cloud_folder,
    ];
  }

  private function applyStatusFilter()
  {
    return function ($query) {
      if (auth()->check()) {
        $user = auth()->user();

        if ($user->creator) {
          // Creators can see movies with an active or creators only status
          $query->whereIn('status_id', [2, 9]);
        } elseif ($user->subscription() || $user->isVip) {
          // Subscribers and VIPs can see movies with an active status
          $query->where('status_id', 2);
        } else {
          // If neither, no movies should be visible
          $query->whereRaw('1 = 0'); // This will always evaluate to false
        }
      } else {
        // If not authenticated, no movies should be visible
        $query->whereRaw('1 = 0');
      }
    };
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create() {
    return Inertia::render('Movies/Create',
//        [
//        'can' => [
//            'viewCreator' => Auth::user()->can('viewCreator', User::class),
//        ]
//    ]
    );
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param HttpRequest $request
   * @return RedirectResponse
   */
  public function store(HttpRequest $request) {
    $request->validate([
        'name'        => 'unique:movies|required|string|max:255',
        'logline'     => 'required|string',
        'description' => 'required|string',
        'file_url'    => 'nullable|active_url',
    ]);

    // Generate the slug
    $slug = \Str::slug($request->name);

    // Create the movie
    $movie = Movie::create([
        'name'          => $request->name,
        'description'   => $request->description,
        'logline'       => $request->logline,
        'copyrightYear' => Carbon::now()->year,
        'slug'          => $slug,
        'file_url'      => $request->file_url,
        'user_id'       => Auth::user()->id,
    ]);

//        $movie = Movie::query()->where('name', $request->name)->firstOrFail();
    // Use this route to return
    // the user to the new movie page.
    return redirect()->route('movies.show', [$movie->slug])->with('success', 'Movie Added Successfully');
  }

  /**
   * Display the specified resource.
   *
   * @param Movie $movie
   * @return Response
   */
  public function show(Movie $movie) {

//    $video = Video::where('movies_id', $movie->id)->first();
//    $trailer = Video::where('movie_trailers_id', $movie->id)->first();

    $movie->load('trailer.video', 'video.appSetting', 'image', 'video', 'appSetting', 'category', 'subCategory', 'status'); // Eager load necessary relationships


    return Inertia::render('Movies/{$id}/Index', [
        'movie'   => [
            'slug'           => $movie->slug,
            'name'           => $movie->name,
            'description'    => $movie->description,
            'logline'        => $movie->logline,
            'file_path'      => $movie->file_path,
            'file_url'       => $movie->file_url,
            'teamName'       => $movie->team_id,
            'teamSlug'       => $movie->slug,
            'image'          => [
                'id'           => $movie->image->id,
                'name'         => $movie->image->name,
                'folder'       => $movie->image->folder,
                'cdn_endpoint' => $movie->appSetting->cdn_endpoint,
                'cloud_folder' => $movie->image->cloud_folder,
            ],
          // need to link the Movie and Team models.
          // change team to $movie->team->name
          // and add team->slug
            'copyrightYear'  => $movie->created_at->format('Y'),
            'release_year'   => $movie->release_year,
            'created_at'     => $movie->created_at,
            'www_url'        => $movie->www_url,
            'instagram_name' => $movie->instagram_name,
            'telegram_url'   => $movie->telegram_url,
            'twitter_handle' => $movie->twitter_handle,
            'category'       => $movie->category ? $movie->category->toArray() : null,
            'subCategory'    => $movie->subCategory ? $movie->subCategory->toArray() : null,
            'statusId'       => $movie->status->id,
            ],
        'video'   => [
            'file_name'     => $movie->video->file_name ?? '',
            'cdn_endpoint'  => $movie->video->appSetting->cdn_endpoint ?? '',
            'folder'        => $movie->video->folder ?? '',
            'cloud_folder'  => $movie->video->cloud_folder ?? '',
            'upload_status' => $movie->video->upload_status ?? '',
        ],
        'trailer' => [
            'file_name'     => $movie->trailer->video->file_name ?? '',
            'cdn_endpoint'  => $movie->trailer->video->appSetting->cdn_endpoint ?? '',
            'folder'        => $movie->trailer->video->folder ?? '',
            'cloud_folder'  => $movie->trailer->video->cloud_folder ?? '',
            'upload_status' => $movie->trailer->video->upload_status ?? '',
        ],
        'can'     => [
            'editMovie' => Auth::user()->can('edit', $movie),
        ]
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param Movie $movie
   * @return Response
   */
  public function edit(Movie $movie) {

    DB::table('users')->where('id', Auth::user()->id)->update([
        'isEditingMovie_id' => $movie->id,
    ]);

    DB::table('shows')->where('id', $movie->id)->update([
        'isBeingEditedByUser_id' => Auth::user()->id,
    ]);

    $categories = MovieCategory::with('subCategories')->get(); // Fetch all categories with their sub-categories
    $movieStatuses = MovieStatus::all();
    $movie->load('trailer.video', 'video.appSetting', 'image', 'video', 'image.appSetting', 'category', 'subCategory'); // Eager load necessary relationships

    return Inertia::render('Movies/{$id}/Edit', [
        'movie'      => $movie,
        'video'      => [
            'file_name'     => $movie->video->file_name ?? '',
            'type'     => $movie->video->type ?? '',
            'cdn_endpoint'  => $movie->video->appSetting->cdn_endpoint ?? '',
            'folder'        => $movie->video->folder ?? '',
            'cloud_folder'  => $movie->video->cloud_folder ?? '',
            'upload_status' => $movie->video->upload_status ?? '',
        ],
        'trailer'    => [
            'trailerDetails' => $movie->trailer, // The entire trailer object
            'video'          => [
                'file_name'     => $movie->trailer->video->file_name ?? '',
                'type'     => $movie->trailer->video->type ?? '',
                'cdn_endpoint'  => $movie->trailer->video->appSetting->cdn_endpoint ?? '',
                'folder'        => $movie->trailer->video->folder ?? '',
                'cloud_folder'  => $movie->trailer->video->cloud_folder ?? '',
                'upload_status' => $movie->trailer->video->upload_status ?? '',
            ],
        ],
        'image'      => [
            'id' => $movie->image->id ?? '',
            'name' => $movie->image->name ?? '',
            'folder' => $movie->image->folder ?? '',
            'cdn_endpoint' => $movie->appSetting->cdn_endpoint ?? '',
            'cloud_folder' => $movie->image->cloud_folder ?? '',
        ],
        'categories' => $categories,
        'statuses' => $movieStatuses,
        'can'        => [
            'editMovie' => Auth::user()->can('edit', Movie::class),
        ],
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param HttpRequest $request
   * @param Movie $movie
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Routing\Redirector|RedirectResponse
   */
  public function update(HttpRequest $request, Movie $movie) {

//    dd($request);
    // validate the request
    $request->validate([
        'name'           => ['required', 'string', 'max:255', Rule::unique('movies')->ignore($movie->id)],
        'description'    => 'required',
        'logline'        => 'required|string',
        'release_year'   => 'integer|min:1900|max:2300',
        'release_date'   => 'date|after:tomorrow',
        'category'       => 'required',
        'sub_category'   => 'nullable',
        'file_url'       => 'nullable|active_url',
        'www_url'        => 'nullable|active_url',
        'instagram_name' => 'nullable|string|max:30',
        'telegram_url'   => 'nullable|active_url',
        'twitter_handle' => 'nullable|string|min:4|max:15',
        'notes'          => 'nullable|string|max:1024',
        'status' => 'required|integer|exists:movie_statuses,id',
        ], [
        'status.exists' => 'The selected status is invalid.',
        'release_date.after' => 'The release date must be at least 24 hours in the future.',
    ]);


    // update the show
    $movie->name = $request->name;
    $movie->description = $request->description;
    $movie->logline = $request->logline;
    $movie->release_year = $request->release_year;
    $movie->movie_category_id = $request->category;
    $movie->movie_category_sub_id = $request->sub_category;
    $movie->slug = \Str::slug($request->name);
    $movie->file_url = $request->file_url;
    $movie->www_url = $request->www_url;
    $movie->instagram_name = $request->instagram_name;
    $movie->telegram_url = $request->telegram_url;
    $movie->twitter_handle = $request->twitter_handle;
    $movie->status_id = $request->status;
    $movie->save();
    sleep(1);


    // redirect
    return redirect(route('movies.show', [$movie->slug]))->with('success', 'Movie Updated Successfully');

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param Movie $movie
   * @return void
   */
  public function destroy(Movie $movie) {
    //
    ////Syntax:
    //Storage::disk('s3')->delete('path/file.jpg');
    //if(Storage::disk('s3')->exists($path)) {
    //    Storage::disk('s3')->delete($path);
    //}
    ////For example your path to file or object key will look like this.
    //$path = 'uploaded_files/d486cebd-746c-4533-a53f-a0f8e77e15e2.jpg';
  }
}
