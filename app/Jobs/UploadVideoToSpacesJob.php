<?php

namespace App\Jobs;

use App\Models\Video;
use App\Models\VideoUploadJob;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UploadVideoToSpacesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $video;
    protected $file;

    /**
     * UploadVideoToSpacesJob constructor.
     * @param VideoUploadJob $file
     * @param Video $video
     */
    public function __construct(VideoUploadJob $file, Video $video)
    {
        $this->video = $video;
        $this->file = $file;
        error_log('constructor for job done.');
    }

    /**
     * @parm UploadedFile $file
     * @throws \Exception
     */
    public function handle()
    {
        error_log('Starting job.');

//        Storage::disk('spaces')->putFile('oogabooga2', 'app/temp-videos', $this->file->file_name);
        Storage::disk('spaces')->putFile('oogabooga2', new File(storage_path('app/temp-videos/'.$this->file->file_name)));
//        $path = Storage::putFileAs('oogabooga2', new File('/path/to/photo'), 'photo.jpg');

//        $file = new UploadedFile(storage_path("app/temp-videos/{$this->file->file_path}"),
//            $this->file->original_name,
//            $this->file->mime_type,
//            null,
//            true
//        );


//        Storage::disk('spaces')->putFileAs($this->video->cloud_folder.$this->video->folder, $this->path, $this->file->original_name);
        Storage::disk('spaces')->putFileAs('oogabooga2', $this->file->path , $this->file->file_name);
//        Storage::disk('spaces')->put(
//            "$this->video->cloud_folder.$this->video->folder/$this->file->original_name",
//            file_get_contents($this->file)
//        );

        error_log('Ending job for ' . $this->file->original_name);

        error_log('Updated the Video model');
        error_log('Now attempting to upload to the cloud.');

        error_log('upload to the cloud done.');

    }

}
