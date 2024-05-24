<?php

namespace App\Console\Commands;

use App\Jobs\UpdateAllScheduleBroadcastDates;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the UpdateAllScheduleBroadcastDates job';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $this->info('Dispatching the UpdateAllScheduleBroadcastDates job...');
      UpdateAllScheduleBroadcastDates::dispatch();
      $this->info('Job dispatched successfully!');
      Log::info('Schedule updated.');

      return 0; // Return success
    }
}
