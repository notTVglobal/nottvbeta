<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\Team;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\File;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

    // This is the /image index page.
    public function index() {
        // tec21: this search functionality only works with Illuminate\Support\Facades\Request
        // which ends up breaking the filepond upload function which uses Illuminate\Http\Request
        return Inertia::render('Image', [
            'images' => Image::query()
//                ->when(Request::input('search'), function ($query, $search) {
//                    $query->where('name', 'like', "%{$search}%");
//                })
                ->latest()
                ->paginate(10)
                ->withQueryString()
                ->through(fn($image) => [
                    'id' => $image->id,
                    'name' => $image->name,
                    'extension' => $image->extension,
                    'folder' => $image->folder,
                    'cdn_folder' => $image->appSetting->cdn_folder,
                    'cdn_endpoint' => $image->appSetting->cdn_endpoint,
                ]),
//            'filters' => Request::only(['search'])
        ]);
    }

    public function show()
    {
        // this is where we will create a page
        // to edit an image and manage its
        // versions.. thumb,small,medium, etc.
        //
        // return all images
        return Image::latest()->pluck('name')->toArray();
    }


    ////////////// GENERIC STORE IMAGE
    //////////////////////////////////
    public function store(HttpRequest $request)
    {
        // validate the incoming file
        // TO DO
        // Need to validate/sanitize the image upload.
        // strip the metadata and convert the image file.
        // don't save the original! HACKER WARNING!!
        //
        // Set up the file and folder
        $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cdn_folder')->first();
        $folder = '/images';
        $file = $request->file('image');
        $filename = $file->getClientOriginalName();

        // Store the file to DO_SPACES
        Storage::disk('spaces')->putFile($cloud_folder.$folder, $request->file('image'));

        // create image model
        $image = Image::create([
            'user_id' => auth()->user()->id,
            'name' => $file->hashName(),
            'extension' => $file->extension(),
            'size' => $file->getSize(),
            'folder' => $folder,
        ])->insertGetId;

        // return that image model back to the frontend
        return $image;
    }


    ////////////// UPLOAD SHOW POSTER
    //////////////////////////////////

    public function uploadShowPoster(HttpRequest $request)
    {

        // validate the incoming file
        // TO DO
        // Need to validate/sanitize the image upload.
        // strip the metadata and convert the image file.
        // don't save the original! HACKER WARNING!!
        //

        // lock show for editing
        // TO DO... this needs to be developed,
        // where more than one person cannot
        // edit or upload to a show at a time.
        //
        // Set up the file and folder
        $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cdn_folder')->first();
        $folder = '/images';
        $file = $request->file('image');
//        dd($file);
        $filename = $file->getClientOriginalName();

        // Store the file to DO_SPACES
        Storage::disk('spaces')->putFile($cloud_folder.$folder, $request->file('image'));

        // Store image locally
        //  $request->file('poster')->store('images');

        // Set up the info for the database model
        $user = auth()->user()->id;
        $showId = auth()->user()->isEditingShow_id;
        $uploadedFile = $request->file('image');

        // create image model
        $image_id = DB::table('images')->insertGetId([
            'user_id' => $user,
            'show_id' => Auth::user()->isEditingShow_id,
            'name' => $uploadedFile->hashName(),
            'extension' => $uploadedFile->extension(),
            'size' => $uploadedFile->getSize(),
            'folder' => $folder,
        ]);

        // store image_id to Shows table
        $show = Show::find($showId);
        $show->image_id = $image_id;
        $show->save();

        // return that image model back to the frontend
        $image = DB::table('images')->where('id', $image_id)->first();
        return $image;

    }

    ////////////// UPLOAD SHOW EPISODE POSTER
    //////////////////////////////////

    public function uploadShowEpisodePoster(HttpRequest $request)
    {

        // validate the incoming file
        // TO DO
        // Need to validate/sanitize the image upload.
        // strip the metadata and convert the image file.
        // don't save the original! HACKER WARNING!!
        //

        // lock show for editing
        // TO DO... this needs to be developed,
        // where more than one person cannot
        // edit or upload to a show at a time.
        //
        // Set up the file and folder
        $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cdn_folder')->first();
        $folder = '/images';
        $file = $request->file('image');
//        dd($file);
        $filename = $file->getClientOriginalName();

        // Store the file to DO_SPACES
        Storage::disk('spaces')->putFile($cloud_folder.$folder, $request->file('image'));
        // Store image locally
        //  $request->file('poster')->store('images');

        // Set up the info for the database model
        $user = auth()->user()->id;
        $showId = auth()->user()->isEditingShowEpisode_id;
        $uploadedFile = $request->file('image');

        // create image model
        $image_id = DB::table('images')->insertGetId([
            'user_id' => $user,
            'show_episode_id' => Auth::user()->isEditingShowEpisode_id,
            'name' => $uploadedFile->hashName(),
            'extension' => $uploadedFile->extension(),
            'size' => $uploadedFile->getSize(),
            'folder' => $folder,
        ]);



        // store image_id to ShowEpisodes table
        $show = ShowEpisode::find($showId);
        $show->image_id = $image_id;
        $show->save();

        // return that image model back to the frontend
        $image = DB::table('images')->where('id', $image_id)->first();
        return $image;

    }

    ////////////// UPLOAD TEAM LOGO
    ///////////////////////////////

    public function uploadTeamLogo(HttpRequest $request)
    {

        // validate the incoming file
        // TO DO
        // Need to validate/sanitize the image upload.
        // strip the metadata and convert the image file.
        // don't save the original! HACKER WARNING!!
        //

        // lock show for editing
        // TO DO... this needs to be developed,
        // where more than one person cannot
        // edit or upload to a show at a time.
        //
        // Set up the file and folder
        $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cdn_folder')->first();
        $folder = '/images';
        $file = $request->file('image');
//        dd($file);
        $filename = $file->getClientOriginalName();

        // Store the file to DO_SPACES
        Storage::disk('spaces')->putFile($cloud_folder.$folder, $request->file('image'));

        // Store image locally
        //  $request->file('poster')->store('images');

        // Set up the info for the database model
        $user = auth()->user()->id;
        $teamId = auth()->user()->isEditingTeam_id;
        $uploadedFile = $request->file('image');

        // create image model
        $image_id = DB::table('images')->insertGetId([
            'user_id' => $user,
            'team_id' => Auth::user()->isEditingTeam_id,
            'name' => $uploadedFile->hashName(),
            'extension' => $uploadedFile->extension(),
            'size' => $uploadedFile->getSize(),
            'folder' => $folder,
        ]);

        // store image_id to Teams table
        $team = Team::find($teamId);
        $team->image_id = $image_id;
        $team->save();

        // return that image model back to the frontend
        $image = DB::table('images')->where('id', $image_id)->first();
        return $image;

    }


