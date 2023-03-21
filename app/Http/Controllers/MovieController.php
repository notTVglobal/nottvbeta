<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Movie;
use App\Models\MovieCategory;
use App\Models\MovieCategorySub;
use App\Models\User;
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
//                    'teamName' => $movie->team->name,
//                    'teamSlug' => $movie->team->slug,
                    'userId' => $movie->user_id,
//                    'userName' => $movie->user->name,
//                    'poster' => $movie->image->name,
                    'slug' => $movie->slug,
                    'copyrightYear' => $movie->created_at->format('Y'),
                    'release_year' => $movie->release_year,
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
//        $request->file('video')->store('videos');
//        if(!$request->hasFile('video')) {
//            return Redirect::back()->with(['message' => 'There is no video present.']);
//        }

        // see if you can add mimetypes to the validation.
        // video/mov
        // video/mp4
        //video/webm
        //
        if (!$request->hasFile('video') && $request->file_url == null) {
            return redirect()->route('movies.create')->with('message', 'Please upload a video or enter a link to a video file.');

        }
        if ($request->file_url != null) {
            $request->validate([
                'file_url' => 'active_url',
            ]);
        }
        $request->validate([
            'name' => 'unique:movies|required|string|max:255',
            'description' => 'required|string',
        ]);

        $video = '';
        $extension = '';
        $size = 0;
        $mimeTypes = '';
        $fileName = '';

//        dd($extension, $mimeTypes);
        if ($request->hasFile('video')) {
            $request->validate([
                'video' => 'file|max:10000000',
            ]);
            $video = $request->file('video');
            $extension = $request->file('video')->extension();
            $size = $request->file('video')->getSize();
            $mimeTypes = $request->file('video')->getMimeType();
            $fileName = $video->hashName();
            Storage::disk('do_spaces')->putFileAs('uploads/movies', $video, $fileName, 'public');
        }

        $slug  = \Str::slug($request->name);
        // create image model
        // NEED TO PROTECT THESE
        Movie::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => $slug,
            'file_path' => $fileName,
            'file_url' => $request->file_url,
            'extension' => $extension,
            'size' => $size,
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

        return Inertia::render('Movies/{$id}/Index', [
            'movie' => [
                'slug' => $movie->slug,
                'name' => $movie->name,
                'description' => $movie->description,
                'filePath' => $movie->file_path,
                'fileUrl' => $movie->file_url,
                // need to link the Movie and Team models.
                // change team to $movie->team->name
                // and add team->slug
                'teamName' => $movie->team_id,
                'teamSlug' => $movie->slug,
                'copyrightYear' => $movie->created_at->format('Y'),
                'release_year' => $movie->release_year,
                'created_at' => $movie->created_at,
                // need to link the Movie and Images models.
                // change poster to $movie->image->name
                'poster' => $movie->image_id,
                'www_url' => $movie->www_url,
                'instagram_name' => $movie->instagram_name,
                'telegram_url' => $movie->telegram_url,
                'twitter_handle' => $movie->twitter_handle,
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
        $categories = MovieCategory::all();
        $sub_categories = MovieCategorySub::all();
        $movie = Movie::query()->where('id', $movie->id)->firstOrFail();

        return Inertia::render('Movies/{$id}/Edit', [
            'movie' => $movie,
            'categories' => $categories,
            'sub_categories' => $sub_categories,
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
            'release_year' => 'integer|min:1900|max:2300',
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
        $movie->release_year = $request->release_year;
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
