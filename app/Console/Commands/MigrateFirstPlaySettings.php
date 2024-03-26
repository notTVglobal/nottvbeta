<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AppSetting;

class MigrateFirstPlaySettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:migrate-first-play';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates existing first play settings into the new JSON column structure';

  public function __construct()
  {
    parent::__construct();
  }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $appSetting = AppSetting::find(1); // Assuming there's only one app setting record

      if (!$appSetting) {
        $this->error('AppSetting not found!');
        return;
      }

      $firstPlaySettings = [
          'channelId'             => $appSetting->first_play_channel_id,
          'useCustomVideo'        => true, // Set based on your logic or existing data
          'customVideoSource'     => $appSetting->first_play_video_source,
          'customVideoSourceType' => $appSetting->first_play_video_source_type,
          'customVideoName'       => $appSetting->first_play_video_name,
      ];

      $appSetting->first_play_settings = $firstPlaySettings;
      $appSetting->save();

      $this->info('First play settings have been successfully migrated to the JSON column.');
    }
}
