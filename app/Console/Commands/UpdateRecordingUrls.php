<?php

namespace App\Console\Commands;

use App\Models\AppSetting;
use App\Models\Recording;
use Illuminate\Console\Command;

class UpdateRecordingUrls extends Command {
  protected $signature = 'recordings:update-urls';
  protected $description = 'Update the download_url and share_url columns on the recordings table';

  public function __construct() {
    parent::__construct();
  }

  /**
   * @throws \Exception
   */
  public function handle() {
    // Fetch the settings
    $settings = AppSetting::find(1);

    if (!$settings) {
      $this->error('App settings not found.');

      return 1;
    }

    $mistServerUri = $settings->mist_server_uri;
    $userRecordingsPath = $settings->mist_server_settings['mist_server_user_recording_folder'] ?? null;
    $autoRecordingsPath = $settings->mist_server_settings['mist_server_automated_recording_folder'] ?? null;
    $generalRecordingsPath = '/media/recordings/';

    if (!$userRecordingsPath || !$autoRecordingsPath) {
      $this->error('Recording paths not found in settings.');
      return 1;
    }

    // Fetch all recordings
    $recordings = Recording::all();

    foreach ($recordings as $recording) {
      $uniqueFilePath = $recording->path;
      $streamName = str_replace('+', '_', $recording->stream_name);
      $recordingDate = (new \DateTime($recording->start_dateTime))->format('Y.m.d.H.i.s'); // Convert to yyyy.mm.dd.hh.mm.ss
      $download_url = '';
      $share_url = '';
      $playback_stream_name = '';
      $comment = '';

      // Get the full file name
      $filename = basename($uniqueFilePath);

      if (str_contains($uniqueFilePath, 'auto')) {
        // Auto recordings
        $prefix = 'recording_' . $streamName . '%2B';
      } elseif (str_contains($uniqueFilePath, 'user')) {
        // User recordings
        $relativePath = substr($uniqueFilePath, strlen($userRecordingsPath));
        $parts = explode('/', $relativePath, 3);
        if (count($parts) < 3) continue; // Skip invalid paths
        $userIdPart = 'recordings_user_' . $parts[1];
        $prefix = $userIdPart . '%2B';
      } elseif (str_contains($uniqueFilePath, 'backup')) {
        // Backup recordings
        $prefix = 'recording_backup%2B';
        $comment = 'backup recording';
      } elseif (str_contains($uniqueFilePath, 'recording')) {
        // General recordings
        $prefix = 'recording%2B';
      } else {
        continue; // Invalid path
      }

      // Append the prefix to the filename
      $fileWithPrefix = $prefix . $filename;

      // Generate URLs
      $download_url = $mistServerUri . $fileWithPrefix . '?dl=' . $streamName . $recordingDate . '.mp4';
      $share_url = $mistServerUri . $fileWithPrefix . '.html';
      $playback_stream_name = $fileWithPrefix . '.mp4';

      // Convert '+' to '%2B' in URLs
      $download_url = str_replace('+', '%2B', $download_url);
      $share_url = str_replace('+', '%2B', $share_url);
      $playback_stream_name = str_replace('+', '%2B', $playback_stream_name);

      // Prepare the data for update
      $updateData = [
          'download_url'         => $download_url,
          'share_url'            => $share_url,
          'playback_stream_name' => $playback_stream_name,
      ];

      if ($comment !== '') {
        $updateData['comment'] = $comment;
      }

      $recording->update($updateData);
    }

    $this->info('Recording URLs updated successfully.');
    return 0;
  }
}
