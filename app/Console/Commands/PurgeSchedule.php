<?php

namespace App\Console\Commands;

use App\Jobs\PurgeExpiredSchedules;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PurgeSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purge:schedule';

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
      dispatch(new PurgeExpiredSchedules());
      $this->info('The purge job has been dispatched.');
      Log::info('Dispatch purge schedule job.');

      return 0;
    }
}
