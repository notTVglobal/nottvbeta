<?php

namespace App\Http\Controllers;

use App\Jobs\CheckImageHashAndHandleDuplicate;
use App\Models\Movie;
use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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
        return Inertia::render('Admin/Images', [
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
                    'cdn_endpoint' => $image->appSetting->cdn_endpoint,
                    'cloud_folder' => $image->cloud_folder,
                    'placeholder_url' => $image->placeholder_url,
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
        // refactor this to /image/{$id}
        // return all images
        return Image::latest()->pluck('name')->toArray();
    }


    ////////////// GENERIC STORE IMAGE
    //////////////////////////////////
//    public function store(HttpRequest $request)
//    {
//
//        // Can we create a function createImage() --- started below
//        // to simplify these upload requests?
//
//
//        // validate the incoming file
//        // TO DO
//        // Need to validate/sanitize the image upload.
//        // strip the metadata and convert the image file.
//        // don't save the original! HACKER WARNING!!
//        //
//        // Set up the file and folder
//        $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cloud_folder')->first();
//        $folder = Carbon::now()->format('/Y/m').'/images';
//        $file = $request->file('image');
//        $filename = $file->getClientOriginalName();
//
//        // Store the file to DO_SPACES
//        // Note: if this changes be sure to update the 'storage_location' below.
//        Storage::disk('spaces')->putFile($cloud_folder.$folder, $request->file('image'));
//
//        // create image model
//        $image = Image::create([
//            'user_id' => auth()->user()->id,
//            'name' => $file->hashName(),
//            'extension' => $file->extension(),
//            'size' => $file->getSize(),
//            'folder' => $folder,
//            'cloud_folder' => $cloud_folder,
//            // storage_location is hard-coded for now
//            // this will be either:
//            // - a storage provider (s3 like aws, digital ocean, wasabi)
//            // - torrent / p2p ( when we integrate this into the MediaBoxes)
//            // - local (maybe the file is only available locally)
//            'storage_location' => 'do_spaces',
//        ])->insertGetId;
//
//        // return that image model back to the frontend
//        return $image;
//    }


    ////////////// UPLOAD SHOW POSTER
    //////////////////////////////////

//    public function uploadShowPoster(HttpRequest $request)
//    {
//
//        // Can we create a function createImage() --- started below
//        // to simplify these upload requests?
//
//
//        // validate the incoming file
//        // TO DO
//        // Need to validate/sanitize the image upload.
//        // strip the metadata and convert the image file.
//        // don't save the original! HACKER WARNING!!
//        //
//
//        // lock show for editing
//        // TO DO... this needs to be developed,
//        // where more than one person cannot
//        // edit or upload to a show at a time.
//        //
//        // Set up the file and folder
//        $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cloud_folder')->first();
//        $folder = Carbon::now()->format('/Y/m').'/images';
//        $file = $request->file('image');
//        $filename = $file->getClientOriginalName();
//
//        // Store the file to DO_SPACES
//        // Note: if this changes be sure to update the 'storage_location' below.
//        Storage::disk('spaces')->putFile($cloud_folder.$folder, $request->file('image'));
//        // Store image locally
//        //  $request->file('poster')->store('images');
//
//        // Set up the info for the database model
//        $user = auth()->user()->id;
//        $showId = auth()->user()->isEditingShow_id;
//        $uploadedFile = $request->file('image');
//
//        // create image model
//        $image_id = DB::table('images')->insertGetId([
//            'user_id' => $user,
//            'show_id' => Auth::user()->isEditingShow_id,
//            'name' => $uploadedFile->hashName(),
//            'extension' => $uploadedFile->extension(),
//            'size' => $uploadedFile->getSize(),
//            'folder' => $folder,
//            'cloud_folder' => $cloud_folder,
//            // storage_location is hard-coded for now
//            // this will be either:
//            // - a storage provider (s3 like aws, digital ocean, wasabi)
//            // - torrent / p2p ( when we integrate this into the MediaBoxes)
//            // - local (maybe the file is only available locally)
//            'storage_location' => 'do_spaces',
//        ]);
//
//        // store image_id to Shows table
//        $show = Show::find($showId);
//        $show->image_id = $image_id;
//        $show->save();
//
//        // return that image model back to the frontend
//        $image = DB::table('images')->where('id', $image_id)->first();
//        return $image;
//
//    }

    ////////////// UPLOAD SHOW EPISODE POSTER
    //////////////////////////////////

//    public function uploadShowEpisodePoster(HttpRequest $request)
//    {
//
//        // Can we create a function createImage() --- started below
//        // to simplify these upload requests?
//
//        // validate the incoming file
//        // TO DO
//        // Need to validate/sanitize the image upload.
//        // strip the metadata and convert the image file.
//        // don't save the original! HACKER WARNING!!
//        //
//
//        // lock show for editing
//        // TO DO... this needs to be developed,
//        // where more than one person cannot
//        // edit or upload to a show at a time.
//        //
//        // Set up the file and folder
//        $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cloud_folder')->first();
//        $folder = Carbon::now()->format('/Y/m').'/images';
//        $file = $request->file('image');
//        $filename = $file->getClientOriginalName();
//
//        // Store the file to DO_SPACES
//        // Note: if this changes be sure to update the 'storage_location' below.
//        Storage::disk('spaces')->putFile($cloud_folder.$folder, $request->file('image'));
//        // Store image locally
//        //  $request->file('poster')->store('images');
//
//        // Set up the info for the database model
//        $user = auth()->user()->id;
//        $showId = auth()->user()->isEditingShowEpisode_id;
//        $uploadedFile = $request->file('image');
//
//        // create image model
//        $image_id = DB::table('images')->insertGetId([
//            'user_id' => $user,
//            'show_episode_id' => Auth::user()->isEditingShowEpisode_id,
//            'name' => $uploadedFile->hashName(),
//            'extension' => $uploadedFile->extension(),
//            'size' => $uploadedFile->getSize(),
//            'folder' => $folder,
//            'cloud_folder' => $cloud_folder,
//            // storage_location is hard-coded for now
//            // this will be either:
//            // - a storage provider (s3 like aws, digital ocean, wasabi)
//            // - torrent / p2p ( when we integrate this into the MediaBoxes)
//            // - local (maybe the file is only available locally)
//            'storage_location' => 'do_spaces',
//        ]);
//
//
//
//        // store image_id to ShowEpisodes table
//        $show = ShowEpisode::find($showId);
//        $show->image_id = $image_id;
//        $show->save();
//
//        // return that image model back to the frontend
//        $image = DB::table('images')->where('id', $image_id)->first();
//        return $image;
//
//    }

    ////////////// UPLOAD FORMERLY UPLOAD TEAM LOGO
    ///////////////////////////////////////////////


  public function upload(HttpRequest $request)
  {
    // Updated validation rules
    $rules = [
        'image' => 'required|image|max:30720', // Max 30MB
        'modelType' => 'nullable|string|in:team,show,showEpisode', // Validate if present
        'modelId' => 'nullable|integer', // Validate if present
    ];

    // Perform validation
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
//      return redirect()->back()->with('error', $validator->errors());
    }

    // Proceed with file upload and record creation
    $file = $request->file('image');
    $modelType = $request->input('modelType', null); // Default to null if not provided
    $modelId = $request->input('modelId', null); // Default to null if not provided

    $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cloud_folder')->first();
    $folder = Carbon::now()->format('/Y/m').'/images';
    Storage::disk('spaces')->putFile($cloud_folder.$folder, $file);

    // Create the image record
    $image = Image::create([
        'name' => $file->hashName(),
        'extension' => $file->extension(),
        'size' => $file->getSize(),
        'folder' => $folder,
        'cloud_folder' => $cloud_folder,
        'storage_location' => 'do_spaces',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ]);

    CheckImageHashAndHandleDuplicate::dispatch($image, $modelType, $modelId);

    // Dynamically update the correct model with the image_id if modelType and modelId are provided
    if (!is_null($modelType) && !is_null($modelId)) {
      $this->updateModelWithImageId($modelType, $modelId, $image->id);
    }

    return $image;
  }

  protected function updateModelWithImageId($modelType, $modelId, $imageId): void {
    $model = null;
    switch ($modelType) {
      case 'team':
        $model = \App\Models\Team::find($modelId);
        break;
      case 'show':
        $model = \App\Models\Show::find($modelId);
        break;
      case 'showEpisode':
        $model = \App\Models\ShowEpisode::find($modelId);
        break;
      case 'movie':
        $model = \App\Models\Movie::find($modelId);
        break;
      case 'newsStory':
        $model = \App\Models\NewsStory::find($modelId);
        break;
      case 'newsRssFeedItem':
        $model = \App\Models\NewsRssFeedItemArchive::find($modelId);
        break;
      case 'otherContent':
        $model = \App\Models\OtherContent::find($modelId);
        break;
      case 'subscriptionPlan':
        $model = \App\Models\SubscriptionPlan::find($modelId);
        break;
      case 'product':
        $model = \App\Models\Product::find($modelId);
        break;
      case 'video':
        $model = \App\Models\Video::find($modelId);
        break;
      // Add more cases as needed for additional models
    }

    if ($model) {
      $model->update(['image_id' => $imageId]);
    } else {
      // Handle error: Model not found
    }
  }

