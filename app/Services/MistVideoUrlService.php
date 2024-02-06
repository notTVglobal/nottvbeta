<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\AppSetting;

class MistVideoUrlService
{
  public function generateUrlAppend(Request $request, $userId): string
  {
    $userId = $userId ?? 0; // Fallback to 0 if no user ID is provided
    $remoteAddr = $request->ip(); // Get the client IP address

    // Fetch the secret from AppSetting
    $secret = AppSetting::where('id', 1)->pluck('mist_access_control_secret') ?? 'default_secret';

    // Generate the hash using the remote IP, user ID, and the secret
    $hash = md5($remoteAddr . $userId . $secret);

    // Return the URL append string
    return "?user={$userId}&hash={$hash}";
  }
}
