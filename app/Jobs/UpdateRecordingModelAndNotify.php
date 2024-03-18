<?php

namespace App\Jobs;

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
    $streamName = $this->recording->stream_name;
    $wildcard = MistStreamWildcard::where('name', $streamName)->first();

    if (!$wildcard) return;

    $model = null;
    $modelType = null;

    if (str_starts_with($streamName, 'show+')) {
      $model = Show::where('mist_stream_wildcard_id', $wildcard->id)->first();
      $modelType = Show::class;
    } elseif (str_starts_with($streamName, 'episode+')) {
      $model = ShowEpisode::where('mist_stream_wildcard_id', $wildcard->id)->first();
      $modelType = ShowEpisode::class;
    }
    if ($model) {
      $this->recording->update([
          'model_type' => $modelType,
          'model_id' => $model->id,
      ]);

      // Assuming $model has been determined as either a Show or ShowEpisode
      $title = "New Recording Available";
      $message = "";
      $url = "";

      if ($model instanceof Show) {
        $team = $model->team;
        $message = "A new recording for the show '{$model->name}' is now available. Check it out!";
        $url = url("/shows/{$model->slug}/manage"); // Adjust the URL pattern as needed
      } elseif ($model instanceof ShowEpisode) {
        $show = $model->show;
        if ($show) {
          $team = $show->team;
          $message = "A new recording for the episode '{$model->name}' of show '{$show->name}' is now available. Check it out!";
          $url = url("/shows/{$show->slug}/episode/{$model->slug}/manage"); // Adjust the URL pattern as needed
        }
      }

      if (!empty($message) && !empty($url) && isset($team)) {
        // Dispatch the notification job
        SendTeamMembersNotificationJob::dispatch($team, $title, $message, $url);
      }
    }
  }
}
