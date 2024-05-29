<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ClamAVScan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clamav:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run ClamAV scan on the server and log the results';

    /**
     * Execute the console command.
     */
  public function handle()
  {
    // Directory to scan
    $scanDir = '/home/forge/not.tv/storage/app';

    // Run clamscan
    $scanResult = shell_exec("clamscan -r $scanDir");

    // Log the scan results
    Log::info('ClamAV Scan Result:', ['result' => $scanResult]);

    // Check if any threats were found
    if (!str_contains($scanResult, 'Infected files: 0')) {
      // Send email notification
      $subject = 'notTV Website ClamAV Scan Report - Threats Found';
      $to = env('ADMIN_EMAIL');
      $body = $scanResult;

      // Send email (assuming mail configuration is set up)
      Mail::raw($body, function ($message) use ($to, $subject) {
        $message->to($to)->subject($subject);
      });
    }

    $this->info('ClamAV scan completed and logged.');

    return 0;
  }
}
