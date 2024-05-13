<?php

namespace App\Http\Controllers;

use App\Models\MovieTrailer;
use App\Models\Video;
use App\Models\User;
use App\Jobs\UploadVideoToSpacesJob;
use App\Models\VideoUploadJob;

use Carbon\Carbon;
use Dotenv\Exception\InvalidFileException;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Illuminate\Http\Request as HttpRequest;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

use App\Models\Movie;
use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\Team;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\File;
use App\Models\Image;

//use function MongoDB\BSON\toJSON;


class VideoUploadController extends Controller {

  protected $video;

  public function index() {

    // tec21: can either of these query builders be used to grab the
    // newest 20 videos from the database (allVideos) for the admin
    // then if the admin searches it searches the whole database and
    // updates the results?
    //
//        $allVideos = DB::table('videos')->get()->chunk(500, function($rows) {
//            // process $rows
//        });
//
//        $subQuery = DB::table('videos')->where(...);
//        DB::query()->fromSub($subQuery, 'alias')->orderBy('alias.id')->chunk(200, function ($chunk) {
//            // Do something
//        });

    function formatBytes($bytes, $precision = 2) {
      $unit = ["B", "KB", "MB", "GB"];
      $exp = floor(log($bytes, 1024)) | 0;

      return round($bytes / (pow(1024, $exp)), $precision) . $unit[$exp];
    }

    return Inertia::render('VideoUpload', [
//            'videos' => fn () => Video::query()->where('user_id', auth()->user()->id)
        'myTotalStorageUsed'    => formatBytes(Video::where('user_id', auth()->user()->id)
            ->where('storage_location', '=', 'spaces')
            ->sum('size')),
        'notTvTotalStorageUsed' => formatBytes(Video::where('storage_location', '=', 'spaces')
            ->sum('size')),
        'videos'                => Video::with('showEpisode.show', 'movie', 'movieTrailer', 'newsStory')
            ->when(Request::input('search'), function ($query, $search) {
              $query->where('file_name', 'like', "%{$search}%");
            })
            ->where('user_id', auth()->user()->id)
            ->where('storage_location', '=', 'spaces')
            ->latest()
            ->paginate(10, ['*'], 'videos')
            ->through(fn($video) => [
                'id'                   => $video->id,
                'user_id'              => $video->user->name,
                'file_name'            => $video->file_name,
                'extension'            => $video->extension,
                'storage_location'     => $video->storage_location,
                'folder'               => $video->folder,
                'cdn_endpoint'         => $video->appSetting->cdn_endpoint,
                'cloud_folder'         => $video->cloud_folder,
                'cloud_private_folder' => $video->cloud_private_folder,
                'upload_status'        => $video->upload_status,
                'category'             => $video->category,
                'type'                 => $video->type,
                'size'                 => formatBytes($video->size),
                'created_at'           => $video->created_at,
                'showEpisode'          => [
                    'data' => $video->showEpisode,
                    'show' => $video->showEpisode?->show, // Optional chaining for safety
                ],
                'movie'                => $video->movie,
                'movieTrailer'         => $video->movieTrailer,
                'newsStory'            => $video->newsStory,
                'can'                  => [
                    'viewAny' => auth()->user()->can('viewAny', $video),
                    'view'    => auth()->user()->can('view', $video),
                    'create'  => auth()->user()->can('create', $video),
                    'edit'    => auth()->user()->can('update', $video),
                    'delete'  => auth()->user()->can('delete', $video),
                ]
            ]),

      // this is a temporary query... use chunking or lazy loading
      // to reduce load on the server ... replace it with a more
      // efficient query.
      //
        'allVideos'             => Video::with('showEpisode', 'movie', 'movieTrailer', 'newsStory')
            ->when(Request::input('search'), function ($query, $search) {
              $query->where('file_name', 'like', "%{$search}%");
            })
            ->where('storage_location', '=', 'spaces')
            ->latest()
            ->paginate(10, ['*'], 'allVideos')
            ->withQueryString()
            ->through(fn($video) => [
                'id'                   => $video->id,
                'user_id'              => $video->user->name,
                'file_name'            => $video->file_name,
                'extension'            => $video->extension,
                'storage_location'     => $video->storage_location,
                'folder'               => $video->folder,
                'cdn_endpoint'         => $video->appSetting->cdn_endpoint,
                'cloud_folder'         => $video->cloud_folder,
                'cloud_private_folder' => $video->cloud_private_folder,
                'upload_status'        => $video->upload_status,
                'category'             => $video->category,
                'type'                 => $video->type,
                'size'                 => formatBytes($video->size),
                'created_at'           => $video->created_at,
                'showEpisode'          => $video->showEpisode,
                'movie'                => $video->movie,
                'movieTrailer'         => $video->movieTrailer,
                'newsStory'            => $video->newsStory,
                'can'                  => [
                    'viewAny' => auth()->user()->can('viewAny', $video),
                    'view'    => auth()->user()->can('view', $video),
                    'create'  => auth()->user()->can('create', $video),
                    'edit'    => auth()->user()->can('update', $video),
                    'delete'  => auth()->user()->can('delete', $video),
                ]
            ]),
        'filters'               => \Illuminate\Support\Facades\Request::only(['search']),
        'can'                   => [
            'viewAny' => auth()->user()->can('viewAny', Video::class)
        ]
    ]);
  }


