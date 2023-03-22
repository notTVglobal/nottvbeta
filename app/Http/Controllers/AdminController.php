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

class AdminController extends Controller
{

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
