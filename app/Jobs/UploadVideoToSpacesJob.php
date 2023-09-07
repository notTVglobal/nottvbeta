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
    public int $timeout = 3600;
    public int $tries = -1;
    public int $backoff = 2;

    /**
     * UploadVideoToSpacesJob constructor.
     * @param Video $video
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array<int, string>
     */
    public function tags(): array
    {
        return ['render', 'video:'.$this->video->id];
    }

    /**
     * @parm UploadedFile $file
     * @throws Exception
     */
    public function handle()
    {
//        error_log('Starting job.');

//        error_log('Now attempting to upload to the cloud.');

        // get the file
//        $file = Storage::get($this->file->file_path.$this->file->filename);

        // upload the file to the cloud
        Storage::disk('spaces')->putFileAs($this->video->cloud_folder.$this->video->folder, storage_path('app/temp-videos/').$this->video->file_name, $this->video->file_name);
//        error_log('upload to the cloud done.');

        // delete the temporary file
        // not working yet.
//        Storage::delete(storage_path('app/temp-videos/').$this->video->file_name);
//        unlink($this->file->getFile()->getPathname());
        unlink(storage_path('app/temp-videos/').$this->video->file_name);

        // update the file_name on the model
        $this->video->upload_status = 'Uploaded to Spaces via Job';
        $this->video->storage_location = 'spaces';
        $this->video->save();

//        error_log('Updated the Video model');



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

    public function retryUntil()
    {
        return now()->addMinute();
    }



}
