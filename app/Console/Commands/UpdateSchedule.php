<?php

namespace App\Console\Commands;

use App\Jobs\UpdateAllScheduleBroadcastDates;
use App\Services\ScheduleService;
use Illuminate\Bus\Dispatcher;
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

  protected ScheduleService $scheduleService;

  public function __construct(ScheduleService $scheduleService)
  {
    parent::__construct();
    $this->scheduleService = $scheduleService;
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $this->info('Dispatching the UpdateAllScheduleBroadcastDates job...');

    try {
      dispatch(new UpdateAllScheduleBroadcastDates($this->scheduleService));
      $this->info('Job dispatched successfully!');
//      Log::debug('UpdateAllScheduleBroadcastDates job dispatched successfully.');
    } catch (\Exception $e) {
      $this->error('Failed to dispatch the job.');
      Log::error('Failed to dispatch UpdateAllScheduleBroadcastDates job.', ['exception' => $e]);
      return 1; // Return failure
    }

    return 0; // Return success
  }
}
