<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Movie;
use App\Models\MovieCategory;
use App\Models\MovieCategorySub;
use App\Models\User;
use App\Models\Video;
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

class MovieController extends Controller
{

    public function __construct()
    {

        $this->middleware('can:edit,movie')->only(['edit']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response {
        return Inertia::render('Movies/Index', [
            'movies' => Movie::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(10, ['*'], 'movies')
                ->withQueryString()
                ->through(fn($movie) => [
                    'id' => $movie->id,
                    'name' => $movie->name,
                    'teamId' => $movie->team_id,
//                    teamSlug' => $movie->team->slug,
                    'description' => $movie->description,

                    'userId' => $movie->user_id,
//                    'userName' => $movie->user->name,
                    'image' => [
                        'id' => $movie->image->id,
                        'name' => $movie->image->name,
                        'folder' => $movie->image->folder,
                        'cdn_endpoint' => $movie->appSetting->cdn_endpoint,
                        'cloud_folder' => $movie->image->cloud_folder,
                    ],
                    'slug' => $movie->slug,
                    'copyrightYear' => $movie->created_at->format('Y'),
                    'release_year' => $movie->release_year,
                    'category' => $movie->movieCategory->name,
                    'subCategory' => $movie->movieCategorySub->name,
                ]),
            'recentlyReviewed' => Movie::with('image')
                ->paginate(3, ['*'], 'recentlyReviewed')
                ->through(fn($movie) => [
                    'id' => $movie->id,
                    'name' => $movie->name,
                    'description' => $movie->description,
                    'logline' => $movie->logline,
                    'image' => [
                        'id' => $movie->image->id,
                        'name' => $movie->image->name,
                        'folder' => $movie->image->folder,
                        'cdn_endpoint' => $movie->appSetting->cdn_endpoint,
                        'cloud_folder' => $movie->image->cloud_folder,
                    ],
                    'slug' => $movie->slug,
                    'copyrightYear' => $movie->created_at->format('Y'),
                    'release_year' => $movie->release_year,
                    'category' => $movie->movieCategory->name,
                    'subCategory' => $movie->movieCategorySub->name,
                ]),
            'mostAnticipated' => Movie::with('image')
                ->paginate(3, ['*'], 'anticipated')
                ->through(fn($movie) => [
                    'id' => $movie->id,
                    'name' => $movie->name,
                    'description' => $movie->description,
                    'logline' => $movie->logline,
                    'image' => [
                        'id' => $movie->image->id,
                        'name' => $movie->image->name,
                        'folder' => $movie->image->folder,
                        'cdn_endpoint' => $movie->appSetting->cdn_endpoint,
                        'cloud_folder' => $movie->image->cloud_folder,
                    ],
                    'slug' => $movie->slug,
                    'copyrightYear' => $movie->created_at->format('Y'),
                    'release_year' => $movie->release_year,
                    'category' => $movie->movieCategory->name,
                    'subCategory' => $movie->movieCategorySub->name,
                ]),
            'comingSoon' => Movie::with('image')
                ->paginate(3, ['*'], 'comingSoon')
                ->through(fn($movie) => [
                    'id' => $movie->id,
                    'name' => $movie->name,
                    'description' => $movie->description,
                    'logline' => $movie->logline,
                    'image' => [
                        'id' => $movie->image->id,
                        'name' => $movie->image->name,
                        'folder' => $movie->image->folder,
                        'cdn_endpoint' => $movie->appSetting->cdn_endpoint,
                        'cloud_folder' => $movie->image->cloud_folder,
                    ],
                    'slug' => $movie->slug,
                    'copyrightYear' => $movie->created_at->format('Y'),
                    'release_year' => $movie->release_year,
                    'category' => $movie->movieCategory->name,
                    'subCategory' => $movie->movieCategorySub->name,
                ]),
            'filters' => Request::only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Movies/Upload', [
            'can' => [
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param HttpRequest $request
     * @return RedirectResponse
     */
    public function store(HttpRequest $request)
    {
        $request->validate([
            'name' => 'unique:movies|required|string|max:255',
            'logline' => 'required|string',
            'description' => 'required|string',
            'file_url' => 'nullable|active_url',
        ]);

        $slug  = \Str::slug($request->name);
        // create image model
        // NEED TO PROTECT THESE
        Movie::create([
            'name' => $request->name,
            'description' => $request->description,
            'logline' => $request->logline,
            'slug' => $slug,
            'file_url' => $request->file_url,
            'user_id' => Auth::user()->id,
        ]);

//        $movie = Movie::query()->where('name', $request->name)->firstOrFail();
        // Use this route to return
        // the user to the new movie page.
        return redirect()->route('movies.show', [$slug])->with('message', 'Movie Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param Movie $movie
     * @return Response
     */
    public function show(Movie $movie)
    {

        $video = Video::where('movies_id', $movie->id)->first();
        $trailer = Video::where('movie_trailers_id', $movie->id)->first();

        return Inertia::render('Movies/{$id}/Index', [
            'movie' => [
                'slug' => $movie->slug,
                'name' => $movie->name,
                'description' => $movie->description,
                'logline' => $movie->logline,
                'file_path' => $movie->file_path,
                'file_url' => $movie->file_url,
                'teamName' => $movie->team_id,
                'teamSlug' => $movie->slug,
                'image' => [
                    'id' => $movie->image->id,
                    'name' => $movie->image->name,
                    'folder' => $movie->image->folder,
                    'cdn_endpoint' => $movie->appSetting->cdn_endpoint,
                    'cloud_folder' => $movie->image->cloud_folder,
                ],
                // need to link the Movie and Team models.
                // change team to $movie->team->name
                // and add team->slug
                'copyrightYear' => $movie->created_at->format('Y'),
                'release_year' => $movie->release_year,
                'created_at' => $movie->created_at,
                'www_url' => $movie->www_url,
                'instagram_name' => $movie->instagram_name,
                'telegram_url' => $movie->telegram_url,
                'twitter_handle' => $movie->twitter_handle,
                'category' => $movie->movieCategory->name,
                'subCategory' => $movie->movieCategorySub->name,
                ],
            'video' => [
                'file_name' => $video->file_name ?? '',
                'cdn_endpoint' => $video->appSetting->cdn_endpoint ?? '',
                'folder' => $video->folder ?? '',
                'cloud_folder' => $video->cloud_folder ?? '',
                'upload_status' => $video->upload_status ?? '',
            ],
            'trailer' => [
                'file_name' => $trailer->file_name ?? '',
                'cdn_endpoint' => $trailer->appSetting->cdn_endpoint ?? '',
                'folder' => $trailer->folder ?? '',
                'cloud_folder' => $trailer->cloud_folder ?? '',
                'upload_status' => $video->upload_status ?? '',
            ],
            'can' => [
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
    public function edit(Movie $movie)
    {
//        $team = Show::query()->where('id', $show->id)->pluck('team_id')->firstOrFail();

        // Currently this queries all images in the database
        // this needs to be changed to limit the query.
        //

//        DB::table('movies')->where('id', $movie->id)->update([
//            'isBeingEditedByUser_id' => Auth::user()->id,
//        ]);

//        function getPoster($show){
//            $getPoster = Image::query()
//                ->where('show_id', $show->id)
//                ->pluck('name')
//                ->first();
//            if(!empty($getPoster)){
//                $poster = $getPoster;
//            } else {
//                $poster = 'EBU_Colorbars.svg.png';
//            }
//            return $poster;
//        }

        DB::table('users')->where('id', Auth::user()->id)->update([
            'isEditingMovie_id' => $movie->id,
        ]);

        DB::table('shows')->where('id', $movie->id)->update([
            'isBeingEditedByUser_id' => Auth::user()->id,
        ]);

        $categories = MovieCategory::all();
        $sub_categories = MovieCategorySub::all();
        $movie = Movie::query()->where('id', $movie->id)->first();
        $video = Video::where('movies_id', $movie->id)->first();
        $trailer = Video::where('movie_trailers_id', $movie->id)->first();

        return Inertia::render('Movies/{$id}/Edit', [
            'movie' => $movie,
            'video' => [
                'file_name' => $video->file_name ?? '',
                'cdn_endpoint' => $video->appSetting->cdn_endpoint ?? '',
                'folder' => $video->folder ?? '',
                'cloud_folder' => $video->cloud_folder ?? '',
                'upload_status' => $video->upload_status ?? '',
             ],
            'trailer' => [
                'file_name' => $trailer->file_name ?? '',
                'cdn_endpoint' => $trailer->appSetting->cdn_endpoint ?? '',
                'folder' => $trailer->folder ?? '',
                'cloud_folder' => $trailer->cloud_folder ?? '',
                'upload_status' => $video->upload_status ?? '',
            ],
            'image' => [
                'id' => $movie->image->id,
                'name' => $movie->image->name,
                'folder' => $movie->image->folder,
                'cdn_endpoint' => $movie->appSetting->cdn_endpoint,
                'cloud_folder' => $movie->image->cloud_folder,
            ],
            'categories' => $categories,
            'sub_categories' => $sub_categories,
            'movieCategory' => $movie->movieCategory->name,
//            'movieCategorySub' => $movie->movieCategorySub->name,
            // ^^^ sub-categories not enabled yet...

//            'team' => Team::query()->where('id', $show->team_id)->firstOrFail(),
//            'showRunner' => User::query()->where('id', $show->user_id)->pluck('id','name')->firstOrFail(),
//            'poster' => getPoster($show),
//            'can' => [
//                'viewShows' => Auth::user()->can('view', Show::class),
//                'editShow' => Auth::user()->can('edit', Show::class),
//                'viewCreator' => Auth::user()->can('viewCreator', User::class),
//            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param HttpRequest $request
     * @param Movie $movie
     * @return void
     */
    public function update(HttpRequest $request, Movie $movie)
    {
        // validate the request
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('movies')->ignore($movie->id)],
            'description' => 'required',
            'logline' => 'required|string',
            'release_year' => 'integer|min:1900|max:2300',
            'category' => 'required',
            'sub_category' => 'nullable',
            'file_url' => 'nullable|active_url',
            'www_url' => 'nullable|active_url',
            'instagram_name' => 'nullable|string|max:30',
            'telegram_url' => 'nullable|active_url',
            'twitter_handle' => 'nullable|string|min:4|max:15',
            'notes' => 'nullable|string|max:1024',
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
        $movie->save();
        sleep(1);


        // redirect
        return redirect(route('movies.show', [$movie->slug]))->with('message', 'Movie Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Movie $movie
     * @return void
     */
    public function destroy(Movie $movie)
    {
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
