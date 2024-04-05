<?php

namespace App\Http\Controllers;

use App\Jobs\AddVideoUrlFromEmbedCodeJob;
use App\Models\AppSetting;
use App\Models\Channel;
use App\Models\Image;
use App\Models\InviteCode;
use App\Models\Movie;
use App\Models\NewsCountry;
use App\Models\SecureNote;
use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;

//use function GuzzleHttp\Promise\all;

class AdminController extends Controller {

  public function __construct() {

//    $this->middleware('can:view,show')->only(['show']);

  }


////////////  SETTINGS
//////////////////////

  public function settings(HttpRequest $request) {
//        $settings = DB::table('app_settings')->where('id', 1)->first();
    $queryParam = $request->query('section');

    $settings = AppSetting::find(1);

    // Fetch all countries
    $countries = NewsCountry::orderBy('name', 'asc')->get();

    // Decrypting the Mist Server Password
    if ($settings->mist_server_password) {
      $decryptedPassword = Crypt::decryptString($settings->mist_server_password);
    }

    return Inertia::render('Admin/Settings', [
        'id'                           => $settings->id,
        'countries'                    => $countries, // Add the list of countries here
        'default_country'              => $settings->country_id,
        'cdn_endpoint'                 => $settings->cdn_endpoint,
        'cloud_folder'                 => str_replace('/', '', $settings->cloud_folder),
        'cloud_private_folder'         => str_replace('/', '', $settings->cloud_private_folder),
        'first_play_video_source'      => $settings->first_play_video_source,
        'first_play_video_source_type' => $settings->first_play_video_source_type,
        'first_play_video_name'        => $settings->first_play_video_name,
        'first_play_channel_id'        => $settings->first_play_channel_id,
//            'mist_server_ip' => $settings->mist_server_ip,
        'mist_server_uri'              => $settings->mist_server_uri,
        'mist_server_rtmp_uri'         => $settings->mist_server_rtmp_uri,
        'public_stats_url'             => $settings->public_stats_url,
        'currentSection'               => $queryParam,
//            'mist_server_api_url' => $settings->mist_server_api_url,
//            'mist_server_username' => $settings->mist_server_username,
//            'mist_server_password' => $decryptedPassword ?? null,
//            'mist_access_control_secret' => $settings->mist_access_control_secret,
    ]);
  }

  public function saveSettings(HttpRequest $request) {
    $request->validate([
        'default_country'      => 'nullable|integer',
        'cdn_endpoint'         => 'nullable|string',
        'cloud_folder'         => 'nullable|string',
        'cloud_private_folder' => 'nullable|string',
        'mist_server_uri'      => 'nullable|url',
        'mist_server_rtmp_uri' => 'nullable|string',
        'public_stats_url'     => 'nullable|url',
    ]);

    $settings = AppSetting::find($request->id);
    $settings->country_id = $request->default_country;
    $settings->cdn_endpoint = $request->cdn_endpoint;
    $settings->cloud_folder = '/' . $request->cloud_folder;
    $settings->cloud_private_folder = '/' . $request->cloud_private_folder;
    $settings->mist_server_uri = rtrim($request->mist_server_uri, '/') . '/';
    $settings->mist_server_rtmp_uri = rtrim($request->mist_server_rtmp_uri, '/') . '/';
    $settings->public_stats_url = $request->public_stats_url;

    $settings->save();

    $db = DB::table('app_settings')
        ->where('id', 1)
        ->update(['cdn_endpoint' => $request->cdn_endpoint]);

    $db = DB::table('app_settings')
        ->where('id', 1)
        ->update(['cloud_folder' => '/' . $request->cloud_folder]);

    return redirect(route('admin.settings'))->with('success', 'Settings Saved Successfully');

  }

////////////  FIRST PLAY SETTINGS
/////////////////////////////////

