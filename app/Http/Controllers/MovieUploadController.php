<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Storage;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Str;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class MovieUploadController extends BaseController
{

// tec21: save the $request to the Movie table
// then process the file upload. If the upload
// fails. The movie page will be there and the
// user can re-attempt the upload without
// needing to re-create the movie model.
//
//
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HttpRequest $request)
    {
        $request->validate([
            'name' => 'unique:movies|required|string|max:255',
            'description' => 'required|string',
            'file_url' => 'string|max:255',
        ]);

// create the Movie model here.
// Change this to the ->save() method.
// After we'll update the model with the uploaded video.
        // create image model
        // NEED TO PROTECT THESE
        Movie::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => \Str::slug($request->name),
            'user_id' => Auth::user()->id,
        ]);

// If a video file is present, upload the video.
        if ($request->hasFile('video')) {
            $request->validate([
                'video' => 'file|max:10000000',
            ]);

            $video = $request->file('video');
            $extension = $request->file('video')->extension();
            $size = $request->file('video')->getSize();
            $mimeTypes = $request->file('video')->getMimeType();

            // this needs to be moved to the createFilename() function below.
            //
            // $fileName = $video->hashName();



            // trigger the upload() function here.

                //

            // update the model with the file information
//                'file_path' => $fileName,
//                'file_url' => $request->file_url,
//                'extension' => $extension,
//                'size' => $size,
        }

        // Return the user to the new movie page.
        return redirect()->route('movies.show', [$slug])->with('message', 'Movie Added Successfully');
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
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

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
            return $this->saveFile($save->getFile());
        }

        // we are in chunk mode, lets send the current progress
        /** @var AbstractHandler $handler */
        $handler = $save->handler();

        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);
    }


// tec21: Save the uploaded file locally
// this will need to be onto an attached
// volume. All of our local public files will
// need to be on this volume.
//
//
    /**
     * Saves the file
     *
     * @param UploadedFile $file
     *
     * @return JsonResponse
     */
    protected function saveFile(UploadedFile $file)
    {
        $fileName = $this->createFilename($file);
        // Group files by mime type
        $mime = str_replace('/', '-', $file->getMimeType());
        // Group files by the date (week
        $dateFolder = date("Y-m-W");

        // Build the file path
        $filePath = "upload/{$mime}/{$dateFolder}/";
        $finalPath = storage_path("app/".$filePath);

        // move the file name
        $file->move($finalPath, $fileName);

        return response()->json([
            'path' => $filePath,
            'name' => $fileName,
            'mime_type' => $mime
        ]);
    }

// tec21: send the file to the queue to convert and
// encrypt using FFMPEG. Return the status of the queue
// to the movie.show page, the user's dashboard who
// uploaded the movie, and the admin dashboard which is
// not yet created.
//
//
    /**
     * Convert and encrypt the file using FFMPEG
     *
     * @param UploadedFile $file
     *
     * @return
     */

    // Code goes here.



    /**
     * Saves the file to Digital Ocean Spaces server
     *
     * @param UploadedFile $file
     *
     * @return JsonResponse
     */
    protected function saveFileToDOSpaces($file)
    {
        $fileName = $this->createFilename($file);

        $disk = \Illuminate\Support\Facades\Storage::disk('do_spaces');
        $disk->putFileAs('uploads/movies', $file, $fileName, 'public');

        $mime = str_replace('/', '-', $file->getMimeType());

        // We need to delete the file when uploaded
        unlink($file->getPathname());

        return response()->json([
            'path' => $disk->url($fileName),
            'name' => $fileName,
            'mime_type' =>$mime
        ]);
    }


// tec21: change this createFilename function to
// hash the name according to the naming convention
// we've set out. I don't have the naming slip handy.
// For the meantime, just make sure the hashed name
// is saved to the database.
//
//
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
}
