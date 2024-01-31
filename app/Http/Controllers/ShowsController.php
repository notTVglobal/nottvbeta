<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Show;
use App\Models\ShowCategory;
use App\Models\ShowCategorySub;
use App\Models\ShowEpisode;
use App\Models\ShowStatus;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\AppSetting;
use App\Models\Video;
use http\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ShowsController extends Controller {

  private string $formattedReleaseDateTime;
  private string $formattedScheduledDateTime;

  public function __construct() {

//        $this->middleware('can:viewAny,show')->only(['index']);
    $this->middleware('can:viewShowManagePage,show')->only(['manage']);
    // tec21: this policy isn't working vvv
//        $this->middleware('can:editShowManagePage,show')->only(['changeEpisodeStatus']);
    $this->middleware('can:edit,show')->only(['edit']);
//        $this->middleware('can:create,show')->only(['store']);
    $this->middleware('can:createEpisode,show')->only(['createEpisode']);
    $this->middleware('can:viewEpisodeManagePage,show')->only(['manageEpisode']);

    $this->formattedReleaseDateTime = '';
    $this->formattedScheduledDateTime = '';

  }

////////////  INDEX
///////////////////

  public function index(): \Inertia\Response {
    return Inertia::render('Shows/Index', [
        'shows'          => $this->fetchShows(),
        'newestEpisodes' => $this->fetchNewestEpisodes(),
        'comingSoon'     => $this->fetchComingSoon(),
        'filters'        => Request::only(['search']),
        'can'            => [
            'viewShows'   => Auth::user()->can('view', Show::class),
            'viewCreator' => Auth::user()->can('viewCreator', User::class),
        ]
    ]);
  }

  private function fetchShows(): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
    return Show::with(['team', 'user', 'image', 'showEpisodes', 'status', 'category', 'subCategory', 'appSetting'])
        ->when(Request::input('search'), function ($query, $search) {
          $query->where('name', 'like', "%{$search}%");
        })
        ->whereHas('showEpisodes', function ($query) {
          $query->where('show_episode_status_id', 7); // Filter for episodes that are published
        })
        ->where(function ($query) {
          $query->where('show_status_id', 1) // Filter for shows that are new
          ->orWhere('show_status_id', 2); // or active
        })
        ->latest()
        ->paginate(6, ['*'], 'shows')
        ->withQueryString()
        ->through(fn($show) => $this->transformShow($show));
  }

  private function transformShow($show): array {
    return [
        'id'                 => $show->id,
        'name'               => $show->name,
        'description'        => $show->description,
        'team_id'            => $show->team_id,
        'teamName'           => $show->team->name ?? null,
        'teamSlug'           => $show->team->slug ?? null,
        'showRunnerId'       => $show->user_id,
        'showRunnerName'     => $show->user->name ?? null,
        'image'              => $this->transformImage($show->image, $show->appSetting),
        'slug'               => $show->slug,
        'totalEpisodes'      => $show->showEpisodes->count(),
        'status'             => $show->status->name ?? null,
        'statusId'           => $show->status->id ?? null,
        'copyrightYear'      => $show->created_at->format('Y'),
        'first_release_year' => $show->first_release_year,
        'last_release_year'  => $show->last_release_year,
        'category'           => $show->category ? $show->category->toArray() : null,
        'subCategory'        => $show->subCategory ? $show->subCategory->toArray() : null,
        'can'                => [
            'editShow' => Auth::user()->can('editShow', $show),
            'viewShow' => Auth::user()->can('viewShowManagePage', $show)
        ]
    ];
  }

  private function fetchNewestEpisodes(): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
    return ShowEpisode::with('show', 'image', 'show.category', 'show.subCategory', 'appSetting')
        ->where('show_episode_status_id', 7) // Assuming 7 is the status ID for published episodes
        ->whereHas('show', function ($query) {
          // Filter shows with status of 1 or 2 (New or Active)
          $query->where('show_status_id', 1)->orWhere('show_status_id', 2);
        })
        ->orderBy('release_dateTime', 'desc')
        ->paginate(5, ['*'], 'episodes')
        ->withQueryString()
        ->through(fn($showEpisode) => $this->transformShowEpisode($showEpisode));
  }

  private function transformShowEpisode($showEpisode): array {
    return [
        'id'              => $showEpisode->id,
        'name'            => $showEpisode->name,
        'description'     => \Str::limit($showEpisode->description, 100, '...'),
        'image'           => $this->transformImage($showEpisode->image, $showEpisode->appSetting),
        'slug'            => $showEpisode->slug,
        'showName'        => $showEpisode->show->name,
        'showSlug'        => $showEpisode->show->slug,
//        'releaseDate' => $showEpisode->release_dateTime,
        'category'        => [
            'name'        => $showEpisode->show->category->name,
            'description' => $showEpisode->show->category->description,
        ],
        'subCategory'     => [
            'name'        => $showEpisode->show->subCategory->name,
            'description' => $showEpisode->show->subCategory->description,
        ],
        'releaseDateTime' => $this->formatReleaseDate($showEpisode->release_dateTime),
    ];
  }

  private function fetchComingSoon(): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
    return Show::with(['team', 'user', 'image', 'showEpisodes', 'status', 'category', 'subCategory', 'appSetting'])
        ->whereHas('showEpisodes', function ($query) {
          $query->where('show_episode_status_id', 6); // Assuming 6 is the status ID for coming soon episodes
        })
        ->where(function ($query) {
          $query->where('show_status_id', 1) // Filter for shows that are new
          ->orWhere('show_status_id', 2); // or active
        })
        ->latest()
        ->paginate(3, ['*'], 'comingSoon')
        ->withQueryString()
        ->through(fn($show) => $this->transformShow($show));
  }

  private function transformImage($image, $appSetting): array {
    return [
        'id'           => $image->id,
        'name'         => $image->name,
        'folder'       => $image->folder,
        'cdn_endpoint' => $appSetting->cdn_endpoint,
        'cloud_folder' => $image->cloud_folder,
    ];
  }

  // release dateTime
  private function formatReleaseDate($date): string {
    if (is_null($date)) {
      // Handle the null case. You can return an empty string or a default value.
      return ''; // or return some default value or message
    }
    $user = Auth::user();
    $userTimezone = $user->timezone;

//    $releaseDateTimeString = $date;

    // Create a Carbon instance from the datetime string
    $releaseDateTime = \Carbon\Carbon::parse($date, 'UTC');

    // Convert to the user's timezone
    $releaseDateTime->setTimezone($userTimezone);

    return $releaseDateTime->toIso8601String();
  }

