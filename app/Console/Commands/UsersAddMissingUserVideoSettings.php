<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserVideoSetting;
use Illuminate\Console\Command;

class UsersAddMissingUserVideoSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:add-missing-video-settings';

    /**
     * The console command description.
     *
     * @var string
     */
  protected $description = 'Add missing user video settings for users who do not have them';

    /**
     * Execute the console command.
     */
  public function handle()
  {
    $usersWithoutSettings = User::doesntHave('videoSettings')->get();

    foreach ($usersWithoutSettings as $user) {
      UserVideoSetting::create([
          'user_id' => $user->id,
          'last_playback_position' => 0,
          'volume_setting' => 1.0,
          'playback_speed' => 1.0,
          'additional_settings' => json_encode([]),
      ]);

      $this->info("Added video settings for user ID {$user->id}");
    }

    $this->info('Finished adding missing user video settings.');

    return 0;
  }
}
