<?php

namespace App\Console\Commands;

use App\Models\Show;
use Illuminate\Console\Command;

class UpdateShowMeta extends Command {

  protected $signature = 'update:show-meta';
  protected $description = 'Update the meta field for all currently scheduled shows';

  public function __construct() {
    parent::__construct();
  }

  public function handle() {

    // Fetch all shows
    $shows = Show::all();

    // Update the meta field
    foreach ($shows as $show) {
      if ($show->schedules()->exists()) {
        // Show has schedules, update meta accordingly
        $meta = $show->meta ?? [];
        if (is_string($meta)) {
          $meta = json_decode($meta, true);
        }
        $meta['isScheduled'] = true;
      } else {
        // Show does not have schedules, set meta to an empty array
        $meta = [];
      }

      $show->meta = $meta;
      $show->save();

      $this->info("Updated meta for show ID: {$show->id}");
    }

    $this->info('All shows have been updated.');
    return 0;
  }
}