////////////  CREATE AND STORE
//////////////////////////////

  public function create() {
    $categories = ShowCategory::all();
    $sub_categories = ShowCategorySub::all();
    $userId = Auth::id(); // Store the user ID in a variable to avoid multiple calls

    $teams = Team::with(['teamStatus:id,status'])
        ->whereIn('team_status_id', [1, 2, 3, 4, 7, 8, 11])
        ->where(function ($query) use ($userId) {
          // Directly including the user-specific conditions in the query
          $query->where('user_id', $userId)
              ->orWhere('team_leader', $userId)
              ->orWhereHas('managers', function ($subQuery) use ($userId) {
                $subQuery->where('user_id', $userId);
              });
        })
        ->get()
        ->map(function ($team) use ($userId) {
          return [
              'id'     => $team->id,
              'name'   => $team->name,
              'slug'   => $team->slug,
              'status' => [
                  'id'     => $team->teamStatus->id,
                  'status' => $team->teamStatus->status,
              ],
              'can'    => [
                  'manageTeam' => Auth::user()->can('manage', $team)
              ]
          ];
        });

    return Inertia::render('Shows/Create', [
        'teams'         => $teams,
        'userId'        => $userId,
        'categories'    => $categories,
        'subCategories' => $sub_categories,
    ]);

  }

  public function store(HttpRequest $request) {
    $validatedData = $request->validate([
        'name'           => 'unique:shows|required|string|max:255',
//            'name' => ['required', 'string', 'max:255', Rule::unique('shows')->ignore($show->id)],
        'description'    => 'required|string',
        'user_id'        => 'required',
        'team_id'        => 'required|exists:teams,id',
        'category'       => 'required',
        'sub_category'   => 'nullable',
        'instagram_name' => 'nullable|string|max:30',
        'telegram_url'   => 'nullable|active_url',
        'twitter_handle' => 'nullable|string|min:4|max:15',
        'notes'          => 'nullable|string|max:1024',
    ],
        ['team_id' => 'A team must be selected.']);
    $teamId = $validatedData['team_id'];
    // Retrieve the team with the team_id and check its status
    $team = Team::find($teamId);
    // Check if the team's status is 1, 2, 3, 4, or 11
    if (!$team || !in_array($team->team_status_id, [1, 2, 3, 4, 11])) {
      return back()->withErrors([
          'team_id' => __('The selected team is invalid or does not have a valid status.'),
      ])->withInput();
    }

    // If the team's status is 1 or 11, change it to 2 (active)
    if (in_array($team->team_status_id, [1, 11])) {
      $team->team_status_id = 2;
      $team->save();
    }

    Show::create([
        'name'                   => $request->name,
        'description'            => $request->description,
        'user_id'                => $request->user_id,
        'team_id'                => $request->team_id,
        'show_category_id'       => $request->category,
        'show_category_sub_id'   => $request->sub_category,
        'slug'                   => \Str::slug($request->name),
        'www_url'                => $request->www_url,
        'instagram_name'         => $request->instagram_name,
        'telegram_url'           => $request->telegram_url,
        'twitter_handle'         => $request->twitter_handle,
        'notes'                  => $request->notes,
        'isBeingEditedByUser_id' => $request->user_id,
        'first_release_year'     => \Carbon\Carbon::now()->format('Y'),
    ]);

    $show = Show::query()->where('name', $request->name)->firstOrFail();

    return redirect()->route('shows.manage', $show)->with('success', 'Show Created Successfully');
  }

