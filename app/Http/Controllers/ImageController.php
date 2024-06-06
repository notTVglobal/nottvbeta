<?php

namespace App\Http\Controllers;

use App\Jobs\CheckImageHashAndHandleDuplicate;
use App\Jobs\SendUserNotificationJob;
use App\Models\AppSetting;
use App\Models\Movie;
use App\Models\NewsRssFeedItemArchive;
use App\Models\NewsStory;
use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\Team;
use App\Traits\AuthorizeModelTrait;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\File;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller {

  use AuthorizeModelTrait;

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
                'id'              => $image->id,
                'name'            => $image->name,
                'extension'       => $image->extension,
                'folder'          => $image->folder,
                'cdn_endpoint'    => $image->appSetting->cdn_endpoint,
                'cloud_folder'    => $image->cloud_folder,
                'placeholder_url' => $image->placeholder_url,
            ]),
//            'filters' => Request::only(['search'])
    ]);
  }

  public function show() {
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
  ///
  /// /**
  // * This controller handles the file upload functionality, including scanning uploaded files for viruses
  // * using ClamAV and removing metadata using ExifTool. The following steps explain how to install these tools
  // * and update the ClamAV database on a server.
  //
  // * 1. Installing ExifTool:
  // *    - ExifTool is a Perl library plus a command-line application for reading, writing, and editing meta information
  // *      in a wide variety of files.
  // *    - To install ExifTool on a Debian-based system, use the following command:
  // *
  // *      ```sh
  // *      sudo apt-get update
  // *      sudo apt-get install libimage-exiftool-perl
  // *      ```
  // *    - Verify the installation by running:
  // *
  // *      ```sh
  // *      exiftool -ver
  // *      ```
  //
  // * 2. Installing ClamAV:
  // *    - ClamAV is an open-source antivirus engine for detecting trojans, viruses, malware, and other malicious threats.
  // *    - To install ClamAV on a Debian-based system, use the following commands:
  // *
  // *      ```sh
  // *      sudo apt-get update
  // *      sudo apt-get install clamav clamav-daemon
  // *      ```
  // *    - After installation, update the ClamAV virus database with:
  // *
  // *      ```sh
  // *      sudo freshclam
  // *      ```
  //
  // * 3. Updating ClamAV Database:
  // *    - It is crucial to keep the ClamAV virus database up to date to ensure the latest threats are detected.
  // *    - Update the database periodically using the following command:
  // *
  // *      ```sh
  // *      sudo freshclam
  // *      ```
  // *    - You can automate this process by setting up a cron job. Edit the crontab file using:
  // *
  // *      ```sh
  // *      sudo crontab -e
  // *      ```
  // *    - Add the following line to schedule a daily update at midnight:
  // *
  // *      ```sh
  // *      0 0 * * * /usr/bin/freshclam
  // *      ```
  //
  // * Ensure that both ExifTool and ClamAV are installed and updated on your server to enhance the security and
  // * metadata handling of the uploaded files.
  // */


  public function upload(HttpRequest $request) {
    Log::info('image uploading...');

//    $rules = [
//        'image'     => 'required|file|image|max:30720', // Max 30MB
//        'modelType' => 'nullable|string', // Validate if present
//        'modelId'   => 'required|numeric', // Validate if present and should be numeric
//    ];

//    $validator = Validator::make($request->all(), $rules);

    // Commenting out the validation for debugging
    // $this->validateModelTypeAndId($validator, $request);

//    if ($validator->fails()) {
//      $errors = $validator->errors();
//      Log::error('Image upload validation failed', ['errors' => $errors->toArray()]);
//      return response()->json(['error' => 'Image upload validation failed.', 'details' => $errors], 422);
//    }

    try {
      $file = $request->file('image');
      if (!$file->isValid()) {
        Log::error('Uploaded file is not valid.');
        return response()->json(['error' => 'The uploaded file is not valid.'], 422);
      }
      $filePath = $file->getPathname();

      // Proceed with file upload and record creation
      // Check the MIME type of the file
      $mimeType = $file->getMimeType();
      if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
        return response()->json(['error' => 'The file must be a valid image (jpeg, png, gif).'], 422);
      }

      Log::info($mimeType);

      if ($request->input('removeExif')) {
        // Remove all metadata from the image
        $exifResult = shell_exec("exiftool -all= $filePath 2>&1");
        Log::info("ExifTool result: $exifResult");
      }

      $modelType = $request->input('modelType', null); // Default to null if not provided
      $modelId = $request->input('modelId', null); // Default to null if not provided

      // Authorize the user to edit the model
      if (!$this->authorizeModel($modelType, $modelId, 'edit')) {
        Log::error('User is not authorized to edit this model.');
        return response()->json(['error' => 'Unauthorized.'], 403);
      }

      // Retrieve cloud_folder and cdn_endpoint from app_settings
      $appSettings = AppSetting::where('id', 1)->first(['cloud_folder', 'cdn_endpoint']);
      if (!$appSettings) {
        throw new \Exception('App settings not found.');
      }

      $cloud_folder = $appSettings->cloud_folder;
      $cdn_endpoint = $appSettings->cdn_endpoint;
      $folder = Carbon::now()->format('/Y/m') . '/images';

      Storage::disk('spaces')->putFile($cloud_folder . $folder, $file);

      // Create the image record
      $image = Image::create([
          'name'             => $file->hashName(),
          'extension'        => $file->extension(),
          'size'             => $file->getSize(),
          'folder'           => $folder,
          'cloud_folder'     => $cloud_folder,
          'storage_location' => 'do_spaces',
          'created_at'       => Carbon::now(),
          'updated_at'       => Carbon::now()
      ]);

      CheckImageHashAndHandleDuplicate::dispatch($image, $modelType, $modelId);

      // Dynamically update the correct model with the image_id if modelType and modelId are provided
      if (!is_null($modelType) && !is_null($modelId)) {
        $this->updateModelWithImageId($modelType, $modelId, $image->id);
      }

      // Append the CDN endpoint to the image data
      $image->cdn_endpoint = $cdn_endpoint;

      return response()->json(['success' => 'Image uploaded successfully.', 'image' => $image]);
    } catch (\Exception $e) {
      Log::error('Image upload failed', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
      return response()->json(['error' => 'An error occurred during image upload. Please try again later.'], 500);
    }
  }

  protected function validateModelTypeAndId($validator, $request): void {
    $modelType = $request->input('modelType');
    $modelId = $request->input('modelId');

    if ($modelType && $modelId) {
      $validModelTypes = $this->getValidModelTypes();

      if (!isset($validModelTypes[$modelType])) {
        $validator->errors()->add('modelType', 'The model type is invalid.');
      } else {
        $modelClass = $validModelTypes[$modelType];
        if (!$modelClass::find($modelId)) {
          $validator->errors()->add('modelId', 'The model ID does not exist.');
        }
      }
    }
  }


  protected function getValidModelTypes(): array {
    return [
        'creator' => \App\Models\Creator::class,
        'team' => \App\Models\Team::class,
        'show' => \App\Models\Show::class,
        'showEpisode' => \App\Models\ShowEpisode::class,
        'newsStory' => \App\Models\NewsStory::class,
        'newsPerson' => \App\Models\NewsPerson::class,
        'newsRssFeedItem' => \App\Models\NewsRssFeedItemArchive::class,
        'movie' => \App\Models\Movie::class,
        'otherContent' => \App\Models\OtherContent::class,
        'subscriptionPlan' => \App\Models\SubscriptionPlan::class,
        'product' => \App\Models\Product::class,
        'video' => \App\Models\Video::class,
    ];
  }

  protected function updateModelWithImageId($modelType, $modelId, $imageId): void {
    $validModelTypes = $this->getValidModelTypes();
    $modelClass = $validModelTypes[$modelType] ?? null;

    if ($modelClass) {
      $model = $modelClass::find($modelId);
      if ($model) {
        $model->update(['image_id' => $imageId]);
      } else {
        // Handle error: Model not found
      }
    }
  }

  public function removeImage(HttpRequest $request): \Illuminate\Http\JsonResponse {
    $request->validate([
        'modelId'   => 'required|integer',
        'modelType' => 'required|string',
        'imageId'   => 'required|integer',
    ]);

    $image = Image::find($request->input('imageId'));

    if ($image) {
      // Check if the image is referenced by any other models
      $otherReferences = false;

      // Get all models that might reference the image
      $referencingModels = [
          'NewsStory',
          'NewsPerson',
          'Creator',
          'Movie',
          'Show',
          'ShowEpisode',
          'OtherContent',
          'Team',
          'Video',
          'NewsRssFeedItemArchive',
          'Product'
        // Add other models here
      ];

      foreach ($referencingModels as $modelClass) {
        $modelClass = 'App\\Models\\' . $modelClass;
        if ($modelClass::where('image_id', $image->id)->where('id', '!=', $request->input('modelId'))->exists()) {
          $otherReferences = true;
          break;
        }
      }

      if (!$otherReferences) {
        if ($image->cloud_folder) {
          // Delete the image file from storage
          $imagePath = $image->cloud_folder . $image->folder . '/' . $image->name;
          Storage::disk('spaces')->delete($imagePath);
        }

        // Delete the image record from the database
        $image->delete();
      }

      // Remove the image_id from the related model
      $modelClass = 'App\\Models\\' . ucfirst($request->input('modelType'));
      $model = $modelClass::find($request->input('modelId'));

      if ($model) {
        $model->image_id = null;
        $model->save();
      }

    }

    return response()->json(['success' => true, 'message' => 'Image removed successfully.']);
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
  protected function createFilename(UploadedFile $file) {
    $extension = $file->getClientOriginalExtension();
    $filename = str_replace("." . $extension, "", $file->getClientOriginalName()); // Filename without extension

    // Add timestamp hash to name of the file
    $filename .= "_" . md5(time()) . "." . $extension;

    return $filename;
  }

}
