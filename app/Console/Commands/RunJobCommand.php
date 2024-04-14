<?php

namespace App\Console\Commands;

use App\Jobs\UpdateNextBroadcastDatesOnScheduleIndexes;
use Illuminate\Console\Command;

class RunJobCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:run-next-broadcast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually run the UpdateNextBroadcastDateTime job';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $this->info('Starting the job...');
      UpdateNextBroadcastDatesOnScheduleIndexes::dispatch();
      $this->info('Job dispatched!');
      return 0;
    }
}