////////////  SHOW
//////////////////

  public function show(Show $show) {

    $latestEpisodeWithVideo = $this->fetchLatestEpisodeWithVideo($show);
    $latestEpisodeWithVideoUrl = $this->fetchLatestEpisodeWithVideoUrl($show);

    return Inertia::render('Shows/{$id}/Index', [
        'show'     => $this->transformShowData($show, $latestEpisodeWithVideo, $latestEpisodeWithVideoUrl),
        'episodes' => $this->fetchEpisodes($show),
        'creators' => $this->fetchCreators($show->team_id),
        'team'     => $this->transformTeamData($show->team),
        'can'      => $this->getPermissions($show),
    ]);
  }

  private function fetchLatestEpisodeWithVideo(Show $show) {
    return $show->showEpisodes()
        ->whereNotNull('video_id')
        ->with('image')
        ->latest()
        ->first();
  }

  private function fetchLatestEpisodeWithVideoUrl(Show $show) {
    return $show->showEpisodes()
        ->whereHas('video', function ($query) {
          $query->whereNotNull('video_url');
        })
        ->with('image')
        ->latest()
        ->first();
  }

  private function transformShowData(Show $show, $latestEpisodeWithVideo, $latestEpisodeWithVideoUrl) {
    return [
        'name'                  => $show->name,
        'slug'                  => $show->slug,
        'description'           => $show->description,
        'showRunner'            => $show->user->name,
        'image'                 => $this->transformImage($show->image, $show->appSetting),
        'category'              => $show->category ? $show->category->toArray() : null,
        'subCategory'           => $show->subCategory ? $show->subCategory->toArray() : null,
        'firstPlayVideo'        => $this->transformEpisodeData($latestEpisodeWithVideo),
        'firstPlayVideoFromUrl' => $this->transformEpisodeData($latestEpisodeWithVideoUrl, true),
        'copyrightYear'         => $show->created_at->format('Y'),
        'first_release_year'    => $show->first_release_year ?? null,
        'last_release_year'     => $show->last_release_year ?? null,
        'www_url'               => $show->www_url,
        'instagram_name'        => $show->instagram_name,
        'telegram_url'          => $show->telegram_url,
        'twitter_handle'        => $show->twitter_handle,
    ];
  }

  private function transformEpisodeData($episode, $isUrl = false) {
    if (!$episode) {
      return [];
    }

    $episodeData = [
        'name'        => $episode->name ?? '',
        'slug'        => $episode->slug ?? '',
        'description' => $episode->description ?? '',
        'image'       => $this->transformImage($episode->image, $episode->appSetting),
    ];

    if ($isUrl) {
      $episodeData['video_url'] = $episode->video->video_url ?? '';
      $episodeData['type'] = $episode->video->type ?? '';
    } else {
      $episodeData['file_name'] = $episode->video->file_name ?? '';
      $episodeData['cdn_endpoint'] = $episode->appSetting->cdn_endpoint ?? '';
      $episodeData['folder'] = $episode->video->folder ?? '';
      $episodeData['cloud_folder'] = $episode->video->cloud_folder ?? '';
      $episodeData['upload_status'] = $episode->video->upload_status ?? '';
      $episodeData['storage_location'] = $episode->video->storage_location ?? '';
    }

    return $episodeData;
  }

  private function fetchEpisodes(Show $show) {
    return ShowEpisode::with('image', 'show', 'showEpisodeStatus')
        ->where('show_id', $show->id)
        ->when(Request::input('search'), function ($query, $search) {
          $query->where('name', 'like', "%{$search}%");
        })
        ->where(function ($query) {
          // Filter episodes with episodeStatus of 7 (Published)
          $query->where('show_episode_status_id', 7);
        })
        ->latest()
        ->paginate(8, ['*'], 'episodes')
        ->withQueryString()
        ->through(fn($showEpisode) => $this->transformShowEpisode($showEpisode));
  }

  private function fetchCreators($teamId) {
    return TeamMember::where('team_id', $teamId)
        ->join('users', 'team_members.user_id', '=', 'users.id')
        ->select('users.*', 'team_members.user_id as team_member_id')
        ->latest()
        ->paginate(10, ['*'], 'creators')
        ->withQueryString()
        ->through(fn($teamMember) => $this->transformTeamMember($teamMember));
  }

  private function transformTeamMember($teamMember) {
    return [
        'id'                 => $teamMember->id,
        'name'               => $teamMember->name,
//        'role' => $teamMember->role, // TODO: create 'role' as a field in the TeamMember model
        'profile_photo_path' => $teamMember->profile_photo_path,
      // Include additional fields as needed
    ];
  }

  private function transformTeamData($team) {
    return [
        'name'   => $team->name,
        'slug'   => $team->slug,
        'poster' => $team->image ? $this->transformImage($team->image, $team->appSetting) : null,
      // Include additional team-related fields as needed
    ];
  }

  private function getPermissions(Show $show) {
    return [
        'manageShow'  => Auth::user()->can('manage', $show),
        'editShow'    => Auth::user()->can('edit', $show),
        'viewCreator' => Auth::user()->can('viewCreator', User::class),
    ];
  }

