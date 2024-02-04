<?php

namespace App\Jobs;

use App\Events\NewVideoUploaded;
use App\Events\VideoProcessed;
use App\Models\Video;
use App\Models\ShowEpisode;
use App\Models\MovieTrailer;
use App\Models\Movie;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadVideoToSpacesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Video $video;
    protected $userId;
    public int $timeout = 3600;
    public int $tries = -1;
    public int $backoff = 2;

    /**
     * UploadVideoToSpacesJob constructor.
     * @param Video $video
     */
    public function __construct(Video $video, $userId)
    {
        $this->video = $video;
        $this->userId = $userId;
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
        Log::info('Video uploaded to Spaces. ID: '.$this->video->id.'. ULID: '. $this->video->ulid .'. Filename: '.$this->video->file_name);
        // delete the temporary file
        // not working yet.
//        Storage::delete(storage_path('app/temp-videos/').$this->video->file_name);
//        unlink($this->file->getFile()->getPathname());
        unlink(storage_path('app/temp-videos/').$this->video->file_name);

        // update the file_name on the model
        $this->video->upload_status = 'Uploaded to Spaces via Job';
        $this->video->storage_location = 'spaces';
        $this->video->save();

        // update the showEpisode if applicable
        // Check if the Video has a show_episodes_id
        if ($this->video->show_episodes_id) {
            // Update the ShowEpisode with the video_id
            $showEpisode = ShowEpisode::find($this->video->show_episodes_id);
            $this->userId = $showEpisode->user_id;
            if ($showEpisode) {
//              Log::info('Before update: ' . json_encode($showEpisode->toArray()));
              try {
              $showEpisode->video_id = $this->video->id;
                $showEpisode->save();
              } catch (\Exception $e) {
                Log::error('Error updating ShowEpisode: ' . $e->getMessage());
              }
//              Log::info('After update: ' . json_encode($showEpisode->fresh()->toArray()));
                // Retrieve the ULID of the ShowEpisode
                $ulid = $showEpisode->ulid;
                // if Show Episode does not have a ulid use the id.
                if(!$ulid) {
                    $ulid = $this->video->show_episodes_id;
                }
//                Log::info('Successfully updated the Show Episode '. $ulid .' with the video ID '. $this->video->id);
              event(new NewVideoUploaded($this->video, $this->video->user_id));
            } else {
                // Handle the case where ShowEpisode is not found
                // e.g., log an error or throw an exception
                Log::info('Problem updating the Show Episode with the video ID.');
            }
        }

        // update the movieTrailer if applicable
        // Check if the Video has a movie_trailers_id
        if ($this->video->movie_trailers_id) {
            // Update the MovieTrailer with the video_id
            $movieTrailer = MovieTrailer::find($this->video->movie_trailers_id);
          $this->userId = $movieTrailer->user_id;
            if ($movieTrailer) {
                $movieTrailer->video_id = $this->video->id;
                $movieTrailer->save();
                // Retrieve the ULID of the MovieTrailer
                $ulid = $movieTrailer->ulid;
                // if Movie Trailer does not have a ulid use the id.
                if(!$ulid) {
                    $ulid = $this->video->movie_trailers_id;
                }
                Log::info('Successfully updated the Movie Trailer '. $ulid .' with the video ID '. $this->video->id);
              event(new NewVideoUploaded($this->video, auth()->user()->id));
            } else {
                // Handle the case where MovieTrailer is not found
                // e.g., log an error or throw an exception
                Log::info('Problem updating the Movie Trailer with the video ID.');
            }
        }

        // update the movie if applicable
        // Check if the Video has a movies_id
        if ($this->video->movies_id) {
            // Update the Movie with the video_id
            $movie = Movie::find($this->video->movies_id);
          $this->userId = $movie->user_id;
            if ($movie) {
                $movie->video_id = $this->video->id;
                $movie->save();
                // Retrieve the ULID of the Movie
                $ulid = $movie->ulid;
                // if Movie does not have a ulid use the id.
                if(!$ulid) {
                    $ulid = $this->video->movies_id;
                }
                Log::info('Successfully updated the Movie '. $ulid .' with the video ID '. $this->video->id);
              event(new NewVideoUploaded($this->video, $this->userId));
            } else {
                // Handle the case where Movie is not found
                // e.g., log an error or throw an exception
                Log::info('Problem updating the Movie with the video ID.');
            }
        }
      event(new VideoProcessed($this->video->user_id));
//      event(new ProcessVideoInfoCompleted($this->videoId, $messageArray));
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
