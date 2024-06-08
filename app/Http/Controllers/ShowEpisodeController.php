<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Http\Resources\ImageResource;
use App\Http\Resources\VideoResource;
use App\Jobs\AddOrUpdateMistStreamJob;
use App\Jobs\AddVideoUrlFromEmbedCodeJob;
use App\Jobs\ProcessVideoInfo;
use App\Jobs\SendTeamMembersNotificationJob;
use App\Models\CreativeCommons;
use App\Models\Creator;
use App\Models\Image;
use App\Models\MistStream;
use App\Models\MistStreamWildcard;
use App\Models\Notification;
use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\Video;
use App\Policies\ShowEpisodePolicy;
use App\Traits\ImageTransformable;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Rules\UniqueEpisodeName;
use Inertia\Inertia;
use Mews\Purifier\Facades\Purifier;
use Symfony\Component\Uid\Ulid;

class ShowEpisodeController extends Controller {

  private string $formattedScheduledDateTime;
  private string $formattedReleaseDateTime;
  private ?int $videoId = null;
  use ImageTransformable;

  public function __construct() {

//        $this->middleware('can:viewShowManagePage,show')->only(['manage']);
//        $this->middleware('can:editShowEpisode,showEpisode')->only(['edit']);
//        $this->middleware('can:createShow,show')->only(['create']);F
//        $this->middleware('can:createEpisode,show')->only(['createEpisode']);
//        $this->middleware('can:viewEpisodeManagePage,show')->only(['manageEpisode']);
    $this->middleware('can:editEpisode,show')->only(['edit']);
    $this->middleware('can:destroy,showEpisode')->only(['destroy']);

    $this->formattedReleaseDateTime = '';
    $this->formattedScheduledDateTime = '';

  }


////////////  INDEX
///////////////////

  public function index() {
    return Inertia::render('Shows/{$id}/Episodes/Index', [

    ]);
  }


////////////  CREATE AND STORE
//////////////////////////////
///
/// The ShowEpisode Create method
/// is currently in the Shows
/// Controller.
///

