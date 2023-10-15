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
use function MongoDB\BSON\toJSON;


class VideoUploadController extends Controller
{


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
            return round($bytes / (pow(1024, $exp)), $precision).$unit[$exp];
        }

        return Inertia::render('VideoUpload', [
//            'videos' => fn () => Video::query()->where('user_id', auth()->user()->id)
            'myTotalStorageUsed' => formatBytes(Video::where('user_id', auth()->user()->id)
                ->where('storage_location', '=', 'spaces')
                ->sum('size')),
            'notTvTotalStorageUsed' => formatBytes(Video::where('storage_location', '=', 'spaces')
                ->sum('size')),
            'videos' => Video::with('showEpisode', 'movie', 'movieTrailer', 'newsPost' )->where('user_id', auth()->user()->id)
                ->where('storage_location', '=', 'spaces')
                ->latest()
                ->paginate(10, ['*'], 'videos')
                ->through(fn($video) => [
                    'id' => $video->id,
                    'file_name' => $video->file_name ?? '',
                    'extension' => $video->extension,
                    'folder' => $video->folder,
                    'storage_location' => $video->storage_location,
                    'cdn_endpoint' => $video->appSetting->cdn_endpoint,
                    'cloud_folder' => $video->cloud_folder,
                    'upload_status' => $video->upload_status,
                    'category' => $video->category,
                    'type' => $video->type,
                    'size' => formatBytes($video->size),
                    'created_at' => $video->created_at,
                    'showEpisode' => $video->showEpisode,
                    'movie' => $video->movie,
                    'movieTrailer' => $video->movieTrailer,
                    'newsPost' => $video->newsPost,
                    'can' => [
                        'viewAny' => auth()->user()->can('viewAny', $video),
                        'view' => auth()->user()->can('view', $video),
                        'create' => auth()->user()->can('create', $video),
                        'edit' => auth()->user()->can('update', $video),
                        'delete' => auth()->user()->can('delete', $video),
                    ]
                ]),

            // this is a temporary query... use chunking or lazy loading
            // to reduce load on the server ... replace it with a more
            // efficient query.
            //
            'allVideos' => Video::with('showEpisode', 'movie', 'movieTrailer', 'newsPost')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('file_name', 'like', "%{$search}%");
                })
                ->where('storage_location', '=', 'spaces')
                ->latest()
                ->paginate(10, ['*'], 'allVideos')
                ->withQueryString()
                ->through(fn($video) => [
                    'id' => $video->id,
                    'user_id' => $video->user->name,
                    'file_name' => $video->file_name,
                    'extension' => $video->extension,
                    'storage_location' => $video->storage_location,
                    'folder' => $video->folder,
                    'cdn_endpoint' => $video->appSetting->cdn_endpoint,
                    'cloud_folder' => $video->cloud_folder,
                    'upload_status' => $video->upload_status,
                    'category' => $video->category,
                    'type' => $video->type,
                    'size' => formatBytes($video->size),
                    'created_at' => $video->created_at,
                    'showEpisode' => $video->showEpisode,
                    'movie' => $video->movie,
                    'movieTrailer' => $video->movieTrailer,
                    'newsPost' => $video->newsPost,
                    'can' => [
                        'viewAny' => auth()->user()->can('viewAny', $video),
                        'view' => auth()->user()->can('view', $video),
                        'create' => auth()->user()->can('create', $video),
                        'edit' => auth()->user()->can('update', $video),
                        'delete' => auth()->user()->can('delete', $video),
                    ]
                ]),
            'filters' => \Illuminate\Support\Facades\Request::only(['search']),
            'can' => [
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

        // validate the file
//        ???


        // create the file receiver
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
//        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));


        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        // receive the file
        $save = $receiver->receive();

        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) {
            // save the file and return any response you need, current example uses `move` function. If you are
            // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())
//            $video = $save->getFile();
//            return $this->saveFile($save->getFile());



            $movieId = $request->movieId;
            $movieTrailerId = $request->movieTrailerId;
            $showEpisodeId = $request->showEpisodeId;


            // this needs to be setup as a new job to prevent timeouts on the frontend.
            return $this->saveFile($save->getFile(), $movieId, $movieTrailerId, $showEpisodeId);

        }

        // we are in chunk mode, lets send the current progress
        $handler = $save->handler();
//
        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true,
            'video' => 'processing',
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
//            $showEpisode = ShowEpisode::where('id', $showEpisodeId)->get();
            Video::query()->where('show_episodes_id', $showEpisodeId)
                ->update(['show_episodes_id' => null]);
        }
        else if ($movieId !== null) {
//            $movie = Movie::where('id', $movieId)->get();
            Video::query()->where('movies_id', $movieId)
                ->update(['movies_id' => null]);
        }
//        else if ($movieTrailerId !== null) {
////            $movieTrailer = MovieTrailer::where('id', $movieTrailerId)->get();
//            Video::query()->where('movie_trailers_id', $movieTrailerId)
//                ->update(['movie_trailers_id' => null]);
//        }

        $path = storage_path('app/temp-videos');
        $fileName = $this->createFilename($file);
        $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cloud_folder')->first();
        $folder = Carbon::now()->format('/Y/m').'/videos';

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
        $video = new Video;
        $video->user_id = auth()->user()->id;
        $video->upload_status = 'processing';
        $video->file_name = $fileName;
        $video->extension = $file->getClientOriginalExtension();
        $video->size = $contents->getSize();
        $video->type = $mime;
        $video->folder = $folder;
        $video->cloud_folder = $cloud_folder;
        $video->show_episodes_id = $showEpisodeId;
        $video->movies_id = $movieId;
//        $video->movie_trailers_id = $movieTrailerId;
        $video->save();
        sleep(1);

        $showEpisode = ShowEpisode::where('id', $showEpisodeId)
            ->update(['video_id' => $video->id]);

        $movie = Movie::where('id', $movieId)
            ->update(['video_id' => $video->id]);

//        $movieTrailer = MovieTrailer::where('id', $movieTrailerId)
//            ->update(['video_id' => $video->id]);

//        error_log('Video saved to database. Next up is the Job.');

        // Dispatch Job
        UploadVideoToSpacesJob::dispatch($video)->onQueue('video_processing');

//        error_log('The end of the saveFile method.');
        return response()->json([
            'path'      => $path,
            'name'      => $fileName,
            'mime_type' => $mime
        ]);
    }

    /**
     * Create unique filename for uploaded file
     * @param UploadedFile $file
     * @return string
     */
    protected function createFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace(".".$extension, "", $file->getClientOriginalName()); // Filename without extension
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(HttpRequest $request)
    {
        $video = Video::query()->where('id', $request->videoId)->first();
//        Storage::disk('spaces')->delete('path/file.jpg');
        Storage::disk('spaces')->delete($video->cloud_folder.$video->folder.'/'.$video->file_name);



//        $user->deleteProfilePhoto();
//        $user->tokens->each->delete();
        $video->delete();

        // redirect
        return redirect('/videoupload')->with('message', $video->file_name.' Deleted Successfully');
    }
}
