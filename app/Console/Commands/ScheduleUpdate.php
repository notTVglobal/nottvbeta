<?php

namespace App\Console\Commands;

use App\Jobs\ScheduleUpdateAllScheduleBroadcastDates;
use Illuminate\Console\Command;

class ScheduleUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually run the ScheduleUpdateAllBroadcastDates job';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $this->info('Dispatching the ScheduleUpdateAllScheduleBroadcastDates job...');
      ScheduleUpdateAllScheduleBroadcastDates::dispatch();
      $this->info('Job dispatched successfully!');

      return 0; // Return success
    }
}
