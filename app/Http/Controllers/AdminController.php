<?php

namespace App\Http\Controllers;

use App\Jobs\AddVideoUrlFromEmbedCodeJob;
use App\Models\AppSetting;
use App\Models\Image;
use App\Models\InviteCode;
use App\Models\Movie;
use App\Models\NewsCountry;
use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;
//use function GuzzleHttp\Promise\all;

class AdminController extends Controller
{

  public function __construct() {

//    $this->middleware('can:view,show')->only(['show']);

  }


////////////  SETTINGS
//////////////////////

    public function settings()
    {
//        $settings = DB::table('app_settings')->where('id', 1)->first();
        $settings = AppSetting::find(1);

      // Fetch all countries
      $countries = NewsCountry::orderBy('name', 'asc')->get();

      // Decrypting the Mist Server Password
        if ($settings->mist_server_password) {
            $decryptedPassword = Crypt::decryptString($settings->mist_server_password);
        }

        return Inertia::render('Admin/Settings', [
            'id' => $settings->id,
            'countries' => $countries, // Add the list of countries here
            'default_country' => $settings->country_id,
            'cdn_endpoint' => $settings->cdn_endpoint,
            'cloud_folder' => str_replace('/', '',$settings->cloud_folder),
            'first_play_video_source' => $settings->first_play_video_source,
            'first_play_video_source_type' => $settings->first_play_video_source_type,
            'first_play_video_name' => $settings->first_play_video_name,
            'first_play_channel_id' => $settings->first_play_channel_id,
            'mist_server_ip' => $settings->mist_server_ip,
            'mist_server_api_url' => $settings->mist_server_api_url,
            'mist_server_username' => $settings->mist_server_username,
            'mist_server_password' => $decryptedPassword ?? null,
        ]);
    }

    public function saveSettings(HttpRequest $request)
    {
        $request->validate([
            'default_country' => 'nullable|integer',
            'cdn_endpoint' => 'nullable|string',
            'cloud_folder' => 'nullable|string',
            'first_play_video_source' => 'nullable|string',
            'first_play_video_source_type' => 'nullable|string',
            'first_play_video_name' => 'nullable|string',
            'first_play_channel_id' => 'nullable|integer',
            'mist_server_ip' => 'nullable|string',
            'mist_server_api_url' => 'nullable|string',
            'mist_server_username' => 'nullable|string',
            'mist_server_password' => 'nullable|string',
        ]);

        // Encrypting the Mist Server Password
        if ($request->mist_server_password) {
            $encryptedPassword = Crypt::encryptString($request->mist_server_password);
        }

        $settings = AppSetting::find($request->id);
        $settings->country_id = $request->default_country;
        $settings->cdn_endpoint = $request->cdn_endpoint;
        $settings->cloud_folder = '/'.$request->cloud_folder;
        $settings->first_play_video_source = $request->first_play_video_source;
        $settings->first_play_video_source_type = $request->first_play_video_source_type;
        $settings->first_play_video_name = $request->first_play_video_name;
        $settings->first_play_channel_id = $request->first_play_channel_id;
        $settings->mist_server_ip = $request->mist_server_ip;
        $settings->mist_server_api_url = $request->mist_server_api_url;
        $settings->mist_server_username = $request->mist_server_username;
        $settings->mist_server_password = $encryptedPassword;
        $settings->save();

      // Now, check for specific form variables and update the JSON file if present
      if ($request->has(['first_play_video_source', 'first_play_video_source_type'])) {

        $dataToUpdate = $this->updateFirstPlayData($request);
//        $dataToUpdate = $request->only([
//            'first_play_video_source',
//            'first_play_video_source_type',
//            'first_play_video_name',
//            'first_play_channel_id',
//        ]);
        $jsonFilePath = 'json/firstPlayData.json';
        Storage::disk('local')->put($jsonFilePath, json_encode($dataToUpdate, JSON_PRETTY_PRINT));

      }
        return redirect(route('admin.settings'))->with('success', 'Settings Saved Successfully');
//
//        $db = DB::table('app_settings')
//            ->where('id', 1)
//            ->update(['cdn_endpoint'=> $request->cdn_endpoint])
//        ;
//
//        $db = DB::table('app_settings')
//            ->where('id', 1)
//            ->update(['cloud_folder'=> '/'.$request->cloud_folder])
//        ;
//
//        // redirect
//        return redirect()->route('admin.settings')->with('message', 'Settings Saved Successfully');

    }


////////////  SERVER TIME
//////////////////////////

    public function getServerTime(Request $request)
    {
        // Get the server's current time as a formatted string
        $serverTime = now()->toIso8601String();

        // Return the server time as JSON response
        return response()->json(['serverTime' => $serverTime]);
    }

////////////  INVITE CODES
//////////////////////////

