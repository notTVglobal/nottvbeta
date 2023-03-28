<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\User;
use App\Jobs\UploadVideoToSpacesJob;
use App\Models\VideoUploadJob;

use Carbon\Carbon;
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
            'videos' => Video::with('user')->where('user_id', auth()->user()->id)
                ->latest()
                ->paginate(10, ['*'], 'videos')
                ->through(fn($video) => [
                    'id' => $video->id,
                    'file_name' => $video->file_name,
                    'category' => $video->category,
                    'type' => $video->type,
                    'size' => formatBytes($video->size),
                    'created_at' => $video->created_at,
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
            'allVideos' => Video::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('file_name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(10, ['*'], 'allVideos')
                ->withQueryString()
                ->through(fn($video) => [
                    'id' => $video->id,
                    'user_id' => $video->user->name,
                    'file_name' => $video->file_name,
                    'category' => $video->category,
                    'type' => $video->type,
                    'size' => formatBytes($video->size),
                    'created_at' => $video->created_at,
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
            return $this->saveFile($save->getFile());

        }

        // we are in chunk mode, lets send the current progress
        $handler = $save->handler();
//
        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true,
            'video' => 'Video Placeholder goes here... or we do a partial reload of the video and the database shows a processing video placeholder until the video is done the job.'
        ]);
    }


    /**
     * Saves the file
     *
     * @param UploadedFile $file
     *
     * @return JsonResponse
     */
    protected function saveFile(UploadedFile $file): JsonResponse {

        $path = storage_path('app/temp-videos');
        $fileName = $this->createFilename($file);
        $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cloud_folder')->first();
        $folder = Carbon::now()->format('/Y/m').'/videos';

        $mime = str_replace('/', '-', $file->getMimeType());

        // Temporarily store the local media file
        $videoFileForJob = VideoUploadJob::create([
            'file_name' => $fileName,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
        ]);

        // move the file to temp-videos
        $contents = $file->move($path, $fileName);

        // Store the video in the database
        $video = new Video;
        $video->user_id = auth()->user()->id;
        $video->file_name = $fileName;
        $video->extension = $file->getClientOriginalExtension();
        $video->size = $contents->getSize();
        $video->type = $mime;
        $video->folder = $folder;
        $video->cloud_folder = $cloud_folder;
        $video->save();
        sleep(1);

        error_log('Video saved to database. Next up is the Job.');

        // Dispatch Job
        UploadVideoToSpacesJob::dispatch($videoFileForJob, $video);

        error_log('The end of the saveFile method.');
        return response()->json([
            'path'      => $path,
            'name'      => $fileName,
            'mime_type' => $mime
        ]);
    }



//        $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cloud_folder')->first();
//        $folder = Carbon::now()->format('/Y/m').'/videos';
//        Storage::disk('spaces')->putFile($cloud_folder.$folder, $file);
//
//        $fileName = $this->createFilename($file);
//        // Group files by mime type
//        $mime = str_replace('/', '-', $file->getMimeType());
//
//        // Store the video in the database
//        $video = new Video;
//        $video->user_id = auth()->user()->id;
//        $video->file_name = $fileName;
//        $video->extension = $file->getClientOriginalExtension();
//        $video->size = $file->getSize();
//        $video->type = $file->getMimeType();
////            $video->full_url = $finalPath;
//        $video->save();
//        sleep(1);
//
//
//
//        return $video;






        // Group files by the date (week
//        $dateFolder = date("Y-m-W");

        // Build the file path
//        $filePath = "upload/{$mime}/{$dateFolder}/";
//        $filePath = "public/videos/";
//        $finalPath = storage_path("app/".$filePath);

        // Store the video in the database
//        $video = new Video;
//        $video->user_id = auth()->user()->id;
//        $video->file_name = $fileName;
//        $video->extension = $file->getClientOriginalExtension();
//        $video->size = $file->getSize();
//        $video->type = $file->getMimeType();
//        $video->full_url = $finalPath;
//        $video->save();
//        sleep(1);

//        $video = Video::create([
//            'user_id' => auth()->user()->id,
//            'file_name' => $fileName,
//            'extension' => $file->getClientOriginalExtension(),
//            'size' => $file->getSize(),
//            'type' => $file->getMimeType(),
//            'full_url' => $finalPath,
//        ]);

            // move the file name
//        $file->move($finalPath, $fileName);
//
//return $video;

//        return Inertia::render('VideoUpload', [
//            'videos' => Videos::get()
//        ]);

//        return redirect()->route('videoupload')->with('message', 'Show Created Successfully');
//        return $video;
//        return response()->json([
//            'video' => [
//                'file_name' => $video->file_name
//            ],
//            'path' => $filePath,
//            'name' => $fileName,
//            'mime_type' => $mime
//        ]);
//    }











    /**
     * Create unique filename for uploaded file
     * @param UploadedFile $file
     * @return string
     */
    protected function createFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace(".".$extension, "", $file->getClientOriginalName()); // Filename without extension

        // Add timestamp hash to name of the file
        $filename .= "_" . md5(time()) . "." . $extension;

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

//        $user->deleteProfilePhoto();
//        $user->tokens->each->delete();
        $video->delete();

        // redirect
        return redirect('/videoupload')->with('message', $video->file_name.' Deleted Successfully');
    }
}
