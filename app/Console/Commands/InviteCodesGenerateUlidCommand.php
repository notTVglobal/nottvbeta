<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InviteCode;
use Illuminate\Support\Str;

class InviteCodesGenerateUlidCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invite-codes:generate-ulid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a ULID for all existing invite codes without one';

    /**
     * Execute the console command.
     *
     * @return int
     */
  public function handle()
  {
    $this->info('Starting to generate ULIDs for invite codes...');

    InviteCode::whereNull('ulid')->get()->each(function ($inviteCode) {
      $inviteCode->ulid = Str::lower(Str::ulid());
      $inviteCode->save();

      $this->line("Generated ULID for invite code ID: {$inviteCode->id}");
    });

    $this->info('Completed generating ULIDs for invite codes.');
  }
}