    public function inviteCodes()
    {
        function getUserName($user){
            return $name = User::query()
                ->where('id', $user)
                ->pluck('name')
                ->first();
        }


        return Inertia::render('Admin/InviteCodes', [
            'invite_codes' => InviteCode::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('code', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(10, ['*'], 'codes')
                ->withQueryString()
                ->through(fn($code) => [
                    'claimed' => $code->claimed,
                    'code' => $code->code,
                    'created_by' => getUserName($code->created_by),
                    'created_at' => $code->created_at,
                    'claimed_by' => getUserName($code->claimed_by),
                    'claimed_at' => $code->claimed_at,
                    'notes' => $code->notes,
                ]),
            // need a pivot table to connect used codes to users.
            'filters' => Request::only(['search']),
// TO DO create a InviteCode Policy
//            'can' => [
//                'create' => Auth::user()->can('create', InviteCode::class),
//                'edit' => Auth::user()->can('edit', InviteCode::class)
//            ]
        ]);
    }

    public function saveInviteCodes(HttpRequest $request)
    {
//        $inviteCodes = $request->validate([
//            'cdn_endpoint' => 'nullable|string',
//            'cloud_folder' => 'nullable|string',
//        ]);

        $validatedData = $request->validate([
            'code' => ['required', 'unique:invite_codes', 'string', 'max:20'],
        ]);

        $invite_code = new InviteCode;
        $invite_code->created_by = Auth::user()->id;
        $invite_code->code = $request->code;
        $invite_code->save();

//        return $invite_code;
        // redirect
        return redirect()->route('admin.inviteCodes')->with('success', 'Code Added Successfully');

    }

    public function exportInviteCodes() {

        // tec21: this needs to

        $codes = InviteCode::all()->toArray();


//        dd($codes);

        $handle = fopen('../storage/app/csv/invite_codes.csv', 'w');
//
//        collect($data)->each(fn ($row) => fputcsv($handle, $data));
//        fputcsv($handle, $data);

//
        foreach ($codes as $line) {
            fputcsv($handle, $line);
        }

        fclose($handle);
//return $codes;
        return redirect()->route('admin.inviteCodes')->with('success', 'exported successfully.');
    }



////////////  MOVIES INDEX
/////////////////////////

  public function moviesIndex()
  {

    return Inertia::render('Admin/Movies', [
        'movies' => Movie::with('team', 'user', 'image', 'status', 'category', 'subCategory')
            ->when(Request::input('search'), function ($query, $search) {
              $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10, ['*'], 'movies')
            ->withQueryString()
            ->through(fn($movie) => [
                'id' => $movie->id,
                'name' => $movie->name,
                'team_id' => $movie->team_id,
                'team' => [
                    'name' => $movie->team->name ?? null,
                    'slug' => $movie->team->slug ?? null,
                ],
                'image' => [
                    'id' => $movie->image->id,
                    'name' => $movie->image->name,
                    'folder' => $movie->image->folder,
                    'cdn_endpoint' => $movie->appSetting->cdn_endpoint,
                    'cloud_folder' => $movie->image->cloud_folder,
                ],
                'slug' => $movie->slug,
                'status' => $movie->status,
                'copyrightYear' => $movie->created_at->format('Y'),
                'category'       => $movie->category ? $movie->category->toArray() : null,
                'subCategory'    => $movie->subCategory ? $movie->subCategory->toArray() : null,
                'can' => [
                    'editMovie' => Auth::user()->can('edit', $movie),
                    'viewMovie' => Auth::user()->can('view', $movie)
                ]
            ]),
        'filters' => Request::only(['search']),
        'can' => [
            'viewMovies' => Auth::user()->can('viewAny', Movie::class),
            'editMovies' => Auth::user()->can('edit', Movie::class),
            'createMovies' => Auth::user()->can('create', Movie::class),
        ]
    ]);
  }


////////////  SHOWS INDEX
/////////////////////////

    public function showsIndex()
    {

        return Inertia::render('Admin/Shows', [
            'shows' => Show::with('team', 'user', 'image', 'showEpisodes', 'status')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(10, ['*'], 'shows')
                ->withQueryString()
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'team_id' => $show->team_id,
                    'teamName' => $show->team->name,
                    'teamSlug' => $show->team->slug,
                    'showRunnerId' => $show->user_id,
                    'showRunnerName' => $show->user->name,
                    'image' => [
                        'id' => $show->image->id,
                        'name' => $show->image->name,
                        'folder' => $show->image->folder,
                        'cdn_endpoint' => $show->appSetting->cdn_endpoint,
                        'cloud_folder' => $show->image->cloud_folder,
                    ],
                    'slug' => $show->slug,
                    'totalEpisodes' => $show->showEpisodes->count(),
                    'status' => $show->status->name,
                    'statusId' => $show->status->id,
                    'copyrightYear' => $show->created_at->format('Y'),
                    'can' => [
                        'editShow' => Auth::user()->can('edit', $show),
                        'viewShow' => Auth::user()->can('viewShowManagePage', $show)
                    ]
                ]),
            'filters' => Request::only(['search']),
            'can' => [
//                'viewShows' => Auth::user()->can('view', Show::class),
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }

////////////  EPISODES INDEX
/////////////////////////

    public function episodesIndex()
    {

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
                    'ulid' => $episode->ulid,
                    'name' => $episode->name,
                    'slug' => $episode->slug,
                    'show' => $episode->show->name,
                    'showSlug' => $episode->show->slug,
                    'teamName' => $episode->show->team->name,
                    'teamSlug' => $episode->show->team->slug,
                    'showRunnerId' => $episode->show->user_id,
                    'showRunnerName' => $episode->show->user->name,
                    'image' => [
                        'id' => $episode->show->image->id,
                        'name' => $episode->show->image->name,
                        'folder' => $episode->show->image->folder,
                        'cdn_endpoint' => $episode->show->appSetting->cdn_endpoint,
                        'cloud_folder' => $episode->show->image->cloud_folder,
                    ],
                    'status' => $episode->showEpisodeStatus->name,
                    'statusId' => $episode->showEpisodeStatus->id,
                    'created_at' => $episode->created_at->format('M d, Y'),
                ]),
            'filters' => Request::only(['search']),
        ]);
    }

