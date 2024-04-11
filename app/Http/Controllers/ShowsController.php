<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShowResource;
use App\Jobs\AddOrUpdateMistStreamJob;
use App\Jobs\AddMistStreamWildcardToServer;
use App\Models\CreativeCommons;
use App\Models\Creator;
use App\Models\Image;
use App\Models\MistStream;
use App\Models\MistStreamWildcard;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ShowsController extends Controller {

  private string $formattedReleaseDateTime;
  private string $formattedScheduledDateTime;

  public function __construct() {

//        $this->middleware('can:viewAny' . \App\Models\Show::class)->only(['index']);
    $this->middleware('can:view,show')->only(['show']);
//    $this->middleware('can:view,' . \App\Models\Show::class)->only(['create']);
//    $this->middleware('can:view,show')->only('index');
    $this->middleware('can:create,' . Show::class)->only(['create']);
    $this->middleware('can:create,' . Show::class)->only(['store']);
//    $this->middleware('can:create' . \App\Models\Show::class)->only(['store']);
    $this->middleware('can:edit,show')->only(['edit']);
    $this->middleware('can:edit,show')->only(['update']);
    $this->middleware('can:destroy,show')->only(['destroy']);

    $this->middleware('can:viewShowManagePage,show')->only(['manage']);

    // tec21: this policy isn't working vvv
//        $this->middleware('can:editShowManagePage,show')->only(['changeEpisodeStatus']);

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
//            'viewShows'   => Auth::user()->can('view', Show::class),
            'viewCreator' => Auth::user()->can('viewCreator', User::class),
        ]
    ]);
  }

  private function fetchShows(): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
    return Show::with(['team', 'user', 'image', 'status', 'category', 'subCategory', 'appSetting', 'showRunner.user'])
        ->where(function ($query) {
          // Apply filter for shows based on show_status_id and episodes' status
          $query->where(function ($q) {
            $q->whereIn('show_status_id', [1, 2])
                ->whereHas('showEpisodes', function ($q) {
                  $q->where('show_episode_status_id', 7);
                });
          })
              // Additional condition for creators
              ->orWhere(function ($q) {
                if (auth()->check() && auth()->user()->creator) {
                  $q->where('show_status_id', 9);
                }
              });
        })
        // Searchable filter for both Show and ShowEpisode
        ->when(Request::input('search'), function ($query, $search) {
          $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%" . $search . "%")
                ->orWhere('description', 'like', "%" . $search . "%")
                // Extend search to ShowEpisode names and descriptions
                ->orWhereHas('showEpisodes', function ($q) use ($search) {
                  $q->where('name', 'like', "%" . $search . "%")
                      ->orWhere('description', 'like', "%" . $search . "%")
                      ->where('show_episode_status_id', 7); // Ensure episode is published
                });
          });
        })
        ->orderByRaw('RAND()')
        ->paginate(6, ['*'], 'shows')
        ->withQueryString()
        ->through(fn($show) => $this->transformShow($show));
  }

  private function transformShow($show): array {

    // Use a subquery to get the oldest episode's releaseDateTime for the show
    $oldestEpisodeReleaseDateTime = $show->showEpisodes()
        ->orderBy('release_dateTime', 'asc')
        ->limit(1)
        ->value('release_dateTime');

    $isNew = false;
    if ($oldestEpisodeReleaseDateTime) {
      $isNew = \Carbon\Carbon::parse($oldestEpisodeReleaseDateTime)
          ->isBetween(\Carbon\Carbon::now()->subWeek(), \Carbon\Carbon::now());
    }

    return [
        'id'                 => $show->id,
        'ulid'               => $show->ulid,
        'name'               => $show->name,
        'description'        => $show->description,
        'team_id'            => $show->team_id,
        'teamName'           => $show->team->name ?? null,
        'teamSlug'           => $show->team->slug ?? null,
        'showRunner'         => [
            'id'   => $show->showRunner->id ?? null,
            'name' => $show->showRunner->user->name ?? null,
        ],
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
        'isNew'              => $isNew,
        'can'                => [
            'editShow' => Auth::user()->can('editShow', $show),
            'viewShow' => Auth::user()->can('viewShowManagePage', $show)
        ]
    ];
  }

  private function fetchNewestEpisodes() {
    $oneWeekAgo = now()->subWeek(); // Calculate the date one week ago from today

    return ShowEpisode::with('show', 'image', 'show.category', 'show.subCategory', 'appSetting')
        ->where('show_episode_status_id', 7) // Assuming 7 is the status ID for published episodes
        ->where('release_dateTime', '>=', $oneWeekAgo) // Only episodes released in the last week
        ->whereHas('show', function ($query) {
          // Filter shows with status of 1 or 2 (New or Active)
          $query->whereIn('show_status_id', [1, 2]);
        })
        ->orderBy('release_dateTime', 'desc')
        ->limit(5) // Limit the results to the 5 newest episodes
        ->get() // Get the results without pagination
        ->map(fn($showEpisode) => $this->transformShowEpisode($showEpisode)); // Transform each episode if needed
  }

  private function transformShowEpisode($showEpisode): array {
    return [
        'id'                => $showEpisode->id,
        'ulid'              => $showEpisode->ulid,
        'episode_number'    => $showEpisode->episode_number,
        'name'              => $showEpisode->name,
        'description'       => \Str::limit($showEpisode->description, 100, '...'),
        'image'             => $this->transformImage($showEpisode->image, $showEpisode->appSetting),
        'slug'              => $showEpisode->slug,
        'showName'          => $showEpisode->show->name,
        'showSlug'          => $showEpisode->show->slug,
//        'releaseDate' => $showEpisode->release_dateTime,
        'category'          => [
            'name'        => $showEpisode->show->category->name,
            'description' => $showEpisode->show->category->description,
        ],
        'subCategory'       => [
            'name'        => $showEpisode->show->subCategory->name,
            'description' => $showEpisode->show->subCategory->description,
        ],
        'releaseDateTime'   => $showEpisode->release_dateTime,
        'scheduledDateTime' => $showEpisode->scheduled_release_dateTime,
    ];
  }

  private function fetchComingSoon() {
    $threeMonthsAgo = now()->subMonths(3);

    return Show::with(['team', 'user', 'image', 'showEpisodes', 'status', 'category', 'subCategory', 'appSetting'])
        ->whereHas('showEpisodes', function ($query) {
          // Instead of filtering by status, directly check for future scheduled_release_dateTime
          $query->where('scheduled_release_dateTime', '>', now());
        })
        ->where('updated_at', '>', $threeMonthsAgo) // Continue filtering shows updated within the last 3 months
        ->where(function ($query) {
          $query->where('show_status_id', 1) // Shows that are new
          ->orWhere('show_status_id', 2); // or active
        })
        ->orderBy(DB::raw('(
            SELECT MIN(show_episodes.scheduled_release_dateTime)
            FROM show_episodes
            WHERE show_episodes.show_id = shows.id
            AND show_episodes.scheduled_release_dateTime > NOW()
        )'))
        ->limit(8) // Limit to 8 shows
        ->get() // Get the results without pagination
        ->map(fn($show) => $this->transformShow($show)); // Transform each show if needed
  }

  private function transformImage($image, $appSetting): array {
    return [
        'id'              => $image->id,
        'name'            => $image->name,
        'folder'          => $image->folder,
        'cdn_endpoint'    => $appSetting->cdn_endpoint,
        'cloud_folder'    => $image->cloud_folder,
        'placeholder_url' => $image->placeholder_url,
    ];
  }

  // release dateTime
  private function formatReleaseDate($date): string {

    // NOTE: We are deprecating this in favour of doing the timezone conversions
    // on the frontend.
    //    if (is_null($date)) {
//      // Handle the null case. You can return an empty string or a default value.
//      return ''; // or return some default value or message
//    }
//    $user = Auth::user();
//    $userTimezone = $user->timezone;
//
////    $releaseDateTimeString = $date;
//
//    // Create a Carbon instance from the datetime string
//    $releaseDateTime = \Carbon\Carbon::parse($date, 'UTC');
//
//    // Convert to the user's timezone
//    $releaseDateTime->setTimezone($userTimezone);

//    return $releaseDateTime->toIso8601String();
  }

////////////  CREATE AND STORE
//////////////////////////////

  public function create() {
    $categories = ShowCategory::with('subCategories')->get(); // Fetch all categories with their sub-categories
    $userId = Auth::id(); // Store the user ID in a variable to avoid multiple calls
    $creatorId = Auth::user()->creator->id;

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
        'teams'      => $teams,
        'userId'     => $userId,
        'creatorId'     => $creatorId,
        'categories' => $categories,
    ]);

  }

  public function store(HttpRequest $request) {
    // Check if the authenticated user is a registered creator
    $creatorId = $this->determineShowRunnerId($request);

    // Check if the authenticated user is not a Creator
    if (is_null($creatorId)) {
      // Use withErrors to pass custom error messages, similar to validation errors
      // The first argument of withErrors can be an array or MessageBag
      return back()->withErrors([
          'show_runner_creator_id' => 'An active registered creator is required as the show runner.'
      ])->withInput();
    }

    // Manually create a validator for the request's data.
    // The above was supposed to go in this validator, but it wasn't working.
    // The form was successfully being submitted despite the show_runner not being active.
    $validator = Validator::make($request->all(), [
        'name'                   => 'unique:shows|required|string|max:255',
        'description'            => 'required|string',
        'user_id'                => 'required',
        'team_id'                => 'required|exists:teams,id',
        'category'               => 'required',
        'sub_category'           => 'nullable',
        'instagram_name'         => 'nullable|string|max:30',
        'telegram_url'           => 'nullable|active_url',
        'twitter_handle'         => 'nullable|string|min:4|max:15',
        'notes'                  => 'nullable|string|max:1024',
        'show_runner_creator_id' => 'nullable|exists:creators,id'
    ], [
        'team_id.exists' => 'A team must be selected.'
    ]);
    // Check if validation fails, including both the custom check and the standard validation rules
    if ($validator->fails()) {
      // Use the validator's failed validation response
      return back()->withErrors($validator)->withInput();
    }

    // Validation passes, extract validated data
    $validatedData = $validator->validated();
    $teamId = $validatedData['team_id'];
    $twitterHandle = str_replace('@', '', $request->input('twitter_handle'));
    $instagramName = str_replace('@', '', $request->input('instagram_name'));

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

    DB::beginTransaction();

    try {
      $show = Show::create([
          'name'                   => $request->name,
          'description'            => $request->description,
          'user_id'                => $request->user_id,
          'team_id'                => $request->team_id,
          'show_category_id'       => $request->category,
          'show_category_sub_id'   => $request->sub_category,
          'slug'                   => \Str::slug($request->name),
          'www_url'                => $request->www_url,
          'instagram_name'         => $instagramName,
          'telegram_url'           => $request->telegram_url,
          'twitter_handle'         => $twitterHandle,
          'notes'                  => $request->notes,
          'isBeingEditedByUser_id' => $request->user_id,
          'first_release_year'     => Carbon::now()->format('Y'),
          'show_runner'            => $creatorId,
      ]);

      $mistStream = MistStream::firstOrCreate([
          'name' => 'show',
      ], [
          'source'    => 'push://',
          'comment'   => 'Created for Show integration.',
          'mime_type' => '',
      ]);

      if ($mistStream->wasRecentlyCreated) {
        // The model was created, run the job
        // Prepare data for add Mist Stream Job
        // Prepare $mistStreamData with necessary details
        $mistStreamData = [
            'name'   => $mistStream['name'], // e.g., "live_stream+myEvent"
            'source' => $mistStream['source'], // Define the source, e.g., 'push://'
          // Add any other necessary details for the mist stream here
        ];
        // $originalName is null for new streams, or set it to the current name if updating an existing stream
        $originalName = null; // This is for a new stream creation, or set appropriately for updates

        AddOrUpdateMistStreamJob::dispatch($mistStreamData, $originalName);
      }

      $lowercaseShowUlid = strtolower($show->ulid); // Mist server can only use lowercase letters, numbers _ - or .

      $mistStreamWildcard = MistStreamWildcard::create([
          'name'           => 'show+' . $lowercaseShowUlid, // by appending show+ this becomes our full stream key.
          'comment'        => 'Automatically created with new show.',
          'source'         => 'push://',
          'mist_stream_id' => $mistStream->id,
      ]);

      $show->update(['mist_stream_wildcard_id' => $mistStreamWildcard->id]);

      DB::commit();

      // Dispatch the job with the MistStreamWildcard after successful creation and association
      // We don't actually need to add wildcards to the Mist Server, they just appear when they get used.
//      CheckOrAddMistStreamToServer::withChain([
//          new AddMistStreamWildcardToServer($mistStreamWildcard)
//      ])->dispatch($mistStream);

      // Return a successful response
      return redirect()->route('shows.manage', $show)->with('success', 'Show Created Successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error("Failed to create show and associated entities: {$e->getMessage()}");

      // Optionally, return an error response or redirect with error message
      return back()->with(['error' => 'Failed to create the show.'])->withInput();
    }
  }

////////////  SHOW
//////////////////

  public function show(Show $show) {

    // Eager load necessary relationships for the Show model
    $show->load([
        'user',
        'image.appSetting',
        'showEpisodes.video.appSetting',
        'category',
        'subCategory',
        'status',
        'showEpisodes' => function ($query) {
          // Apply conditions or sorting to episodes here
          $query->with(['show', 'video.appSetting', 'video.mistStream', 'video.mistStreamWildcard', 'image.appSetting', 'mistStreamWildcard'])->orderBy('release_dateTime', 'desc');
        },
        'team'         => function ($query) {
          // Eager load team members and their user details
          $query->with(['members' => function ($query) {
            $query->select(['users.id', 'users.name', 'users.profile_photo_path']);
          }]);
        },
        'showRunner.user'
    ]);

//    // Determine the order dynamically based on the show's 'episode_play_order' attribute
//    $orderMethod = $show->episode_play_order === 'newest' ? 'latest' : 'oldest';
//
//    // Fetch the first play episode with the required conditions
//    $firstPlayEpisode = $show->showEpisodes()
//        ->where('show_episode_status_id', 7) // Only published episodes
//        ->whereNotNull('video_id') // Ensures there's an associated video record
//        ->whereHas('video', function ($query) {
//          $query->whereNotNull('storage_location')
//              ->where('storage_location', '!=', 'external_error') // Exclude 'external_error' storage locations
//              ->where('upload_status', '!=', 'processing'); // Exclude videos that are still processing
//        })
//        ->with(['image', 'video']) // Eager loading related models for efficiency
//        ->$orderMethod('release_dateTime') // Dynamically apply ordering based on user preference
//        ->first();

    $firstPlayEpisode = $this->determineFirstPlayEpisode($show);

    return Inertia::render('Shows/{$id}/Index', [
        'show'     => $this->transformShowData($show, $firstPlayEpisode),
        'episodes' => $this->fetchEpisodes($show),
        'creators' => $this->fetchCreators($show->team_id),
        'team'     => $this->transformTeamData($show->team),
        'can'      => $this->getPermissions($show),
    ]);
  }

  private function determineFirstPlayEpisode(Show $show) {
    // Determine the order direction dynamically based on the show's 'episode_play_order' attribute
    $orderDirection = $show->episode_play_order === 'newest' ? 'desc' : 'asc';

    // Fetch the first play episode with the required conditions
    return $show->showEpisodes()
        ->where('show_episode_status_id', 7) // Only published episodes
        ->whereNotNull('video_id') // Ensures there's an associated video record
        ->whereHas('video', function ($query) {
          $query
              ->where(function ($q) {
                $q->whereNotNull('video_url') // Include episodes with external videos
                ->orWhere(function ($innerQuery) {
                  $innerQuery->whereNotNull('storage_location')
                      ->where('storage_location', '!=', 'external_error') // Exclude 'external_error' storage locations
                      ->where('upload_status', '!=', 'processing'); // Exclude videos that are still processing
                });
              });
        })
        ->with(['image', 'video']) // Eager load related models for efficiency
        ->orderBy('release_dateTime', $orderDirection) // Apply dynamic ordering
        ->first();
  }

  private function transformShowData(Show $show, $firstPlayEpisode) {
    return [
        'id'                 => $show->id,
        'ulid'               => $show->ulid,
        'name'               => $show->name,
        'slug'               => $show->slug,
        'description'        => $show->description,
        'showRunner'         => [
            'id'   => $show->showRunner->id ?? null,
            'name' => $show->showRunner->user->name ?? null,
        ],
        'image'              => $this->transformImage($show->image, $show->appSetting),
        'category'           => $show->category ? $show->category->toArray() : null,
        'subCategory'        => $show->subCategory ? $show->subCategory->toArray() : null,
        'firstPlayEpisode'   => $firstPlayEpisode ? $this->transformEpisodeData($firstPlayEpisode) : null,
        'copyrightYear'      => $show->created_at->format('Y'),
        'first_release_year' => $show->first_release_year ?? null,
        'last_release_year'  => $show->last_release_year ?? null,
        'www_url'            => $show->www_url,
        'instagram_name'     => $show->instagram_name,
        'telegram_url'       => $show->telegram_url,
        'twitter_handle'     => $show->twitter_handle,
        'status'             => $show->status,
    ];
  }

  private function transformEpisodeData($episode): array {
    if (!$episode) return [];

    $episodeData = [
        'id'               => $episode->id,
        'ulid'             => $episode->ulid,
        'episode_number'   => $episode->episode_number,
        'name'             => $episode->name ?? '',
        'slug'             => $episode->slug ?? '',
        'description'      => $episode->description ?? '',
        'image'            => $this->transformImage($episode->image, $episode->appSetting),
        'creative_commons' => $episode->creativeCommons,
        'copyrightYear'    => $episode->copyrightYear,
        'release_dateTime' => $episode->release_dateTime,

      // Other episode attributes...
      // Include both 'file_name' and 'video_url' as applicable
    ];

    // Check if the episode has an associated video
    if ($episode->video) {

      // Determine the media type based on its storage location.
      // If the storage location is marked as 'external', categorize it as 'externalVideo';
      // otherwise, it's considered an internal 'show' video.
//      $mediaType = $episode->video->storage_location === 'external' ? 'externalVideo' : 'show'; // Adjust logic as needed

      // Bitchute needs to be run through a proxy for our AudioStore settings to work... so we are replacing
      // our $mediaType with this:
      // Check the storage_location and set $mediaType accordingly
      if ($episode->video?->storage_location === 'external') {
        $mediaType = 'externalVideo';
      } elseif ($episode->video?->storage_location === 'bitchute') {
        $mediaType = 'bitchute';
      } else {
        $mediaType = 'show';
      }

      // Construct an array to hold video details for the episode.
      $episodeData['video'] = [
          'ulid'                 => $episode->video->ulid ?? '',
          'mediaType'            => $mediaType, // New attribute for NowPlayingStore
          'video_url'            => $episode->video->video_url ?? '',
          'type'                 => $episode->video->type ?? '',
          'file_name'            => $episode->video->file_name ?? '',
          'cdn_endpoint'         => $episode->video->appSetting->cdn_endpoint ?? '',
          'folder'               => $episode->video->folder ?? '',
          'cloud_folder'         => $episode->video->cloud_folder ?? '',
          'upload_status'        => $episode->video->upload_status ?? '',
          'storage_location'     => $episode->video->storage_location ?? '',
          'mist_stream'          => $episode->video->mistStream ?? null,
          'mist_stream_wildcard' => $episode->video->mistStreamWildcard ?? null,
        // Include other relevant video attributes...

      ];
    }

    return $episodeData;
  }


  private function fetchEpisodes(Show $show) {
    // Determine the episode play order from the Show model
    $episodePlayOrder = $show->episode_play_order; // Assume 'oldest' or 'newest'

    $episodesQuery = ShowEpisode::with(['image', 'show', 'showEpisodeStatus'])
        ->where('show_id', $show->id)
        ->when(Request::input('search'), function ($query, $search) {
          $query->where('name', 'like', "%{$search}%");
        })
        ->whereIn('show_episode_status_id', [6, 7]); // Adjusted as per previous discussion

    // Apply conditional ordering based on the Show's episode play order
    if ($episodePlayOrder === 'oldest') {
      $episodesQuery->orderBy('release_dateTime', 'asc'); // Oldest episodes first
    } else {
      $episodesQuery->orderBy('release_dateTime', 'desc'); // Newest episodes first
    }

    // Decide whether to paginate based on whether a search term is present
    $episodes = Request::input('search') ?
        $episodesQuery->paginate(8, ['*'], 'episodes')->withQueryString() :
        $episodesQuery->get();

    return $episodes->map(fn($episode) => $this->transformShowEpisode($episode));
  }

//  private function fetchEpisodes(Show $show) {
//
//    Log::info(
//        ShowEpisode::with(['image', 'show', 'showEpisodeStatus'])
//            ->where('show_id', $show->id)
//            ->when(Request::input('search'), function ($query, $search) {
//              $query->where('name', 'like', "%{$search}%");
//            })
//            ->where('show_episode_status_id', 7)
//            ->latest()
//            ->toSql()
//    );
//    // Use already loaded episodes if no search filter is applied
//    if (!Request::input('search')) {
//      return $show->showEpisodes->map(function ($episode) {
//        return $this->transformShowEpisode($episode);
//      });
//    }
//
//
//
//    // Fetch and filter episodes based on search criteria when specified
//    return ShowEpisode::with(['image', 'show', 'showEpisodeStatus'])
//        ->where('show_id', $show->id)
//        ->when(Request::input('search'), function ($query, $search) {
//          $query->where('name', 'like', "%{$search}%");
//        })
//        ->where('show_episode_status_id', 7) // Filter for published episodes
//        ->latest()
//        ->paginate(8, ['*'], 'episodes')
//        ->withQueryString()
//        ->through(fn($showEpisode) => $this->transformShowEpisode($showEpisode));
//  }

  private function fetchCreators($teamId) {
    // Fetch the team with its members including pivot data and selecting specific user fields
    $team = Team::with(['members' => function ($query) {
      // Adjust the select fields as needed
      $query->select(['users.id', 'users.name', 'users.profile_photo_path'])
          ->withPivot('active', 'created_at', 'updated_at');
    }])->findOrFail($teamId);

    // Transform team members (users) for presentation
    return $team->members->map(function ($user) {
      // Access pivot data like $user->pivot->active if needed
      return [
          'id'                 => $user->id,
          'name'               => $user->name,
          'profile_photo_path' => $user->profile_photo_path,
          'profile_photo_url'  => $user->profile_photo_url,
          'active'             => $user->pivot->active ?? null,
        // Include additional details as necessary
      ];
    });
  }


//    return TeamMember::where('team_id', $teamId)
//        ->join('users', 'team_members.user_id', '=', 'users.id')
//        ->select('users.*', 'team_members.user_id as team_member_id')
//        ->latest()
//        ->paginate(10, ['*'], 'creators')
//        ->withQueryString()
//        ->through(fn($teamMember) => $this->transformTeamMember($teamMember));
//  }

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

  public function manage(Show $show): \Inertia\Response {
    // Eager load related entities for the Show model
    $show->load(['user', 'image', 'appSetting', 'category', 'subCategory', 'showRunner.user', 'team', 'schedules.showScheduleRecurrenceDetails', 'recordings']);

    $episodeStatuses = DB::table('show_episode_statuses')->get()->toArray();
    $filteredStatuses = array_slice($episodeStatuses, 0, -2);


    $now = now(); // Current time

    // Initialize an array to hold schedule details
    $scheduleDetails = [];

    // Determine if the show is actively scheduled or has a schedule in the future
    // And compile schedule details
    $isScheduled = $show->schedules->contains(function ($schedule) use ($now, &$scheduleDetails) {
      $isActiveOrFuture = $schedule->start_time >= $now || ($schedule->end_time >= $now);

      // Assuming $schedule->start_time is a string in 'Y-m-d H:i:s' format
      // First, parse the start_time with the schedule's timezone
      $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $schedule->start_time, $schedule->timezone);

      // Then, convert the datetime to UTC
      $startDateTimeUtc = $startDateTime->setTimezone('UTC');

      if ($isActiveOrFuture) {
        $detail = [
            'contentType'     => $schedule->content_type,
            'contentId'       => $schedule->content_id,
            'type'            => $schedule->recurrence_flag ? 'recurring' : 'one-time',
            'startDateTime'   => $startDateTimeUtc->toDateTimeString(), // Converted to UTC
            'durationMinutes' => $schedule->duration_minutes,
            'timezone'        => $schedule->timezone,
        ];

        if ($schedule->recurrence_flag) {
          // For recurring schedules, add additional details
          // For recurring schedules, add additional details
          $daysOfWeek = $schedule->showScheduleRecurrenceDetails ? json_decode($schedule->showScheduleRecurrenceDetails->days_of_week, true) : [];
          $orderedDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

          // Sort days of week based on the ordered list
          usort($daysOfWeek, function ($a, $b) use ($orderedDays) {
            return array_search($a, $orderedDays) - array_search($b, $orderedDays);
          });

          // Check for specific combinations
          if (count($daysOfWeek) == 7) {
            $detail['daysOfWeek'] = 'Weekdays and Weekends';
          } elseif (count(array_intersect($daysOfWeek, $orderedDays)) == 5 && !in_array('Saturday', $daysOfWeek) && !in_array('Sunday', $daysOfWeek)) {
            $detail['daysOfWeek'] = 'Weekdays';
          } elseif (count($daysOfWeek) == 2 && in_array('Saturday', $daysOfWeek) && in_array('Sunday', $daysOfWeek)) {
            $detail['daysOfWeek'] = 'Weekends';
          } else {
            // If none of the specific combinations match, use the sorted list directly
            $detail['daysOfWeek'] = implode(', ', $daysOfWeek);
          }

          // Combine start_date and start_time to form a complete datetime string
//          $dateTimeString = $schedule->showScheduleRecurrenceDetails->start_date;
//          $timezone = $schedule->showScheduleRecurrenceDetails->timezone;
          // Parsing the datetime string in the specified timezone, then converting to UTC
//          $dateTimeUtc = Carbon::createFromFormat('Y-m-d H:i:s', $dateTimeString, $timezone)
//              ->setTimezone('UTC');

          // Log for debugging
//          Log::debug('DateTime in UTC:', ['dateTimeUtc' => $dateTimeUtc->toIso8601String()]);

          // Prepare the full UTC datetime string in ISO 8601 format for frontend consumption
//          $detail['startDateTimeIsoUtc'] = $dateTimeUtc->toIso8601String();
          $detail['startDateTimeIsoUtc'] = $startDateTimeUtc;

          // If you need to retain the original 'startTime' for any reason, keep this line as is
//          $detail['startTime'] = $schedule->showScheduleRecurrenceDetails ? $schedule->showScheduleRecurrenceDetails->start_time : null;
//          Log::debug('Original StartTime:', ['startTime' => $detail['startTime']]);

        }

        $scheduleDetails[] = $detail;
      }

      return $isActiveOrFuture;
    });

    // Fetch the Mist Server URI setting outside the map function to avoid repetitive database queries
    $mistServerUri = AppSetting::where('id', 1)->pluck('mist_server_uri')->first();

    $recordings = $show->recordings->map(function ($recording) use ($mistServerUri, $show) {
      // Process path to remove unnecessary parts and prepare for URL usage
      $path = str_replace(['/media/recordings/'], [''], $recording->path);
      $encodedPath = urlencode($path);
      $streamName = 'recordings%2B' . $encodedPath . '.mp4'; // Prepare stream name

      // Format the start time if available and is a Carbon instance, else use a default
      $formattedStartTime = optional($recording->start_time)->format(' Y m d H i s') ?? 'unknown_time';

      // Construct the file name and encode it for URL usage
      $downloadFileName = $show->name . $formattedStartTime . '.mp4';
      $encodedFileName = urlencode($downloadFileName);

      // Construct the download URL with correct query parameters
      $downloadUrl = rtrim($mistServerUri, '/') . '/' . $streamName . '?dl=1&filename=' . $encodedFileName;

      // Construct a share URL
      $shareUrl = rtrim($mistServerUri, '/') . '/' . $encodedPath . '.html';

      // Return the modified recording with additional download details
      return collect($recording->only([
          'id', 'file_extension', 'start_time', 'end_time',
          'total_milliseconds_recorded', 'mist_stream_wildcard_id', 'download_url', 'path', 'comment'
      ]))
          ->put('streamName', $streamName)
          ->put('shareUrl', $shareUrl)
          ->put('download', [
              'url' => $downloadUrl,
              'fileName' => $downloadFileName
          ]);
    });

    return Inertia::render('Shows/{$id}/Manage', [
        'show'            => [
            'id'              => $show->id,
            'ulid'            => $show->ulid,
            'name'            => $show->name,
            'description'     => $show->description,
            'slug'            => $show->slug,
            'image'           => $this->transformImage($show->image, $show->appSetting),
            'copyrightYear'   => $show->created_at->format('Y'),
            'category'        => [
                'id'          => $show->category->id,
                'name'        => $show->category->name,
                'description' => $show->category->description,
            ],
            'subCategory'     => [
                'id'          => $show->subCategory->id,
                'name'        => $show->subCategory->name,
                'description' => $show->subCategory->description,
            ],
            'notes'           => $show->notes,
            'isScheduled'     => $isScheduled ?? false,
            'scheduleDetails' => $scheduleDetails ?? [],
            'showRunner'      => [
                'id'   => $show->showRunner->id ?? null,
                'name' => $show->showRunner->user->name ?? null,
            ],
//            'recordings'      => $show->recordings->map->only(['id', 'path', 'file_extension', 'start_time', 'end_time', 'total_milliseconds_recorded', 'mist_stream_wildcard_id', 'download_url']), // Include only necessary fields
            'recordings'      => $recordings,
        ],
        'team'            => [
            'name' => $show->team->name,
            'slug' => $show->team->slug,
        ],
        'episodes'        => ShowEpisode::with('image', 'show', 'showEpisodeStatus', 'recordings')
            ->where('show_id', $show->id)
            ->when(Request::input('search'), function ($query, $search) {
              $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(5, ['*'], 'shows')
            ->withQueryString()
            ->through(fn($showEpisode) => [
                'id'                       => $showEpisode->id,
                'ulid'                     => $showEpisode->ulid,
                'name'                     => $showEpisode->name,
                'image'                    => $this->transformImage($showEpisode->image, $showEpisode->appSetting),
                'slug'                     => $showEpisode->slug,
                'episodeNumber'            => $showEpisode->episode_number,
                'notes'                    => $showEpisode->notes,
                'episodeStatus'            => $showEpisode->showEpisodeStatus->name,
                'episodeStatusId'          => $showEpisode->showEpisodeStatus->id,
                'isPublished'              => $showEpisode->isPublished,
                'scheduledReleaseDateTime' => $showEpisode->scheduled_release_dateTime,
                'releaseDateTime'          => $showEpisode->release_dateTime,
                'recordings'               => $showEpisode->recordings->map->only(['id', 'path', 'file_extension', 'start_time', 'end_time', 'total_milliseconds_recorded', 'mist_stream_wildcard_id', 'download_url']), // Include only necessary fields
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


    // Assuming 'creator' or 'showRunner' is the correct relationship name
    $show->load('image.appSetting', 'category', 'subCategory', 'team.members', 'showRunner.user');

    // Fetch categories and statuses separately as they are not part of the ShowResource
    $categories = ShowCategory::with('subCategories')->get(); // Efficiently fetch categories with sub-categories
    $statuses = ShowStatus::all();
    // Use ShowResource to transform the Show model, and append additional data
    $showResourceArray = (new ShowResource($show))->resolve();

    // Manually append additional fields
    $showResourceArray = array_merge($showResourceArray, [
        'notes'              => $show->notes,
        'show_status_id'     => $show->show_status_id,
        'episode_play_order' => $show->episode_play_order,
    ]);


    // Load the team with members who have creators with status_id of 1
    $team = Team::with(['members' => function($query) {
      // Filter members to those who have a creator with status_id of 1
      $query->whereHas('creator', function($query) {
        $query->where('status_id', 1);
      });
    }, 'members.creator']) // Ensure to still load the creator relationship
    ->findOrFail($show->team_id);

    // Now, mapping team members to include creator_id, this will only include members filtered by the above condition
    $teamMembers = $team->members->map(function ($user) {
      // At this point, every user has a creator with status_id of 1 due to the query filter
      $creatorId = $user->creator ? $user->creator->id : null;

      return [
          'creator_id' => $creatorId,
          'name'       => $user->name,
        // Include any other necessary attributes
      ];
    });

    // Return the Inertia response with the ShowResource and additional data
    return Inertia::render('Shows/{$id}/Edit', [
        'show'        => $showResourceArray,
        'team'        => $team, // Directly use the loaded $team
        'teamMembers' => $teamMembers,
      // we are loading the image twice... once in the showResource
        'image'       => [
            'id'           => $show->image->id,
            'name'         => $show->image->name,
            'folder'       => $show->image->folder,
            'cdn_endpoint' => $show->appSetting->cdn_endpoint,
            'cloud_folder' => $show->image->cloud_folder,
        ],
        'categories'  => $categories,
        'statuses'    => $statuses,
        'can'         => [
            'editShow' => Auth::user()->can('edit', $show),
        ],
    ]);

  }

//  public function edit(Show $show) {
//    DB::table('users')->where('id', Auth::user()->id)->update([
//        'isEditingShow_id' => $show->id,
//    ]);
//
//    DB::table('shows')->where('id', $show->id)->update([
//        'isBeingEditedByUser_id' => Auth::user()->id,
//    ]);
//
//    $categories = ShowCategory::with('subCategories')->get(); // Fetch all categories with their sub-categories
//    $statuses = ShowStatus::all();
//    $show->load('user', 'image', 'appSetting', 'category', 'subCategory'); // Eager load necessary relationships
//
//    return Inertia::render('Shows/{$id}/Edit', [
//        'show'        => $show,
//        'team'        => Team::query()->where('id', $show->team_id)->firstOrFail(),
////        'showRunner'  => User::query()->where('id', $show->user_id)->pluck('id', 'name')->firstOrFail(),
////        'showRunner'  => [
////                        'name' => $show->user->name,
////                        'id' => $show->user->id,
////        ],
//        'image'       => $this->transformImage($show->image, $show->appSetting),
//        'categories'  => $categories,
//        'statuses'    => $statuses,
//        'category'    => $show->category ? $show->category->toArray() : null,
//        'subCategory' => $show->subCategory ? $show->subCategory->toArray() : null,
//        'can'         => [
//            'editShow' => Auth::user()->can('edit', $show),
//        ],
//    ]);
//  }

////////////  UPDATE
////////////////////

  public function update(HttpRequest $request, Show $show) {

    $creatorId = $this->determineShowRunnerId($request);

    // Check if the authenticated user is not a Creator
    if (is_null($creatorId)) {
      // Use withErrors to pass custom error messages, similar to validation errors
      // The first argument of withErrors can be an array or MessageBag
      return back()->withErrors([
          'show_runner_creator_id' => 'An active registered creator is required as the show runner.'
      ])->withInput();
    }

    // Check if show_runner is null before validation
    if (is_null($show->show_runner && $request->show_runner)) {
      // Flash a session message to inform the user
      return redirect()->back()->with([
          'error'                             => 'Please set the Show Runner',
          'form.error.show_runner_creator_id' => 'Please set the Show Runner', // Custom form error
      ])->withInput();
    }

    // validate the request
    $request->validate([
        'name'                   => ['required', 'string', 'max:255', Rule::unique('shows')->ignore($show->id)],
        'description'            => 'required',
        'release_date'           => 'date|after:tomorrow',
        'category'               => 'required',
        'sub_category'           => 'nullable',
        'www_url'                => 'nullable|active_url',
        'instagram_name'         => 'nullable|string|max:30',
        'telegram_url'           => 'nullable|active_url',
        'twitter_handle'         => 'nullable|string|min:4|max:15',
        'notes'                  => 'nullable|string|max:1024',
        'show_status_id'         => 'required|integer|exists:show_statuses,id',
        'episode_play_order'     => 'required|string',
        'show_runner_creator_id' => 'exists:creators,id'
    ], [
        'status.exists'      => 'The selected status is invalid.',
        'release_date.after' => 'The release date must be at least 24 hours in the future.',
    ]);

    $twitterHandle = str_replace('@', '', $request->input('twitter_handle'));
    $instagramName = str_replace('@', '', $request->input('instagram_name'));

    // update the show
    $show->name = $request->name;
    $show->description = $request->description;
    $show->show_category_id = $request->category;
    $show->show_category_sub_id = $request->sub_category;
    $show->slug = \Str::slug($request->name);
    $show->www_url = $request->www_url;
    $show->instagram_name = $instagramName;
    $show->telegram_url = $request->telegram_url;
    $show->twitter_handle = $twitterHandle;
    $show->notes = htmlentities($request->notes);
    $show->show_status_id = $request->show_status_id;
    $show->episode_play_order = $request->episode_play_order;
    $show->show_runner = $creatorId;
    $show->save();

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

    // Check if the show was found
    if (!$show) {
      // Respond with an error if not found
      return response()->json([
          'message' => 'Show not found',
      ], 404); // Not Found status code
    }

    // validate the request
    $request->validate([
        'notes' => 'nullable|string|max:1024',
    ]);

    // update the show notes
    $show->notes = $request->notes;
    $show->save();

    return $show;
  }

////////////  DESTROY
/////////////////////

  public function destroy(Show $show) {
    $show->delete();

    // redirect
    return redirect()->route('shows.index')->with('message', 'Show Deleted Successfully');
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

    $creativeCommons = CreativeCommons::all();

    $show->load('user', 'image', 'appSetting', 'category', 'subCategory', 'showRunner.user'); // Eager load necessary relationships

    return Inertia::render('Shows/{$id}/Episodes/Create', [
        'show'             => [
            'name'        => $show->name,
            'id'          => $show->id,
            'slug'        => $show->slug,
            'showRunner'  => [
                'name' => $show->showRunner->user->name ?? null,
            ],
            'image'       => $this->transformImage($show->image, $show->appSetting),
            'category'    => [
                'name'        => $show->category->name,
                'description' => $show->category->description,
            ],
            'subCategory' => [
                'name'        => $show->subCategory->name,
                'description' => $show->subCategory->description,
            ],

        ],
        'team'             => Team::query()->where('id', $show->team_id)->first(),
        'user'             => [
            'id' => auth()->user()->id,
        ],
        'creative_commons' => $creativeCommons,
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

//    $show->load('team', 'showEpisodes.creativeCommons', 'showEpisodes.video', 'image', 'appSetting', 'category', 'subCategory'); // Eager load necessary relationships

    // Eager load relationships for the Show model
    $show->load(['team', 'image', 'appSetting', 'category', 'subCategory', 'showRunner.user']);

    // Eager load relationships for the ShowEpisode model
    $showEpisode->load(['creativeCommons', 'video.appSetting', 'image', 'showEpisodeStatus', 'mistStreamWildcard']);


    return Inertia::render('Shows/{$id}/Episodes/{$id}/Manage', [
        'show'              => [
            'name'          => $show->name,
            'slug'          => $show->slug,
            'showRunner'    => [
                'name' => $show->showRunner->user->name ?? null,
            ],
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
            'show_id'                    => $showEpisode->show_id,
            'name'                       => $showEpisode->name,
            'slug'                       => $showEpisode->slug,
            'description'                => $showEpisode->description,
            'notes'                      => $showEpisode->notes,
            'episode_number'             => $showEpisode->episode_number,
            'status'                     => $showEpisode->showEpisodeStatus,
            'release_year'               => $showEpisode->release_year ?? null,
            'release_dateTime'           => $this->formattedReleaseDateTime ?? null,
            'scheduled_release_dateTime' => $this->formattedScheduledDateTime ?? null,
            'copyrightYear'              => $showEpisode->copyrightYear ?? null,
            'creative_commons'           => $showEpisode->creativeCommons ?? null,
            'mist_stream_wildcard_id'    => $showEpisode->mist_stream_wildcard_id,
            'mist_stream_wildcard'       => $showEpisode->mistStreamWildcard,
            'video_id'                   => $showEpisode->video_id,
            'youtube_url'                => $showEpisode->youtube_url,
            'video_embed_code'           => $showEpisode->video_embed_code,
            'video'                      => [
                'file_name'        => $showEpisode->video->file_name ?? '',
                'cdn_endpoint'     => $showEpisode->video->appSetting->cdn_endpoint ?? '',
                'folder'           => $showEpisode->video->folder ?? '',
                'cloud_folder'     => $showEpisode->video->cloud_folder ?? '',
                'upload_status'    => $showEpisode->video->upload_status ?? '',
                'video_url'        => $showEpisode->video->video_url ?? '',
                'type'             => $showEpisode->video->type ?? '',
                'storage_location' => $showEpisode->video->storage_location ?? '',
            ],
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

  private function getAuthenticatedCreatorId() {
    // Retrieve the Creator associated with the authenticated user
    $creator = Auth::user()->creator ?? null;

    // Check if the Creator exists and is active (assuming statusId = 1 indicates active)
    if ($creator && $creator->status_id == 1) {
      return $creator->id;
    }

    // Return null if there's no Creator or if the Creator is not active
    return null;
  }

  private function determineShowRunnerId($request) {
    // Default to the authenticated creator's ID if they are active
    $creatorId = $this->getAuthenticatedCreatorId();

    // If `show_runner` is provided in the request, verify it's an active creator
    if (!is_null($request->show_runner_creator_id)) {
      $specifiedCreator = Creator::where('id', $request->show_runner_creator_id)
          ->where('status_id', 1) // Assuming 'status 1' indicates active status
          ->first();

      if ($specifiedCreator) {
        $creatorId = $specifiedCreator->id; // Use the specified creator id if active
      } else {
        // If specified creator is not active or doesn't exist, return null
        // The calling method will handle this case appropriately
        return null;
      }
    }

    return $creatorId;
  }

}
