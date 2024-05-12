<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Middleware;
use App\Models\AppSetting;

class HandleInertiaRequests extends Middleware {
  /**
   * The root template that's loaded on the first page visit.
   *
   * @see https://inertiajs.com/server-side-setup#root-template
   * @var string
   */
  protected $rootView = 'app';

  /**
   * Determines the current asset version.
   *
   * @see https://inertiajs.com/asset-versioning
   * @param \Illuminate\Http\Request $request
   * @return string|null
   */
  public function version(Request $request): ?string {
    return parent::version($request);
  }

  /**
   * Defines the props that are shared by default.
   *
   * @see https://inertiajs.com/shared-data
   * @param \Illuminate\Http\Request $request
   * @return array
   */
  public function share(Request $request): array {

    // Define the path to the JSON file within the storage/app/json directory
//    $jsonFilePath = 'json/firstPlayData.json';
//
//    // Define the cache key
//    $cacheKey = 'firstPlayData';
////    Cache::forget($cacheKey);
////    if (Auth::check()) {
//      // Attempt to get the cached data for logged-in users
//      if (Cache::get($cacheKey)) {
//        $firstPlayData = Cache::get($cacheKey);
//      } else {
//        // Set the cached data for logged-in users
//        $firstPlayData = Cache::remember($cacheKey, now()->addHours(24), function () {
//          $jsonFilePath = 'json/firstPlayData.json';
//          if (Storage::disk('local')->exists($jsonFilePath)) {
//            $jsonContent = Storage::disk('local')->get($jsonFilePath);
//
//            return json_decode($jsonContent, true);
//          } else {
//            // Default values if the file doesn't exist
//            return $firstPlayData = [
//                'first_play_video_source'      => 'https://mist.not.tv/hls/test2/index.m3u8?tkn=1151458905',
//                'first_play_video_source_type' => 'application/x-mpegURL',
//                'first_play_video_name'        => 'Test Pattern Default',
//                'first_play_channel_id'        => '',
//            ];
//          }
//        });
//      }
////    } else {
//      // For non-logged-in users, directly load from the file without caching
//      if (Storage::disk('local')->exists($jsonFilePath)) {
//        $jsonContent = Storage::disk('local')->get($jsonFilePath);
//        $firstPlayData = json_decode($jsonContent, true);
//      } else {
//        $firstPlayData = [
//            'first_play_video_source'      => 'https://mist.not.tv/hls/test2/index.m3u8?tkn=1151458905',
//            'first_play_video_source_type' => 'application/x-mpegURL',
//            'first_play_video_name'        => 'Test Pattern Default',
//            'first_play_channel_id'        => '',
//        ];
//      }
//    }

// Define the path to the JSON file within the storage/app/json directory
    $jsonFilePath = 'json/firstPlayData.json';
    $cacheKey = 'firstPlayData';

// Define a fallback data array
    $fallbackData = [
        'first_play_video_source'      => 'https:\/\/mist.nottv.io\/hls\/test\/index.m3u8',
        'first_play_video_source_type' => 'application\/x-mpegURL',
        'first_play_video_name'        => 'Test Pattern Default',
        'first_play_channel_id'        => 1,
        'use_custom_video'             => false,
        'custom_video_source'          => 'https:\/\/mist.nottv.io\/hls\/test\/index.m3u8',
        'custom_video_source_type'     => 'application\/x-mpegURL',
        'custom_video_name'            => 'Test Bars & Tone',
    ];

// Function to load data from the JSON file or return fallback data
    $loadData = function () use ($jsonFilePath, $fallbackData) {
      if (Storage::disk('local')->exists($jsonFilePath)) {
        $jsonContent = Storage::disk('local')->get($jsonFilePath);

        return json_decode($jsonContent, true);
      }

      return $fallbackData;
    };

    // Retrieve or cache the firstPlayData for all users
    $firstPlayData = Cache::remember($cacheKey, now()->addHours(24), $loadData);

    return array_merge(parent::share($request), [
        'flash'     => [
            'message'  => fn() => $request->session()->get('message'),
            'success'  => fn() => $request->session()->get('success'),
            'warning'  => fn() => $request->session()->get('warning'),
            'error'    => fn() => $request->session()->get('error'),
            'feedback' => fn() => $request->session()->get('feedback'),
        ],
        'firstPlay' => fn() => $firstPlayData,
        'appUrl'    => fn() => config('app.url'),
        'user' => function () use ($request) {
          $user = $request->user();
          if ($user) {
            // Eager load the video settings relationship
            $user->load('videoSettings');

            return $user ? array_merge($user->only(
                'id',
                'name',
                'email',
                'timezone',
                'country',
                'isVip',
                'profile_photo_url',
                'profile_photo_path'
            ), [
                'isCreator' => !empty($user->creator), // Assuming $user->creator is a property or method that returns a truthy or falsy value
                'videoSettings' => $user->videoSettings ? $user->videoSettings->toArray() : null,
                'isSubscriber'       => $user->subscribed('default'),
                'subscriptionStatus' => $user->subscription('default'),
            ]) : null;
          }
          return null;
        },
      // Other shared data...
    ]);
  }

  public function clearFlash(Request $request): void {
    $request->session()->forget('message');
    $request->session()->forget('success');
    $request->session()->forget('warning');
    $request->session()->forget('error');
  }
}
