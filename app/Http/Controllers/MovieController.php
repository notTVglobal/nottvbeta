<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Movies', [
            'can' => [
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
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
    public function store(Request $request)
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'video' => 'file|max:10000000',
        ]);

        $video = $request->file('video');
        $extension = $request->file('video')->extension();
        $size = $request->file('video')->getSize();
        $mimeTypes = $request->file('video')->getMimeType();

//        dd($extension, $mimeTypes);
        if ($request->hasFile('video')) {
            Storage::disk('do_spaces')->putFileAs('uploads/movies', $video, time().'.'.$extension, 'public');
        }

        // create image model
        // NEED TO PROTECT THESE
        Movie::create([
            'name' => $request->name,
            'description' => $request->description,
            'file_path' => $video->hashName(),
            'extension' => $extension,
            'size' => $size,
            'user_id' => Auth::user()->id,
        ]);

        // return that image model back to the frontend
        return redirect()->route('movies.show')->with('message', 'Movie Uploaded Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
        return Inertia::render('Movies/Index', [
            'video' => '1668721032.mp4',
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
    }
}
