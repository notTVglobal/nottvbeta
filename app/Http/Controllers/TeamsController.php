<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Models\Notification;
use App\Models\ShowCategory;
use App\Models\ShowCategorySub;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\TeamTransfer;
use App\Models\User;
use App\Models\Creator;
use App\Models\Image;
use App\Models\Show;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request as HttpRequest;
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
            'teams' => Team::with('user', 'image', 'shows', 'teamStatus')
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
                    'teamCreator' => $team->user->name,
                    'status' => $team->teamStatus,
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
            'user_id' => 'required|exists:users,id',
            'totalSpots' => 'required|integer|min:1',
        ]);

        // Find the user and their creator profile
        $user = User::with('creator')->find($request->user_id);
        if (!$user || !$user->creator) {
            return redirect()->back()->with('error', 'The selected user is not a creator.');
        }

        // Check if the creator's status is active
        if ($user->creator->status->id != 1) {
            return redirect()->back()->with('error', 'The creator does not have the required status to create a team.');
        }

        Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
//            'team_leader' => $user->creator->id, // Set team_leader to the creator's ID
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
            'shows' => Show::with('team', 'image')
                ->where('team_id', $team->id)
                ->where(function ($query) {
                  if (auth()->check() && auth()->user()->creator) {
                    // For creators, include shows with status 9 and also shows that are new or active with specific episode statuses
                    $query->where('show_status_id', 9)
                        ->orWhere(function ($q) {
                          $q->whereIn('show_status_id', [1, 2])
                              ->whereHas('showEpisodes', function ($q) {
                                $q->where('show_episode_status_id', 7);
                              });
                        });
                  } else {
                    // For all other users, filter for shows that are new or active and have episodes with a specific status
                    $query->whereIn('show_status_id', [1, 2])
                        ->whereHas('showEpisodes', function ($q) {
                          $q->where('show_episode_status_id', 7);
                        });
                  }
                })
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
                    'statusId' => $show->status->id,
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

        // check if teamCreator is not null before attempting to access its related properties
        $teamCreatorData = [
            'id' => null,
            'name' => null,
            'creator_status_id' => null,
            'creator_status_name' => null,
        ];

        if ($team->user_id) {
            $teamCreatorData = [
                'id' => $team->user->id,
                'name' => $team->user->name,
                'creator_status_id' => optional(optional($team->user->creator)->status)->id ?? null,
                'creator_status_name' => optional(optional($team->user->creator)->status)->status ?? null,
                ];
        }

        // check if teamLeader is not null before attempting to access its related properties
        $teamLeaderData = [
            'id' => null,
            'name' => null,
            'creator_status_id' => null,
            'creator_status_name' => null,
        ];

        if ($team->teamLeader) {
            $teamLeaderData = [
                'id' => $team->teamLeader->user->id ?? null,
                'name' => $team->teamLeader->user->name ?? null,
                'creator_status_id' => optional($team->teamLeader->status)->id ?? null,
                'creator_status_name' => optional($team->teamLeader->status)->status ?? null,
            ];
        }

        $managers = $team->managers->map(function ($manager) {
            return [
                'id' => $manager->id,
                'name' => $manager->name
            ];
        });

        // For can permissions passed to Vue below:

        $userId = auth()->id(); // Get the authenticated user's id

        // Managers
        $managersIds = $team->managers->pluck('id')->toArray(); // Get only ids of managers
        $isTeamManager = in_array($userId, $managersIds);

        // Team Leader
        $isTeamLeader = $userId == $team->team_leader;

        // Team Owner
        $isTeamOwner = $userId == $team->user_id;

        // Team Members
        // Assuming members are a relation in your Team model
        $membersIds = $team->members->pluck('id')->toArray(); // Get only ids of members
        $isTeamMember = in_array($userId, $membersIds);

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
            'image' => [
                'id' => $team->image->id,
                'name' => $team->image->name,
                'folder' => $team->image->folder,
                'cdn_endpoint' => $team->appSetting->cdn_endpoint,
                'cloud_folder' => $team->image->cloud_folder,
            ],
            'teamCreator' => $teamCreatorData,
            'teamLeader' => $teamLeaderData,

        // tec21: 'members' will need to be returned with pagination and searchable.
            // this will be for larger teams. But, at that point, all of this will
            // probably be re-written.
            //
            'members' => $team->members,
            'managers' => $managers,
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
                        'id' => $show->image->id,
                        'name' => $show->image->name,
                        'folder' => $show->image->folder,
                        'cdn_endpoint' => $show->appSetting->cdn_endpoint,
                        'cloud_folder' => $show->image->cloud_folder,
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
                'manageTeam' => auth()->user()->can('manage', $team),
                'transferTeam' => auth()->user()->can('transfer', $team),
                'isTeamOwner' => $isTeamOwner,
                'isTeamLeader' => $isTeamLeader,
                'isTeamManager' => $isTeamManager,
                'isTeamMember' => $isTeamMember,
                'isAdmin' => auth()->user()->isAdmin,
        ]
        ]);
    }