  /**
   * Handles the file upload
   *
   * @param HttpRequest $request
   *
   * @return JsonResponse
   *
   * @throws UploadFailedException
   * @throws UploadMissingFileException
   */

  public function upload(HttpRequest $request): JsonResponse {
    $user = auth()->user();
    $uploadIdentifier = 'upload_' . $user->id . '_' . $request->dzuuid;

    // validate the file
//        ???
    $movieId = $request->movieId;
    $movieTrailerId = $request->movieTrailerId;
    $showEpisodeId = $request->showEpisodeId;


    // create the file receiver
    $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
//        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));


    // check if the upload is success, throw exception or return response you need
    if ($receiver->isUploaded() === false) {
      throw new UploadMissingFileException();
    }

    // receive the file
    $save = $receiver->receive();


// Check if the message has already been logged for this upload
    if (!session()->has($uploadIdentifier)) {
      // Determine the log message based on conditions
      if ($movieId) {
        $logMessage = 'A new movie upload has started.';
      } else if ($movieTrailerId) {
        $logMessage = 'A new movie trailer upload has started.';
      } else if ($showEpisodeId) {
        $logMessage = 'A new show episode upload has started.';
      } else
        $logMessage = 'A new video upload has started by ' . $user->name;

      Log::info($logMessage);

      // Set the flag in the session to indicate the message has been logged
      session()->put($uploadIdentifier, true);

    }

    // check if the upload has finished (in chunk mode it will send smaller files)
    if ($save->isFinished()) {
      // save the file and return any response you need, current example uses `move` function. If you are
      // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())
//            $video = $save->getFile();
//            return $this->saveFile($save->getFile());

      session()->forget($uploadIdentifier);

      // this needs to be setup as a new job to prevent timeouts on the frontend.
      return $this->saveFile($save->getFile(), $movieId, $movieTrailerId, $showEpisodeId);

    }

    // we are in chunk mode, lets send the current progress
    $handler = $save->handler();

//

    return response()->json([
        "done"   => $handler->getPercentageDone(),
        'status' => true,
        'video'  => 'processing',
//            'videoId' => $this->video->id, // Include the video ID in the response
    ]);
  }

