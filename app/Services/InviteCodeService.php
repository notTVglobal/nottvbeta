<?php

namespace App\Services;

use App\Models\InviteCode;
use Illuminate\Support\Carbon;

class InviteCodeService
{
  /**
   * Validates an invite code.
   *
   * @param string $code
   * @return array
   */
  public function validateCode(InviteCode $inviteCode): array
  {
//    $inviteCode = InviteCode::where('code', $inviteCode)->first();

//    if (!$inviteCode) {
//      return ['success' => false, 'message' => 'Oops! It looks like your invite code might have been mistyped or does not exist.'];
//    }

    if ($inviteCode->claimed) {
      return ['success' => false, 'message' => 'This invite code has already embarked on its journey with another creator.'];
    }

    if ($inviteCode->expiry_date && Carbon::now()->greaterThan($inviteCode->expiry_date)) {
      return ['success' => false, 'message' => 'Time flies! This invite code has expired, but don’t let that stop your creative journey.'];
    }

    // Code is valid
    return ['success' => true, 'message' => 'Invite code is valid.', 'code' => $inviteCode];
  }
}