  public function create(Show $show) {

    $creativeCommons = CreativeCommons::all();

    $show->load('image.appSetting', 'showRunner.user'); // Eager load necessary relationships

    return Inertia::render('Shows/{$id}/Episodes/Create', [
        'show'             => [
            'name'        => $show->name,
            'id'          => $show->id,
            'slug'        => $show->slug,
            'showRunner'  => [
                'name' => $show->showRunner->user->name ?? null,
            ],
            'image'       => $this->transformImage($show->image),
            'category'    => [
                'name'        => $show->getCachedCategory()->name ?? null,
                'description' => $show->getCachedCategory()->description ?? null,
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


  public function store(HttpRequest $request) {

    // Add a default value for copyrightYear if it's missing
    $currentYear = date('Y');
    $request->merge(['copyrightYear' => $request->input('copyrightYear', $currentYear)]);

    // Log the request data for debugging purposes
    Log::debug('Request Data:', $request->all());

    $rules = [
        'name'                => [
            'required',
            'string',
            'max:255',
            'distinct:ignore_case',
            new UniqueEpisodeName($request->show_id)
        ],
        'description'         => 'required|string|max:5000',
        'user_id'             => 'required|exists:users,id',
        'show_id'             => 'required|exists:shows,id',
        'show_slug'           => 'required|exists:shows,slug',
        'episode_number'      => 'nullable|string|min:1|max:10',
        'video_url'           => 'nullable|ends_with:.mp4',
        'video_embed_code'    => 'nullable|string|max:2000',
        'notes'               => 'nullable|string|max:2000',
      // 'youtube_url'         => 'active_url', // Commented out
        'creative_commons_id' => 'required|integer|exists:creative_commons,id',
        'copyrightYear'       => [
            'required',
            'integer',
            'min:1800',
            'max:' . date('Y'),
        ],

    ];

    $messages = [
      // Custom error messages
        'name.required'                => 'The name is required.',
        'description.required'         => 'A description is required.',
        'user_id.required'             => 'A user ID is required.',
        'show_id.required'             => 'A show ID is required.',
        'creative_commons_id.required' => 'Please choose a Creative Commons / Copyright.',
        'creative_commons_id.integer'  => 'The Creative Commons ID must be an integer.',
        'creative_commons_id.exists'   => 'The selected Creative Commons ID is invalid.',
        'copyrightYear.integer'        => 'The copyright year must be an integer.',
        'copyrightYear.required'       => 'The copyright year is required.',
    ];

    // Create a validator instance
    $validator = Validator::make($request->all(), $rules, $messages);

    // Apply conditional validation for copyrightYear
//    $validator->sometimes('copyrightYear', 'required|integer|min:1900|max:' . date('Y'), function ($input) {
//      return;
//    });


    // Perform validation
    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    // Retrieve the validated data
    $validatedData = $validator->validated();


    // Clean the description
    $sanitizedDescription = Purifier::clean($validatedData['description'], 'customNoCss');


//    // Conditional validation for copyrightYear
//    $validatedData->sometimes('copyrightYear', 'required|integer|min:1900|max:' . date('Y'), function ($input) {
//      return $input->creative_commons_id != 8;
//    });
//
//    if ($validatedData->fails()) {
//      return redirect()->back()->withErrors($validatedData)->withInput();
//    }
//
//    $sanitizedDescription = Purifier::clean($validatedData['description'], 'customNoCss');
    // MOVE THIS TO A JOB... SEND THE showEpisode SLUG with it.
    // get the *.mp4 video url from embed code
    // save the *.mp4 url to the video_file_url
//        if ($request->video_embed_code) {
//            $videoUrlFromEmbedCode = $this->getVideoUrlFromEmbedCode($request->video_embed_code);
//            if ($videoUrlFromEmbedCode === false) {
//                $videoUrl = $request->video_url;
//            } else
//                $videoUrl = $videoUrlFromEmbedCode;
//        } else $videoUrl = $request->video_url;

    // Sanitize description


    DB::beginTransaction();

    try {
      $user = Auth::user();

      $showEpisode = new ShowEpisode();
      $showEpisode->ulid = (string) Ulid::generate();
      $showEpisode->isBeingEditedByUser_id = $user->id;
      $showEpisode->name = e($validatedData['name']);
      $showEpisode->description = $sanitizedDescription;
      $showEpisode->user_id = $user->id;
      $showEpisode->show_id = $validatedData['show_id'];
      $showEpisode->episode_number = e($validatedData['episode_number']);
      $showEpisode->slug = \Str::slug($validatedData['name']);
      $showEpisode->notes = e($validatedData['notes']);
      $showEpisode->release_year = Carbon::now()->format('Y');
      // Set copyrightYear to null if it's not required
      $showEpisode->copyrightYear = e($validatedData['copyrightYear']);
      $showEpisode->creative_commons_id = $validatedData['creative_commons_id'];
      $showEpisode->video_embed_code = e($validatedData['video_embed_code']);

      $showEpisode->save();

      if ($validatedData['video_embed_code'] && !$validatedData['video_url']) {

        // Create and save the notification
        $notification = new Notification;
        $notification->user_id = $user->id;

        // make the image the show_episode_poster
        $notification->image_id = $showEpisode->image_id;
        $notification->url = '/shows/' . $showEpisode->show->slug . '/episode/' . $showEpisode->slug . '/manage';
        $notification->title = $showEpisode->name;
        $notification->message = 'The video url is being generated from the embed code. You will be notified when it is done.';


        $notification->save();

        // Trigger the event to broadcast the new notification
        event(new NewNotificationEvent($notification));
        AddVideoUrlFromEmbedCodeJob::dispatch($showEpisode)->onQueue('video_processing');
      }

      if (!empty($validatedData['video_url'])) { // Check for non-empty video_url

        // Create and save the video as before
        $video = new Video([
            'user_id'          => $user->id,
            'name'             => 'External video',
            'file_name'        => 'external_video_' . Str::uuid(),
            'type'             => 'video/mp4',
            'video_url'        => $validatedData['video_url'],
            'storage_location' => 'external',
            'show_episodes_id' => $showEpisode->id,
        ]);
        $video->save();

        // Dispatch job as before
        $jobName = null;
        dispatch(new ProcessVideoInfo($video->id, $jobName))->onQueue('high');

        $showEpisode->video_id = $video->id;
        $showEpisode->youtube_url = $validatedData['youtube_url'];
        $showEpisode->video_embed_code = $validatedData['video_embed_code'];
        $showEpisode->save();

      }


      if ($showEpisode->show->show_status_id === 1) {
        $show = Show::find($showEpisode->show_id);
        $show->show_status_id = 2;
        $show->save();
      }

      $showSlug = $validatedData['show_slug'];
      $showEpisodeSlug = $showEpisode->slug;

      $title = $showEpisode->name;

      $message = '<span class="text-green-500 italic">A new episode has been created.</span>';

      $url = '/shows/' . $showSlug . '/episode/' . $showEpisodeSlug;

      dispatch(new SendTeamMembersNotificationJob($showEpisode->show->team, $title, $message, $url));

      if ($validatedData['video_embed_code'] && !$validatedData['video_url']) {
        // Create and save the notification
        $notification = new Notification;
        $userId = auth()->user()->id;
        $notification->user_id = $userId;

        // make the image the show_episode_poster
        $notification->image_id = $showEpisode->image_id;
        $notification->url = '/shows/' . $showEpisode->show->slug . '/episode/' . $showEpisode->slug;
        $notification->title = $showEpisode->name;
        $notification->message = 'The video url is being generated from the embed code. You will be notified when it is done.';
        $notification->save();

        // Trigger the event to broadcast the new notification
        event(new NewNotificationEvent($notification));
        AddVideoUrlFromEmbedCodeJob::dispatch($showEpisode)->onQueue('video_processing');
      }

      $mistStream = MistStream::firstOrCreate([
          'name' => 'episode',
      ], [
          'source'    => 'push://',
          'comment'   => 'Created for Episode integration.',
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

      $lowercaseShowEpisodeUlid = strtolower($showEpisode->ulid); // Mist server can only use lowercase letters, numbers _ - or .

      $mistStreamWildcard = MistStreamWildcard::create([
          'name'           => 'episode+' . $lowercaseShowEpisodeUlid, // by appending show+ this becomes our full stream key.
          'comment'        => 'Automatically created with new episode.',
          'mime_type'      => 'application/x-mpegURL',
          'source'         => 'push://',
          'mist_stream_id' => $mistStream->id,
      ]);

      $showEpisode->update(['mist_stream_wildcard_id' => $mistStreamWildcard->id]);

      DB::commit();

      return redirect()
          ->route('showEpisode.manage',
              ['show' => $showSlug, 'showEpisode' => $showEpisodeSlug])
          ->with('success', 'Episode Created Successfully');

    } catch (\Exception $e) {
      DB::rollBack();
      Log::error("Failed to create episode and associated entities: {$e->getMessage()}");

      // Optionally, return an error response or redirect with error message
      return back()->with(['error' => 'Failed to create the episode.'])->withInput();
    }
  }



////////////  SHOW
//////////////////

  public function show(Show $show, ShowEpisode $showEpisode) {

    // Eager load related entities for the Show model
    $show->load('user', 'team.user', 'image.appSetting');

    // Eager load related entities for the ShowEpisode model
    $showEpisode->load(['creativeCommons', 'video.appSetting', 'video.mistStream', 'video.mistStreamWildcard', 'mistStreamWildcard', 'image.appSetting']);

//      TODO: Add TeamMember to this eager load

    // Assuming $teamId is available
    $teamId = $show->team_id;

//        $video = Video::where('show_episodes_id', $showEpisode->id)->first();


    // NOTE: we are deprecating the backend timezone conversion
    // in favour of the frontend timezone conversion.
    // OLD CODE: convert release dateTime to user's timezone
//    if ($showEpisode->release_dateTime) {
//      $this->formattedReleaseDateTime = $this->convertTimeToUserTime($showEpisode->release_dateTime);
//    }


    $videoIsAvailable = $showEpisode->video?->video_url ||
        ($showEpisode->video?->folder && $showEpisode->video?->upload_status !== 'processing');

//    Log::warning($videoIsAvailable);


    // Determine the media type based on its storage location.
    // If the storage location is marked as 'external', categorize it as 'externalVideo';
    // otherwise, it's considered an internal 'show' video.
//    $mediaType = $showEpisode->video?->storage_location === 'external' ? 'externalVideo' : 'show'; // Adjust logic as needed
    // Bitchute needs to be run through a proxy for our AudioStore settings to work... so we are replacing
    // our $mediaType with this:
    // Check the storage_location and set $mediaType accordingly
    if ($showEpisode->video?->storage_location === 'external') {
      $mediaType = 'externalVideo';
    } elseif ($showEpisode->video?->storage_location === 'bitchute') {
      $mediaType = 'bitchute';
    } else {
      $mediaType = 'show';

    }
    if (!$showEpisode->relationLoaded('video')) {
      return response()->json(['error' => 'Video not loaded'], 500);
    }

    $user = Auth::user();

    $component = $user ? 'Shows/{$id}/Episodes/{$id}/Index' : 'LoggedOut/Shows/{$id}/Episodes/{$id}/Index';

    return Inertia::render($component, [
        'show'     => [
            'name'               => $show->name,
            'slug'               => $show->slug,
            'showRunner'         => $show->user->name,
            'image'              => $show->image ? (new ImageResource($show->image))->resolve() : null,
            'copyright_year'     => $show->created_at->format('Y'),
            'first_release_year' => $show->first_release_year,
            'last_release_year'  => $show->last_release_year,
            'category'           => $show->getCachedCategory() ?? null,
            'subCategory'        => $show->getCachedSubCategory(),
        ],
        'team'     => [
            'name' => $show->team->name,
            'slug' => $show->team->slug,
        ],
        'episode'  => [
            'id'                         => $showEpisode->id,
            'ulid'                       => $showEpisode->ulid,
            'name'                       => $showEpisode->name,
            'slug'                       => $showEpisode->slug,
            'description'                => $showEpisode->description,
            'episode_number'             => $showEpisode->episode_number,
            'created_at'                 => $showEpisode->created_at,
            'release_year'               => $showEpisode->release_year ?? null,
            'release_dateTime'           => $showEpisode->release_dateTime ?? null,
            'creative_commons'           => $showEpisode->creativeCommons ?? null,
            'copyrightYear'              => $showEpisode->copyrightYear ?? null,
            'scheduled_release_dateTime' => $showEpisode->scheduled_release_dateTime ?? null,
            'mist_stream_wildcard_id'    => $showEpisode->mist_stream_wildcard_id,
            'mist_stream_wildcard'       => $showEpisode->mistStreamWildcard,
            'image'                      => $showEpisode->image ? (new ImageResource($showEpisode->image))->resolve() : null,
            'video'                      => (new VideoResource($showEpisode->video))->resolve(),
//            'video'                      => [
//                'ulid'                 => $showEpisode->video->ulid ?? '',
//                'isAvailable'          => $videoIsAvailable,
//                'mediaType'            => $mediaType, // New attribute for NowPlayingStore
//                'file_name'            => $showEpisode->video->file_name ?? '',
//                'cdn_endpoint'         => $showEpisode->video->appSetting->cdn_endpoint ?? '',
//                'folder'               => $showEpisode->video->folder ?? '',
//                'cloud_folder'         => $showEpisode->video->cloud_folder ?? '',
//                'upload_status'        => $showEpisode->video->upload_status ?? '',
//                'video_url'            => $showEpisode->video->video_url ?? '',
//                'type'                 => $showEpisode->video->type ?? '',
//                'storage_location'     => $showEpisode->video->storage_location ?? '',
//                'mist_stream'          => $showEpisode->video->mistStream ?? null,
//                'mist_stream_wildcard' => $showEpisode->video->mistStreamWildcard ?? null,
//            ],
            'youtube_url'                => $showEpisode->youtube_url ?? '',
            'video_embed_code'           => $showEpisode->video_embed_code,
        ],
//        'video'    => [
//            'file_name'     => $showEpisode->showEpisodevideo->file_name ?? '',
//            'cdn_endpoint'  => $showEpisode->video->appSetting->cdn_endpoint ?? '',
//            'folder'        => $showEpisode->video->folder ?? '',
//            'cloud_folder'  => $showEpisode->video->cloud_folder ?? '',
//            'upload_status' => $showEpisode->video->upload_status ?? '',
//        ],
        'creators' => TeamMember::where('team_id', $teamId)
            ->join('users', 'team_members.user_id', '=', 'users.id')
            ->select('users.*', 'team_members.user_id')
            ->latest()
            ->paginate(3)
            ->withQueryString()
            ->through(fn($user) => [
                'id'                 => $user->id,
                'name'               => $user->name,
                'profile_photo_path' => $user->profile_photo_path,
            ]),
        'can'      => [
            'manageShow'  => optional($user)->can('manage', $show),
            'editShow'    => optional($user)->can('edit', $show),
            'viewCreator' => optional($user)->can('viewCreator', User::class),
        ]

    ]);

  }


////////////  MANAGE
////////////////////
///
/// The ShowEpisode Manage method
/// is currently in the Shows
/// Controller.
///

  public function manage(Show $show, ShowEpisode $showEpisode) {

//    // convert release dateTime to user's timezone
//    if ($showEpisode->release_dateTime) {
//      $this->formattedReleaseDateTime = $this->convertTimeToUserTime($showEpisode->release_dateTime);
//    }
//
//    // convert scheduled_release dateTime to user's timezone
//    if ($showEpisode->scheduled_release_dateTime) {
//      $this->formattedScheduledDateTime = $this->convertTimeToUserTime($showEpisode->scheduled_release_dateTime);
//    }

//    $show->load('team', 'showEpisodes.creativeCommons', 'showEpisodes.video', 'image', 'appSetting', 'category', 'subCategory'); // Eager load necessary relationships

    // Eager load relationships for the Show model
    $show->load(['team.image.appSetting', 'image.appSetting', 'showRunner.user']);

    // Eager load relationships for the ShowEpisode model
    $showEpisode->load(['creativeCommons', 'video.appSetting', 'image.appSetting', 'showEpisodeStatus', 'mistStreamWildcard']);


    return Inertia::render('Shows/{$id}/Episodes/{$id}/Manage', [
        'show'              => [
            'name'          => $show->name,
            'slug'          => $show->slug,
            'showRunner'    => [
                'name' => $show->showRunner->user->name ?? null,
            ],
            'image'         => $show->image ? (new ImageResource($show->image))->resolve() : null,
            'copyrightYear' => $show->created_at->format('Y'),
        ],
        'team'              => [
            'name'  => $show->team->name,
            'slug'  => $show->team->slug,
            'image' => $show->team->image ? (new ImageResource($show->team->image))->resolve() : null,
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
            'release_dateTime'           => $showEpisode->release_dateTime ?? null,
            'scheduled_release_dateTime' => $showEpisode->scheduled_release_dateTime ?? null,
            'copyright_year'              => $showEpisode->copyrightYear ?? null,
            'creative_commons'           => $showEpisode->creativeCommons ?? null,
            'mist_stream_wildcard_id'    => $showEpisode->mist_stream_wildcard_id,
            'mist_stream_wildcard'       => $showEpisode->mistStreamWildcard,
            'video_id'                   => $showEpisode->video_id,
            'youtube_url'                => $showEpisode->youtube_url,
            'video_embed_code'           => $showEpisode->video_embed_code,
            'video'                      => (new VideoResource($showEpisode->video))->resolve(),
            'image'                      => $showEpisode->image ? (new ImageResource($showEpisode->image))->resolve() : null,

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


////////////  EDIT
//////////////////

  public function edit(Show $show, ShowEpisode $showEpisode) {

    // Update user editing status securely and efficiently
    $user = Auth::user();
    $user->update(['isEditingShowEpisode_id' => $showEpisode->id]);

    // Mark the episode as being edited by the user
    $showEpisode->update(['isBeingEditedByUser_id' => $user->id]);

    $creativeCommons = CreativeCommons::all();

//    // convert release dateTime to user's timezone
//    if ($showEpisode->release_dateTime) {
//      $this->formattedReleaseDateTime = $this->convertTimeToUserTime($showEpisode->release_dateTime);
//    }
//
//    // convert scheduled_release dateTime to user's timezone
//    if ($showEpisode->scheduled_release_dateTime) {
//      $this->formattedScheduledDateTime = $this->convertTimeToUserTime($showEpisode->scheduled_release_dateTime);
//    }

    // Eager load necessary relationships for the show
    $show->load('team', 'user', 'image.appSetting');

    // Since showEpisode is a specific episode, load related data just for this instance
    $showEpisode->load('creativeCommons', 'image.appSetting', 'video.appSetting');

    $creativeCommons = $showEpisode->getCachedCreativeCommons();

    return Inertia::render('Shows/{$id}/Episodes/{$id}/Edit', [

        'episode'          => [
            'id'                         => $showEpisode->id,
            'ulid'                       => $showEpisode->ulid,
            'name'                       => $showEpisode->name,
            'slug'                       => $showEpisode->slug,
            'description'                => $showEpisode->description,
            'notes'                      => $showEpisode->notes,
            'episode_number'             => $showEpisode->episode_number,
            'status'                     => $showEpisode->showEpisodeStatus,
            'release_year'               => $showEpisode->release_year ?? null,
            'release_dateTime'           => $showEpisode->release_dateTime ?? null,
            'scheduled_release_dateTime' => $showEpisode->scheduled_release_dateTime ?? null,
            'copyright_year'             => $showEpisode->copyrightYear ?? null,
            'creative_commons'           => $showEpisode->creativeCommons ?? null,
            'mist_stream_id'             => $showEpisode->mist_stream_id,
            'youtube_url'                => $showEpisode->youtube_url,
            'video_embed_code'           => $showEpisode->video_embed_code,
            'video'                      => (new VideoResource($showEpisode->video))->resolve(),
//            'video'                      => [
//                'id'               => $showEpisode->video->id ?? null,
//                'file_name'        => $showEpisode->video->file_name ?? '',
//                'cdn_endpoint'     => $showEpisode->video->appSetting->cdn_endpoint ?? '',
//                'folder'           => $showEpisode->video->folder ?? '',
//                'cloud_folder'     => $showEpisode->video->cloud_folder ?? '',
//                'upload_status'    => $showEpisode->video->upload_status ?? '',
//                'video_url'        => $showEpisode->video->video_url ?? '',
//                'type'             => $showEpisode->video->type ?? '',
//                'storage_location' => $showEpisode->video->storage_location ?? '',
//            ],
        ],
        'image'            => $showEpisode->image ? (new ImageResource($showEpisode->image))->resolve() : null,
        'show'             => [
            'id'                 => $show->id,
            'name'               => $show->name,
            'slug'               => $show->slug,
            'showRunner'         => $show->user->name,
            'image'              => $show->image ? (new ImageResource($show->image))->resolve() : null,
            'first_release_year' => $show->first_release_year,
            'last_release_year'  => $show->last_release_year,
            'category'           => $show->getCachedCategory()->name ?? null,
            'subCategory'        => $show->getCachedSubCategory()->name ?? null,
        ],
        'team'             => [
            'name' => $show->team->name,
            'slug' => $show->team->slug,
        ],
        'creative_commons' => $creativeCommons,
        'can'              => [
            'manageShow'  => $user->can('manage', $show),
            'editShow'    => $user->can('edit', $show),
            'editEpisode' => $user->can('edit', $show),
            'viewCreator' => $user->can('viewCreator', User::class),
        ]
    ]);
  }

////////////  UPLOAD
////////////////////

  public function upload(Show $show, ShowEpisode $showEpisode) {
//        $team = Show::query()->where('id', $show->id)->pluck('team_id')->firstOrFail();

    // Currently this queries all images in the database
    // this needs to be changed to limit the query.
    //
    DB::table('users')->where('id', Auth::user()->id)->update([
        'isEditingShowEpisode_id' => $showEpisode->id,
    ]);

    DB::table('show_episodes')->where('id', $showEpisode->id)->update([
        'isBeingEditedByUser_id' => Auth::user()->id,
    ]);

    return Inertia::render('Shows/{$id}/Episodes/{$id}/Upload', [
        'poster'  => $showEpisode->image->name,
        'show'    => [
            'name'       => $show->name,
            'slug'       => $show->slug,
            'showRunner' => $show->user->name,
            'image'      => [
                'id'           => $show->image->id,
                'name'         => $show->image->name,
                'folder'       => $show->image->folder,
                'cdn_endpoint' => $show->appSetting->cdn_endpoint,
                'cloud_folder' => $show->image->cloud_folder,
            ],
        ],
        'team'    => [
            'name' => $show->team->name,
            'slug' => $show->team->slug,
        ],
        'episode' => $showEpisode,
        'can'     => [
            'manageShow'  => Auth::user()->can('manage', $show),
            'editShow'    => Auth::user()->can('edit', $show),
            'viewCreator' => Auth::user()->can('viewCreator', User::class),
        ]
    ]);
  }


////////////  UPDATE
////////////////////

  public function update(HttpRequest $request, ShowEpisode $showEpisode) {

    // validate the request
    $validatedData = $request->validate([
        'name'                       => [
            'required',
            'string',
            'max:255',
            'distinct:ignore_case',
            new UniqueEpisodeName($showEpisode),
        ],
        'creative_commons_id'        => 'required|integer|exists:creative_commons,id',
        'copyright_year'             => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
        'episode_number'             => 'nullable|string|min:1|max:10',
        'description'                => 'required|string|max:5000',
        'notes'                      => 'nullable|string|max:2000',
        'video_url'                  => 'nullable|active_url|ends_with:.mp4',
        'youtube_url'                => 'nullable|active_url',
        'video_embed_code'           => 'nullable|string|max:2000',
        'release_dateTime'           => 'nullable|date',
        'scheduled_release_dateTime' => 'nullable|date|after:now',
    ], [
        'copyright_year.integer'       => 'Please choose a copyright year',
        'creative_commons_id.required' => 'Please choose a Creative Commons / Copyright',
    ]);

    // Sanitize the name field
    $processedName = strip_tags($validatedData['name']);

    // Sanitize the episode_number field
    // Sanitize the episode_number field if it exists
    if (array_key_exists('episode_number', $validatedData)) {
      $validatedData['episode_number'] = e($validatedData['episode_number']);
    }

    // Sanitize description
    $sanitizedDescription = Purifier::clean($validatedData['description'], 'customNoCss');

    // Check if the video_url is not empty and is different from the existing one.
    if (!empty($validatedData['video_url'])) {
      // Attempt to find an existing video with the same URL for any episode
      $existingVideo = Video::where('video_url', $validatedData['video_url'])->first();

      if ($existingVideo) {
        // If a video with the same URL already exists, associate its ID with the show episode
        $showEpisode->video_id = $existingVideo->id;
      } else {
        // No existing video with the same URL, proceed to create a new Video instance
        $video = new Video([
            'user_id'          => Auth::id(), // Simpler way to get the authenticated user's ID
            'name'             => 'External video',
            'file_name'        => 'external_video_' . Str::uuid(),
            'type'             => 'video/mp4', // Assuming a default type for external videos
            'video_url'        => $validatedData['video_url'],
            'storage_location' => 'external',
          // 'show_episodes_id' might not be needed if you're associating via $showEpisode->video_id below
          // 'show_episodes_id' => $showEpisode->id,
        ]);
        $video->save();

        // Dispatch job as before
        $jobName = null;
        dispatch(new ProcessVideoInfo($video->id, $jobName))->onQueue('high');

        // After saving the new video, set its ID as the video_id for the show episode
        $showEpisode->video_id = $video->id;
      }
    }

    $releaseYear = null;

    Log::channel('custom_error')->info('episode name: ' . $request->name);
    Log::channel('custom_error')->info('release date in: ' . $request->release_dateTime);
    Log::channel('custom_error')->info('scheduled date in: ' . $request->scheduled_release_dateTime);

    if ($validatedData['release_dateTime']) {
      // Create a Carbon instance from the DateTime string
      $releaseDateTime = Carbon::parse($validatedData['release_dateTime'])->toDateTimeString();

      // Get the year from the Carbon instance
      $releaseYear = Carbon::parse($validatedData['release_dateTime'])->year;
    }

    if ($validatedData['scheduled_release_dateTime']) {
      $showEpisode->show_episode_status_id = 6;
      $releaseYear = null;

      $scheduledReleaseDatetime = Carbon::parse($validatedData['scheduled_release_dateTime'])->toDateTimeString();
    }

    if ($showEpisode->scheduled_release_dateTime && !$validatedData['scheduled_release_dateTime']) {
      $showEpisode->show_episode_status_id = 5;
    }

    $showSlug = Show::query()->where('id', $showEpisode->show_id)->pluck('slug')->first();
    $oldEmbedCode = $showEpisode->video_embed_code;
    $videoUrlFromEmbedCode = '';

    // get the *.mp4 video url from embed code
    // save the *.mp4 url to the video_file_url
//        if ($request->video_embed_code && $request->video_embed_code !== $showEpisode->video_embed_code) {
//            $videoUrlFromEmbedCode = $this->getVideoUrlFromEmbedCode($request->video_embed_code);
//            if ($videoUrlFromEmbedCode === false) {
//                $videoUrl = $request->video_url;
//            } else
//                $videoUrl = $videoUrlFromEmbedCode;
//        } else $videoUrl = $request->video_url;

    // update the show
    $showEpisode->name = $validatedData['name'];
    $showEpisode->description = $sanitizedDescription;
    $showEpisode->episode_number = $validatedData['episode_number'] ?? null;
    $showEpisode->slug = \Str::slug($validatedData['name']);
    $showEpisode->notes = e($validatedData['notes']);
    $showEpisode->youtube_url = $validatedData['youtube_url'];
    $showEpisode->video_embed_code = $validatedData['video_embed_code'];
    $showEpisode->release_dateTime = $releaseDateTime ?? null;
    $showEpisode->release_year = $releaseYear ?? null;
    $showEpisode->scheduled_release_dateTime = $scheduledReleaseDatetime ?? null;
    $showEpisode->copyrightYear = $validatedData['copyright_year'] ?? null;
    $showEpisode->creative_commons_id = $validatedData['creative_commons_id'];
    $showEpisode->save();

    if ($validatedData['video_embed_code'] !== $oldEmbedCode && !$validatedData['video_url']) {
      // Create and save the notification
      $notification = new Notification;
      $userId = auth()->user()->id;
      $notification->user_id = $userId;

      // Make the image the show_episode_poster
      $notification->image_id = $showEpisode->image_id;
      $notification->url = '/shows/' . $showEpisode->show->slug . '/episode/' . $showEpisode->slug . '/manage';
      $notification->title = $showEpisode->name;
      $notification->message = 'The video URL is being generated from the embed code. You will be notified when it is done.';
      $notification->save();

      // Trigger the event to broadcast the new notification
      event(new NewNotificationEvent($notification));
      AddVideoUrlFromEmbedCodeJob::dispatch($showEpisode)->onQueue('video_processing');
    }

//        if ($videoUrlFromEmbedCode === false) {
////                return response()->json(['message' => 'The embed code could not get a video file.'], 500);
//            return redirect(route('shows.showEpisodes.show', [$showSlug, $showEpisodeSlug]))->with([
//                'message' => 'The embed code could not get a video file. Please check the embed code and the video url.',
//                'messageType' => 'warning',
//            ]);
//        }

    // gather the data needed to render the Manage page
    // this is all redundant. It's all contained in the
    // teams.manage route above. But I (tec21) don't know
    // how to simplify this *frustrated*.

    // get new slug
//        $showEpisodeSlug = $showEpisode->slug;

    // redirect
    return redirect(route('showEpisode.manage', [$showSlug, $showEpisode->slug]))
        ->with('success', 'Episode Updated Successfully');


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
    $id = $request->episodeId;
    $episode = ShowEpisode::find($id);

    // validate the request
    $request->validate([
        'notes' => 'nullable|string|max:1024',
    ]);

    // update the show notes
    $episode->notes = $request->notes;
    $episode->save();

    return $episode;
  }


////////////  DESTROY
/////////////////////
///
///

  public function destroy(Show $show, ShowEpisode $showEpisode) {
    // TODO: remove showEpisode from Image (discuss first..)

    if ($showEpisode->isPublished) {
      return response()->json(['alert', 'This episode has already been published. Please contact the notTV team for assistance.'], 400);
    }
    $showEpisode->delete();
    Log::channel('slack')->info('An episode has been deleted. Show: ' . $showEpisode->show->name . '. Episode: ' . $showEpisode->name);

    // Create a SendTeamMembersNotificationJob
    // Send the following message with:
    // 1. teamSlug or teamId
    // 2. message (An episode has been deleted. Show: ' . $showEpisode->show->name . '. Episode: ' . $showEpisode->name)
    $message = '<span class="text-yellow-500 italic">An episode has been deleted.</span> <br /><br /> <span class="font-semibold">Show:</span> ' . $showEpisode->show->name . '.<br /> <span class="font-semibold">Episode:</span> ' . $showEpisode->name;

    $title = $showEpisode->show->team->name;

    $url = '/teams/' . $showEpisode->show->team->slug . '/manage/';

    dispatch(new SendTeamMembersNotificationJob($showEpisode->show->team, $title, $message, $url));


    //return with message
    return response()->json(['message', 'Episode Deleted Successfully'], 200);

    // redirect
//        return redirect()->route('showEpisode')->with('message', 'Episode Deleted Successfully');
  }


  public function getVideoUrlFromEmbedCode($embedCode) {
    $firstMp4 = '';
    $matches = [];
    $response = '';
    $sourceIs = '';

    try {
      // strip the url from the embed code
      $regex = '/https?\:\/\/[^\",]+/i';
      preg_match($regex, $embedCode, $match);
      $sourceUrl = implode(" ", $match);
//            $scraperApiKey =  env('SCRAPER_API_KEY');
//            Log::channel('custom_error')->warning($scraperApiKey);

      // get the page source from the url
//            $proxy_address = 'http://scraperapi.autoparse=true:' . env('SCRAPER_API_KEY') . '@proxy-server.scraperapi.com:8001';
////            $proxy_address = 'https://api.scraperapi.com?api_key=' . env('SCRAPER_API_KEY') . '&autoparse=true&url=' . $url;
//            $response = file_get_contents($proxy_address);
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_PROXY, $proxy_address);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//            curl_setopt($ch, CURLOPT_HEADER, FALSE);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//            $response = curl_exec($ch);
//            curl_close($ch);

      $url =
          "https://api.scraperapi.com?api_key=" . env('SCRAPER_API_KEY') . "&url=" . $sourceUrl . "&render=true&country_code=us";
//            Log::channel('custom_error')->error($url);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,
          true);
      curl_setopt($ch, CURLOPT_HEADER,
          false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,
          0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,
          0);
      $response = curl_exec($ch);
      curl_close($ch);

//            Log::channel('custom_error')->error($response);


      // get the mp4 urls from the page.
      if (str_contains($sourceUrl, 'rumble.com')) {
        // if rumble ...
        $sourceIs = 'rumble';
        $pattern = '/https(.*?)mp4/';
        preg_match_all($pattern, $response, $matches);
        // start at the first "mp4" extract https: .... .mp4 and replace \ with ""
        $firstMp4 = $matches[0][0];
      } elseif (str_contains($sourceUrl, 'bitchute.com')) {
        // if bitchute ...
        $sourceIs = 'bitchute';
        $pattern = '/<source src="https(.*?)mp4/';
        preg_match_all($pattern, $response, $matches);
        // start at the first "mp4" extract https: .... .mp4 and replace \ with ""
        $firstMp4 = $matches[0][0];
//                Log::channel('custom_error')->error('FIRST MP4: '.$firstMp4);
      }


      // Check if the data is null
      if ($matches[0] == []) {
        throw new \Exception("The data is undefined.");
      }

      // this poster save needs to be finished...
      // get the jpg urls from the page.
//            $pattern = '/poster="([^"]+)"/';
//
//            preg_match_all($pattern, $response, $imageMatches);
      // start at the first "mp4" extract https: .... .mp4 and replace \ with ""
//            $firstJpg = $imageMatches[0];
//
//            $image = str_replace('poster=', '', $firstJpg);
//            $string = implode(', ', $image);
//            $imageFilename = $this->saveImageFromInternet($string);
//            // create image model
//            $id = DB::table('images')->insertGetId([
////                'name' => $uploadedFile->hashName(),
//                'name' => $imageFilename,
////                'extension' => $imageFilename->extension(),
////                'size' => $imageFilename->getSize(),
//                'user_id' => auth()->user()->id,
//                'show_episode_id' => $episodeId,
//            ]);
//
//            // store image_id to Shows table
//            $episode = ShowEpisode::find($episodeId);
//            $episode->image_id = $id;
//            $episode->save();
      if ($sourceIs === 'rumble') {
        return str_replace('\\', '', $firstMp4);
      } elseif ($sourceIs === 'bitchute') {
        $url = str_replace('<source src="', '', $firstMp4);

//                Log::channel('custom_error')->error('Bitchute Matches: '. $url);
        return $url;
      } else
        return false;

    } catch (\Exception $e) {
      // Log the error using Laravel's logging system
      Log::channel('custom_error')->error($response);
      Log::channel('custom_error')->error($e->getMessage());
      Log::channel('custom_error')->error($matches);

      // Optionally, return a response to the client
      return false;
    }
  }

  public function saveImageFromInternet($imageUrl) {
    // Get the URL of the image from the request
//        $imageUrl = $request->input('image_url');
//        dd($imageUrl);
    // Fetch the image content using Laravel HTTP Client
    $response = Http::get($imageUrl);

    if ($response->successful()) {
      // Generate a unique filename for the saved image
      $filename = 'image_' . time() . '.' . $response->extension();

      // Save the image to the local storage (e.g., 'public' disk)
//            Storage::disk('public')->put($filename, $response->body());
      $response->file('poster')->store('images');

      // Return a success response or perform further actions
      return $filename;
//            return response()->json(['message' => 'Image saved successfully']);

    } else {
      // Handle the case where the image retrieval was not successful
      return response()->json(['message' => 'Failed to fetch the image'], 500);
    }
  }

  public function convertTimeToUserTime($dateTime): ?string {
    try {
      // Get the user's timezone
      $user = Auth::user();
      if (!$user) {
        // No authenticated user, unable to determine timezone
        return null;
      }

      $userTimezone = $user->timezone;

      // Create a Carbon instance from the DateTime string
      $dateTime = Carbon::parse($dateTime);

      // Convert to the user's timezone
      $dateTime->setTimezone($userTimezone);

      // Format $dateTime as a string in ISO 8601 format:
      return $dateTime->toIso8601String();
    } catch (\Exception $e) {
      // Handle any parsing or timezone conversion errors here
      return null;
    }
  }


  public function convertToUtcTime($dateTime): string {
    // Create a Carbon instance from the DateTime string
    $dateTime = Carbon::parse($dateTime);

    // Convert to UTC
    $dateTime->setTimezone('UTC');

    // Format $dateTime as a string in ISO 8601 format:
    return $dateTime->toIso8601String();
  }

}
