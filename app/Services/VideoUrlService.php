<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\AppSetting;
use Illuminate\Support\Facades\Log;

class VideoUrlService
{

  public function generateSecureVideoUrl(Request $request, $videoPath)
  {
    // Assuming this code is executed in a context where the user is authenticated.
    $user = auth()->user(); // Directly use the authenticated user instance.
    if ($user) {
      $userIp = $request->ip();
      $secretKey = AppSetting::where('id', 1)->first()->mist_access_control_secret ?? 'default_secret';
      // Directly using $user->id since we know $user is not null here.
      $hash = hash('sha256', $user->id . $userIp . $secretKey);

      $user->active_secure_video_hash = $hash;
      $user->save();
    } else {
      // Handle the case where there's no authenticated user, if necessary.
      // This could be redirecting to login, throwing an exception, etc.,
      // depending on your application's requirements.
    }

    return "{$videoPath}?user={$user->id}&hash={$hash}";

//    $userId = auth()->user()->id ?? null;
//    $user = User::where('id', $userId)->first();
//    $userIp = $request->ip();
//    $secretKey = AppSetting::where('id', 1)->first()->mist_access_control_secret ?? 'default_secret';
//    $hash = hash('sha256', $userId . $userIp . $secretKey);
//    $user->active_secure_video_hash = $hash;
//    $user->save();

  }
}
