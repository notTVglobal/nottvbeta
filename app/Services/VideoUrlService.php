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
    $secretKey = AppSetting::where('id', 1)->first()->mist_access_control_secret ?? 'default_secret';
    $hash = hash('sha256', $userId . $userIp . $secretKey);

    return "{$videoPath}?user={$userId}&hash={$hash}";
  }

}
