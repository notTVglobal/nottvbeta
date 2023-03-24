<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Show;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

////////////  SETTINGS
//////////////////////

    public function settings()
    {
        return Inertia::render('Admin/Settings', [
            'cdn_endpoint' => DB::table('app_settings')->where('id', 1)->pluck('cdn_endpoint'),
        ]);
    }

    public function saveSettings(HttpRequest $request)
    {
        $settings = $request->validate([
            'cdn_endpoint' => 'string',
        ]);

        $db = DB::table('app_settings')
            ->where('id', 1)
            ->update(['cdn_endpoint'=> $request->cdn_endpoint]);

        // redirect
        return redirect()->route('admin.settings')->with('message', 'Settings Saved Successfully');

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
                    'poster' => $show->image->name,
                    'slug' => $show->slug,
                    'totalEpisodes' => $show->showEpisodes->count(),
                    'status' => $show->showStatus->name,
                    'statusId' => $show->showStatus->id,
                    'copyrightYear' => $show->created_at->format('Y'),
                    'can' => [
                        'editShow' => Auth::user()->can('editShow', $show),
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


}