//    public function upload(HttpRequest $request)
//    {
////        $metadata = $request->input('metadata');
//        // Can we create a function createImage() --- started below
//        // to simplify these upload requests?
//
//
//        // validate the incoming file
//        // TO DO
//        // Need to validate/sanitize the image upload.
//        // strip the metadata and convert the image file.
//        // don't save the original! HACKER WARNING!!
//        //
//
//        // lock show for editing
//        // TO DO... this needs to be developed,
//        // where more than one person cannot
//        // edit or upload to a show at a time.
//        //
//        // Set up the file and folder
//        $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cloud_folder')->first();
//        $folder = Carbon::now()->format('/Y/m').'/images';
//        $file = $request->file('image');
//        $filename = $file->getClientOriginalName();
//
//        // Store the file to DO_SPACES
//        // Note: if this changes be sure to update the 'storage_location' below.
//        Storage::disk('spaces')->putFile($cloud_folder.$folder, $request->file('image'));
//        // Store image locally
//        //  $request->file('poster')->store('images');
//
//        // Set up the info for the database model
//        $user = auth()->user()->id;
//        $teamId = auth()->user()->isEditingTeam_id;
//        $uploadedFile = $request->file('image');
//
//        // create image model
//        $image_id = DB::table('images')->insertGetId([
//            'user_id' => $user,
//            'team_id' => Auth::user()->isEditingTeam_id,
//            'name' => $uploadedFile->hashName(),
//            'extension' => $uploadedFile->extension(),
//            'size' => $uploadedFile->getSize(),
//            'folder' => $folder,
//            'cloud_folder' => $cloud_folder,
//            // storage_location is hard-coded for now
//            // this will be either:
//            // - a storage provider (s3 like aws, digital ocean, wasabi)
//            // - torrent / p2p ( when we integrate this into the MediaBoxes)
//            // - local (maybe the file is only available locally)
//            'storage_location' => 'do_spaces',
//        ]);
//
//        // store image_id to Teams table
//        $team = Team::find($teamId);
//        $team->image_id = $image_id;
//        $team->save();
//
//        // return that image model back to the frontend
//        $image = DB::table('images')->where('id', $image_id)->first();
//        return $image;
//
//    }


