<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\Creator;
use App\Models\Image;
use App\Models\Show;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
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

        return Inertia::render('Teams/Index', [
            'teams' => Team::with('user', 'image', 'shows')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(10)
                ->withQueryString()
                ->through(fn($team) => [
                    'id' => $team->id,
                    'name' => $team->name,
                    'logo' => $team->image->name,
                    'teamOwner' => $team->user->name,
                    'slug' => $team->slug,
                    'totalShows' => $team->shows->count(),
                    'memberSpots' => $team->memberSpots,
                    'totalSpots' => $team->totalSpots,
                    'can' => [
                        'editTeam' => Auth::user()->can('edit', $team)
                    ]
                ]),
            'filters' => Request::only(['search']),
            'can' => [
                'viewTeams' => Auth::user()->can('view', Team::class),
                'createTeam' => Auth::user()->can('create', Team::class),
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Teams/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HttpRequest $request, Team $team)
    {
//        $attributes = Request::validate([
//            'name' => 'unique:teams|required',
//            'description' => 'required',
//            'logo',
//            'totalSpots',
//            'user_id' => 'required',
//            'slug' => 'required',
//        ]);
//        // create the team
//        Team::create($attributes);
//        // redirect
//        return redirect('/teams')->with('message', 'Team Created Successfully');

        $request->validate([
            'name' => 'unique:teams|required|max:255',
            'description' => 'required|string|max:5000',
            'user_id' => 'required',
            'totalSpots' => 'required|integer|min:1',
        ]);
        Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'totalSpots' => $request->totalSpots,
            'slug' => \Str::slug($request->name),
            'isBeingEditedByUser_id' => $request->user_id,
        ]);

        $newTeam = Team::query()->where('name', $request->name)->firstOrFail();
        $team->id = $newTeam->id;
        $team->members()->attach($request->user_id);

        return redirect()->route('teams.manage', $newTeam)->with('message', 'Team Created Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(team $team)
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

        function showRunner($userId) {
            $showRunner = User::query()->where('id', $userId)->first();
            return $showRunner->name;
        }

        function getPoster($show){
            $getPoster = Image::query()
                ->where('show_id', $show->id)
                ->pluck('name')
                ->first();
            if(!empty($getPoster)){
                $poster = $getPoster;
            } else {
                $poster = 'EBU_Colorbars.svg.png';
            }
            return $poster;
        }

        return Inertia::render('Teams/{$id}/Index', [
            'team' => $team,
            'logo' => getLogo($team),
            'shows' => DB::table('shows')->where('team_id', $team->id)
                ->latest()
                ->paginate(5)
                ->withQueryString()
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'description' => $show->description,
                    'showRunner' => showRunner($show->user_id),
                    'team_id' => $show->team_id,
                    'poster' => getPoster($show),
                    'slug' => $show->slug,
                ]),
            'filters' => Request::only(['team_id']),
            'can' => [
                'viewTeams' => Auth::user()->can('view', Team::class),
                'editTeam' => Auth::user()->can('edit', Team::class),
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $team
     * @return \Illuminate\Http\Response
     */
    // URL path is currently set to show.id
    // change show($id) to show($slug) to
    // make URL path = slug.
    public function manage(Team $team)
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

        function getPoster($show){
            $getPoster = Image::query()
                ->where('show_id', $show->id)
                ->pluck('name')
                ->first();
            if(!empty($getPoster)){
                $poster = $getPoster;
            } else {
                $poster = 'EBU_Colorbars.svg.png';
            }
            return $poster;
        }

        $teamLeader = User::query()->where('id', $team->user_id)->pluck('name')->first();

        // tec21: I am querying the database here because there is currently no
        // pivot table between creators and teams.
        //
        function getTeams($userId) {
            $teams = TeamMember::query()->where('user_id', $userId)->pluck('team_id');
            return $teams;
}
        return Inertia::render('Teams/{$id}/Manage', [
            'team' => $team,
            'logo' => getLogo($team),
            'teamLeader' => $teamLeader,
            'members' => $team->members,

            'creators' => Creator::join('users AS user', 'creators.user_id', '=', 'user.id')
                ->select('creators.*', 'user.name AS name')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(3)
                ->withQueryString()
                ->through(fn($creator) => [
                    'id' => $creator->user->id,
                    'name' => $creator->user->name,
                    'profile_photo_url' => $creator->user->profile_photo_url,
                    'teams' => getTeams($creator->user->id),
                ]),

//            'creators' => Creator::with('user')
//                ->when(Request::input('search'), function ($query, $search) {
//                    $query->where('name', 'like', "%{$search}%");
//                        })
//                ->latest()
//                ->paginate(5)
//                ->withQueryString()
//                ->through(fn($creator) => [
//                    'id' => $creator->user->id,
//                    'name' => $creator->user->name,
//            ]),
//

            'shows' => DB::table('shows')->where('team_id', $team->id)
                ->latest()
                ->paginate(5)
                ->withQueryString()
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'description' => $show->description,
                    'showRunnerName' => User::query()->where('id', $show->user_id)->pluck('name')->first(),
                    'team_id' => $show->team_id,
                    'poster' => getPoster($show),
                    'slug' => $show->slug,
                ]),
            'filters' => Request::only(['team_id']),
            'creatorFilters' => Request::only(['search']),
            'can' => [
                'viewTeams' => Auth::user()->can('view', Team::class),
                'manageTeam' => Auth::user()->can('manage', Team::class),
                'editTeam' => Auth::user()->can('edit', Team::class),
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(team $team)
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

        DB::table('users')->where('id', Auth::user()->id)->update([
            'isEditingTeam_id' => $team->id,
        ]);

        DB::table('teams')->where('id', $team->id)->update([
            'isBeingEditedByUser_id' => Auth::user()->id,
        ]);

        // Currently this queries all images in the database
        // this needs to be changed to limit the query.
        //
        return Inertia::render('Teams/{$id}/Edit', [
            'team' => $team,
            'teamLeaderName' => User::query()->where('id', $team->user_id)->pluck('name')->first(),
            'logo' => getLogo($team),
            'can' => [
                'viewTeams' => Auth::user()->can('view', Team::class),
                'editTeam' => Auth::user()->can('edit', Team::class),
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HttpRequest $request, Team $team)
    {

        // validate the request
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('teams')->ignore($team->id)],
            'description' => 'required|string|max:5000',
            'totalSpots' => 'required|integer|min:1',
        ]);

        // update the team
        $team->name = $request->name;
        $team->description = $request->description;
        $team->totalSpots = $request->totalSpots;
        $team->save();
        sleep(1);

        $teamSlug = $team->slug;

        // gather the data needed to render the Manage page
        // this is all redundant. It's all contained in the
        // teams.manage route above. But I (tec21) don't know
        // how to simplify this *frustrated*.


        // redirect
        return redirect(route('teams.manage', [$teamSlug]))->with('message', 'Team Updated Successfully');
//        return redirect('/teams/{$team->id}/manage');

//        return Inertia::render('Teams/{$id}/Manage', [
//            // responses need to be limited to only
//            // the information required with ->only()
//            // https://inertiajs.com/responses
//            'team' => $team,
//            'logoName' => Image::query()->where('id', $team->image_id)->pluck('name')->first(),
//            'shows' => DB::table('shows')->where('team_id', $team->id)
//                ->latest()
//                ->paginate(5)
//                ->withQueryString()
//                ->through(fn($show) => [
//                    'id' => $show->id,
//                    'name' => $show->name,
//                    'description' => $show->description,
//                    'team_id' => $show->team_id,
//                    'poster' => $show->image_id,
//                ]),
//// tec21: this edit() policy for Teams is broken. Expecting 2 arguments,
//        // only 1 is getting passed.
//            'can' => [
//                'viewTeams' => Auth::user()->can('view', Team::class),
//                'createTeam' => Auth::user()->can('create', Team::class),
//                'editTeam' => Auth::user()->can('edit', Team::class),
//                'viewCreator' => Auth::user()->can('viewCreator', User::class),
//            ]
//        ])->with('message', 'Team Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->delete();
        sleep(1);

        return redirect()->route('teams')->with('message', 'Team Deleted Successfully');
    }
}
