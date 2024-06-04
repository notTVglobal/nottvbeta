<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Traits\HandlesDigitalOceanUrls;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

//use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

//use Pion\Laravel\ChunkUpload\Storage;

use Inertia\Response;
use Inertia\ResponseFactory;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload;
use Illuminate\Http\UploadedFile;

//
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Inertia\Inertia;


class VideoController extends Controller {

  use HandlesDigitalOceanUrls;

  public function __construct() {

    $this->middleware('auth')->except(['index', 'show']);
  }

  public function mistApi(Request $request) {

    $challenge = $request->challenge;
    $password = $request->password;


//        dd($challenge);

    // do we add the challenge and the password together?

//        $newHashedPassword = [$challenge + $password];

    // or do we concatenate them?

//        $newHashedPassword = ($password . '+' . $challenge);
    $newHashedPassword = ($password . $challenge);

    $x = hash('md5', $newHashedPassword);

//        dd($x);
    return redirect()->route('video')->with('message', $x);

  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    //
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
   * @param HttpRequest $request
   * @return JsonResponse
   */
  public function store(Request $request): JsonResponse {

    $file = $request->file('video');

    $filename = $request->file('video')->getFilename();
        $path = Storage::disk('local-video-chunks')->put($filename, $file);
//    Storage::disk('local-video-chunks')->put('example.txt', $file);

    File::append($path, $file->get());

    if ($request->has('is_last') && $request->boolean('is_last')) {
      $name = basename($path, '.part');

      File::move($path, "/storage/app/public/videos/{$name}");
    }

    return response()->json(['uploaded' => true]);
  }


  /**
   * Handles the file upload
   *
   * @param FileReceiver $receiver
   *
   * @return JsonResponse
   *
   * @throws UploadMissingFileException
   *
   */
  public function uploadFile(FileReceiver $receiver): JsonResponse {
//    dd($receiver);
    // check if the upload is success, throw exception or return response you need
    if ($receiver->isUploaded() === false) {
      throw new UploadMissingFileException();
    }
    // receive the file
    $save = $receiver->receive();


    // check if the upload has finished (in chunk mode it will send smaller files)
    if ($save->isFinished()) {
      // save the file and return any response you need
      return $this->saveFile($save->getFile());
    }

    // we are in chunk mode, lets send the current progress
    $handler = $save->handler();

    return response()->json([
        "done" => $handler->getPercentageDone()
    ]);
  }

  /**
   * @throws UploadMissingFileException
   * @throws UploadFailedException
   */
  public function newUploader(Request $request): JsonResponse {
    /**
     * Handles the file upload
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws UploadMissingFileException
     * //         * @throws UploadFailedException
     */

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
    $handler = $save->handler();

    return response()->json([
        "done" => $handler->getPercentageDone(),
    ]);
  }

  /**
   * Saves the file
   *
   * @param UploadedFile $file
   *
   * @return JsonResponse
   */
  protected function saveFile(UploadedFile $file) {
    $fileName = $this->createFilename($file);
    // Group files by mime type
    $mime = str_replace('/', '-', $file->getMimeType());
    // Group files by the date (week
    $dateFolder = date("Y-m-W");

    // Build the file path
    $filePath = "upload/{$mime}/{$dateFolder}/";
    $finalPath = storage_path("app/" . $filePath);

    // move the file name
    $file->move($finalPath, $fileName);

    return response()->json([
        'path'      => $filePath,
        'name'      => $fileName,
        'mime_type' => $mime
    ]);
  }

  /**
   * Create unique filename for uploaded file
   * @param UploadedFile $file
   * @return string
   */
  protected function createFilename(UploadedFile $file) {
    $extension = $file->getClientOriginalExtension();
    $filename = str_replace("." . $extension, "", $file->getClientOriginalName()); // Filename without extension

    // Add timestamp hash to name of the file
    $filename .= "_" . md5(time()) . "." . $extension;

    return $filename;
  }


  /**
   * Display the specified resource.
   *
   * @param Video $video
   * @return Response|ResponseFactory
   */
  public function show(Video $video): Response|ResponseFactory {

    // Eager load the appSetting relationship
    $video->load('appSetting', 'user.creator');

    $videoSource = $this->getPreSignedUrl($video);

    return inertia('VideoPlayerPage', [
        'video'       => [
            'id'       => $video->id,
            'filename' => $video->filename,
            'creator' => [
                'slug' => $video->user->creator->slug,
            ],
            'user'     => [
                'name'               => $video->user->name,
                'profile_photo_path' => $video->user->profile_photo_path,
                'profile_photo_url'  => $video->user->profile_photo_url,
            ],
        ],
        'ulid'        => $video->ulid,
        'videoSource' => $videoSource,
        'videoType'   => $video->type,
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param Video $video
   * @return \Illuminate\Http\Response
   */
  public function edit(Video $video) {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param HttpRequest $request
   * @param Video $video
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Video $video) {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param HttpRequest $request
   * @return Application|RedirectResponse|Redirector
   * @throws AuthorizationException
   */
  public function destroy(HttpRequest $request): \Illuminate\Routing\Redirector|Application|\Illuminate\Http\RedirectResponse {
    $video = Video::findOrFail($request->videoId);

    try {
      // Authorize the user
      $this->authorize('delete', $video);

      // Delete the video file from storage
      Storage::disk('spaces')->delete($video->cloud_folder . '/' . $video->folder . '/' . $video->file_name);

      // Delete the video record from the database
      $video->delete();

      // Redirect with a success message
      return redirect('/videoupload')->with('message', $video->file_name . ' Deleted Successfully');
    } catch (AuthorizationException $e) {
      // Redirect with an error message if authorization fails
      return redirect('/videoupload')->with('error', $e->getMessage());
    }
  }


}
