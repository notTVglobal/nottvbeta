<?php

namespace App\Console\Commands;

use App\Services\FirstPlayService;
use Illuminate\Console\Command;

class ScheduleCheckAndUpdateChannels extends Command
{
  protected $signature = 'schedule:check-and-update-channels';
  protected $description = 'Check and update channels based on the schedule and channel priority settings';

  protected FirstPlayService $firstPlayService;

  public function __construct(FirstPlayService $firstPlayService)
  {
    parent::__construct();
    $this->firstPlayService = $firstPlayService;
  }

  /**
     * Execute the console command.
     */
  public function handle(): void {
    $this->firstPlayService->checkAndUpdateChannels();
    $this->info('Channels have been checked and updated if needed.');
  }
}
