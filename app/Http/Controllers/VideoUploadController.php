<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\User;

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

        function getCreator($user){
            return $name = User::query()
                ->where('id', $user)
                ->pluck('name')
                ->first();
        }

        function formatBytes($bytes, $precision = 2) {
            $unit = ["B", "KB", "MB", "GB"];
            $exp = floor(log($bytes, 1024)) | 0;
            return round($bytes / (pow(1024, $exp)), $precision).$unit[$exp];
        }

        return Inertia::render('VideoUpload', [
            'videos' => fn () => Video::query()->where('user_id', auth()->user()->id)
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
                    'user_id' => getCreator($video->user_id),
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
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws UploadMissingFileException
     * @throws UploadFailedException
     */
    public function upload(HttpRequest $request) {
        // create the file receiver
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

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

            // trigger an event here... VideoUploaded.

            // the event will trigger a listener to run the rest of this code in a job/queue.
            return $this->saveFileTos3($save->getFile());
        }

        // we are in chunk mode, lets send the current progress
        /** @var AbstractHandler $handler */
        $handler = $save->handler();
//
        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);

    }

    /**
     * Saves the file to S3 server
     *
     * @param UploadedFile $file
     *
     * @return JsonResponse
     */
    protected function saveFileToS3($file)
    {
        $fileName = $this->createFilename($file);

        $cloud_folder = DB::table('app_settings')->where('id', 1)->pluck('cloud_folder')->first();
        $folder = Carbon::now()->format('/Y/m').'/images';

        $disk = Storage::disk('spaces')->putFileAs('oogabooga', $file, $fileName);

        $mime = str_replace('/', '-', $file->getMimeType());

        // We need to delete the file when uploaded to s3
        unlink($file->getPathname());

        // Store the video in the database
        $video = new Video;
        $video->user_id = auth()->user()->id;
        $video->file_name = $fileName;
        $video->extension = $file->getClientOriginalExtension();
        $video->size = $file->getSize();
        $video->type = $file->getMimeType();
        $video->full_url = $fileName;
        $video->save();
        sleep(1);

        dd($video);

        return response()->json([
            'path' => $disk->url($fileName),
            'name' => $fileName,
            'mime_type' =>$mime
        ]);
    }

//    /**
//     * Saves the file
//     *
//     * @param UploadedFile $file
//     *
//     * @return \Illuminate\Http\JsonResponse
//     */
//    protected function saveFile(UploadedFile $file)
//    {
//        $fileName = $this->createFilename($file);
//        // Group files by mime type
//        $mime = str_replace('/', '-', $file->getMimeType());
//        // Group files by the date (week
//        $dateFolder = date("Y-m-W");
//
//        // Build the file path
//        $filePath = "upload/{$mime}/{$dateFolder}/";
//        $finalPath = storage_path("app/".$filePath);
//
//        // move the file name
//        $file->move($finalPath, $fileName);
//
//        // Store the video in the database
//        $video = new Video;
//        $video->user_id = auth()->user()->id;
//        $video->file_name = $fileName;
//        $video->extension = $file->getClientOriginalExtension();
//        $video->size = $file->getSize();
//        $video->type = $file->getMimeType();
//        $video->full_url = $finalPath;
//        $video->save();
//        sleep(1);
//
//        return response()->json([
//            'path' => $filePath,
//            'name' => $fileName,
//            'mime_type' => $mime
//        ]);



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
    protected function createFilename(UploadedFile $file)
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
