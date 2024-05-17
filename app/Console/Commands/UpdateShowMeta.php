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

    // Fetch all shows with schedules
    $shows = Show::whereHas('schedules')->get();

    // Update the meta field
    foreach ($shows as $show) {
      $meta = $show->meta ?? [];
      $meta['isScheduled'] = true;
      $show->meta = $meta;
      $show->save();

      $this->info("Updated meta for show ID: {$show->id}");
    }

    $this->info('All scheduled shows have been updated.');
    return 0;
  }
}
