<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\AppSetting;
use Illuminate\Support\Facades\Log;

class VideoUrlService {

  public function generateSecureVideoUrl(Request $request, $videoPath) {
    // Assuming this code is executed in a context where the user is authenticated.
    $user = auth()->user(); // Directly use the authenticated user instance.
    $userId = $user->id;

    $userIp = $request->ip();
    $secretKey = AppSetting::where('id', 1)->first()->mist_access_control_secret ?? 'default_secret';

    $hash = hash('sha256', $userId . $userIp . $secretKey);


    return "{$videoPath}?user={$userId}&hash={$hash}";

  }
}