////////////// UPLOAD MOVIE POSTER
    ///////////////////////////////

    public function uploadMoviePoster(HttpRequest $request)
    {

        // validate the incoming file
        // TO DO
        // Need to validate/sanitize the image upload.
        // strip the metadata and convert the image file.
        // don't save the original! HACKER WARNING!!
        //

        // lock show for editing
        // TO DO... this needs to be developed,
        // where more than one person cannot
        // edit or upload to a show at a time.
        //
        // Set up the file and folder
        $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cdn_folder')->first();
        $folder = '/images';
        $file = $request->file('image');
        $filename = $file->getClientOriginalName();

        // Store the file to DO_SPACES
        Storage::disk('spaces')->putFile($cloud_folder.$folder, $request->file('image'));

        // Store image locally
        //  $request->file('poster')->store('images');

        // Set up the info for the database model
        $user = auth()->user()->id;
        $movieId = auth()->user()->isEditingMovie_id;
        $uploadedFile = $request->file('image');

        // create image model
        $image_id = DB::table('images')->insertGetId([
            'user_id' => $user,
            'movie_id' => Auth::user()->isEditingMovie_id,
            'name' => $uploadedFile->hashName(),
            'extension' => $uploadedFile->extension(),
            'size' => $uploadedFile->getSize(),
            'folder' => $folder,
        ]);

        // store image_id to Movies table
        $movie = Movie::find($movieId);
        $movie->image_id = $image_id;
        $movie->save();

        // return that image model back to the frontend
        $image = DB::table('images')->where('id', $image_id)->first();
        return $image;

    }






    ////////////// CREATE FILENAME
    //////////////////////////////
    protected function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace(".".$extension, "", $file->getClientOriginalName()); // Filename without extension

        // Add timestamp hash to name of the file
        $filename .= "_" . md5(time()) . "." . $extension;

        return $filename;
    }




}
