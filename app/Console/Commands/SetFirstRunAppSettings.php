<?php

namespace App\Console\Commands;

use App\Models\AppSetting;
use Illuminate\Console\Command;

class SetFirstRunAppSettings extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:set-first-run-settings';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Set the first run app settings';

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
      return 1;
    }

    $firstPlaySettings = [
        'channelId'             => null,
        'useCustomVideo'        => true,
        'customVideoSource'     => '',
        'customVideoSourceType' => '',
        'customVideoName'       => '',
    ];

    $appSetting->first_play_settings = $firstPlaySettings;
    $appSetting->save();

    $this->info('First run app settings have been successfully set.');

    return 0;
  }
}
