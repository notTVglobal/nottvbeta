<?php

namespace App\Http\Controllers;

use App\Events\CreatorContentStatusUpdated;
use App\Factories\MistServerServiceFactory;
use App\Http\Resources\ImageResource;
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
use App\Services\MistServer\MistServerService;
use App\Traits\ImageTransformable;
use http\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Mews\Purifier\Facades\Purifier;

class ShowsController extends Controller {

  private string $formattedReleaseDateTime;
  private string $formattedScheduledDateTime;
  protected MistServerService $playbackService;
  use ImageTransformable;

  public function __construct() {

    $this->playbackService = MistServerServiceFactory::make('playback');

    // Apply auth middleware only to certain methods
    $this->middleware('auth')->except(['index', 'show', 'checkIsLive']);

//    $this->middleware('can:viewAny,show')->only(['index']);
//    $this->middleware('can:view,show')->only(['show']);


    $this->middleware('can:create,' . Show::class)->only(['create']);
    $this->middleware('can:create,' . Show::class)->only(['store']);

    $this->middleware('can:edit,show')->only(['edit']);
    $this->middleware('can:edit,show')->only(['update']);
    $this->middleware('can:destroy,show')->only(['destroy']);

    $this->middleware('can:viewShowManagePage,show')->only(['manage']);

    $this->middleware('can:createEpisode,show')->only(['createEpisode']);
    $this->middleware('can:viewEpisodeManagePage,show')->only(['manageEpisode']);

    $this->formattedReleaseDateTime = '';
    $this->formattedScheduledDateTime = '';

  }

////////////  INDEX
///////////////////

  public function index(): \Inertia\Response {

    $user = Auth::user();

    $canViewCreator = optional($user)->can('viewCreator', User::class);

    $component = $user ? 'Shows/Index' : 'LoggedOut/Shows/Index';

    return Inertia::render($component, [
        'shows'          => $this->fetchShows(),
        'newestEpisodes' => $this->fetchNewestEpisodes(),
        'comingSoon'     => $this->fetchComingSoon(),
        'filters'        => Request::only(['search']),
        'can'            => [
//            'viewShows'   => Auth::user()->can('view', Show::class),
            'viewCreator' => $canViewCreator,
        ]
    ]);
  }

  private function fetchShows(): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
    // Check if a seed is already set in the session
    $seed = Session::get('random_seed');

    // If not, generate a new seed and store it in the session
    if (!$seed) {
      $seed = mt_rand();
      Session::put('random_seed', $seed);
    }


