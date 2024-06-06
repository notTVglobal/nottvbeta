<?php

namespace App\Console\Commands;

use App\Models\AppSetting;
use App\Models\Recording;
use Illuminate\Console\Command;

class UpdateRecordingUrls extends Command
{
  protected $signature = 'recordings:update-urls';
  protected $description = 'Update the download_url and share_url columns on the recordings table';

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
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
      $download_url = '';
      $share_url = '';

      if (str_contains($uniqueFilePath, $userRecordingsPath)) {
        $relativePath = substr($uniqueFilePath, strlen($userRecordingsPath));
        $parts = explode('/', $relativePath, 2);
        if (count($parts) < 2) continue; // Skip invalid paths
        $userIdPart = 'recordings_user_' . $parts[0];
        $filename = $parts[1];
        $filenameTransformed = str_replace('+', '%2B', $filename);

        $download_url = $mistServerUri . $userIdPart . '%2B' . $filenameTransformed . '.mp4?dl=1';
        $share_url = $mistServerUri . $userIdPart . '%2B' . $filenameTransformed . '.html';
      } elseif (str_contains($uniqueFilePath, $autoRecordingsPath)) {
        $relativePath = substr($uniqueFilePath, strlen($autoRecordingsPath));
        $parts = explode('/', $relativePath, 2);
        if (count($parts) < 2) continue; // Skip invalid paths
        $wildcardIdPart = 'recordings_' . str_replace('+', '_', $parts[0]); // Fix wildcard ID part
        $filename = $parts[1];
        $filenameTransformed = str_replace('+', '%2B', $filename);

        $download_url = $mistServerUri . $wildcardIdPart . '%2B' . $filenameTransformed . '.mp4?dl=1';
        $share_url = $mistServerUri . $wildcardIdPart . '%2B' . $filenameTransformed . '.html';
      } elseif (str_contains($uniqueFilePath, $generalRecordingsPath)) {
        // Handle general recordings like: '/media/recordings/show+wildcardId_dateTime.mkv'
        $relativePath = substr($uniqueFilePath, strlen($generalRecordingsPath));
        $filenameTransformed = str_replace('+', '%2B', $relativePath);

        $download_url = $mistServerUri . 'recordings%2B' . $filenameTransformed . '.mp4?dl=1';
        $share_url = $mistServerUri . 'recordings%2B' . $filenameTransformed . '.html';
      } else {
        continue; // Skip recordings that don't match the expected structure
      }

      $recording->update([
          'download_url' => $download_url,
          'share_url' => $share_url,
      ]);
    }

    $this->info('Recording URLs updated successfully.');
    return 0;
  }
}
