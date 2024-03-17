<?php

namespace App\Console\Commands;

use App\Models\InviteCode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireInviteCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire:inviteCodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Claims expired invite codes in the database.';

    /**
     * Execute the console command.
     *
     * @return int
     */
  public function handle(): int {
    $expiredCodes = InviteCode::with('user') // Eager load the distributor
    ->where('expiry_date', '<=', now())
        ->where('claimed', false)
        ->get();

    foreach ($expiredCodes as $code) {
      $code->claimed = true;
      $code->save();
      // Notify the distributor
      // $code->user could be used here to access the distributor's info
    }
    Log::info('Expired old Invite Codes.');
  }

}
