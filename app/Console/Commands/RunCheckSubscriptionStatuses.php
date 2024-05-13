<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CheckSubscriptionStatuses;

class RunCheckSubscriptionStatuses extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'run:check-subscription-statuses';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Manually run the CheckSubscriptionStatuses job';

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    CheckSubscriptionStatuses::dispatch();

    $this->info('CheckSubscriptionStatuses job has been dispatched.');

    return 0;
  }
}