    return Show::with(['team.user', 'image.appSetting', 'status', 'showRunner.user'])
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
        // Use the seed to randomize the order
        ->orderByRaw('RAND(?)', [$seed])
        ->paginate(6, ['*'], 'shows')
        ->withQueryString()
        ->through(fn($show) => $this->transformShow($show));
  }

  private function transformShow($show): array {

    $user = Auth::user();

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

    // Get the next closest scheduled release dateTime in the future
    $nextScheduledReleaseDateTime = $show->showEpisodes()
        ->where('scheduled_release_dateTime', '>', now())
        ->orderBy('scheduled_release_dateTime', 'asc')
        ->value('scheduled_release_dateTime');

    return [
        'id'                         => $show->id,
        'ulid'                       => $show->ulid,
        'name'                       => $show->name,
        'description'                => $show->description,
        'team_id'                    => $show->team_id,
        'teamName'                   => $show->team->name ?? null,
        'teamSlug'                   => $show->team->slug ?? null,
        'showRunner'                 => [
            'id'   => $show->showRunner->id ?? null,
            'name' => $show->showRunner->user->name ?? null,
        ],
        'image'                      => $this->transformImage($show->image),
        'slug'                       => $show->slug,
        'totalEpisodes'              => $show->showEpisodes->count(),
        'scheduled_release_dateTime' => $nextScheduledReleaseDateTime ?? null,
        'status'                     => $show->status->name ?? null,
        'statusId'                   => $show->status->id ?? null,
        'copyrightYear'              => $show->created_at->format('Y'),
        'first_release_year'         => $show->first_release_year,
        'last_release_year'          => $show->last_release_year,
        'category'                   => $show->getCachedCategory() ? $show->getCachedCategory()->toArray() : null,
        'subCategory'                => $show->getCachedSubCategory() ? $show->getCachedSubCategory()->toArray() : null,
        'isNew'                      => $isNew,
        'can'                        => [
            'editShow' => optional($user)->can('editShow', $show),
            'viewShow' => optional($user)->can('viewShowManagePage', $show)
        ]
    ];
  }

  private function fetchNewestEpisodes() {
    $oneWeekAgo = now()->subWeek(); // Calculate the date one week ago from today

    return ShowEpisode::with('show', 'image.appSetting', 'show.category', 'show.subCategory')
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
    $show = $showEpisode->show; // Assuming the 'show' relationship is loaded or handled appropriately

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
            'name'        => $show->getCachedCategory()->name ?? null,
            'description' => $show->getCachedCategory()->description ?? null,
        ],
        'subCategory'       => [
            'name'        => $show->getCachedSubCategory()->name ?? null,
            'description' => $show->getCachedSubCategory()->description ?? null,
        ],
        'releaseDateTime'   => $showEpisode->release_dateTime,
        'scheduledDateTime' => $showEpisode->scheduled_release_dateTime,
    ];
  }

  private function fetchComingSoon() {
    $threeMonthsAgo = now()->subMonths(3);

    return Show::with(['team.user', 'image.appSetting', 'showEpisodes', 'status'])
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

  // release dateTime
//  private function formatReleaseDate($date): string {

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
//  }

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
        'creatorId'  => $creatorId,
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
        'www_url'                => 'nullable|active_url',
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
    $validatedData = $validator->validated(); // Ensure all data is validated


    // Sanitize social media handles
    $twitterHandle = isset($validatedData['twitter_handle']) ? str_replace('@', '', $validatedData['twitter_handle']) : null;
    $instagramName = isset($validatedData['instagram_name']) ? str_replace('@', '', $validatedData['instagram_name']) : null;

    // Sanitize URLs
    $wwwUrl = isset($validatedData['www_url']) ? filter_var($validatedData['www_url'], FILTER_SANITIZE_URL) : null;
    $telegramUrl = isset($validatedData['telegram_url']) ? filter_var($validatedData['telegram_url'], FILTER_SANITIZE_URL) : null;

    // Sanitize description
    $sanitizedDescription = Purifier::clean($validatedData['description'], 'customNoCss');

    // Sanitize and process name
    $processedName = strip_tags($validatedData['name']);

    // Retrieve the team with the team_id and check its status
    $team = Team::find($validatedData['team_id']);
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
          'name'                   => $processedName,
          'description'            => $sanitizedDescription,
          'user_id'                => $validatedData['user_id'],
          'team_id'                => $validatedData['team_id'],
          'show_category_id'       => $validatedData['category'],
          'show_category_sub_id'   => $validatedData['sub_category'] ?? null,  // Handle optional data
          'slug'                   => \Str::slug($validatedData['name']),
          'www_url'                => $wwwUrl,
          'instagram_name'         => $instagramName,
          'telegram_url'           => $telegramUrl,
          'twitter_handle'         => $twitterHandle,
          'notes'                  => $validatedData['notes'] ?? null,
          'isBeingEditedByUser_id' => $validatedData['user_id'],  // Assuming this is intended to be the same as user_id
          'first_release_year'     => Carbon::now()->format('Y'),
          'show_runner'            => $creatorId,
          'meta'                   => [],
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
          'mime_type'      => 'application/x-mpegURL',
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
        'image.appSetting',
        'status',
        'scheduleIndexes',
        'mistStreamWildcard',
        'showEpisodes' => function ($query) {
          // Apply conditions or sorting to episodes here
          $query->with([
              'video' => function ($query) {
                $query->with(['appSetting', 'mistStream', 'mistStreamWildcard']);
              },
          ])->orderBy('release_dateTime', 'desc');
        },
        'team'         => function ($query) {
          // Eager load team members and their user details
          $query->with(['members' => function ($query) {
            $query->select(['id', 'name', 'profile_photo_path']);
          }]);
        }
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

    $user = Auth::user();

    $component = $user ? 'Shows/{$id}/Index' : 'LoggedOut/Shows/{$id}/Index';

    return Inertia::render($component, [
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
        ->with(['image.appSetting', 'video.appSetting']) // Eager load related models for efficiency
        ->orderBy('release_dateTime', $orderDirection) // Apply dynamic ordering
        ->first();
  }

  private function getNextBroadcastDate(Show $show) {
    $currentDateTime = Carbon::now();
    $closestBroadcast = null;

    foreach ($show->scheduleIndexes as $index) {
      $nextBroadcastDateTime = Carbon::parse($index['next_broadcast']);

      // Check if the next_broadcast datetime is now or in the future
      if ($nextBroadcastDateTime->gte($currentDateTime)) {
        // If closestBroadcast is null or this broadcast is closer, update closestBroadcast
        if ($closestBroadcast === null || $nextBroadcastDateTime->lt(Carbon::parse($closestBroadcast['next_broadcast']))) {
          $closestBroadcast = $index;
        }
      }
    }

    return $closestBroadcast;
  }


  public function checkIsLive(Show $show): \Illuminate\Http\JsonResponse {
    try {
      // Retrieve active streams
      $activeStreamsResponse = $this->playbackService->activeStreams();

      // Extract the array of stream names
      $activeStreams = $activeStreamsResponse['active_streams'] ?? [];

      // Log the active streams for debugging purposes
//      Log::debug('Active streams: ', $activeStreams);

      // Check if mistStreamWildcard is set and has a name
      $mistStreamWildcard = $show->mistStreamWildcard;
      if (!$mistStreamWildcard || !isset($mistStreamWildcard->name)) {
//        Log::debug('Mist stream wildcard is missing or does not have a name:', ['show_id' => $show->id]);
        return response()->json(['isLive' => false, 'liveScheduledStartTime' => null, 'mistStreamName' => null]);
      }

      // Check if any active stream matches the show's mistStreamWildcard name
      $mistStreamName = $show->mistStreamWildcard->name;

      foreach ($activeStreams as $streamName) {
        if (str_contains($streamName, $mistStreamName)) {
          // Found a matching stream, now check the broadcast dates for the first schedule
          $schedule = $show->schedules->first();
          if ($schedule) {
            $broadcastData = json_decode($schedule->broadcast_dates, true);

            // Ensure broadcast_data contains both broadcastDates array and durationMinutes
            if (!isset($broadcastData['broadcastDates'], $broadcastData['durationMinutes']) || !is_array($broadcastData['broadcastDates'])) {
              Log::error('Broadcast dates entry is missing required fields or is not an array:', ['broadcast_data' => $broadcastData]);

              return response()->json(['isLive' => false, 'liveScheduledStartTime' => null]);
            }

            $durationMinutes = $broadcastData['durationMinutes'];

            foreach ($broadcastData['broadcastDates'] as $datetime) {
              if (!is_string($datetime)) {
                Log::error('Invalid broadcast date entry:', ['datetime' => $datetime]);
                continue;
              }

              $startTime = Carbon::parse($datetime);
              $endTime = $startTime->copy()->addMinutes($durationMinutes);
              $now = Carbon::now();

              if ($now->between($startTime, $endTime)) {
                return response()->json(['isLive' => true, 'liveScheduledStartTime' => $startTime->toIso8601String(), 'mistStreamName' => $mistStreamName]);
              }
            }
          }
        }
      }
    } catch (\Exception $e) {
      Log::error('Error in checkIsLive:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

      return response()->json(['isLive' => false, 'liveScheduledStartTime' => null, 'mistStreamName' => null]);
    }

    return response()->json(['isLive' => false, 'liveScheduledStartTime' => null, 'mistStreamName' => null]);
  }

  private function transformShowData(Show $show, $firstPlayEpisode): array {

    $socialMediaLinks = [
        'www_url'        => $show->www_url,
        'instagram_name' => $show->instagram_name,
        'telegram_url'   => $show->telegram_url,
        'twitter_handle' => $show->twitter_handle,
    ];

    return [
        'id'                 => $show->id,
        'ulid'               => $show->ulid,
        'name'               => $show->name,
        'slug'               => $show->slug,
        'description'        => $show->description,
        'nextBroadcast'      => $this->getNextBroadcastDate($show),
        'showRunner'         => [
            'id'   => $show->showRunner->id ?? null,
            'name' => $show->showRunner->user->name ?? null,
        ],
        'image'              => $this->transformImage($show->image),
        'category'           => $show->getCachedCategory() ? $show->getCachedCategory()->toArray() : null,
        'subCategory'        => $show->getCachedSubCategory() ? $show->getCachedSubCategory()->toArray() : null,
        'firstPlayEpisode'   => $firstPlayEpisode ? $this->transformEpisodeData($firstPlayEpisode) : null,
        'episodePlayOrder'   => $show->episode_play_order,
        'copyrightYear'      => $show->created_at->format('Y'),
        'first_release_year' => $show->first_release_year ?? null,
        'last_release_year'  => $show->last_release_year ?? null,
        'socialMediaLinks'   => $socialMediaLinks,
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
        'image'            => $this->transformImage($episode->image),
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

    $episodesQuery = ShowEpisode::with(['image.appSetting', 'show', 'showEpisodeStatus'])
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
          ->withPivot('active', 'created_at', 'updated_at')
          ->with('creator'); // Eager load the creator relationship
    }])->findOrFail($teamId);

    // Transform team members (users) for presentation
    return $team->members->filter(function ($user) {
      // Access the creator's profile to check if it's public
      return $user->creator->is_public ?? false;
    })->map(function ($user) {
      // Access pivot data like $user->pivot->active if needed
      return [
          'id'                 => $user->id,
          'slug'               => $user->creator->slug,
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
        'poster' => $team->image ? $this->transformImage($team->image) : null,
      // Include additional team-related fields as needed
    ];
  }

  private function getPermissions(Show $show) {
    $user = Auth::user();

    return [
        'manageShow'  => optional($user)->can('manage', $show),
        'editShow'    => optional($user)->can('edit', $show),
        'viewCreator' => optional($user)->can('viewCreator', User::class),
    ];
  }

////////////  MANAGE
////////////////////

  public function manage(Show $show): \Inertia\Response {
    // Eager load related entities for the Show model
    $show->load(['image.appSetting', 'user', 'team.user', 'schedules', 'recordings']);

    $episodeStatuses = DB::table('show_episode_statuses')->get()->toArray();
    $filteredStatuses = array_slice($episodeStatuses, 0, -2);

    // Fetch the Mist Server URI setting outside the loop to avoid repetitive database queries
    $mistServerUri = Cache::rememberForever('mist_server_uri', function () {
      return AppSetting::where('id', 1)->value('mist_server_uri');
    });

    // delete these:
//    $userRecordingsPath = config('paths.user_recordings_path');
//    $autoRecordingsPath = config('paths.auto_recordings_path');

    // Paginate recordings directly
    $paginatedRecordings = $show->recordings()->orderBy('start_dateTime', 'asc')->paginate(10);

    $userRecordingsPath = $settings->mist_server_settings['mist_server_user_recording_folder'] ?? null;
    $autoRecordingsPath = $settings->mist_server_settings['mist_server_automated_recording_folder'] ?? null;

//    Log::debug('Recording paths', ['userRecordingsPath' => $userRecordingsPath, 'autoRecordingsPath' => $autoRecordingsPath]);

    // Transform each recording for the frontend
//    $recordings = $paginatedRecordings->getCollection()->transform(function ($recording) use ($autoRecordingsPath, $userRecordingsPath, $mistServerUri, $show) {

    $recordings = $paginatedRecordings->getCollection()->transform(function ($recording) use ($userRecordingsPath, $autoRecordingsPath, $mistServerUri, $show) {

      // Initialize variables
      $streamPrefix = '';
      $userId = '';

      // Check if the path is for a user recording
      if (str_contains($recording->path, 'recordings_user')) {
        // Correctly strip out the part of the path up to and including 'recordings_user/'
        $basePath = '/media/recordings_user/';
        $relativePath = substr($recording->path, strlen($basePath));

        // Now explode the rest to find the user ID
        $pathParts = explode('/', $relativePath);
        if (!empty($pathParts[0]) && is_numeric($pathParts[0])) {
          $userId = $pathParts[0]; // This should now correctly be the user ID
          $streamPrefix = 'recordings_user_' . $userId . '%2B';
        } else {
          // Log error if user ID is missing or not numeric
          Log::error('Invalid or missing user ID in path', ['path' => $recording->path]);

          return null; // Skip this iteration if user ID is not valid
        }
      } else {
        // It's an automatic recording NOTE: $show->ulid is the show streamName
        $streamPrefix = 'recordings_show_' . strtolower($show->ulid) . '%2B';
      }

      // Extract the filename including the extension from the recording path
      $filename = basename($recording->path);

      // Get the rawurlencoded filename
      $encodedFilename = rawurlencode($filename);

      // Construct the stream name by appending the encoded filename to the prefix
      $playableStreamName = $streamPrefix . $encodedFilename . (str_ends_with($encodedFilename, '.mkv') ? '.mp4' : '');

//      // here's where we want to use the filename again.. rawurlencoded:
//      // Encode the entire path to ensure it's safe for URL usage
//      $encodedPath = rawurlencode(trim($recording->path, '/ '));
//
//      // finally it constructs the full path+filename which we call $streamName...
//      // Construct the stream name by appending the prefix and the encoded path
//      $streamName = $streamPrefix . $encodedPath . '.mp4';


      $settings = AppSetting::find(1);


      // delete these:
//      $path = str_contains($recording->path, $userRecordingsPath) ? str_replace($userRecordingsPath, '', $recording->path) : str_replace($autoRecordingsPath, '', $recording->path);
//      $streamPrefix = str_contains($recording->path, $userRecordingsPath) ? 'user_recordings%2B' : 'recordings%2B';
//      $encodedPath = rawurlencode(trim($path, '/ '));
//      $streamName = $streamPrefix . $encodedPath . '.mp4';

      $formattedStartTime = optional($recording->start_dateTime)->format('_Y.m.d.H.i.s') ?? 'unknown_time';
//      $downloadFileName = rawurlencode($show->name . $formattedStartTime . (str_ends_with($show->name . $formattedStartTime, '.mkv') ? '.mp4' : ''));
      $originalFileName = $show->name . $formattedStartTime;
      $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);

// Check if the filename ends with .mkv
      if (strtolower($extension) === 'mkv') {
        $downloadFileName = rawurlencode($originalFileName . '.mp4');
      } else {
        $downloadFileName = rawurlencode($originalFileName);
      }
      $downloadUrl = rtrim($mistServerUri, '/') . '/' . $playableStreamName . '?dl=1&filename=' . $downloadFileName;
      $shareUrl = rtrim($mistServerUri, '/') . '/' . $streamPrefix . $encodedFilename . '.html';

      return [
          'id'                          => $recording->id,
          'comment'                     => $recording->comment,
          'start_dateTime'              => $recording->start_dateTime,
          'end_dateTime'                => $recording->end_dateTime,
          'total_milliseconds_recorded' => $recording->total_milliseconds_recorded,
          'streamName'                  => $playableStreamName,
          'shareUrl'                    => $shareUrl,
          'download'                    => [
              'url'      => $downloadUrl,
              'fileName' => $downloadFileName,
          ],
      ];
    });

    // Update the paginated collection with transformed data
    $paginatedRecordings->setCollection($recordings);

//    $recordings = $show->recordings->map(function ($recording) use ($autoRecordingsPath, $userRecordingsPath, $mistServerUri, $show) {
//      Log::debug('Original recording path', ['path' => $recording->path]);
//      // Determine the correct directory to remove from the path
//      if (str_contains($recording->path, $userRecordingsPath)) {
//        $path = str_replace([$userRecordingsPath], [''], $recording->path);
//        $streamPrefix = 'user_recordings%2B'; // Use a different prefix if needed
//        Log::debug('User recording path modified', ['modifiedPath' => $path]);
//      } else {
//        $path = str_replace([$autoRecordingsPath], [''], $recording->path);
//        $streamPrefix = 'recordings%2B';
//        Log::debug('Auto recording path modified', ['modifiedPath' => $path]);
//      }
//
//      // URL encode the path to ensure it's safe for use in URLs
//      $path = trim($path, '/ ');  // Trim slashes and spaces // NEW
//      $encodedPath = rawurlencode($path);
//      $streamName = $streamPrefix . $encodedPath . '.mp4'; // Prepare stream name
//
//      // Format the start time as a string suitable for a filename, assuming $recording->start_dateTime is a Carbon instance
//      $formattedStartTime = optional($recording->start_dateTime)->format('_Y.m.d.H.i.s') ?? 'unknown_time';
//
//      // Construct the file name and encode it for URL usage
//      $downloadFileName = $show->name . $formattedStartTime . '.mp4';
//      $encodedFileName = rawurlencode($downloadFileName);
//
//      // Construct the download URL with correct query parameters
//      $downloadUrl = rtrim($mistServerUri, '/') . '/' . $streamName . '?dl=1&filename=' . $encodedFileName;
//      Log::debug('Download URL', ['url' => $downloadUrl]);
//
//      // Construct a share URL
//      $shareUrl = rtrim($mistServerUri, '/') . '/' . $encodedPath . '.html';
//      Log::debug('Share URL', ['url' => $shareUrl]);
//
//      // Return the modified recording with additional download details
//      return collect($recording->only([
//          'id', 'file_extension', 'start_dateTime', 'end_dateTime',
//          'total_milliseconds_recorded', 'mist_stream_wildcard_id', 'download_url', 'path', 'comment'
//      ]))
//          ->put('streamName', $streamName)
//          ->put('shareUrl', $shareUrl)
//          ->put('download', [
//              'url'      => $downloadUrl,
//              'fileName' => $downloadFileName
//          ]);
//    });

    $mostRecentSchedule = $show->schedules()->latest()->first();
    if ($mostRecentSchedule) {
      // Convert start_dateTime to UTC
      $startTimeUTC = Carbon::createFromFormat('Y-m-d H:i:s', $mostRecentSchedule->start_dateTime, $mostRecentSchedule->timezone)
          ->setTimezone('UTC')
          ->toDateTimeString();

      // Convert end_dateTime to UTC
      $endTimeUTC = Carbon::createFromFormat('Y-m-d H:i:s', $mostRecentSchedule->end_dateTime, $mostRecentSchedule->timezone)
          ->setTimezone('UTC')
          ->toDateTimeString();

      // Assuming $mostRecentSchedule is your Schedule model instance
      $extraMetadata = $mostRecentSchedule->extra_metadata;

      // Decode the JSON string into a PHP object
      $decodedMetadata = json_decode($extraMetadata);

      // Check if decoding was successful and the property exists
      if ($decodedMetadata && property_exists($decodedMetadata, 'daysOfWeek')) {
        $daysOfWeek = $decodedMetadata->daysOfWeek;
      } else {
        $daysOfWeek = []; // Default to an empty array if not found
      }

      // Construct the schedule details array
      $scheduleDetails = [
          'contentType'     => $mostRecentSchedule->content_type,
          'contentId'       => $mostRecentSchedule->content_id,
          'startTime'       => $startTimeUTC,
          'endTime'         => $endTimeUTC,
          'timezone'        => 'UTC', // Since we're converting to UTC
          'broadcastDates'  => $mostRecentSchedule->broadcast_dates,
          'durationMinutes' => $mostRecentSchedule->duration_minutes,
          'priority'        => $mostRecentSchedule->priority,
          'daysOfWeek'      => $daysOfWeek, // Now this contains the array of days or is empty
          'type'            => $mostRecentSchedule->recurrence_details_id ? 'recurring' : 'one-time',
      ];

    } else {
      // Handle case where no recent schedule exists
    }

//    Log::debug('Final data structure for front end', ['recordings' => $recordings]);

    return Inertia::render('Shows/{$id}/Manage', [
        'show'            => [
            'id'              => $show->id,
            'ulid'            => $show->ulid,
            'name'            => $show->name,
            'description'     => $show->description,
            'meta'            => $show->meta,
            'slug'            => $show->slug,
            'image'           => $this->transformImage($show->image),
            'copyrightYear'   => $show->created_at->format('Y'),
            'category'        => [
                'id'          => $show->getCachedCategory()->id ?? null,
                'name'        => $show->getCachedCategory()->name ?? null,
                'description' => $show->getCachedCategory()->description ?? null,
            ],
            'subCategory'     => [
                'id'          => $show->getCachedSubCategory()->id ?? null,
                'name'        => $show->getCachedSubCategory()->name ?? null,
                'description' => $show->getCachedSubCategory()->description ?? null,
            ],
            'notes'           => $show->notes,
            'isScheduled'     => $show->schedules->isNotEmpty(),
            'scheduleDetails' => $scheduleDetails ?? [],
            'showRunner'      => [
                'id'   => $show->showRunner->id ?? null,
                'name' => $show->showRunner->user->name ?? null,
            ],
//            'recordings'      => $show->recordings->map->only(['id', 'path', 'file_extension', 'start_dateTime', 'end_dateTime', 'total_milliseconds_recorded', 'mist_stream_wildcard_id', 'download_url']), // Include only necessary fields
            'recordings'      => $paginatedRecordings,
        ],
        'team'            => [
            'name' => $show->team->name,
            'slug' => $show->team->slug,
        ],
        'episodes'        => ShowEpisode::with('image.appSetting', 'show', 'showEpisodeStatus', 'recordings')
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
                'image'                    => $this->transformImage($showEpisode->image),
                'slug'                     => $showEpisode->slug,
                'episodeNumber'            => $showEpisode->episode_number,
                'notes'                    => $showEpisode->notes,
                'episodeStatus'            => $showEpisode->showEpisodeStatus->name,
                'episodeStatusId'          => $showEpisode->showEpisodeStatus->id,
                'isPublished'              => $showEpisode->isPublished,
                'scheduledReleaseDateTime' => $showEpisode->scheduled_release_dateTime,
                'releaseDateTime'          => $showEpisode->release_dateTime,
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

    Auth::user()->update([
        'isEditingShow_id' => $show->id,
    ]);

    $show->update([
        'isBeingEditedByUser_id' => Auth::id(),
    ]);

    // Assuming 'creator' or 'showRunner' is the correct relationship name
    $show->load('image.appSetting', 'showRunner.user');

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
    $team = Team::with(['members.creator' => function ($query) {
      $query->where('status_id', 1);
    }])->findOrFail($show->team_id);


//     Load the team with members who have creators with status_id of 1
//    $team = Team::with(['members' => function ($query) {
//      // Filter members to those who have a creator with status_id of 1
//      $query->whereHas('creator', function ($query) {
//        $query->where('status_id', 1);
//      });
//    }, 'members.creator']) // Ensure to still load the creator relationship
//    ->findOrFail($show->team_id);

//     Now, mapping team members to include creator_id, this will only include members filtered by the above condition
    $teamMembers = $team->members->map(function ($user) {
      // At this point, every user has a creator with status_id of 1 due to the query filter
      $creatorId = $user->creator ? $user->creator->id : null;

      return [
          'creator_id' => $creatorId,
          'name'       => $user->name,
        // Include any other necessary attributes
      ];
    });

//     Map team members to include creator_id
    $teamMembers = $team->members->map(function ($user) {
      return [
          'creator_id' => $user->creator ? $user->creator->id : null,
          'name'       => $user->name,
      ];
    });


    // Return the Inertia response
    return Inertia::render('Shows/{$id}/Edit', [
        'show'        => $showResourceArray,
        'team'        => $team,
        'teamMembers' => $teamMembers,
        'image'       => $show->image ? (new ImageResource($show->image))->resolve() : null,
        'categories'  => $categories,
        'statuses'    => $statuses,
        'can'         => [
            'editShow' => Auth::user()->can('edit', $show),
        ],
    ]);

  }

////////////  UPDATE
////////////////////

  public function update(HttpRequest $request, Show $show) {

    $creatorId = $this->determineShowRunnerId($request);

    // Check if the authenticated user is not a Creator
    if (is_null($creatorId)) {
      // Use withErrors to pass custom error messages, similar to validation errors
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
    $validatedData = $request->validate([
        'name'                   => ['required', 'string', 'max:255', Rule::unique('shows')->ignore($show->id)],
        'description'            => 'required|string|max:5000',
        'release_date'           => ['date', 'after:tomorrow'],
        'category'               => 'required|integer',
        'sub_category'           => 'nullable|integer',
        'www_url'                => 'nullable|active_url',
        'instagram_name'         => 'nullable|string|max:30',
        'telegram_url'           => 'nullable|active_url',
        'twitter_handle'         => 'nullable|string|min:4|max:15',
        'notes'                  => 'nullable|string|max:1024',
        'show_status_id'         => ['required', 'integer', 'exists:show_statuses,id'],
        'episode_play_order'     => 'required|string',
        'show_runner_creator_id' => 'nullable|integer|exists:creators,id'
    ], [
        'show_status_id.exists' => 'The selected status is invalid.',
        'release_date.after'    => 'The release date must be at least 24 hours in the future.'
    ]);

    // Sanitize social media handles
    $twitterHandle = isset($validatedData['twitter_handle']) ? str_replace('@', '', $validatedData['twitter_handle']) : null;
    $instagramName = isset($validatedData['instagram_name']) ? str_replace('@', '', $validatedData['instagram_name']) : null;

    // Sanitize URLs
    $wwwUrl = isset($validatedData['www_url']) ? filter_var($validatedData['www_url'], FILTER_SANITIZE_URL) : null;
    $telegramUrl = isset($validatedData['telegram_url']) ? filter_var($validatedData['telegram_url'], FILTER_SANITIZE_URL) : null;

    // Sanitize the notes to prevent XSS
    $sanitizedNotes = htmlentities($validatedData['notes'] ?? '', ENT_QUOTES, 'UTF-8');

    // Sanitize description
    $sanitizedDescription = Purifier::clean($validatedData['description'], 'customNoCss');

    // Sanitize and process name
    $processedName = strip_tags($validatedData['name']);

    $show->update([
        'name'                 => $processedName,
        'description'          => $sanitizedDescription,
        'slug'                 => \Str::slug($processedName),
        'release_date'         => $validatedData['release_date'] ?? null,
        'show_category_id'     => $validatedData['category'],
        'show_category_sub_id' => $validatedData['sub_category'] ?? null,
        'www_url'              => $wwwUrl,
        'instagram_name'       => $instagramName,
        'telegram_url'         => $telegramUrl,
        'twitter_handle'       => $twitterHandle,
        'notes'                => $sanitizedNotes,
        'show_status_id'       => $validatedData['show_status_id'],
        'episode_play_order'   => $validatedData['episode_play_order'],
        'show_runner'          => $creatorId
    ]);

    // redirect
    return redirect(route('shows.manage', [$show->slug]))->with('success', 'Show Updated Successfully');
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

////////////  MANAGE EPISODE
////////////////////////////
///
/// tec21: this might be better
/// in the ShowEpisodes Controller.


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
      $releaseDateTime = null;
      $releaseYear = null;
      $formattedUtcDatetime = null;
      $isPublished = 0;

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

        // Convert to UTC and format as MySQL datetime string
        $formattedUtcDatetime = $carbonDatetime->utc()->format('Y-m-d H:i:s');

      } else if ($newStatusId === 7) { // 7 is 'published'
        $now = Carbon::now();
        $minutes = $now->minute;
        $roundedMinutes = $minutes >= 30 ? 30 : 0;

        $releaseDateTime = $now->minute($roundedMinutes)->second(0)->millisecond(0)->format('Y-m-d H:i:s');
        $releaseYear = $now->year;
        $isPublished = 1;
      }

      if (!$episode) {
        return response()->json(['message' => 'Episode not found'], 404);
      }

      // Update the episode's status
      $episode->show_episode_status_id = $newStatusId;
      $episode->release_dateTime = $releaseDateTime;
      $episode->release_year = $releaseYear;
      $episode->scheduled_release_dateTime = $formattedUtcDatetime;
      $episode->isPublished = $isPublished;
      $episode->save();


      // Return the updated episode details
      return response()->json([
          'message'                    => 'Episode status updated successfully',
          'episode_id'                 => $episode->id,
          'episode_status_id'          => $episode->show_episode_status_id,
          'scheduled_release_dateTime' => $episode->scheduled_release_dateTime,
      ]);

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

  public function updateMeta(HttpRequest $request, Show $show): \Illuminate\Http\JsonResponse {
    try {
      $validatedData = $request->validate([
//          'isSaving' => 'nullable|boolean',
          'isUpdatingSchedule' => 'nullable|boolean',
          'updatedBy'          => 'nullable|string',
      ]);

//      Log::debug('Validated data', ['validatedData' => $validatedData]);

      // Since the 'meta' field is cast to JSON, it will be automatically decoded to an array
      $meta = $show->meta ?? [];

      if (is_string($meta)) {
        $meta = json_decode($meta, true);
      }

      // Check if someone else is already updating the schedule
      if (isset($meta['isUpdatingSchedule']) && $meta['isUpdatingSchedule'] && isset($meta['updatedBy']) && $meta['updatedBy'] !== $validatedData['updatedBy']) {
//        Log::debug('Schedule update conflict: currently being updated by ' . $meta['updatedBy']);

        return response()->json(['message' => '', 'notificationType' => 'silent']);
      }

      // Update 'isUpdatingSchedule' and 'updatedBy' keys
      $meta['isUpdatingSchedule'] = $validatedData['isUpdatingSchedule'] ?? null;
      $meta['updatedBy'] = $validatedData['isUpdatingSchedule'] ? ($validatedData['updatedBy'] ?? null) : null;
      $meta['triggeredBy'] = 'ShowsController updateMeta()';

//      Log::debug('Updated meta data', ['meta' => $meta]);

      // Assign the array directly to the 'meta' attribute
      $show->meta = $meta;
      $show->save();

//      Log::debug('Meta saved successfully for show', ['show_id' => $show->id, 'meta' => $meta]);

      broadcast(new CreatorContentStatusUpdated('show', $show->id, $meta));

      return response()->json(['message' => 'Show meta updated successfully']);
    } catch (\Exception $e) {
      Log::error('Error updating meta for show', [
          'show_id' => $show->id,
          'error'   => $e->getMessage(),
          'stack'   => $e->getTraceAsString()
      ]);

      return response()->json(['message' => 'Failed to update show meta'], 500);
    }
  }

  // Internal method to update meta without request validation
  protected function updateMetaInternally(Show $show): void {
    try {
      $meta = is_string($show->meta) ? json_decode($show->meta, true) : [];
      if (!is_array($meta)) {
        $meta = [];
      }

      // Update the meta data
      $meta['isUpdatingSchedule'] = false;
      $meta['updatedBy'] = null;
      $meta['triggeredBy'] = 'ShowsController updateMetaInternally()';

//      Log::debug('Updated meta data', ['meta' => $meta]);

      // Encode the meta array back to JSON and save it
      $show->meta = json_encode($meta);
      $show->save();

      broadcast(new CreatorContentStatusUpdated('show', $show->id, $meta));

//      Log::debug('Show meta updated successfully internally.');
    } catch (\Exception $e) {
      Log::error('Error updating meta for show internally', [
          'show_id' => $show->id,
          'error'   => $e->getMessage(),
          'stack'   => $e->getTraceAsString()
      ]);
    }
  }

  public function userLeftChannel(HttpRequest $request): \Illuminate\Http\JsonResponse {
    $user = $request->input('user');
    $channel = $request->input('channel');
    $showSlug = $request->input('showSlug'); // Assuming you pass the show slug

//    Log::debug('User left the channel', ['user' => $user, 'channel' => $channel]);

    // Find the show based on the slug
    $show = Show::where('slug', $showSlug)->first();

    if ($show) {
      $meta = is_string($show->meta) ? json_decode($show->meta, true) : [];
      if (!is_array($meta)) {
        $meta = [];
      }

      // Check if the user that left is the one updating the schedule
      if (isset($meta['isUpdatingSchedule']) && $meta['isUpdatingSchedule'] && isset($meta['updatedBy']) && $meta['updatedBy'] === $user['name']) {
        // Call updateMeta internally
        $this->updateMetaInternally($show);
      }
    }

    return response()->json(['message' => 'Event broadcasted.']);
  }

}