////////////// UPLOAD MOVIE POSTER
    ///////////////////////////////

//    public function uploadMoviePoster(HttpRequest $request)
//    {
//
//        // Can we create a function createImage() --- started below
//        // to simplify these upload requests?
//
//
//        // validate the incoming file
//        // TO DO
//        // Need to validate/sanitize the image upload.
//        // strip the metadata and convert the image file.
//        // don't save the original! HACKER WARNING!!
//        //
//
//        // lock show for editing
//        // TO DO... this needs to be developed,
//        // where more than one person cannot
//        // edit or upload to a show at a time.
//        //
//        // Set up the file and folder
//        $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cloud_folder')->first();
//        $folder = Carbon::now()->format('/Y/m').'/images';
//        $file = $request->file('image');
//        $filename = $file->getClientOriginalName();
//
//        // Store the file to DO_SPACES
//        Storage::disk('spaces')->putFile($cloud_folder.$folder, $request->file('image'));
//
//        // Store image locally
//        //  $request->file('poster')->store('images');
//
//        // Set up the info for the database model
//        $user = auth()->user()->id;
//        // tec21: we use this 'isEditingNNNN_id' to get the
//        // model the image belongs to, because I couldn't
//        // find a way to send any custom data to the backend
//        // from filepond.
//        $movieId = auth()->user()->isEditingMovie_id;
//        $uploadedFile = $request->file('image');
//
//        // create image model
//        $image_id = DB::table('images')->insertGetId([
//            'user_id' => $user,
//            'movie_id' => Auth::user()->isEditingMovie_id,
//            'name' => $uploadedFile->hashName(),
//            'extension' => $uploadedFile->extension(),
//            'size' => $uploadedFile->getSize(),
//            'folder' => $folder,
//            'cloud_folder' => $cloud_folder,
//            'storage_location' => 'do_spaces',
//        ]);
//
//        // store image_id to Movies table
//        $movie = Movie::find($movieId);
//        $movie->image_id = $image_id;
//        $movie->save();
//
//        // return that image model back to the frontend
//        $image = DB::table('images')->where('id', $image_id)->first();
//        return $image;
//
//    }



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
