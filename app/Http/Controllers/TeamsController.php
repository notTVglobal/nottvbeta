<?php

namespace App\Http\Controllers;

use App\Models\ShowCategory;
use App\Models\ShowCategorySub;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\Creator;
use App\Models\Image;
use App\Models\Show;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TeamsController extends Controller
{

    public function __construct()
    {
        //tec21: this authorization works... but I'm having trouble
        // with the other ones below. So they are in web.php
        $this->middleware('can:viewTeamManagePage,team')->only(['manage']);
        $this->middleware('can:update,team')->only(['edit']);
        $this->middleware('can:view,team')->only(['show']);


// If you are having troubles with the policies saying
// "Too few arguments..."
// then change the function name to something that
// isn't duplicated in the controller.

        //tec21: these would apply more to a resource like user generated
        // posts and comments, photos and videos.
        //
        // To get this app started, I'm going to put the authorizations in
        // the web.php file.
        //
//        $this->authorizeResource(Team::class, 'team')->only(['manage']);
//        $this->middleware('can:create,team')->only(['create', 'store']);
//        $this->middleware('can:createTeam,team')->only(['create']);
//        $this->middleware('can:create,team')->only(['store']);

    }

////////////  INDEX
///////////////////

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

////////////  CREATE AND STORE
//////////////////////////////

    public function create()
    {
        return Inertia::render('Teams/Create');
    }


    public function store(HttpRequest $request, Team $team)
    {

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

////////////  SHOW
//////////////////

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

        function showCategoryName($id) {
            $showCategoryName = ShowCategory::query()->where('id', $id)->pluck('name');
            return $showCategoryName->toArray();
        }

        function showCategorySubName($id) {
            $showCategorySubName = ShowCategorySub::query()->where('id', $id)->pluck('name');
            return $showCategorySubName->toArray();
        }

        return Inertia::render('Teams/{$id}/Index', [
            'team' => $team,
            'logo' => getLogo($team),
            'image' => [
                'id' => $team->image->id,
                'name' => $team->image->name,
                'folder' => $team->image->folder,
                'cdn_endpoint' => $team->appSetting->cdn_endpoint,
                'cloud_folder' => $team->image->cloud_folder,
            ],
            'shows' => Show::with('team', 'image')->where('team_id', $team->id)
                ->latest()
                ->paginate(6, ['*'], 'shows')
                ->withQueryString()
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'description' => $show->description,
                    'showRunner' => showRunner($show->user_id),
                    'team_id' => $show->team_id,
                    'poster' => getPoster($show),
                    'image' => [
                        'id' => $show->image->id,
                        'name' => $show->image->name,
                        'folder' => $show->image->folder,
                        'cdn_endpoint' => $show->appSetting->cdn_endpoint,
                        'cloud_folder' => $show->image->cloud_folder,
                    ],
                    'slug' => $show->slug,
                    'copyrightYear' => Carbon::parse($show->created_at)->format('Y'),
                    'categoryName' => showCategoryName($show->show_category_id),
                    'categorySubName' => showCategorySubName($show->show_category_sub_id),
                ]),
            'creators' => TeamMember::where('team_id', $team->id)
                ->join('users', 'team_members.user_id', '=', 'users.id')
                ->select('users.*', 'team_members.user_id')
                ->latest()
                ->paginate(5, ['*'], 'creator')
//                ->withQueryString()
                ->through(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_photo_path' => $user->profile_photo_path,
                ]),
            'filters' => Request::only(['team_id']),
            'can' => [
                'viewTeam' => auth()->user()->can('view', $team),
                'manageTeam' => auth()->user()->can('viewTeamManagePage', $team),
                'editTeam' => auth()->user()->can('update', $team),
            ]
        ]);
    }

