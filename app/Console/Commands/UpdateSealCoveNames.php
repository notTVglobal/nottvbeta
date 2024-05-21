<?php

namespace App\Console\Commands;

use App\Models\NewsCity;
use Illuminate\Console\Command;

class UpdateSealCoveNames extends Command {
  protected $signature = 'update:seal-cove-names';
  protected $description = 'Update names for Seal Cove locations';

  public function __construct() {
    parent::__construct();
  }

  public function handle() {
    // Update Seal Cove in Fortune Bay
    NewsCity::where('id', 974)
        ->update(['name' => 'Seal Cove, Fortune Bay']);

    // Update Seal Cove in Connaigre Peninsula
    NewsCity::where('id', 975)
        ->update(['name' => 'Seal Cove, Connaigre Peninsula']);

    $this->info('Seal Cove names updated successfully.');
  }
}