  /**
   * Saves the file
   *
   * @param UploadedFile $file
   *
   * @return JsonResponse
   */
  protected function saveFile(UploadedFile $file, $movieId, $movieTrailerId, $showEpisodeId): JsonResponse {

    // remove the previous video
    if ($showEpisodeId !== null) {
//            Log::info('Video upload Show Episode ID ' . $showEpisodeId);
//      $showEpisode = ShowEpisode::where('id', $showEpisodeId)->get();
      Log::info('Show Episode video uploaded for ' . $showEpisodeId);
      Video::query()->where('show_episodes_id', $showEpisodeId)
          ->update(['show_episodes_id' => null]);
      $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cloud_folder')->first();
    } else if ($movieId !== null) {
//            Log::info('Video upload Movie ID ' . $movieId);
//            $movie = Movie::where('id', $movieId)->get();
      Log::info('Movie video uploaded for ' . $movieId);
      Video::query()->where('movies_id', $movieId)
          ->update(['movies_id' => null]);
      $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cloud_folder')->first();
    } else if ($movieTrailerId !== null) {
//            Log::info('Video upload Movie Trailer ID ' . $movieTrailerId);
//            $movieTrailer = MovieTrailer::where('id', $movieTrailerId)->get();
      Log::info('Movie Trailer video uploaded for ' . $movieTrailerId);
      Video::query()->where('movie_trailers_id', $movieTrailerId)
          ->update(['movie_trailers_id' => null]);
      $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cloud_folder')->first();
    } else {
      // if the upload is from the creator user's video upload page its considered private.
      // TODO: This function will need to be updated and made more efficient to handle
      // various use cases including upload OtherContent, NewsStory videos and BonusContent videos, etc.
      Log::info('Private video uploaded.');
      $cloud_private_folder = DB::table('app_settings')->where('id', 1)->pluck('cloud_private_folder')->first();
    }

    $path = storage_path('app/temp-videos');
    $fileName = $this->createFilename($file);
    $folder = Carbon::now()->format('/Y/m') . '/videos';

//        $mime = str_replace('/', '-', $file->getMimeType());
    $mime = $file->getMimeType();

//  tec21: this was used for testing/development. The model can be deleted if we
//  don't have another use for it.
//        // Temporarily store the local media file
//        $videoFileForJob = VideoUploadJob::create([
//            'file_name' => $fileName,
//            'file_path' => $path,
//            'mime_type' => $file->getMimeType(),
//        ]);

    // move the file to temp-videos
    $contents = $file->move($path, $fileName);

    // create a method to delete any remaining .part files in the chunks folder
    // $fileName+'.part'
    // unlink($file->getFile()->getPathname());

    // Store the video in the database
    $this->video = new Video;
    $this->video->user_id = auth()->user()->id;
    $this->video->upload_status = 'processing';
    $this->video->file_name = $fileName;
    $this->video->extension = $file->getClientOriginalExtension();
    $this->video->size = $contents->getSize();
    $this->video->type = $mime;
    $this->video->folder = $folder;
    $this->video->cloud_folder = $cloud_folder ?? null;
    $this->video->cloud_private_folder = $cloud_private_folder ?? null; // changed video uploads to use cloud_private_folder, the video table is still cloud_folder
    $this->video->show_episodes_id = $showEpisodeId;
    $this->video->movies_id = $movieId;
//        $video->movie_trailers_id = $movieTrailerId;
    $this->video->save();
    sleep(2);

//        Log::info('A new video has been uploaded: '. $this->video->ulid);

    if ($showEpisodeId) {
      try {
        $showEpisode = ShowEpisode::where('id', $showEpisodeId)
            ->update(['video_id' => $this->video->id]);
//                Log::info('Successfully updated a Show Episode with a video ID before upload to spaces.');
      } catch (\Exception $e) {
        Log::channel('custom_error')->info("Update failed: " . $e->getMessage());
        Log::info('Failed to update the Show Episode with the video ID before upload to spaces.');
      }
    }

    if ($movieId) {
      try {
        $movie = Movie::where('id', $movieId)
            ->update(['video_id' => $this->video->id]);
//                Log::info('Successfully updated a Movie with a video ID before upload to spaces.');
      } catch (\Exception $e) {
        Log::channel('custom_error')->info("Update failed: " . $e->getMessage());
        Log::info('Failed to update the Movie with the video ID before upload to spaces.');
      }
    }

    if ($movieTrailerId) {
      try {
        $movieTrailer = MovieTrailer::where('id', $movieTrailerId)
            ->update(['video_id' => $this->video->id]);
//                Log::info('Successfully updated a Movie Trailer with a video ID before upload to spaces.');
      } catch (\Exception $e) {
        Log::channel('custom_error')->info("Update failed: " . $e->getMessage());
        Log::info('Failed to update the Movie Trailer with the video ID before upload to spaces.');
      }
    }


//        error_log('Video saved to database. Next up is the Job.');

    // Dispatch Job
    UploadVideoToSpacesJob::dispatch($this->video, auth()->user()->id)->onQueue('video_processing');
//        Log::info('Video '. $this->video->ulid .' has been dispatched for upload to Spaces.');

//        error_log('The end of the saveFile method.');
    return response()->json([
        'path'      => $path,
        'name'      => $fileName,
        'mime_type' => $mime,
        'video'     => 'processing',
        'videoId'   => $this->video->id, // Include the video ID in the response
    ]);
  }

  /**
   * Create unique filename for uploaded file
   * @param UploadedFile $file
   * @return string
   */
  protected function createFilename(UploadedFile $file): string {
    $extension = $file->getClientOriginalExtension();
    $filename = str_replace("." . $extension, "", $file->getClientOriginalName()); // Filename without extension
    $hashedFilename = md5($filename);

    // Add timestamp hash to name of the file
//        $hashedFilename .= md5(date('r')) . "." . $extension;
    $filename .= '_' . md5(time()) . "." . $extension;

    return $filename;
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param \App\Models\Video $video
   * @return \Illuminate\Http\Response
   */
  public function show(Video $video) {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param \App\Models\Video $video
   * @return \Illuminate\Http\Response
   */
  public function edit(Video $video) {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param \App\Models\Video $video
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Video $video) {
    //
  }


}