  public function fetchFirstPlaySettings(): \Illuminate\Http\JsonResponse {
    $appSetting = AppSetting::find(1);
//    // Check if first_play_settings is empty and populate it from existing fields
//    if (empty($appSetting->first_play_settings)) {
//      $appSetting->first_play_settings = [
//          'channelId'             => $appSetting->first_play_channel_id,
//          'useCustomVideo'        => true, // Assuming default to true, adjust as necessary
//          'customVideoSource'     => $appSetting->first_play_video_source,
//          'customVideoSourceType' => $appSetting->first_play_video_source_type,
//          'customVideoName'       => $appSetting->first_play_video_name,
//      ];
//      // Optionally save the updated model if you want to persist the merged settings
//      $appSetting->save();
//    }
    // Return a JSON response with dynamic message and status
    $message = 'First play settings retrieved.';
    $status = 'success';

    return response()->json([
        'firstPlaySettings' => $appSetting->first_play_settings,
        'success'           => true,
        'message'           => $message,
        'status'            => $status, // Include the status in the response
    ]);
  }

  public function updateFirstPlaySettings(HttpRequest $request): \Illuminate\Http\JsonResponse {
    // Validate the incoming request data
    $validator = Validator::make($request->all(), [
        'channelId'             => 'required_if:useCustomVideo,false|nullable|exists:channels,id',
        'useCustomVideo'        => 'required|boolean',
        'customVideoSource'     => 'required_if:useCustomVideo,true|nullable|string',
        'customVideoSourceType' => 'required_if:useCustomVideo,true|nullable|string',
        'customVideoName'       => 'required_if:useCustomVideo,true|nullable|string',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
      return response()->json([
          'success' => false,
          'message' => 'Validation errors occurred.',
          'errors'  => $validator->errors(),
      ], 422);
    }

    // Retrieve the application settings (assuming a single record for simplicity)
    $appSetting = AppSetting::find(1);
    if (!$appSetting) {
      return response()->json([
          'success' => false,
          'message' => 'App settings not found.',
      ], 404);
    }


    // Prepare the common settings to be updated
    $updateData = [
        'first_play_settings'   => [
            'channelId'             => $request->channelId,
            'useCustomVideo'        => $request->useCustomVideo,
            'customVideoSource'     => $request->customVideoSource,
            'customVideoSourceType' => $request->customVideoSourceType,
            'customVideoName'       => $request->customVideoName,
        ],
        'first_play_channel_id' => $request->channelId,
    ];

    // Conditionally add the custom video fields to the update if useCustomVideo is true
    if ($request->useCustomVideo) {
      $updateData = array_merge($updateData, [
          'first_play_video_source'      => $request->customVideoSource,
          'first_play_video_source_type' => $request->customVideoSourceType, // Ensure this should not be $request->videoSourceType instead
          'first_play_video_name'        => $request->customVideoName, // Ensure this is the correct request field
      ]);
    }

    // Perform the update
    $appSetting->update($updateData);

    // Ensure dataToUpdate uses the newly updated model attributes
    $cacheDataToUpdate = [
        'mist_server_uri'              => $appSetting->mist_server_uri,
        'first_play_video_source'      => $appSetting->first_play_video_source,
        'first_play_video_source_type' => $appSetting->first_play_video_source_type,
        'first_play_video_name'        => $appSetting->first_play_video_name,
        'first_play_channel_id'        => $appSetting->first_play_settings['channelId'],
        'use_custom_video'             => $appSetting->first_play_settings['useCustomVideo'],
        'custom_video_source'          => $appSetting->first_play_settings['customVideoSource'],
        'custom_video_source_type'     => $appSetting->first_play_settings['customVideoSourceType'],
        'custom_video_name'            => $appSetting->first_play_settings['customVideoName'],
    ];

    $jsonFilePath = 'json/firstPlayData.json';
    Cache::forget('firstPlayData');
    Storage::disk('local')->put($jsonFilePath, json_encode($cacheDataToUpdate, JSON_PRETTY_PRINT));

    // Return a success response
    return response()->json([
        'success'           => true,
        'message'           => 'First play settings updated successfully.',
        'firstPlaySettings' => $appSetting->first_play_settings,
    ]);
  }

////////////  CLEAR FIRST PLAY DATA CACHE
/////////////////////////////////////////

// tec21: I think this is deprecated.... we use the Json file as our cache. 2024-03-26

  /**
   * @throws AuthorizationException
   */
  public function clearFirstPlayDataCache(): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse {

    if (!Auth::user() || !Auth::user()->isAdmin) {
      throw new AuthorizationException('You do not have permission to perform this action.');
    }

    Cache::forget('firstPlayData');

    return redirect('/admin/settings')->with('message', 'First play cache cleared successfully.');
  }





////////////  ADMIN CHANNELS PAGE
/////////////////////////////////
  public function adminChannelsPage() {

//Channel::with(['externalSource', 'channelPlaylist', 'mistStream'])->get();

    return Inertia::render('Admin/Channels/Index', [
//        'channels' => Channel::with(['channelExternalSource', 'channelPlaylist', 'mistStream'])
//            ->when(Request::input('search'), function ($query, $search) {
//              $query->where('name', 'like', "%{$search}%");
//            })  ->latest()
//            ->paginate(13, ['*'], 'admin/channels')
//            ->withQueryString()
//            ->through(fn($channel) => [
//                'id' => $channel->id,
//                'name' => $channel->name,
//                'channelPlaylist' => $channel->channelPlaylist ?? null,
//                'mistStream' => $channel->mistStream ?? null,
//                'externalSource' => $channel->channelExternalSource ?? null,
//                'playbackPriorityType' => $channel->playback_priority_type ?? null,
//            ]),
//        'filters' => Request::only(['search']),
    ]);
  }

  ////////////  ADMIN SCHEDULE PAGE
/////////////////////////////////
  public function adminSchedulePage() {

//Channel::with(['externalSource', 'channelPlaylist', 'mistStream'])->get();

    return Inertia::render('Admin/Schedule/Index');
  }

////////////  SERVER TIME
//////////////////////////

  public function getServerTime(Request $request) {
    // Get the server's current time as a formatted string
    $serverTime = now()->toIso8601String();

    // Return the server time as JSON response
    return response()->json(['serverTime' => $serverTime]);
  }

////////////  INVITE CODES
//////////////////////////

//  public function inviteCodes() {
//    function getUserName($user) {
//      return $name = User::query()
//          ->where('id', $user)
//          ->pluck('name')
//          ->first();
//    }
//
//
//    return Inertia::render('Admin/InviteCodes', [
//        'invite_codes' => InviteCode::query()
//            ->when(Request::input('search'), function ($query, $search) {
//              $query->where('code', 'like', "%{$search}%");
//            })
//            ->latest()
//            ->paginate(10, ['*'], 'codes')
//            ->withQueryString()
//            ->through(fn($code) => [
//                'claimed'    => $code->claimed,
//                'code'       => $code->code,
//                'created_by' => getUserName($code->created_by),
//                'created_at' => $code->created_at,
//                'claimed_by' => getUserName($code->claimed_by),
//                'claimed_at' => $code->claimed_at,
//                'notes'      => $code->notes,
//            ]),
//      // need a pivot table to connect used codes to users.
//        'filters'      => Request::only(['search']),
//// TO DO create a InviteCode Policy
////            'can' => [
////                'create' => Auth::user()->can('create', InviteCode::class),
////                'edit' => Auth::user()->can('edit', InviteCode::class)
////            ]
//    ]);
//  }
//
//  public function saveInviteCodes(HttpRequest $request) {
////        $inviteCodes = $request->validate([
////            'cdn_endpoint' => 'nullable|string',
////            'cloud_folder' => 'nullable|string',
////        ]);
//
//    $validatedData = $request->validate([
//        'code' => ['required', 'unique:invite_codes', 'string', 'max:20'],
//    ]);
//
//    $invite_code = new InviteCode;
//    $invite_code->created_by = Auth::user()->id;
//    $invite_code->code = $request->code;
//    $invite_code->save();
//
////        return $invite_code;
//    // redirect
//    return redirect()->route('admin.inviteCodes')->with('success', 'Code Added Successfully');
//
//  }


////////////  MOVIES INDEX
/////////////////////////

  public function moviesIndex() {

    return Inertia::render('Admin/Movies', [
        'movies'  => Movie::with('team', 'user', 'image', 'status', 'category', 'subCategory')
            ->when(Request::input('search'), function ($query, $search) {
              $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10, ['*'], 'movies')
            ->withQueryString()
            ->through(fn($movie) => [
                'id'            => $movie->id,
                'name'          => $movie->name,
                'team_id'       => $movie->team_id,
                'team'          => [
                    'name' => $movie->team->name ?? null,
                    'slug' => $movie->team->slug ?? null,
                ],
                'image'         => [
                    'id'              => $movie->image->id,
                    'name'            => $movie->image->name,
                    'folder'          => $movie->image->folder,
                    'cdn_endpoint'    => $movie->appSetting->cdn_endpoint,
                    'cloud_folder'    => $movie->image->cloud_folder,
                    'placeholder_url' => $movie->image->placeholder_url,
                ],
                'slug'          => $movie->slug,
                'status'        => $movie->status,
                'copyrightYear' => $movie->created_at->format('Y'),
                'category'      => $movie->category ? $movie->category->toArray() : null,
                'subCategory'   => $movie->subCategory ? $movie->subCategory->toArray() : null,
                'can'           => [
                    'editMovie' => Auth::user()->can('edit', $movie),
                    'viewMovie' => Auth::user()->can('view', $movie)
                ]
            ]),
        'filters' => Request::only(['search']),
        'can'     => [
            'viewMovies'   => Auth::user()->can('viewAny', Movie::class),
            'editMovies'   => Auth::user()->can('edit', Movie::class),
            'createMovies' => Auth::user()->can('create', Movie::class),
        ]
    ]);
  }


////////////  SHOWS INDEX
/////////////////////////

  public function showsIndex() {

    return Inertia::render('Admin/Shows', [
        'shows'   => Show::with('team', 'user', 'image', 'showEpisodes', 'status')
            ->when(Request::input('search'), function ($query, $search) {
              $query->where('name', 'like', "%{$search}%")
                  ->orWhere('ulid', 'like', "%" . $search . "%");;
            })
            ->latest()
            ->paginate(10, ['*'], 'shows')
            ->withQueryString()
            ->through(fn($show) => [
                'id'             => $show->id,
                'name'           => $show->name,
                'team_id'        => $show->team_id,
                'teamName'       => $show->team->name,
                'teamSlug'       => $show->team->slug,
                'showRunnerId'   => $show->user_id,
                'showRunnerName' => $show->user->name,
                'image'          => [
                    'id'              => $show->image->id,
                    'name'            => $show->image->name,
                    'folder'          => $show->image->folder,
                    'cdn_endpoint'    => $show->appSetting->cdn_endpoint,
                    'cloud_folder'    => $show->image->cloud_folder,
                    'placeholder_url' => $show->image->placeholder_url,
                ],
                'slug'           => $show->slug,
                'totalEpisodes'  => $show->showEpisodes->count(),
                'status'         => $show->status->name,
                'statusId'       => $show->status->id,
                'copyrightYear'  => $show->created_at->format('Y'),
                'can'            => [
                    'editShow' => Auth::user()->can('edit', $show),
                    'viewShow' => Auth::user()->can('viewShowManagePage', $show)
                ]
            ]),
        'filters' => Request::only(['search']),
        'can'     => [
//                'viewShows' => Auth::user()->can('view', Show::class),
            'viewCreator' => Auth::user()->can('viewCreator', User::class),
        ]
    ]);
  }

////////////  EPISODES INDEX
/////////////////////////

  public function episodesIndex() {

    return Inertia::render('Admin/Episodes', [
        'episodes' => ShowEpisode::with('show.team', 'show.image', 'show.user', 'show', 'showEpisodeStatus')
            ->when(Request::input('search'), function ($query, $search) {
              $query->where('name', 'like', "%{$search}%")
                  ->orWhere('ulid', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10, ['*'], 'episodes')
            ->withQueryString()
            ->through(fn($episode) => [
                'ulid'           => $episode->ulid,
                'name'           => $episode->name,
                'slug'           => $episode->slug,
                'show'           => $episode->show->name,
                'showSlug'       => $episode->show->slug,
                'teamName'       => $episode->show->team->name,
                'teamSlug'       => $episode->show->team->slug,
                'showRunnerId'   => $episode->show->user_id,
                'showRunnerName' => $episode->show->user->name,
                'image'          => [
                    'id'              => $episode->show->image->id,
                    'name'            => $episode->show->image->name,
                    'folder'          => $episode->show->image->folder,
                    'cdn_endpoint'    => $episode->show->appSetting->cdn_endpoint,
                    'cloud_folder'    => $episode->show->image->cloud_folder,
                    'placeholder_url' => $episode->image->placeholder_url,
                ],
                'status'         => $episode->showEpisodeStatus->name,
                'statusId'       => $episode->showEpisodeStatus->id,
                'created_at'     => $episode->created_at->format('M d, Y'),
            ]),
        'filters'  => Request::only(['search']),
    ]);
  }

////////////  TEAMS INDEX
//////////////////////////

  public function teamsIndex() {

    function getLogo($team) {
      $getLogo = Image::query()
          ->where('team_id', $team->id)
          ->pluck('name')
          ->first();
      if (!empty($getLogo)) {
        $logo = $getLogo;
      } else {
        $logo = 'Ping.png';
      }

      return $logo;
    }

    return Inertia::render('Admin/Teams', [
        'teams'   => Team::with('user', 'image', 'shows', 'teamStatus')
            ->when(\Illuminate\Support\Facades\Request::input('search'), function ($query, $search) {
              $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10, ['*'], 'teams')
            ->withQueryString()
            ->through(fn($team) => [
                'id'          => $team->id,
                'name'        => $team->name,
                'logo'        => getLogo($team),
                'image'       => [
                    'id'              => $team->image->id,
                    'name'            => $team->image->name,
                    'folder'          => $team->image->folder,
                    'cdn_endpoint'    => $team->appSetting->cdn_endpoint,
                    'cloud_folder'    => $team->image->cloud_folder,
                    'placeholder_url' => $team->image->placeholder_url,
                ],
                'teamCreator' => $team->user->name,
                'status'      => $team->teamStatus,
                'slug'        => $team->slug,
                'totalShows'  => $team->shows->count(),
                'memberSpots' => $team->memberSpots,
                'totalSpots'  => $team->totalSpots,
                'can'         => [
                    'editTeam' => Auth::user()->can('update', $team),
                    'viewTeam' => Auth::user()->can('viewTeamManagePage', $team)
                ]
            ]),
        'filters' => Request::only(['search']),
        'can'     => [
            'viewCreator' => Auth::user()->can('viewCreator', User::class),
        ]
    ]);
  }

  public function deleteUser(HttpRequest $request) {
    $user = User::query()->where('id', $request->userId)->first();

    $user->deleteProfilePhoto();
    $user->tokens->each->delete();
    $user->delete();

    // redirect
    return redirect('/users')->with('warning', $user->name . ' Deleted Successfully');
  }


////////////  GET VIDEOS FROM EMBED CODES (created for a single purpose)
////////////////////////////////////////////////////////////////////////
  public function getVideosFromEmbedCodes() {
    // update all episodes... create one job per episode where episode has embed code.
    // get
    foreach (ShowEpisode::all() as $showEpisode) {
      AddVideoUrlFromEmbedCodeJob::dispatch($showEpisode)->onQueue('video_processing');
    }

    return redirect('/admin/settings')->with('message', 'Job submitted to update videos from embed codes.',);

    // send notification on job completion.
  }

  ////////////  SECURE NOTES
////////////////////////////

  public function fetchSecureNotes(HttpRequest $request) {
    try {
      $userId = auth()->user()->id;
      $userName = auth()->user()->name;

      $note = SecureNote::firstOrCreate(
          ['user_id' => $userId],
          ['content' => ''] // No need to manually encrypt here
      );

      // No need to manually decrypt here; the accessor does it
      return response()->json(['content' => $note->content]);
    } catch (\Exception $e) {
      Log::error('Error fetching secure notes', ['error' => $e->getMessage()]);

      return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
    }
  }

  public function putSecureNotes(HttpRequest $request) {
    $request->validate(['content' => 'nullable|string']);

    try {
      $userId = $request->user()->id;
      $userName = $request->user()->name;

      $note = SecureNote::updateOrCreate(
          ['user_id' => $userId],
          ['content' => $request->input('content')] // No need to manually encrypt here
      );

      return response()->json(['message' => 'Secure notes updated successfully.']);
    } catch (\Exception $e) {
      Log::error('Error updating secure notes', ['error' => $e->getMessage()]);

      return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
    }
  }


}
