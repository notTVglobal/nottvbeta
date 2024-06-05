<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Http\Resources\ShowResource;
use App\Http\Resources\TeamDetailedResource;
use App\Http\Resources\TeamMemberResource;
use App\Http\Resources\TeamResource;
use App\Models\Notification;
use App\Models\SchedulesIndex;
use App\Models\ShowCategory;
use App\Models\ShowCategorySub;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\TeamTransfer;
use App\Models\User;
use App\Models\Creator;
use App\Models\Image;
use App\Models\Show;
use App\Http\Resources\ImageResource;
use App\Rules\ZoomUrl;
use App\Traits\GetTeamUserData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request as HttpRequest;
use Inertia\Inertia;
use Mews\Purifier\Facades\Purifier;


class TeamsController extends Controller {

  use GetTeamUserData;

  public function __construct() {
    //tec21: this authorization works... but I'm having trouble
    // with the other ones below. So they are in web.php

    // Apply auth middleware only to certain methods
    $this->middleware('auth')->except(['index', 'show']);

//    $this->middleware('can:viewAny,team')->only(['index']);
//    $this->middleware('can:view,team')->only(['show']);
    $this->middleware('can:viewTeamManagePage,team')->only(['manage']);
    $this->middleware('can:update,team')->only(['edit']);
    $this->middleware('can:delete,team')->only(['destroy']);


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

  public function index() {

    $user = Auth::user();

    $canViewCreator = optional($user)->can('viewCreator', User::class);

//    function getLogo($team) {
//      $getLogo = Image::query()
//          ->where('team_id', $team->id)
//          ->pluck('name')
//          ->first();
//      if (!empty($getLogo)) {
//        $logo = $getLogo;
//      } else {
//        $logo = 'Ping.png';
//      }
//
//      return $logo;
//    }

    $component = $user ? 'Teams/Index' : 'LoggedOut/Teams/Index';

    return Inertia::render($component, [
        'teams'   => Team::with('user', 'image.appSetting', 'shows', 'teamStatus')
            ->where('is_demo', false)
            ->when(Request::input('search'), function ($query, $search) {
              $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(6)
            ->withQueryString()
            ->through(fn($team) => [
                'id'          => $team->id,
                'name'        => $team->name,
                'logo'        => $team->image->name,
                'image'       => $team->image ? (new ImageResource($team->image))->resolve() : null,
                'teamCreator' => $team->user->name,
                'status'      => $team->teamStatus,
                'slug'        => $team->slug,
                'totalShows'  => $team->shows->count(),
                'memberSpots' => $team->members()->count(),
                'totalSpots'  => $team->totalSpots,
                'can'         => [
                    'editTeam' => optional($user)->can('editTeam', $team),
                    'viewTeam' => optional($user)->can('viewTeamManagePage', $team)
                ]
            ]),
        'filters' => Request::only(['search']),
        'can'     => [
            'viewCreator' => $canViewCreator,
        ]
    ]);
  }

////////////  CREATE AND STORE
//////////////////////////////

  public function create() {
    return Inertia::render('Teams/Create');
  }


  public function store(HttpRequest $request, Team $team) {

    $validatedData = $request->validate([
        'name'           => 'unique:teams|required|max:255',
        'description'    => 'nullable|string|max:5000',
        'user_id'        => 'required|exists:users,id',
        'totalSpots'     => 'required|integer|min:1',
        'www_url'        => 'nullable|active_url',
        'instagram_name' => 'nullable|string|max:30',
        'telegram_url'   => 'nullable|active_url',
        'twitter_handle' => 'nullable|string|min:4|max:15',
    ]);

    $twitterHandle = isset($validatedData['twitter_handle']) ? str_replace('@', '', $validatedData['twitter_handle']) : null;
    $instagramName = isset($validatedData['instagram_name']) ? str_replace('@', '', $validatedData['instagram_name']) : null;


    // Find the user and their creator profile
    $user = User::with('creator')->find($request->user_id);
    if (!$user || !$user->creator) {
      return redirect()->back()->with('error', 'The selected user is not a creator.');
    }

    // Check if the creator's status is active
    if ($user->creator->status->id != 1) {
      return redirect()->back()->with('error', 'The creator does not have the required status to create a team.');
    }

    // Sanitize social media handles
    $twitterHandle = isset($validatedData['twitter_handle']) ? str_replace('@', '', $validatedData['twitter_handle']) : null;
    $instagramName = isset($validatedData['instagram_name']) ? str_replace('@', '', $validatedData['instagram_name']) : null;

    // Sanitize URLs
    $wwwUrl = isset($validatedData['www_url']) ? filter_var($validatedData['www_url'], FILTER_SANITIZE_URL) : null;
    $telegramUrl = isset($validatedData['telegram_url']) ? filter_var($validatedData['telegram_url'], FILTER_SANITIZE_URL) : null;

    // Sanitize description
    $sanitizedDescription = Purifier::clean($validatedData['description']);

    // Simply strip any unwanted tags from the title without encoding characters
    $processedName = strip_tags($validatedData['name']);


    Team::create([
        'name'                   => $processedName,
        'description'            => $sanitizedDescription,  // Use the sanitized description
        'user_id'                => $validatedData['user_id'],
      //            'team_leader' => $user->creator->id, // Set team_leader to the creator's ID
        'totalSpots'             => $validatedData['totalSpots'],
        'slug'                   => \Str::slug($validatedData['name']),
        'isBeingEditedByUser_id' => $validatedData['user_id'],  // Assuming this is intended to be the same as user_id
        'www_url'                => $wwwUrl,
        'instagram_name'         => $instagramName,
        'telegram_url'           => $telegramUrl,
        'twitter_handle'         => $twitterHandle,
    ]);

    $newTeam = Team::query()->where('name', $request->name)->firstOrFail();
    $team->id = $newTeam->id;
    $team->members()->attach($request->user_id);

    return redirect()->route('teams.manage', $newTeam)->with('message', 'Team Created Successfully');

  }

////////////  SHOW
//////////////////

  public function show(Team $team) {
    // Eagerly load the image with its appSetting relationship
    $team->load('image.appSetting', 'scheduleIndexes', 'members',);

    $user = Auth::user();

    $component = $user ? 'Teams/{$id}/Index' : 'LoggedOut/Teams/{$id}/Index';

//    $team->socialMediaLinks = [
//        'www_url'        => $team->www_url,
//        'instagram_name' => $team->instagram_name,
//        'telegram_url'   => $team->telegram_url,
//        'twitter_handle' => $team->twitter_handle,
//    ];

    return Inertia::render($component, [
        'team'         => (new TeamResource($team))->resolve(),
        'shows'        => Show::with('team', 'image.appSetting')
            ->where('team_id', $team->id)
            ->where(function ($query) {
              if (auth()->check() && auth()->user()->creator) {
                // For creators, include shows with status 9 and also shows that are new or active with specific episode statuses
                $query->where('show_status_id', 9)
                    ->orWhere(function ($q) {
                      $q->whereIn('show_status_id', [1, 2]);
//                          ->whereHas('showEpisodes', function ($q) {
//                            $q->where('show_episode_status_id', 7);
//                          });
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
                'id'              => $show->id,
                'name'            => $show->name,
                'description'     => $show->description,
                'team_id'         => $show->team_id,
                'image'           => $show->image ? (new ImageResource($show->image))->resolve() : null,
                'slug'            => $show->slug,
                'copyrightYear'   => Carbon::parse($show->created_at)->format('Y'),
                'categoryName'    => $show->getCachedCategory()->name ?? null,
                'categorySubName' => $show->getCachedSubCategory()->name ?? null,
                'statusId'        => $show->status->id,
            ]),
        'contributors' => TeamMember::where('team_id', $team->id)
            ->join('users', 'team_members.user_id', '=', 'users.id')
            ->join('creators', 'creators.user_id', '=', 'users.id') // Assuming there's a creators table linked to users
            ->whereJsonContains('creators.settings->profile_is_public', true)
            ->select('users.id', 'users.name', 'users.profile_photo_path', 'creators.slug', 'team_members.user_id', 'team_members.created_at as team_member_created_at')
            ->orderBy('team_member_created_at', 'desc') // Specify the alias for the created_at column
            ->paginate(15, ['*'], 'creator')
            ->through(fn($user) => [
                'id'                 => $user->id,
                'slug'               => $user->slug,
                'name'               => $user->name,
                'profile_photo_path' => $user->profile_photo_path,
                'profile_photo_url'  => $user->profile_photo_url,
            ]),
        'filters'      => Request::only(['team_id']),
        'can'          => $this->getUserPermissions($team),
//        'can'           => [
//            'viewTeam'   => optional($user)->can('view', $team),
//            'manageTeam' => optional($user)->can('viewTeamManagePage', $team),
//            'editTeam'   => optional($user)->can('update', $team),
//        ]
    ]);


//      $shows = Show::with('team', 'image.appSetting')
//        ->where('team_id', $team->id)
//        ->when(auth()->check() && auth()->user()->creator, function ($query) {
//          // For creators, include shows with status 9 and also shows that are new or active with specific episode statuses
//          $query->where('show_status_id', 9)
//              ->orWhere(function ($q) {
//                $q->whereIn('show_status_id', [1, 2])
//                    ->whereExists(function ($query) {
//                      $query->select(DB::raw(1))
//                          ->from('show_episodes')
//                          ->whereColumn('shows.id', 'show_episodes.show_id')
//                          ->where('show_episode_status_id', 7);
//                    });
//              });
//        }, function ($query) {
//          // For all other users, filter for shows that are new or active and have episodes with a specific status
//          $query->whereIn('show_status_id', [1, 2])
//              ->whereExists(function ($query) {
//                $query->select(DB::raw(1))
//                    ->from('show_episodes')
//                    ->whereColumn('shows.id', 'show_episodes.show_id')
//                    ->where('show_episode_status_id', 7);
//              });
//        })
//        ->latest()
//        ->paginate(6, ['*'], 'shows')
//        ->withQueryString()
//        ->through(fn($show) => [
//            'id'              => $show->id,
//            'name'            => $show->name,
//            'description'     => $show->description,
//            'team_id'         => $show->team_id,
//            'image'           => $show->image ? (new ImageResource($show->image))->resolve() : null,
//            'slug'            => $show->slug,
//            'copyrightYear'   => Carbon::parse($show->created_at)->format('Y'),
//            'categoryName'    => $show->getCachedCategory()->name ?? null,
//            'categorySubName' => $show->getCachedSubCategory()->name ?? null,
//            'statusId'        => $show->status->id,
//        ]);
//
//    Log::debug('Shows data:', ['shows' => $shows]);
//    return Inertia::render($component, [
//        'team'          => (new TeamResource($team))->resolve(),
//        'nextBroadcast' => $team->nextBroadcast,
//        'image'         => $team->image ? (new ImageResource($team->image))->resolve() : null,
//        'shows'         => $shows,
////        'creators'      => TeamMember::where('team_id', $team->id)
////            ->join('users', 'team_members.user_id', '=', 'users.id')
////            ->select('users.*', 'team_members.user_id')
////            ->latest()
////            ->paginate(5, ['*'], 'creator')
//////                ->withQueryString()
////            ->through(fn($user) => [
////                'id'                 => $user->id,
////                'name'               => $user->name,
////                'profile_photo_path' => $user->profile_photo_path,
////                'profile_photo_url'  => $user->profile_photo_url,
////            ]),
//        'filters'       => Request::only(['team_id']),
//        'can'           => [
//            'viewTeam'   => optional($user)->can('view', $team),
//            'manageTeam' => optional($user)->can('viewTeamManagePage', $team),
//            'editTeam'   => optional($user)->can('update', $team),
//        ]
//    ]);
  }

////////////  MANAGE
////////////////////

  public function manage(Team $team): \Inertia\Response {

    // Load necessary relationships
    $team->load([
        'user',
        'teamLeader',
        'managers',
        'members',
        'image.appSetting'
    ]);

    // Retrieve shows
    $shows = $this->getShows($team->id);

    // Prepare the response data using TeamDetailedResource
    return Inertia::render('Teams/{$id}/Manage', [
        'team'        => (new TeamDetailedResource($team))->resolve(),
        'shows'       => $shows,
        'filters'     => Request::only(['team_id']),
        'can'         => $this->getUserPermissions($team),
    ]);
  }


  // tec21: I am querying the database here because there is currently no
  // pivot table between creators and teams.
  protected function getTeams($userId): \Illuminate\Support\Collection {
    return TeamMember::query()->where('user_id', $userId)->pluck('team_id');
  }

  // tec21: 2024-05-17 we are replacing this with searchCreators
  protected function getContributors() {

    // Assuming Creator model is related to User and has a 'status' relationship
    // Adjust the logic as per your actual model relationships and requirements
    return Creator::join('users', 'creators.user_id', '=', 'users.id')
        ->select('creators.*', 'users.name as user_name')
        ->when(Request::input('search'), function ($query, $search) {
          return $query->where('users.name', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(3)
        ->withQueryString()
        ->through(function ($creator) {
          return [
              'id'                 => $creator->user->id,
              'name'               => $creator->user_name,
              'teams'              => $this->getTeams($creator->user->id),
              'profile_photo_path' => $creator->user->profile_photo_path,
              'profile_photo_url'  => $creator->user->profile_photo_url
            // Add other necessary fields
          ];
        });
  }

  protected function getShows($teamId) {
    $shows = Show::with(['team', 'image.appSetting', 'category', 'subCategory', 'showRunner.user'])
        ->where('team_id', $teamId)
        ->latest()
        ->paginate(5)
        ->withQueryString();

    // Convert each show into a ShowResource, automatically handling the formatting
    return ShowResource::collection($shows);
  }
//    public function manage(Team $team, Show $show)
//    {
//        function getPoster($show){
//            $getPoster = Image::query()
//                ->where('show_id', $show->id)
//                ->pluck('name')
//                ->first();
//            if(!empty($getPoster)){
//                $poster = $getPoster;
//            } else {
//                $poster = 'EBU_Colorbars.svg.png';
//            }
//            return $poster;
//        }
//
//        // check if teamCreator is not null before attempting to access its related properties
//        $teamCreatorData = [
//            'id' => null,
//            'name' => null,
//            'creator_status_id' => null,
//            'creator_status_name' => null,
//        ];
//
//        if ($team->user_id) {
//            $teamCreatorData = [
//                'id' => $team->user->id,
//                'name' => $team->user->name,
//                'creator_status_id' => optional(optional($team->user->creator)->status)->id ?? null,
//                'creator_status_name' => optional(optional($team->user->creator)->status)->status ?? null,
//                ];
//        }
//
//        // check if teamLeader is not null before attempting to access its related properties
//        $teamLeaderData = [
//            'id' => null,
//            'name' => null,
//            'creator_status_id' => null,
//            'creator_status_name' => null,
//        ];
//
//        if ($team->teamLeader) {
//            $teamLeaderData = [
//                'id' => $team->teamLeader->user->id ?? null,
//                'name' => $team->teamLeader->user->name ?? null,
//                'creator_status_id' => optional($team->teamLeader->status)->id ?? null,
//                'creator_status_name' => optional($team->teamLeader->status)->status ?? null,
//            ];
//        }
//
//        $managers = $team->managers->map(function ($manager) {
//            return [
//                'id' => $manager->id,
//                'name' => $manager->name
//            ];
//        });
//
//        // For can permissions passed to Vue below:
//
//        $userId = auth()->id(); // Get the authenticated user's id
//
//        // Managers
//        $managersIds = $team->managers->pluck('id')->toArray(); // Get only ids of managers
//        $isTeamManager = in_array($userId, $managersIds);
//
//        // Team Leader
//        $isTeamLeader = $userId == $team->team_leader;
//
//        // Team Owner
//        $isTeamOwner = $userId == $team->user_id;
//
//        // Team Members
//        // Assuming members are a relation in your Team model
//        $membersIds = $team->members->pluck('id')->toArray(); // Get only ids of members
//        $isTeamMember = in_array($userId, $membersIds);
//
//        // tec21: I am querying the database here because there is currently no
//        // pivot table between creators and teams.
//        //
//        function getTeams($userId) {
//            $teams = TeamMember::query()->where('user_id', $userId)->pluck('team_id');
//            return $teams;
//}
//
//        function showCategoryName($id) {
//            $showCategoryName = ShowCategory::query()->where('id', $id)->pluck('name');
//            return $showCategoryName->toArray();
//        }
//
//        return Inertia::render('Teams/{$id}/Manage', [
//            'team' => $team,
//            'image' => [
//                'id' => $team->image->id,
//                'name' => $team->image->name,
//                'folder' => $team->image->folder,
//                'cdn_endpoint' => $team->appSetting->cdn_endpoint,
//                'cloud_folder' => $team->image->cloud_folder,
//            ],
//            'teamCreator' => $teamCreatorData,
//            'teamLeader' => $teamLeaderData,
//
//        // tec21: 'members' will need to be returned with pagination and searchable.
//            // this will be for larger teams. But, at that point, all of this will
//            // probably be re-written.
//            //
//            'members' => $team->members,
//            'managers' => $managers,
////            'members' => TeamMember::join('users AS user', 'team_members.user_id', '=', 'user.id')
////                ->select('team_members.*', 'user.name AS name')
////                ->when(Request::input('search'), function ($query, $search) {
////                    $query->where('name', 'like', "%{$search}%");
////                })
////                ->latest()
////                ->paginate(3)
////                ->withQueryString()
////                ->through(fn($member) => [
////                    'id' => $member->user_id,
//////                    'name' => $member->user->name,
//////                    'profile_photo_url' => $member->user->profile_photo_url,
//////                    'teams' => getTeams($member->user->id),
////                ]),
//
//            'creators' => Creator::join('users AS user', 'creators.user_id', '=', 'user.id')
//                ->select('creators.*', 'user.name AS name')
//                ->when(Request::input('search'), function ($query, $search) {
//                    $query->where('name', 'like', "%{$search}%");
//                })
//                ->latest()
//                ->paginate(3)
//                ->withQueryString()
//                ->through(fn($creator) => [
//                    'id' => $creator->user_id,
//                    'name' => $creator->user->name,
//                    'profile_photo_url' => $creator->user->profile_photo_url,
//                    'profile_photo_path' => $creator->user->profile_photo_path,
//                    'teams' => getTeams($creator->user->id),
//                ]),
//
////            'shows' => DB::table('shows')->where('team_id', $team->id)
//            'shows' => Show::with('team', 'image')->where('team_id', $team->id)
//                ->latest()
//                ->paginate(5)
//                ->withQueryString()
//                ->through(fn($show) => [
//                    'id' => $show->id,
//                    'name' => $show->name,
//                    'description' => $show->description,
//                    'showRunnerName' => User::query()->where('id', $show->user_id)->pluck('name')->first(),
//                    'team_id' => $show->team_id,
//                    'image' => [
//                        'id' => $show->image->id,
//                        'name' => $show->image->name,
//                        'folder' => $show->image->folder,
//                        'cdn_endpoint' => $show->appSetting->cdn_endpoint,
//                        'cloud_folder' => $show->image->cloud_folder,
//                    ],
//                    'slug' => $show->slug,
//                    'notes' => $show->notes,
//                    'copyrightYear' => Carbon::parse($show->created_at)->format('Y'),
//                    'categoryName' => showCategoryName($show->show_category_id),
//                ]),
//            'filters' => Request::only(['team_id']),
//            'creatorFilters' => Request::only(['search']),
//            'can' => [
//                'editTeam' => auth()->user()->can('update', $team),
//                'manageTeam' => auth()->user()->can('manage', $team),
//                'transferTeam' => auth()->user()->can('transfer', $team),
//                'isTeamOwner' => $isTeamOwner,
//                'isTeamLeader' => $isTeamLeader,
//                'isTeamManager' => $isTeamManager,
//                'isTeamMember' => $isTeamMember,
//                'isAdmin' => auth()->user()->isAdmin,
//        ]
//        ]);
//    }

////////////  SEARCH CREATORS (add Team Member)
//////////////////////////////////////////////

  public function searchCreators(HttpRequest $request): \Illuminate\Http\JsonResponse {
    $search = $request->input('search');
    $creators = Creator::join('users', 'creators.user_id', '=', 'users.id')
        ->select('creators.*', 'users.name as user_name')
        ->when($search, function ($query, $search) {
          return $query->where('users.name', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(3);

    $creators->getCollection()->transform(function ($creator) {
      return [
          'id'                 => $creator->user->id,
          'name'               => $creator->user_name,
          'teams'              => $this->getTeams($creator->user->id),
          'profile_photo_path' => $creator->user->profile_photo_path,
          'profile_photo_url'  => $creator->user->profile_photo_url
        // Add other necessary fields
      ];
    });

    return response()->json($creators);
  }


////////////  TRANSFER
//////////////////////

  public function sendTransferRequest(Team $team, HttpRequest $request) {
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
        'team_id'                   => $team->id,
        'former_owner_user_id'      => $formerOwnerUserId, // ID of the former owner
        'new_owner_user_id'         => $newOwnerUserId, // ID of the new owner
        'blockchain_id'             => $blockchainId, // ID of the associated blockchain (if applicable)
        'blockchain_transaction_id' => $blockchainTransactionId, // ID of the blockchain transaction (if applicable)
        'transfer_status_id'        => $transferStatusId, // ID of the initial transfer status (e.g., 'pending')
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
    $notification->url = '/teams/' . $team->slug;
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

  public function edit(Team $team) {
    // lock the team from being edited by more than 1 person.
    DB::table('users')->where('id', Auth::user()->id)->update([
        'isEditingTeam_id' => $team->id,
    ]);
    DB::table('teams')->where('id', $team->id)->update([
        'isBeingEditedByUser_id' => Auth::user()->id,
    ]);

    // check if teamLeader is not null before attempting to access its related properties
    $teamLeaderData = [
        'id'                  => null,
        'name'                => null,
        'creator_status_id'   => null,
        'creator_status_name' => null,
    ];

    if ($team->teamLeader) {
      $teamLeaderData = [
          'id'                  => $team->teamLeader->user->id ?? null,
          'name'                => $team->teamLeader->user->name ?? null,
          'creator_status_id'   => $team->user?->creator?->status->id ?? null,
          'creator_status_name' => $team->teamLeader?->status ?? null,
      ];
    }

    // check if teamCreator is not null before attempting to access its related properties
    $teamCreatorData = [
        'id'                  => null,
        'name'                => null,
        'creator_status_id'   => null,
        'creator_status_name' => null,
    ];

    if ($team->user_id) {
      $teamCreatorData = [
          'id'                  => $team->user->id,
          'name'                => $team->user->name,
          'creator_status_id'   => $team->user?->creator?->status->id ?? null,
          'creator_status_name' => $team->teamLeader?->status ?? null,
      ];
    }

    // Get all active team members
    $activeMembers = $team->members()->wherePivot('active', 1)->get();

    // Prepare the list of possible team leaders
    $possibleTeamLeaders = $activeMembers->map(function ($member) use ($team) {
      return [
          'id'   => $member->id,
          'name' => $member->name,
          'role' => $this->determineRole($member, $team)
      ];
    });

    $socialMediaLinks = [
        'www_url'        => $team->www_url,
        'instagram_name' => $team->instagram_name,
        'telegram_url'   => $team->telegram_url,
        'twitter_handle' => $team->twitter_handle,
    ];

    return Inertia::render('Teams/{$id}/Edit', [
        'team'                => [
            'id'               => $team->id,
            'name'             => $team->name,
            'description'      => $team->description,
            'slug'             => $team->slug,
            'totalSpots'       => $team->totalSpots,
            'team_status_id'   => $team->teamStatus->id,
            'team_status_name' => $team->teamStatus->status,
            'socialMediaLinks' => $socialMediaLinks,
            'members'          => $team->members,
        ],
        'teamCreator'         => $teamCreatorData,
        'teamLeader'          => $teamLeaderData,
        'possibleTeamLeaders' => $possibleTeamLeaders,
        'image'               => [
            'id'           => $team->image->id,
            'name'         => $team->image->name,
            'folder'       => $team->image->folder,
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
        'can'                 => [
//                'viewTeams' => Auth::user()->can('view', Team::class),
            'editTeam' => Auth::user()->can('edit', Team::class),
//                'viewCreator' => Auth::user()->can('viewCreator', User::class),
        ]
    ]);
  }


////////////  UPDATE
////////////////////

  /**
   * @throws ValidationException
   */
  public function update(HttpRequest $request, Team $team) {

    if ($request->totalSpots < $team->members()->count()) {
      return redirect(route('teams.edit', [$team->slug]))->with('message', 'You already have the maximum. Please remove team members or increase the maximum # of team members.');
    }

    // validate the request
    $validatedData = $request->validate([
        'name'           => ['required', 'string', 'max:255', Rule::unique('teams')->ignore($team->id)],
        'description'    => 'nullable|string|max:5000',
        'totalSpots'     => 'required|integer|min:1',
        'teamLeader'     => 'nullable|exists:users,id',
        'www_url'        => 'nullable|active_url',
        'instagram_name' => 'nullable|string|max:30',
        'telegram_url'   => 'nullable|active_url',
        'twitter_handle' => 'nullable|string|min:4|max:15',
    ]);

    // Initialize the team_leader_id variable
    $team_leader_id = null;

    // Sanitize description
    $sanitizedDescription = Purifier::clean($validatedData['description']);


    // Sanitize social media handles
    $twitterHandle = isset($validatedData['twitter_handle']) ? str_replace('@', '', $validatedData['twitter_handle']) : null;
    $instagramName = isset($validatedData['instagram_name']) ? str_replace('@', '', $validatedData['instagram_name']) : null;

    // Sanitize URLs
    $wwwUrl = isset($validatedData['www_url']) ? filter_var($validatedData['www_url'], FILTER_SANITIZE_URL) : null;
    $telegramUrl = isset($validatedData['telegram_url']) ? filter_var($validatedData['telegram_url'], FILTER_SANITIZE_URL) : null;

    // Sanitize and process name
    $processedName = strip_tags($validatedData['name']);

    // Sanitize social media handles
//    $twitterHandle = isset($validatedData['twitter_handle']) ? str_replace('@', '', $validatedData['twitter_handle']) : null;
//    $instagramName = isset($validatedData['instagram_name']) ? str_replace('@', '', $validatedData['instagram_name']) : null;

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

    // update the team
    $team->update([
        'name'           => $processedName,
        'description'    => $sanitizedDescription,  // Use the sanitized description
        'totalSpots'     => $validatedData['totalSpots'],
        'team_leader'    => $team_leader_id,  // Optional, handle absence
        'slug'           => \Str::slug($validatedData['name']),  // Creating slug from the validated name
        'www_url'        => $wwwUrl,
        'instagram_name' => $instagramName,
        'telegram_url'   => $telegramUrl,
        'twitter_handle' => $twitterHandle
    ]);

    $teamSlug = $team->slug;

    // redirect
    return redirect(route('teams.manage', [$teamSlug]))->with('message', 'Team Updated Successfully');

  }

  public function savePublicMessage(HttpRequest $request, Team $team): \Illuminate\Http\JsonResponse {

    // Log the incoming request data
    Log::debug('Incoming request data', $request->all());

    // Validate the input
    $validatedData = $request->validate([
        'public_message'         => 'nullable|string|max:440',  // Validate against the requirements
        'next_broadcast_details' => 'nullable|json',    // Validate that it is a valid JSON object
        'schedule_index_id'      => 'required_with:next_broadcast_details|exists:schedules_indexes,id', // Validate that the schedule_index_id exists
    ]);

    if (isset($validatedData['schedule_index_id'])) {
      Log::debug('get it.');
      $scheduleIndex = SchedulesIndex::find($validatedData['schedule_index_id']);
      if ($scheduleIndex) {
        Log::debug('got it.');
        // Decode the next_broadcast_details JSON string to an array
        $nextBroadcastDetails = json_decode($validatedData['next_broadcast_details'], true);

        // Ensure next_broadcast_details is decoded properly
        if (json_last_error() !== JSON_ERROR_NONE) {
          return response()->json(['errors' => ['next_broadcast_details' => 'Invalid JSON format']], 422);
        }

        // Ensure next_broadcast_details is an array
        if (!is_array($nextBroadcastDetails)) {
          Log::debug('not an array.');
          $nextBroadcastDetails = [];
        }

        // Validate each detail in the next_broadcast_details
        foreach ($nextBroadcastDetails as $detail) {
          if (is_array($detail) && array_key_exists('zoomLink', $detail)) {
            $validationResults = Validator::make($detail, [
                'zoomLink' => ['nullable', 'url']  // Standard URL validation
            ]);

            // Check if the nested validation passes
            if ($validationResults->fails()) {
              return response()->json(['errors' => $validationResults->errors()], 422);
            }
          }
          Log::debug('good.');
        }

        $currentDetails = $scheduleIndex->next_broadcast_details ? json_decode($scheduleIndex->next_broadcast_details, true) : [];

        // Ensure currentDetails is an array
        if (!is_array($currentDetails)) {
          $currentDetails = [];
        }

        // Merge new details with current details
        foreach ($nextBroadcastDetails as $detail) {
          if (is_array($detail) && isset($detail['zoomLink'])) {
            // Remove any existing zoomLink entry
            $currentDetails = array_filter($currentDetails, fn($d) => !isset($d['zoomLink']));
            // Add the new zoomLink entry
            $currentDetails[] = $detail;
          }
        }

        // Save the updated JSON details
        $scheduleIndex->next_broadcast_details = json_encode($currentDetails);
        $scheduleIndex->save();

        // Log the saved state
        Log::info('Saved next_broadcast_details', ['next_broadcast_details' => $scheduleIndex->next_broadcast_details]);
      }
    }

    // Save the public message
    $sanitizedMessage = Purifier::clean($validatedData['public_message'], [
        'HTML.Allowed'          => 'p,br,b,u,i,strong,em,sub,sup', // Allow subscript and superscript tags
        'CSS.AllowedProperties' => [] // Specify allowed CSS properties, empty array means none allowed
    ]);

    $team->public_message = $sanitizedMessage;
    $team->save();

    // Return a successful response back to the client
    return response()->json([
        'message'        => 'Public message and broadcast details updated successfully.',
        'public_message' => $validatedData['public_message']
    ]);
  }




////////////  DESTROY
/////////////////////

  public function destroy(Team $team) {
    $team->delete();
    sleep(1);

    return redirect()->route('teams')->with('message', 'Team Deleted Successfully');
  }


  private function determineRole($member, $team) {
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



