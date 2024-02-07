<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\AppSetting;

class VideoUrlService
{

  public function generateSecureVideoUrl(Request $request, $videoPath)
  {
    $userId = auth()->id();
    $userIp = $request->ip();
    $secret = AppSetting::where('id', 1)->first()->mist_access_control_secret ?? 'default_secret';
    $hash = hash('sha256', $userIp . $secret);

    return "{$videoPath}?user={$userId}&hash={$hash}";
  }


  public function generateUrlAppend(Request $request, $userId): string
  {
    $userId = $userId ?? 0; // Fallback to 0 if no user ID is provided
    $remoteAddr = $request->ip(); // Get the client IP address

    // Fetch the secret from AppSetting
    $secret = AppSetting::where('id', 1)->first()->mist_access_control_secret ?? 'default_secret';

    // Generate the hash using the remote IP, user ID, and the secret
    $hash = md5($remoteAddr . $secret);

    // Return the URL append string
    return "?user={$userId}&hash={$hash}";
  }
}
