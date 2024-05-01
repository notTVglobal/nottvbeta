<?php

namespace App\Console\Commands;

use App\Jobs\SchedulePurgeExpiredSchedules;
use Illuminate\Console\Command;

class SchedulePurge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:purge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge expired schedules from the database';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      // Dispatch the job
      dispatch(new SchedulePurgeExpiredSchedules());

      $this->info('The purge job has been dispatched.');
      return 0;
    }
}
