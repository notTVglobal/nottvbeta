<?php

namespace App\Jobs;

use App\Models\Video; // Import the Video model
use FFMpeg\FFMpeg;
//use FFMpeg\FFProbe;
use ProtoneMedia\LaravelFFMpeg\FFMpeg\FFProbe;
use FFMpeg\FFProbe\DataMapping\Format;
use App\Events\ProcessVideoInfoCompleted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessVideoInfo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $videoId;
    protected $jobName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($videoId, $jobName)
    {
        $this->videoId = $videoId;
        $this->jobName = $jobName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $cachedJobName = null;

        if ($this->jobName) {
            // Retrieve the job name from the cache
            $cachedJobName = Cache::get($this->jobName);
        }

        try {
            // Perform job logic...
            $video = Video::find($this->videoId);

            // Initialize FFMpeg
            $ffmpeg = FFMpeg::create();

            // Get the video file path (local storage)
//        $videoFilePath = Storage::disk('local')->path($this->videoUrl);

            // Open the video file
//        $video = $ffmpeg->open($videoFilePath);
            $ffmpegVideo = $ffmpeg->open($video->video_url);

            // Get video information
            $format = $ffmpegVideo->getFormat();
            $audioCodec = $format->get('audio.codec_name');
            $videoCodec = $format->get('video.codec_name');
            $audioChannels = $format->get('audio.channels');
            $duration = $format->get('duration');

            // Get the video extension, video type (MIME type), and video size
            $videoExtension = pathinfo($video->video_url, PATHINFO_EXTENSION);

            $ffProbe = FFProbe::create();
            $videoInfo = $ffProbe->format($video->video_url);
            $videoType = $videoInfo->get('format_name');
            $videoSize = $videoInfo->get('size');
            // Update the database with the extracted information

            // Check if the video model exists
            if ($video) {
                // Update the attributes of the Video model
                $video->update([
                    'extension' => $videoExtension,
                    'type' => $videoType,
                    'size' => $videoSize,
                    'audio_codec' => $audioCodec,
                    'video_codec' => $videoCodec,
                    'audio_channels' => $audioChannels,
                    'length' => gmdate("H:i:s", $duration),
                ]);

                // Optionally, you can return a response or perform other actions here
            } else {
                // Handle the case where the Video model with the specified ID was not found
            }

            // Log success
            Log::channel('custom_error')->info('Video info updated for video ID ' . $this->videoId);

            // Dispatch a custom event with success data
            $messageArray = [
                'type' => 'success', // or 'error' for different types
                'content' => 'Video info updated for video ID ' . $this->videoId,
            ];
            event(new ProcessVideoInfoCompleted($this->videoId, $messageArray));
        } catch (\Exception $e) {
            // Handle errors...
            Log::channel('custom_error')->info('Error updating video info for video ID ' . $this->videoId . '. Error: ' . $e->getMessage());


            // Dispatch a custom event with error data
            $messageArray = [
                'type' => 'error', // or 'error' for different types
                'content' => 'Error updating video info for video ID ' . $this->videoId . '. Error: ' . $e->getMessage(),
            ];
            event(new ProcessVideoInfoCompleted($this->videoId, $messageArray));
        }

        if ($this->jobName) {
            // Check if the retrieved job name matches the one passed to JobB
            if ($cachedJobName !== null) {
                // Clear the coordination flag for JobB
                Cache::forget($this->jobName);
            }
        }



    }
}