////////////  TRANSFER
//////////////////////

    public function sendTransferRequest(Team $team, HttpRequest $request)
    {
        // If you are not signed in, no way.
        if (auth()->guest()) {
            abort(403, 'You are not signed in.');
        }

        // verify the auth user is the team owner
        $userOwnsTeam = auth()->user()->id === $team->user_id;
        if (!$userOwnsTeam) {
            abort(403, 'You are not authorized to perform this action.');
        }

        // verify the team is eligible to be transferred.
        $teamIsEligible = !in_array($team->teamStatus->id, [6, 7, 8, 9, 10, 12]);
        if (!$teamIsEligible) {
            return response()->json(['error' => 'The team is not eligible for transfer.'], 422);
        }

        // change the team status to transfer
        $team->team_status_id = 12;
        $team->save();

        $formerOwnerUserId = auth()->user()->id;
        $newOwnerUserId = $request->user_id;
        $blockchainId = null;
        $blockchainTransactionId = null;
        $transferStatusId = 1; // pending

        // save a record in the team_transfers table marked pending approval (and later on blockchain approval when we develop that part.)
        $teamTransfer = new TeamTransfer([
            'team_id' => $team->id,
            'former_owner_user_id' => $formerOwnerUserId, // ID of the former owner
            'new_owner_user_id' => $newOwnerUserId, // ID of the new owner
            'blockchain_id' => $blockchainId, // ID of the associated blockchain (if applicable)
            'blockchain_transaction_id' => $blockchainTransactionId, // ID of the blockchain transaction (if applicable)
            'transfer_status_id' => $transferStatusId, // ID of the initial transfer status (e.g., 'pending')
        ]);

        $teamTransfer->save();

        // create dashboard notification with accept/reject options.

        // send a dashboard notification to the user who must approve or reject the transfer
        // send a notification to user who must approve or reject the transfer
        // MODIFY THIS CODE:
        // notify new team member
        $user = User::findOrFail($request->user_id);
        $notification = new Notification;
        $notification->user_id = $user->id;
        // make the image the team_poster
        $notification->image_id = $team->image_id;
        $notification->url = '/teams/'.$team->slug;
        $notification->title = $team->name;
        $notification->message = '<span class="text-green-500">This team has been transferred to you. Please go to your <Link href="/dashboard" class="text-blue-600 hover:text-blue-400">Creator Dashboard</Link> to accept or reject the transfer.</span>';
        $notification->save();
        // Trigger the event to broadcast the new notification
        event(new NewNotificationEvent($notification));

        // email the user who must approve or reject the transfer

        // return response
//        return response()->json(['message' => 'Transfer request sent successfully.'], 200);
//        return Inertia::render('Teams/{$id}/Manage', ['message' => 'Transfer request sent successfully.']);
        return redirect(route('teams.manage', [$team->slug]))->with('message', 'Transfer request sent successfully.');


    }

