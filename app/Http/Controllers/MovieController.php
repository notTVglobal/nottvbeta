<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Inertia\Inertia;

class MovieController extends Controller
{

    public function __construct()
    {

        //

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Movies');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
        $request->validate([
            'name' => 'unique:movies|required|string|max:255',
            'description' => 'required|string',
            'video' => 'file|max:10000000',
        ]);

        $video = $request->file('video');
        $extension = $request->file('video')->extension();
        $size = $request->file('video')->getSize();
        $mimeTypes = $request->file('video')->getMimeType();
        $fileName = $video->hashName();

//        dd($extension, $mimeTypes);
        if ($request->hasFile('video')) {
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
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
        return Inertia::render('Movies/{$id}/Index', [
            'movie' => [
                'name' => $movie->name,
                'description' => $movie->description,
                'filePath' => $movie->file_path,
                // need to link the Movie and Team models.
                // change team to $movie->team->name
                // and add team->slug
                'teamName' => $movie->team_id,
                'teamSlug' => $movie->slug,
                'copyrightYear' => $movie->created_at->format('Y'),
                'created_at' => $movie->created_at,
                // need to link the Movie and Images models.
                // change poster to $movie->image->name
                'poster' => $movie->image_id,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
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
