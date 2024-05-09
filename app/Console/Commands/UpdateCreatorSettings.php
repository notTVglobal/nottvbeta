<?php

namespace App\Console\Commands;

use App\Models\Creator;
use Illuminate\Console\Command;

class UpdateCreatorSettings extends Command {
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'update:creator-settings';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Updates settings for all creators to include profile_is_public = false ';

  /**
   * Execute the console command.
   *
   * @return void
   */
  public function handle() {
    $this->info('Updating settings for all creators...');

    // Fetch all creators and update settings
    Creator::chunk(100, function ($creators) {
      foreach ($creators as $creator) {
        $settings = $creator->settings ?? [];
        $settings['profile_is_public'] = $settings['profile_is_public'] ?? false;
        $creator->settings = $settings;
        $creator->save();
      }
      $this->info('Updated settings for creator ID: ' . $creator->id);
    });



    $this->info('All creators have been updated successfully.');
  }
}
