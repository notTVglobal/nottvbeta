<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use App\Models\{Recording, MistStreamWildcard, Show, ShowEpisode, Team};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateRecordingModelAndNotify  implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected Recording $recording;

  /**
   * Create a new job instance.
   *
   * @param Recording $recording The recording instance.
   */
  public function __construct(Recording $recording)
  {
    $this->recording = $recording;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {

//    Log::debug('Handling the recording update process.');

    $streamName = $this->recording->stream_name;

//    Log::debug('Stream name obtained', ['streamName' => $streamName]);

    $wildcard = MistStreamWildcard::where('name', $streamName)->first();

    if (!$wildcard) {
      Log::info('No wildcard found for the given stream name', ['streamName' => $streamName]);
      return;
    }

    $model = null;
    $modelType = null;

    if (str_starts_with($streamName, 'show+')) {
      $model = Show::where('mist_stream_wildcard_id', $wildcard->id)->first();
      $modelType = Show::class;
//      Log::debug('Show model found based on stream name', ['modelType' => $modelType, 'modelId' => $model ? $model->id : null]);
    } elseif (str_starts_with($streamName, 'episode+')) {
      $model = ShowEpisode::where('mist_stream_wildcard_id', $wildcard->id)->first();
      $modelType = ShowEpisode::class;
//      Log::debug('ShowEpisode model found based on stream name', ['modelType' => $modelType, 'modelId' => $model ? $model->id : null]);
    }
    if ($model) {
      $this->recording->update([
          'model_type' => $modelType,
          'model_id' => $model->id,
      ]);
//      Log::debug('Recording model updated', ['modelType' => $modelType, 'modelId' => $model->id]);

      // Assuming $model has been determined as either a Show or ShowEpisode
      $title = "New Recording Available";
      $message = "";
      $url = "";

      if ($model instanceof Show) {
        $team = $model->team;
        $url = url("/shows/{$model->slug}/manage"); // Adjust the URL pattern as needed
        $message = "A new recording for the show '{$model->name}' is now available. Check it out!";
//        Log::debug('Notification setup for Show', ['showName' => $model->name, 'url' => $url]);
      } elseif ($model instanceof ShowEpisode) {
        $show = $model->show;
        if ($show) {
          $team = $show->team;
          $message = "A new recording for the episode '{$model->name}' of show '{$show->name}' is now available. Check it out!";
          $url = url("/shows/{$show->slug}/episode/{$model->slug}/manage"); // Adjust the URL pattern as needed
//          Log::debug('Notification setup for ShowEpisode', ['episodeName' => $model->name, 'showName' => $show->name, 'url' => $url]);
        }
      }

      if (!empty($message) && !empty($url) && isset($team)) {
        // Dispatch the notification job
        SendTeamMembersNotificationJob::dispatch($team, $title, $message, $url);
//        Log::debug('Notification job dispatched', ['teamId' => $team->id, 'message' => $message]);
      }
    } else {
      Log::warning('Model not found for recording linkage', ['streamName' => $streamName]);
    }
  }
}
