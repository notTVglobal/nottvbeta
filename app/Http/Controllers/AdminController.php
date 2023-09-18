<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Image;
use App\Models\InviteCode;
use App\Models\Show;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
//use function GuzzleHttp\Promise\all;

class AdminController extends Controller
{

////////////  SETTINGS
//////////////////////

    public function settings()
    {
//        $settings = DB::table('app_settings')->where('id', 1)->first();
        $settings = AppSetting::find(1);
        return Inertia::render('Admin/Settings', [
            'id' => $settings->id,
            'cdn_endpoint' => $settings->cdn_endpoint,
            'cloud_folder' => str_replace('/', '',$settings->cloud_folder),
            'first_play_video_source' => $settings->first_play_video_source,
            'first_play_video_source_type' => $settings->first_play_video_source_type,
            'first_play_video_name' => $settings->first_play_video_name,
            'first_play_channel_id' => $settings->first_play_channel_id,
            'first_play_channel_name' => $settings->channel->name,
        ]);
    }

    public function saveSettings(HttpRequest $request)
    {
        $request->validate([
            'cdn_endpoint' => 'nullable|string',
            'cloud_folder' => 'nullable|string',
            'first_play_video_source' => 'nullable|string',
            'first_play_video_source_type' => 'nullable|string',
            'first_play_video_name' => 'nullable|string',
            'first_play_channel_id' => 'nullable|integer',
        ]);

        $settings = AppSetting::find($request->id);
        $settings->cdn_endpoint = $request->cdn_endpoint;
        $settings->cloud_folder = '/'.$request->cloud_folder;
        $settings->first_play_video_source = $request->first_play_video_source;
        $settings->first_play_video_source_type = $request->first_play_video_source_type;
        $settings->first_play_video_name = $request->first_play_video_name;
        $settings->first_play_channel_id = $request->first_play_channel_id;
        $settings->save();
        sleep(1);

        return redirect(route('admin.settings'))->with('message', 'Settings Saved Successfully');
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
        return redirect()->route('admin.inviteCodes')->with('message', 'Code Added Successfully');

    }

    public function exportInviteCodes() {

        // tec21: this needs to

        $codes = InviteCode::all()->toArray();


//        dd($codes);

        $handle = fopen('../storage/app/invite_codes.csv', 'w');
//
//        collect($data)->each(fn ($row) => fputcsv($handle, $data));
//        fputcsv($handle, $data);

//
        foreach ($codes as $line) {
            fputcsv($handle, $line);
        }

        fclose($handle);
//return $codes;
        return redirect()->route('admin.inviteCodes')->with('message', 'exported successfully.');
    }


////////////  SHOWS INDEX
/////////////////////////

    public function showsIndex()
    {

        return Inertia::render('Admin/Shows', [
            'shows' => Show::with('team', 'user', 'image', 'showEpisodes', 'showStatus')
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
                    'status' => $show->showStatus->name,
                    'statusId' => $show->showStatus->id,
                    'copyrightYear' => $show->created_at->format('Y'),
                    'can' => [
                        'editShow' => Auth::user()->can('edit', $show),
                        'viewShow' => Auth::user()->can('viewShowManagePage', $show)
                    ]
                ]),
            'filters' => Request::only(['search']),
            'can' => [
                'viewShows' => Auth::user()->can('view', Show::class),
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
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
            'teams' => Team::with('user', 'image', 'shows')
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
                    'teamOwner' => $team->user->name,
                    'slug' => $team->slug,
                    'totalShows' => $team->shows->count(),
                    'memberSpots' => $team->memberSpots,
                    'totalSpots' => $team->totalSpots,
                    'can' => [
                        'editTeam' => Auth::user()->can('editTeam', $team),
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
        return redirect('/users')->with('message', $user->name.' Deleted Successfully');
    }


////////////  GET VIDEOS FROM EMBED CODES (created for a single purpose)
////////////////////////////////////////////////////////////////////////
    public function getVideosFromEmbedCodes() {
        // update all episodes... create one job per episode where episode has embed code.

        // send notification on job completion.
    }



}