////////////  EDIT
//////////////////

    public function edit(Team $team)
    {
        // lock the team from being edited by more than 1 person.
        DB::table('users')->where('id', Auth::user()->id)->update([
            'isEditingTeam_id' => $team->id,
        ]);
        DB::table('teams')->where('id', $team->id)->update([
            'isBeingEditedByUser_id' => Auth::user()->id,
        ]);

        // check if teamLeader is not null before attempting to access its related properties
        $teamLeaderData = [
            'id' => null,
            'name' => null,
            'creator_status_id' => null,
            'creator_status_name' => null,
        ];

        if ($team->teamLeader) {
            $teamLeaderData = [
                'id' => $team->teamLeader->user->id ?? null,
                'name' => $team->teamLeader->user->name ?? null,
                'creator_status_id' => $team->user?->creator?->status->id ?? null,
                'creator_status_name' => $team->teamLeader?->status ?? null,
            ];
        }

        // check if teamCreator is not null before attempting to access its related properties
        $teamCreatorData = [
            'id' => null,
            'name' => null,
            'creator_status_id' => null,
            'creator_status_name' => null,
        ];

        if ($team->user_id) {
            $teamCreatorData = [
                'id' => $team->user->id,
                'name' => $team->user->name,
                'creator_status_id' => $team->user?->creator?->status->id ?? null,
                'creator_status_name' => $team->teamLeader?->status ?? null,
            ];
        }

        // Get all active team members
        $activeMembers = $team->members()->wherePivot('active', 1)->get();

        // Prepare the list of possible team leaders
        $possibleTeamLeaders = $activeMembers->map(function ($member) use ($team) {
            return [
                'id' => $member->id,
                'name' => $member->name,
                'role' => $this->determineRole($member, $team)
            ];
        });

        return Inertia::render('Teams/{$id}/Edit', [
            'team' => [
                'name' => $team->name,
                'description' => $team->description,
                'slug' => $team->slug,
                'totalSpots' => $team->totalSpots,
                'team_status_id' => $team->teamStatus->id,
                'team_status_name' => $team->teamStatus->status,
            ],
            'teamCreator' => $teamCreatorData,
            'teamLeader' => $teamLeaderData,
            'possibleTeamLeaders' => $possibleTeamLeaders,
            'image' => [
                'id' => $team->image->id,
                'name' => $team->image->name,
                'folder' => $team->image->folder,
                'cdn_endpoint' => $team->appSetting->cdn_endpoint,
                'cloud_folder' => $team->image->cloud_folder,
            ],
//            'creators' => Creator::join('users AS user', 'creators.user_id', '=', 'user.id')
//                ->select('creators.*', 'user.name AS name')
//                ->when(Request::input('search'), function ($query, $search) {
//                    $query->where('name', 'like', "%{$search}%");
//                })
//                ->latest()
//                ->paginate(5)
//                ->withQueryString()
//                ->through(fn($creator) => [
//                    'id' => $creator->user->id,
//                    'name' => $creator->user->name,
//                ]),
            'can' => [
//                'viewTeams' => Auth::user()->can('view', Team::class),
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
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('teams')->ignore($team->id)],
            'description' => 'required|string|max:5000',
            'totalSpots' => 'required|integer|min:1',
            'teamLeader' => 'nullable|exists:users,id',
        ]);

        // Initialize the team_leader_id variable
        $team_leader_id = null;

        // Check if the teamLeader is provided and is a valid creator with a status of 1
        if (!is_null($validatedData['teamLeader'])) {
            $creator = Creator::where('user_id', $validatedData['teamLeader'])
                ->whereHas('status', function ($query) {
                    $query->where('id', 1);
                })->first();

            if (!$creator) {
                throw ValidationException::withMessages([
                    'teamLeader' => 'The selected team leader must be a valid creator with an active status.'
                ]);
            }

            $team_leader_id = $creator->id;
        }


        // Check if the user is a creator with a status of 1
//        $creator = Creator::where('user_id', $validatedData['teamLeader'])
//            ->whereHas('status', function ($query) {
//                $query->where('id', 1);
//            })->first();

//        if (!$creator) {
//            throw ValidationException::withMessages([
//                'teamLeader' => 'The selected team leader must be a valid creator with an active status.'
//            ]);
//        }
        // Update the team leader only if a valid creator is found
//        if ($creator) {
//            $team->team_leader = $creator->id;
//        } else {
//            $team->team_leader = null;
//        }

        // update the team
        $team->name = $request->name;
        $team->description = $request->description;
        $team->totalSpots = $request->totalSpots;
        $team->team_leader = $team_leader_id;
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


    private function determineRole($member, $team)
    {
        if ($member->id === $team->user_id) {
            return 'creator';
        } elseif ($member->id === $team->team_leader) {
            return 'leader';
        } elseif ($team->managers->contains('id', $member->id)) {
            return 'manager';
        } else {
            return 'member';
        }
    }



}



