<?php

namespace App\Jobs;

use App\Models\Video;
use App\Models\VideoUploadJob;

use Carbon\Carbon;
use Exception;
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

    protected Video $video;
    protected VideoUploadJob $file;
    public int $timeout = 3600;

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
     * @throws Exception
     */
    public function handle()
    {
//        error_log('Starting job.');

        // tec21: this works, just disabled for troubleshooting.
//        error_log('Now attempting to upload to the cloud.');
        // upload the file to the cloud
//        $url = Storage::disk('spaces')->putFile($this->video->cloud_folder.$this->video->folder, 'storage/app/temp-videos/'.$this->file->file_name);
//        $file = Storage::disk('spaces')->putFileAs($this->video->cloud_folder.$this->video->folder, 'storage/app/temp-videos/'.$this->file->file_name, 'file.mp4');
//        error_log('upload to the cloud done.');

        // update the file_name on the model
        DB::table('videos')->where('id', $this->video->id)->update(['full_url' => '$url']);
//        $video = Video::query('id', $this->video->id);
//        $video->file_url = $path;
//        $video-save();
//        error_log('Updated the Video model');

        // delete the temporary file
        Storage::disk('public')->delete(storage_path('app/temp-videos/').$this->file->file_name);
//        unlink($this->file->getFile()->getPathname());

//        error_log('Ending job for ' . $this->file->original_name);
    }

    /**
     * @throws Exception
     */
    protected function getVideo(): Video
    {
        if($this->video instanceof Video){
            return $this->video;
        } else {
            throw new Exception("Video not set on UploadVideoToSpacesJob");
        }
    }



}