////////////  TEAMS INDEX
//////////////////////////

    public function teamsIndex()
    {

        function getLogo($team){
            $getLogo = Image::query()
                ->where('team_id', $team->id)
                ->pluck('name')
                ->first();
            if(!empty($getLogo)){
                $logo = $getLogo;
            } else {
                $logo = 'Ping.png';
            }
            return $logo;
        }

        return Inertia::render('Admin/Teams', [
            'teams' => Team::with('user', 'image', 'shows', 'teamStatus')
                ->when(\Illuminate\Support\Facades\Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(10, ['*'], 'teams')
                ->withQueryString()
                ->through(fn($team) => [
                    'id' => $team->id,
                    'name' => $team->name,
                    'logo' => getLogo($team),
                    'image' => [
                        'id' => $team->image->id,
                        'name' => $team->image->name,
                        'folder' => $team->image->folder,
                        'cdn_endpoint' => $team->appSetting->cdn_endpoint,
                        'cloud_folder' => $team->image->cloud_folder,
                    ],
                    'teamCreator' => $team->user->name,
                    'status' => $team->teamStatus,
                    'slug' => $team->slug,
                    'totalShows' => $team->shows->count(),
                    'memberSpots' => $team->memberSpots,
                    'totalSpots' => $team->totalSpots,
                    'can' => [
                        'editTeam' => Auth::user()->can('update', $team),
                        'viewTeam' => Auth::user()->can('viewTeamManagePage', $team)
                    ]
                ]),
            'filters' => Request::only(['search']),
            'can' => [
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }

    public function deleteUser(HttpRequest $request)
    {
        $user = User::query()->where('id', $request->userId)->first();

        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();

        // redirect
        return redirect('/users')->with('warning', $user->name.' Deleted Successfully');
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


////////////  UPDATE FIRST PLAY JSON FILE
/////////////////////////////////////////
  private function updateFirstPlayData(HttpRequest $request) {
    $validatedData = $request->validate([
        'first_play_video_source' => 'required|string',
        'first_play_video_source_type' => 'required|string',
        'first_play_video_name' => 'nullable|string',
        'first_play_channel_id' => 'nullable|integer',
    ]);

    $jsonFilePath = 'json/firstPlayData.json';
    Storage::disk('local')->put($jsonFilePath, json_encode($validatedData, JSON_PRETTY_PRINT));
    return $validatedData;
//    return response()->json(['message' => 'First play data updated successfully.'], 200);
  }

////////////  CLEAR FIRST PLAY DATA CACHE
/////////////////////////////////////////
  public function clearFirstPlayDataCache() {

    if (!Auth::user() || !Auth::user()->isAdmin) {
      throw new AuthorizationException('You do not have permission to perform this action.');
    }

    Cache::forget('firstPlayData');
    return redirect('/admin/settings')->with('message', 'First play cache cleared successfully.');
  }

}