////////////  MANAGE
////////////////////

    public function manage(Team $team, Show $show)
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

        function showCategoryName($id) {
            $showCategoryName = ShowCategory::query()->where('id', $id)->pluck('name');
            return $showCategoryName->toArray();
        }

        return Inertia::render('Teams/{$id}/Manage', [
            'team' => $team,
            'logo' => getLogo($team),
            'image' => [
                'id' => $team->image->id,
                'name' => $team->image->name,
                'folder' => $team->image->folder,
                'cdn_endpoint' => $team->appSetting->cdn_endpoint,
                'cloud_folder' => $team->image->cloud_folder,
            ],
            'teamLeader' => $teamLeader,

        // tec21: 'members' will need to be returned with pagination and searchable.
            // this will be for larger teams. But, at that point, all of this will
            // probably be re-written.
            //
            'members' => $team->members,
//            'members' => TeamMember::join('users AS user', 'team_members.user_id', '=', 'user.id')
//                ->select('team_members.*', 'user.name AS name')
//                ->when(Request::input('search'), function ($query, $search) {
//                    $query->where('name', 'like', "%{$search}%");
//                })
//                ->latest()
//                ->paginate(3)
//                ->withQueryString()
//                ->through(fn($member) => [
//                    'id' => $member->user_id,
////                    'name' => $member->user->name,
////                    'profile_photo_url' => $member->user->profile_photo_url,
////                    'teams' => getTeams($member->user->id),
//                ]),

            'creators' => Creator::join('users AS user', 'creators.user_id', '=', 'user.id')
                ->select('creators.*', 'user.name AS name')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(3)
                ->withQueryString()
                ->through(fn($creator) => [
                    'id' => $creator->user_id,
                    'name' => $creator->user->name,
                    'profile_photo_url' => $creator->user->profile_photo_url,
                    'profile_photo_path' => $creator->user->profile_photo_path,
                    'teams' => getTeams($creator->user->id),
                ]),

//            'shows' => DB::table('shows')->where('team_id', $team->id)
            'shows' => Show::with('team', 'image')->where('team_id', $team->id)
                ->latest()
                ->paginate(5)
                ->withQueryString()
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'description' => $show->description,
                    'showRunnerName' => User::query()->where('id', $show->user_id)->pluck('name')->first(),
                    'team_id' => $show->team_id,
                    'image' => [
                        'id' => $team->image->id,
                        'name' => $team->image->name,
                        'folder' => $team->image->folder,
                        'cdn_endpoint' => $team->appSetting->cdn_endpoint,
                        'cloud_folder' => $team->image->cloud_folder,
                    ],
                    'slug' => $show->slug,
                    'notes' => $show->notes,
                    'copyrightYear' => Carbon::parse($show->created_at)->format('Y'),
                    'categoryName' => showCategoryName($show->show_category_id),
                ]),
            'filters' => Request::only(['team_id']),
            'creatorFilters' => Request::only(['search']),
            'can' => [
                'editTeam' => auth()->user()->can('update', $team),
        ]
        ]);
    }


////////////  EDIT
//////////////////

    public function edit(Team $team)
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
            'image' => [
                'id' => $team->image->id,
                'name' => $team->image->name,
                'folder' => $team->image->folder,
                'cdn_endpoint' => $team->appSetting->cdn_endpoint,
                'cloud_folder' => $team->image->cloud_folder,
            ],
            'creators' => Creator::join('users AS user', 'creators.user_id', '=', 'user.id')
                ->select('creators.*', 'user.name AS name')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(5)
                ->withQueryString()
                ->through(fn($creator) => [
                    'id' => $creator->user->id,
                    'name' => $creator->user->name,
                ]),
            'can' => [
                'viewTeams' => Auth::user()->can('view', Team::class),
                'editTeam' => Auth::user()->can('edit', Team::class),
//                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }


////////////  UPDATE
////////////////////

    public function update(HttpRequest $request, Team $team)
    {
        if($request->totalSpots < $team->memberSpots) {
            return redirect(route('teams.edit', [$team->slug]))->with('message', 'You already have the maximum. Please remove team members or increase the maximum # of team members.');
        }

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
        $team->slug = \Str::slug($request->name);
        $team->save();
        sleep(1);

        $teamSlug = $team->slug;

        // redirect
        return redirect(route('teams.manage', [$teamSlug]))->with('message', 'Team Updated Successfully');

    }


////////////  DESTROY
/////////////////////

    public function destroy(Team $team)
    {
        $team->delete();
        sleep(1);

        return redirect()->route('teams')->with('message', 'Team Deleted Successfully');
    }
}