////////////  MANAGE
////////////////////

  public function manage(Show $show) {
    $episodeStatuses = DB::table('show_episode_statuses')->get()->toArray();
    $filteredStatuses = array_slice($episodeStatuses, 0, -2);

    return Inertia::render('Shows/{$id}/Manage', [
        'show'            => [
            'name'          => $show->name,
            'description'   => $show->description,
            'showRunner'    => $show->user->name,
            'slug'          => $show->slug,
            'image'         => $this->transformImage($show->image, $show->appSetting),
            'copyrightYear' => $show->created_at->format('Y'),
            'category'      => [
                'id'          => $show->category->id,
                'name'        => $show->category->name,
                'description' => $show->category->description,
            ],
            'subCategory'   => [
                'id'          => $show->subCategory->id,
                'name'        => $show->subCategory->name,
                'description' => $show->subCategory->description,
            ],
            'notes'         => $show->notes,
        ],
        'team'            => [
            'name' => $show->team->name,
            'slug' => $show->team->slug,
        ],
        'episodes'        => ShowEpisode::with('image', 'show', 'showEpisodeStatus')
            ->where('show_id', $show->id)
            ->when(Request::input('search'), function ($query, $search) {
              $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(5, ['*'], 'shows')
            ->withQueryString()
            ->through(fn($showEpisode) => [
                'id'              => $showEpisode->id,
                'ulid'            => $showEpisode->ulid,
                'name'            => $showEpisode->name,
                'image'           => $this->transformImage($showEpisode->image, $showEpisode->appSetting),
                'slug'            => $showEpisode->slug,
                'episodeNumber'   => $showEpisode->episode_number,
                'notes'           => $showEpisode->notes,
                'episodeStatus'   => $showEpisode->showEpisodeStatus->name,
                'episodeStatusId' => $showEpisode->showEpisodeStatus->id,
                'isPublished'     => $showEpisode->isPublished,
            ]),
        'episodeStatuses' => $filteredStatuses,
        'can'             => [
            'editShow'         => auth()->user()->can('edit', $show),
            'manageShow'       => auth()->user()->can('manage', $show),
            'createEpisode'    => auth()->user()->can('createEpisode', $show),
            'createAssignment' => auth()->user()->can('editShow', $show),
            'goLive'           => auth()->user()->can('goLive', $show),
        ]
    ]);
  }

////////////  EDIT
//////////////////

  public function edit(Show $show) {
    DB::table('users')->where('id', Auth::user()->id)->update([
        'isEditingShow_id' => $show->id,
    ]);

    DB::table('shows')->where('id', $show->id)->update([
        'isBeingEditedByUser_id' => Auth::user()->id,
    ]);

    $categories = ShowCategory::with('subCategories')->get(); // Fetch all categories with their sub-categories
    $statuses = ShowStatus::all();
    $show->load('image', 'appSetting', 'category', 'subCategory'); // Eager load necessary relationships

    return Inertia::render('Shows/{$id}/Edit', [
        'show'        => $show,
        'team'        => Team::query()->where('id', $show->team_id)->firstOrFail(),
        'showRunner'  => User::query()->where('id', $show->user_id)->pluck('id', 'name')->firstOrFail(),
        'image'       => $this->transformImage($show->image, $show->appSetting),
        'categories'  => $categories,
        'statuses'    => $statuses,
        'category'    => $show->category ? $show->category->toArray() : null,
        'subCategory' => $show->subCategory ? $show->subCategory->toArray() : null,
        'can'         => [
            'editShow' => Auth::user()->can('edit', $show),
        ],
    ]);
  }

////////////  UPDATE
////////////////////

  public function update(HttpRequest $request, Show $show) {
    // validate the request
    $request->validate([
        'name'           => ['required', 'string', 'max:255', Rule::unique('shows')->ignore($show->id)],
        'description'    => 'required',
        'release_date'   => 'date|after:tomorrow',
        'category'       => 'required',
        'sub_category'   => 'nullable',
        'www_url'        => 'nullable|active_url',
        'instagram_name' => 'nullable|string|max:30',
        'telegram_url'   => 'nullable|active_url',
        'twitter_handle' => 'nullable|string|min:4|max:15',
        'notes'          => 'nullable|string|max:1024',
        'status'         => 'required|integer|exists:show_statuses,id',
    ], [
        'status.exists'      => 'The selected status is invalid.',
        'release_date.after' => 'The release date must be at least 24 hours in the future.',
    ]);

    // update the show
    $show->name = $request->name;
    $show->description = $request->description;
    $show->show_category_id = $request->category;
    $show->show_category_sub_id = $request->sub_category;
    $show->slug = \Str::slug($request->name);
    $show->www_url = $request->www_url;
    $show->instagram_name = $request->instagram_name;
    $show->telegram_url = $request->telegram_url;
    $show->twitter_handle = $request->twitter_handle;
    $show->notes = htmlentities($request->notes);
    $show->status_id = $request->status;
    $show->save();
    sleep(1);

    // gather the data needed to render the Manage page
    // this is all redundant. It's all contained in the
    // teams.manage route above. But I (tec21) don't know
    // how to simplify this *frustrated*.

    // redirect
    return redirect(route('shows.manage', [$show->slug]))->with('success', 'Show Updated Successfully');

//        return Inertia::render('Shows/{$id}/Manage', [
//            // responses need to be limited to only
//            // the information required with ->only()
//            // https://inertiajs.com/responses
//            'show' => $show,
//            'posterName' => Image::query()->where('id', $show->image_id)->pluck('name')->firstOrFail(),
//            'team' => Team::query()->where('id', $show->team_id)->firstOrFail(),
//            'showRunnerName' => User::query()->where('id', $show->user_id)->pluck('name')->firstOrFail(),
//        ])->with('message', 'Show Updated Successfully');
  }

  public function updateNotes(HttpRequest $request) {
    // get the show
    $id = $request->showId;
    $show = Show::find($id);

    // validate the request
    $request->validate([
        'notes' => 'nullable|string|max:1024',
    ]);

    // update the show notes
    $show->notes = $request->notes;
    $show->save();
    sleep(1);

    return $show;
  }

////////////  DESTROY
/////////////////////

  public function destroy(Show $show) {
    $show->delete();
    sleep(1);

    // redirect
    return redirect()->route('shows')->with('message', 'Show Deleted Successfully');
  }

  ////////////////////////////////////////////////////////////////////////////////////
  ///                                EPISODES                                     ///
  ////////////////////////////////////////////////////////////////////////////////////

////////////  CREATE EPISODE
////////////////////////////
///
/// tec21: this might be better
/// in the ShowEpisodes Controller.

  public function createEpisode(Show $show) {

    return Inertia::render('Shows/{$id}/Episodes/Create', [
        'show' => [
            'name'             => $show->name,
            'id'               => $show->id,
            'slug'             => $show->slug,
            'showRunner'       => $show->user->name,
            'image'            => $this->transformImage($show->image, $show->appSetting),
            'showCategoryName' => $show->showCategory->name,
            'categorySubName'  => $show->showCategorySub->name,
        ],
        'team' => Team::query()->where('id', $show->team_id)->first(),
        'user' => [
            'id' => auth()->user()->id,
        ],
    ]);
  }

////////////  MANAGE EPISODE
////////////////////////////
///
/// tec21: this might be better
/// in the ShowEpisodes Controller.

  public function manageEpisode(Show $show, ShowEpisode $showEpisode) {

    // convert release dateTime to user's timezone
    if ($showEpisode->release_dateTime) {
      $this->formattedReleaseDateTime = $this->convertTimeToUserTime($showEpisode->release_dateTime);
    }

    // convert scheduled_release dateTime to user's timezone
    if ($showEpisode->scheduled_release_dateTime) {
      $this->formattedScheduledDateTime = $this->convertTimeToUserTime($showEpisode->scheduled_release_dateTime);
    }

    $videoForEpisode = Video::where('show_episodes_id', $showEpisode->id)->first();

    return Inertia::render('Shows/{$id}/Episodes/{$id}/Manage', [
        'show'              => [
            'name'          => $show->name,
            'slug'          => $show->slug,
            'showRunner'    => $show->user->name,
            'image'         => $this->transformImage($show->image, $show->appSetting),
            'copyrightYear' => $show->created_at->format('Y'),
        ],
        'team'              => [
            'name' => $show->team->name,
            'slug' => $show->team->slug,
        ],
        'episode'           => [
            'id'                         => $showEpisode->id,
            'ulid'                       => $showEpisode->ulid,
            'name'                       => $showEpisode->name,
            'slug'                       => $showEpisode->slug,
            'description'                => $showEpisode->description,
            'notes'                      => $showEpisode->notes,
            'episode_number'             => $showEpisode->episode_number,
            'status'                     => $showEpisode->showEpisodeStatus,
            'release_year'               => $showEpisode->release_year ?? null,
            'release_dateTime'           => $this->formattedReleaseDateTime ?? null,
            'scheduled_release_dateTime' => $this->formattedScheduledDateTime ?? null,
            'mist_stream_id'             => $showEpisode->mist_stream_id,
            'video_id'                   => $showEpisode->video_id,
            'youtube_url'                => $showEpisode->youtube_url,
            'video_embed_code'           => $showEpisode->video_embed_code,
            'video'                      => $this->transformVideo($videoForEpisode),
            'image'                      => $this->transformImage($showEpisode->image, $showEpisode->appSetting),
        ],
        'releaseDateTime'   => $this->formattedReleaseDateTime ?? null,
        'scheduledDateTime' => $this->formattedScheduledDateTime ?? null,
        'episodeStatus'     => [
            'id'   => $showEpisode->showEpisodeStatus->id,
            'name' => $showEpisode->showEpisodeStatus->name,
        ],
        'can'               => [
            'editEpisode' => auth()->user()->can('editEpisode', $show),
            'goLive'      => auth()->user()->can('goLive', $show),
        ]
    ]);
  }

  private function transformVideo($video) {
    return $video ? [
        'file_name'        => $video->file_name ?? '',
        'cdn_endpoint'     => $video->appSetting->cdn_endpoint ?? '',
        'folder'           => $video->folder ?? '',
        'cloud_folder'     => $video->cloud_folder ?? '',
        'upload_status'    => $video->upload_status ?? '',
        'video_url'        => $video->video_url ?? '',
        'type'             => $video->type ?? '',
        'storage_location' => $video->storage_location ?? '',
    ] : null;
  }

////////////  CHANGE EPISODE STATUS
////////////////////////////
///

  public function changeEpisodeStatus(HttpRequest $request, Show $show) {
    // if publishing an episode... episode_status_id === 7
    // set release year and dateTime
    // Carbon::now();

    // Get the episode ID and new status from the request
    $episodeId = $request->input('episode_id');
    $newStatusId = $request->input('new_status_id');

    // Find the episode by ID
    $episode = ShowEpisode::find($episodeId);

    if ($episode->isPublished && !auth()->user()->isAdmin) {
      return response()->json(['alert' => 'This episode has already been published. Please contact the notTV team for assistance.']);
    }

    try {
      if ($newStatusId === 6) { // 6 is 'scheduled'
        // validate the request
        $request->validate([
            'scheduled_release_dateTime' => 'date|after:now',
        ]);
        $scheduledDateTime = $request->input('scheduled_release_dateTime');

        // Create a Carbon instance from the datetime string
        $carbonDatetime = Carbon::parse($scheduledDateTime);

        // Convert to UTC
        $utcDatetime = $carbonDatetime->utc();

        // Format $utcDatetime as a string in ISO 8601 format:
        $formattedUtcDatetime = $utcDatetime->toIso8601String();

      } else if ($newStatusId === 7) { // 7 is 'published'
        $releaseDateTime = Carbon::now();
        $releaseYear = Carbon::now()->year;
        $formattedUtcDatetime = null;
        $isPublished = 1;
      } else if ($newStatusId > 7) {
        $releaseDateTime = null;
        $releaseYear = null;
        $formattedUtcDatetime = null;
      }

      if (!$episode) {
        return response()->json(['message' => 'Episode not found'], 404);
      }

      // Update the episode's status
      $episode->show_episode_status_id = $newStatusId;
      $episode->release_dateTime = $releaseDateTime ?? null;
      $episode->release_year = $releaseYear ?? null;
      $episode->scheduled_release_dateTime = $formattedUtcDatetime ?? null;
      $episode->isPublished = $isPublished ?? 0;
      $episode->save();

      // If successful, return a success response
      return response()->json(['message' => 'Episode status updated successfully']);

      // If successful, you can return a success message
//            return Redirect::back()->with('success', 'Episode status changed successfully');
    } catch (\Exception $e) {
      // If an error occurs, return an error response
      return response()->json(['error' => 'Error changing episode status: ' . $e->getMessage()], 500);
    }
  }

  public function convertTimeToUserTime($dateTime): ?string {
    if (is_null($dateTime)) {
      // Handle the null case. You can return an empty string or a default value.
      return ''; // or return some default value or message
    }
    try {
      // Get the user's timezone
      $user = Auth::user();
      if (!$user) {
        // No authenticated user, unable to determine timezone
        return null;
      }

      $userTimezone = $user->timezone;

      // Create a Carbon instance from the DateTime string
      $dateTime = \Carbon\Carbon::parse($dateTime, 'UTC');

      // Convert to the user's timezone
      $dateTime->setTimezone($userTimezone);

      // Format $dateTime as a string in ISO 8601 format:
      return $dateTime->toIso8601String();
    } catch (\Exception $e) {
      // Handle any parsing or timezone conversion errors here
      return null;
    }
  }

}
