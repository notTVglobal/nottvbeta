<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use App\Models\InviteCode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class UnclaimedInviteCode implements InvokableRule
{

  protected $expectedRoleId;

  public function __construct(array $expectedRoleIds = [])
  {
    $this->expectedRoleIds = $expectedRoleIds;
  }

  /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
      // If $value is already an InviteCode model, use it directly.
      // Otherwise, assume $value is a string and query for the InviteCode.
      $inviteCode = $value instanceof InviteCode ? $value : InviteCode::where('code', $value)->first();

      if (!$inviteCode) {
        return $fail($attribute.' is not a valid invite code.');
      }

//      $inviteCode = InviteCode::where('code', $value)->first();

//      if (!$inviteCode) {
//        $fail('The invite code is invalid.');
//        return;
//      }

      if ($inviteCode->claimed) {
        $fail('This invite code has already been claimed.');
        return;
      }

      // Adjust validation logic accordingly
      if (!in_array($inviteCode->user_role_id, $this->expectedRoleIds, true)) {
        $fail('This invite code is not intended for this type of registration.');
        return;
      }

      if ($inviteCode->expiry_date && Carbon::now()->greaterThan($inviteCode->expiry_date)) {
        $fail('This invite code has expired.');
        return;
      }

      if (isset($inviteCode->volume) && $inviteCode->uses >= $inviteCode->volume) {
        $fail('This invite code has reached its maximum number of uses.');
      }

    }
}
