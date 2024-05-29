<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Jobs\ProcessVideoInfo;
use App\Models\CreativeCommons;
use App\Models\Image;
use App\Models\Movie;
use App\Models\MovieCategory;
use App\Models\MovieCategorySub;
use App\Models\MovieStatus;
use App\Models\User;
use App\Models\Video;
use App\Traits\ImageTransformable;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\VideoUrlService;

class MovieController extends Controller {

  protected VideoUrlService $mistVideoUrlService;
  use ImageTransformable;

  public function __construct(VideoUrlService $mistVideoUrlService) {

    $this->middleware('can:view,movie')->only(['show']);
    $this->middleware('can:create,' . \App\Models\Movie::class)->only(['create']);
    $this->middleware('can:create,' . \App\Models\Movie::class)->only(['store']);
    $this->middleware('can:edit,movie')->only(['edit']);
    $this->middleware('can:edit,movie')->only(['update']);
    $this->middleware('can:destroy,movie')->only(['destroy']);

    $this->mistVideoUrlService = $mistVideoUrlService;

  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

  public function index(): Response {
    return Inertia::render('Movies/Index', [
        'movies'           => $this->fetchMovies(),
        'recentlyReviewed' => $this->fetchRecentlyReviewed(),
        'mostAnticipated'  => $this->fetchMostAnticipated(),
        'comingSoon'       => $this->fetchComingSoon(),
        'filters'          => Request::only(['search']),
    ]);
  }

  public function indexCategories(): Response {
    $categories = Cache::rememberForever('movie_categories', function () {
      return MovieCategory::select('name', 'slug', 'description')->get();
    });

    return Inertia::render('Movies/Category/Index', [
        'categories' => $categories,
    ]);
  }

  public function showCategory(MovieCategory $movieCategory, HttpRequest $request): Response
  {
    $cacheKey = 'movie_category_subcategories_' . $movieCategory->id;

    $subCategories = Cache::rememberForever($cacheKey, function () use ($movieCategory) {
      return $movieCategory->subCategories()->select('name', 'description', 'id')->get();
    });

    $query = $movieCategory->movies()->with(['image.appSetting', 'video.appSetting']);

    // Apply search filter if provided
    if ($search = $request->input('search')) {
      $query->where('title', 'like', "%{$search}%")
          ->orWhere('description', 'like', "%{$search}%");
    }

    // Apply subcategory filter if provided
    if ($subCategoryId = $request->input('subCategory')) {
      $query->where('sub_category_id', $subCategoryId);
    }

    // Paginate results
    $movies = $query->paginate(10);
    $moviesResource = MovieResource::collection($movies);

    return Inertia::render('Movies/Category/{$id}/Index}', [
        'category' => $movieCategory,
        'subCategories' => $subCategories,
        'movies' => $moviesResource,
        'pagination' => [
            'total' => $movies->total(),
            'per_page' => $movies->perPage(),
            'current_page' => $movies->currentPage(),
            'last_page' => $movies->lastPage(),
            'from' => $movies->firstItem(),
            'to' => $movies->lastItem()
        ]
    ]);
  }

  // The goal is to fetch movies, apply a search filter if necessary,
  // paginate the results, and transform each movie with the required data.
  private function fetchMovies() {
    return Movie::with(['image.appSetting', 'status'])
        ->when(Request::input('search'), function ($query, $search) {
          $query->where('name', 'like', "%{$search}%");
        })
        ->where($this->applyStatusFilter())
        ->latest()
        ->paginate(12, ['*'], 'movies')
        ->withQueryString()
        ->through(fn($movie) => $this->transformMovie($movie));
  }

  // This method should fetch a list of recently reviewed movies,
  // apply any necessary transformations, and paginate the results.
  // We'll assume that "recently reviewed" can be determined by a
  // specific attribute in your Movie model, such as a reviewed_at
  // timestamp, or simply by the created_at or updated_at timestamps
  // if reviewed_at is not available.
  private function fetchRecentlyReviewed() {
    return Movie::with(['image.appSetting', 'status'])
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
  private function fetchMostAnticipated() {
    return Movie::with(['image.appSetting', 'status'])
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
  private function fetchComingSoon() {
    $tomorrow = now()->addDay(); // Get the date and time for 24 hours in the future

    return Movie::with(['image.appSetting', 'status'])
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
  private function transformMovie($movie) {
    return [
        'id'            => $movie->id,
        'name'          => $movie->name,
        'description'   => $movie->description,
        'logline'       => $movie->logline,
        'teamId'        => $movie->team_id,
      // 'teamSlug'   => $movie->team->slug, // Uncomment if 'team' relationship is loaded
        'userId'        => $movie->user_id,
      // 'userName'   => $movie->user->name, // Uncomment if 'user' relationship is loaded
        'image'         => $this->transformImage($movie->image),
        'slug'          => $movie->slug,
        'copyrightYear' => $movie->created_at->format('Y'),
        'release_year'  => $movie->release_year,
        'category'      => $movie->getCachedCategory() ? $movie->getCachedCategory()->toArray() : null,
        'subCategory'   => $movie->getCachedSubCategory() ? $movie->getCachedSubCategory()->toArray() : null,
        'statusId'      => $movie->status->id,
        'isNew'         => Carbon::parse($movie->releaseDateTime)->isBetween(Carbon::now()->subWeek(), Carbon::now()),
    ];
  }



  private function applyStatusFilter() {
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
        'video_url'   => 'nullable|active_url|ends_with:.mp4',
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
        'user_id'       => Auth::user()->id,
    ]);

    if (!empty($request->video_url)) { // Check for non-empty video_url
      // Create and save the video as before
      $video = new Video([
          'user_id'          => Auth::user()->id,
          'name'             => 'External video',
          'file_name'        => 'external_video_' . \Illuminate\Support\Str::uuid(),
          'type'             => 'video/mp4',
          'video_url'        => $request->video_url,
          'storage_location' => 'external',
          'movies_id'        => $movie->id,
      ]);
      $video->save();

      $movie->video_id = $video->id;
      $movie->save();
    }

    // $movie = Movie::query()->where('name', $request->name)->firstOrFail();
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
  public function show(VideoUrlService $videoUrlService, HttpRequest $request, Movie $movie) {

//    $video = Video::where('movies_id', $movie->id)->first();
//    $trailer = Video::where('movie_trailers_id', $movie->id)->first();

    $movie->load('trailers.video', 'video.appSetting', 'image.appSetting', 'team', 'status', 'creativeCommons'); // Eager load necessary relationships

    // Determine the media type based on its storage location.
    // If the storage location is marked as 'external', categorize it as 'externalVideo';
    // otherwise, it's considered an internal 'show' video.
    // Attempt to determine the media type based on the storage location of the video
    $movieStorageLocation = $movie->video?->storage_location;
    // Determine the media type based on the storage location, default to 'show'
    $mediaTypeMovie = $movieStorageLocation === 'external' ? 'externalVideo' : 'movie';

// Movies HasMany Movie Trailers... so these need to be part of an array...
//    $movieTrailerStorageLocation = $movie->trailers?->video?->storage_location;
//    $mediaTypeMovieTrailer = $movieTrailerStorageLocation === 'external' ? 'externalVideo' : 'movie';


//  Generate the secure URL with hash for Mist Server Access Control
//// Check if $movie->video is not null and then if video_url is not null
//    if (!is_null($movie->video) && !is_null($movie->video->video_url)) {
//      // Handle the null case, e.g., set $secureVideoUrl to null, a default value, or perform an action
//      $secureVideoUrl = $movie->video->video_url; // or any appropriate handling
//    } else {
//      // If video_url is not null, proceed to generate the secure URL
//      $secureVideoUrl = $videoUrlService->generateSecureVideoUrl($request, $movie->video->video_url);
//    }

    // Initialize $secureVideoUrl to a default value in case $movie->video or $movie->video->video_url is null
    $secureVideoUrl = null;

// Check if $movie->video is not null and then if video_url is not null
    if ($movie->video !== null && $movie->video->video_url !== null) {
      // If video_url is not null, proceed to generate the secure URL
      $secureVideoUrl = $videoUrlService->generateSecureVideoUrl($request, $movie->video->video_url);
    } else if ($movie->video) {
      $secureVideoUrl = $movie->video->video_url;
      // Here, handle the case where $movie->video is null or $movie->video->video_url is null
      // For example, keep $secureVideoUrl as null or set it to a default/fallback value
      // $secureVideoUrl = 'default_value_or_action'; // Uncomment or modify as needed
    }

// Now, $secureVideoUrl is either the generated secure URL, null, or a default value, based on the checks above


    return Inertia::render('Movies/{$id}/Index', [
        'movie' => [
            'slug'             => $movie->slug,
            'name'             => $movie->name,
            'description'      => $movie->description,
            'logline'          => $movie->logline,
//            'file_path'      => $movie->file_path,
//            'file_url'       => $movie->file_url,
            'team'             => [
                'name' => $movie->team->name ?? null,
                'slug' => $movie->team->slug ?? null,
            ],
            'image'            => [
                'id'              => $movie->image->id,
                'name'            => $movie->image->name,
                'folder'          => $movie->image->folder,
                'cdn_endpoint'    => $movie->image->appSetting->cdn_endpoint,
                'cloud_folder'    => $movie->image->cloud_folder,
                'placeholder_url' => $movie->image->placeholder_url,
            ],
          // need to link the Movie and Team models.
          // change team to $movie->team->name
          // and add team->slug
//            'copyrightYear'  => $movie->created_at->format('Y'),
            'copyrightYear'    => $movie->copyrightYear ?? null,
            'creative_commons' => $movie->creativeCommons ?? null,
            'release_year'     => $movie->release_year ?? null,
//            'created_at'     => $movie->created_at,
            'www_url'          => $movie->www_url ?? null,
            'instagram_name'   => $movie->instagram_name ?? null,
            'telegram_url'     => $movie->telegram_url ?? null,
            'twitter_handle'   => $movie->twitter_handle ?? null,
//            'category'       => $movie->category ? $movie->category->toArray() : null,
            'category'         => [
                'name'        => $movie->getCachedCategory()->name ?? null,
                'description' => $movie->getCachedCategory()->description ?? null,
            ],
//            'subCategory'    => $movie->subCategory ? $movie->subCategory->toArray() : null,
            'subCategory'      => [
                'name'        => $movie->getCachedSubCategory()->name ?? null,
                'description' => $movie->getCachedSubCategory()->description ?? null,
            ],
            'status'           => [
                'id'   => $movie->status->id ?? null,
                'name' => $movie->status->name ?? null,
            ],
            'isNew'            => Carbon::parse($movie->releaseDateTime)->isBetween(Carbon::now()->subWeek(), Carbon::now()),
            'video'            => [
                'mediaType'        => $mediaTypeMovie, // New attribute for NowPlayingStore
                'file_name'        => $movie->video->file_name ?? '',
                'cdn_endpoint'     => $movie->video->appSetting->cdn_endpoint ?? '',
                'folder'           => $movie->video->folder ?? '',
                'cloud_folder'     => $movie->video->cloud_folder ?? '',
                'upload_status'    => $movie->video->upload_status ?? '',
//                'video_url'        => $movie->video->video_url ?? '',
                'video_url'        => $secureVideoUrl,
                'type'             => $movie->video->type ?? '',
                'storage_location' => $movie->video->storage_location ?? '',
            ],
            'trailer'          => [
                'mediaType'        => null, // New attribute for NowPlayingStore
                'file_name'        => $movie->trailer->video->file_name ?? '',
                'cdn_endpoint'     => $movie->trailer->video->appSetting->cdn_endpoint ?? '',
                'folder'           => $movie->trailer->video->folder ?? '',
                'cloud_folder'     => $movie->trailer->video->cloud_folder ?? '',
                'upload_status'    => $movie->trailer->video->upload_status ?? '',
                'video_url'        => $movie->trailer->video->video_url ?? '',
                'type'             => $movie->trailer->video->type ?? '',
                'storage_location' => $movie->trailer->video->storage_location ?? '',
            ],
        ],
        'can'   => [
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

    Auth::user()->update(['isEditingMovie_id' => $movie->id]);

    $movie->update(['isBeingEditedByUser_id' => Auth::id()]);

    $categories = MovieCategory::with('subCategories')->get(); // Fetch all categories with their sub-categories
    $movieStatuses = MovieStatus::all();
    $creativeCommons = CreativeCommons::all();

    $movie->load('trailers.video', 'video.appSetting', 'creativeCommons', 'image.appSetting'); // Eager load necessary relationships

    return Inertia::render('Movies/{$id}/Edit', [
        'movie'            => $movie,
        'video'            => [
            'file_name'     => $movie->video->file_name ?? '',
            'type'          => $movie->video->type ?? '',
            'cdn_endpoint'  => $movie->video->appSetting->cdn_endpoint ?? '',
            'folder'        => $movie->video->folder ?? '',
            'cloud_folder'  => $movie->video->cloud_folder ?? '',
            'upload_status' => $movie->video->upload_status ?? '',
            'video_url'     => $movie->video->video_url ?? '',
        ],
        'trailer'          => [
            'trailerDetails' => $movie->trailer, // The entire trailer object
            'video'          => [
                'file_name'     => $movie->trailer->video->file_name ?? '',
                'type'          => $movie->trailer->video->type ?? '',
                'cdn_endpoint'  => $movie->trailer->video->appSetting->cdn_endpoint ?? '',
                'folder'        => $movie->trailer->video->folder ?? '',
                'cloud_folder'  => $movie->trailer->video->cloud_folder ?? '',
                'upload_status' => $movie->trailer->video->upload_status ?? '',
            ],
        ],
        'image'            => [
            'id'           => $movie->image->id ?? '',
            'name'         => $movie->image->name ?? '',
            'folder'       => $movie->image->folder ?? '',
            'cdn_endpoint' => $movie->image->appSetting->cdn_endpoint ?? '',
            'cloud_folder' => $movie->image->cloud_folder ?? '',
        ],
        'categories'       => $categories,
        'statuses'         => $movieStatuses,
        'creative_commons' => $creativeCommons,
        'can'              => [
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
    $validatedData = $request->validate([
        'name'                => ['required', 'string', 'max:255', Rule::unique('movies')->ignore($movie->id)],
        'description'         => 'required',
        'logline'             => 'required|string',
        'release_date'        => 'date|after:tomorrow',
        'release_year'        => [
            'required_if:status,2',
            'nullable',
            'integer',
            'min:1900',
            'max:' . date('Y')
        ],
        'creative_commons_id' => 'required|integer|exists:creative_commons,id',
        'copyrightYear'       => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
        'category'            => 'required',
        'sub_category'        => 'nullable',
        'video_url'           => 'nullable|url|ends_with:.mp4',
        'www_url'             => 'nullable|active_url',
        'instagram_name'      => 'nullable|string|max:30',
        'telegram_url'        => 'nullable|active_url',
        'twitter_handle'      => 'nullable|string|min:4|max:15',
        'notes'               => 'nullable|string|max:1024',
        'status'              => 'required|integer|exists:movie_statuses,id',
    ], [
        'category.required'            => 'The category is required.',
        'creative_commons_id.required' => 'The creative commons selection is required.',
        'status.exists'                => 'The selected status is invalid.',
        'copyrightYear.integer'        => 'Please choose a copyright year',
        'release_date.after'           => 'The release date must be at least 24 hours in the future.',
        'release_year.required_if'     => 'The release year is required when the status is active.',
        'release_year.integer'         => 'The release year must be an number.',
        'release_year.min'             => 'The release year must be greater than 1900.',
        'release_year.max'             => 'The release year cannot be beyond this year.',
    ]);

    // Capture the original status before any changes
    $originalStatus = $movie->status_id;

    // Check if the status is being changed from something other than 1 to 2
    if ($originalStatus != 1 && $request->status == 2) {
      // Set releaseDateTime to now
      $movie->releaseDateTime = now();
    }

    // Check if the video_url is not empty and is different from the existing one.
    if (!empty($request->video_url)) {
      // Attempt to find an existing video with the same URL for any episode
      $existingVideo = Video::where('video_url', $request->video_url)->first();

      if ($existingVideo) {
        // If a video with the same URL already exists, associate its ID with the show episode
        $movie->video_id = $existingVideo->id;
      } else {
        // No existing video with the same URL, proceed to create a new Video instance
        $video = new Video([
            'user_id'          => Auth::id(), // Simpler way to get the authenticated user's ID
            'name'             => 'External video',
            'file_name'        => 'external_video_' . \Illuminate\Support\Str::uuid(),
            'type'             => 'video/mp4', // Assuming a default type for external videos
            'video_url'        => $request->video_url,
            'storage_location' => 'external',
          // 'show_episodes_id' might not be needed if you're associating via $showEpisode->video_id below
          // 'show_episodes_id' => $showEpisode->id,
        ]);
        $video->save();

        // After saving the new video, set its ID as the video_id for the show episode
        $movie->video_id = $video->id;

      }
    } else { // else the video_url is empty, check if there was previously a video.
      // we need to either delete the video (soft delete) or... something...
      $movie->video_id = null;
    }

    // update the show
    $movie->name = $request->name;
    $movie->description = $request->description;
    $movie->logline = $request->logline;
    $movie->release_year = $request->release_year;
    $movie->copyrightYear = $request->copyrightYear;
    $movie->creative_commons_id = $request->creative_commons_id;
    $movie->movie_category_id = $request->category;
    $movie->movie_category_sub_id = $request->sub_category;
    $movie->slug = \Str::slug($request->name);
    $movie->www_url = $request->www_url;
    $movie->instagram_name = $request->instagram_name;
    $movie->telegram_url = $request->telegram_url;
    $movie->twitter_handle = $request->twitter_handle;
    $movie->status_id = $request->status; // Update the movie's status with the new status from the request
    $movie->save();
    sleep(1);

//dd($movie->slug);
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
