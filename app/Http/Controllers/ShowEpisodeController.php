<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Jobs\AddVideoUrlFromEmbedCodeJob;
use App\Jobs\ProcessVideoInfo;
use App\Models\Creator;
use App\Models\Image;
use App\Models\Notification;
use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Rules\UniqueEpisodeName;
use Inertia\Inertia;

class ShowEpisodeController extends Controller
{

    private string $formattedScheduledDateTime;
    private string $formattedReleaseDateTime;
    private ?int $videoId = null;

    public function __construct()
    {

//        $this->middleware('can:viewShowManagePage,show')->only(['manage']);
//        $this->middleware('can:editShow,show')->only(['edit']);
//        $this->middleware('can:createShow,show')->only(['create']);
//        $this->middleware('can:createEpisode,show')->only(['createEpisode']);
//        $this->middleware('can:viewEpisodeManagePage,show')->only(['manageEpisode']);
          $this->middleware('can:editEpisode,show')->only(['editEpisode']);

          $this->formattedReleaseDateTime = '';
          $this->formattedScheduledDateTime = '';

    }


////////////  INDEX
///////////////////

    public function index()
    {
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

    public function store(HttpRequest $request)
    {
        $request->validate([
            'name' => [
                'required',
                'max:255',
                'accepted_if:show_id,',
                'distinct:ignore_case',
                new UniqueEpisodeName($request->show_id)
            ],
            'description' => 'required',
            'user_id' => 'required',
            'show_id' => 'required',
            'episode_number' => 'nullable|max:10',
            'notes' => 'nullable|string',
            'video_url' => 'nullable|active_url',
            'youtube_url' => 'nullable|active_url',
            'video_embed_code' => 'nullable|string',
        ]);

        if ($request->video_url) {

            // Create a new Video instance
            $video = new Video();

            // Set the user_id, video_url and storage_location attributes
            $video->user_id = $request->user_id;
            $video->name = 'External video';
            $video->file_name = 'external_video_' . Str::uuid();
            $video->video_url = $request->video_url;
            $video->storage_location = 'external';

            // Save the video to the database
            $video->save();

            // Get the ID of the newly created Video model
            $this->videoId = $video->id;

            $jobName = null;

            // get the video information.
            dispatch(new ProcessVideoInfo($this->videoId, $jobName))->onQueue('high');
        }


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

        $showEpisode = new ShowEpisode();
        $showEpisode->isBeingEditedByUser_id = Auth::user()->id;
        $showEpisode->name = $request->name;
        $showEpisode->description = $request->description;
        $showEpisode->user_id = Auth::user()->id;
        $showEpisode->show_id = $request->show_id;
        $showEpisode->episode_number = $request->episode_number;
        $showEpisode->slug = \Str::slug($request->name);
//        $showEpisode->video_url = $request->url;
        $showEpisode->video_id = $this->videoId;
        $showEpisode->youtube_url = $request->youtube_url;
        $showEpisode->video_embed_code = $request->video_embed_code;
        $showEpisode->notes = $request->notes;
        $showEpisode->release_year = Carbon::now()->format('Y');

        $showEpisode->save();

        if ($showEpisode->show->show_status_id === 1) {
            $show = Show::find($showEpisode->show_id);
            $show->show_status_id = 2;
            $show->save();
        }

        $showSlug = $request->show_slug;
        $showEpisodeSlug = $showEpisode->slug;

        if ($request->video_embed_code && !$request->video_url) {
            // Create and save the notification
            $notification = new Notification;
            $userId = auth()->user()->id;
            $notification->user_id = $userId;

            // make the image the show_episode_poster
            $notification->image_id = $showEpisode->image_id;
            $notification->url = '/shows/'.$showEpisode->show->slug.'/episode/'.$showEpisode->slug;
            $notification->title = $showEpisode->name;
            $notification->message = 'The video url is being generated from the embed code. You will be notified when it is done.';
            $notification->save();

            // Trigger the event to broadcast the new notification
            event(new NewNotificationEvent($notification));
            AddVideoUrlFromEmbedCodeJob::dispatch($showEpisode)->onQueue('video_processing');
        }

        // Use this route to return
        // the user to the new episode page.

//        $episode = ShowEpisode::query()
//            ->where('name', $request->name)
//            ->pluck('show_id')
//            ->firstOrFail();

        return redirect()
            ->route('shows.showEpisodes.show',
                ['show' => $showSlug, 'showEpisode' => $showEpisodeSlug])
            ->with('success', 'Episode Created Successfully');

    }


////////////  SHOW
//////////////////

    public function show(Show $show, ShowEpisode $showEpisode) {

        $teamId = $show->team_id;
//        $video = Video::where('show_episodes_id', $showEpisode->id)->first();

        // convert release dateTime to user's timezone
        if ($showEpisode->release_dateTime) {
            $this->formattedReleaseDateTime = $this->convertTimeToUserTime($showEpisode->release_dateTime);
        }

        // convert scheduled_release dateTime to user's timezone
        if ($showEpisode->scheduled_release_dateTime) {
            $this->formattedScheduledDateTime = $this->convertTimeToUserTime($showEpisode->scheduled_release_dateTime);
        }

        return Inertia::render('Shows/{$id}/Episodes/{$id}/Index', [
            'show' => [
                'name' => $show->name,
                'slug' => $show->slug,
                'showRunner' => $show->user->name,
                'image' => [
                    'id' => $showEpisode->image->id,
                    'name' => $showEpisode->image->name,
                    'folder' => $showEpisode->image->folder,
                    'cdn_endpoint' => $showEpisode->appSetting->cdn_endpoint,
                    'cdn_folder' => $showEpisode->appSetting->cdn_folder,
                ],
                'copyrightYear' => $show->created_at->format('Y'),
                'first_release_year' => $show->first_release_year,
                'last_release_year' => $show->last_release_year,
                'categoryName' => $show->showCategory->name,
                'categorySubName' => $show->showCategorySub->name,
            ],
            'team' => [
                'name' => $show->team->name,
                'slug' => $show->team->slug,
            ],
            'episode' => [
                'name' => $showEpisode->name,
                'slug' => $showEpisode->slug,
                'description' => $showEpisode->description,
                'episode_number' => $showEpisode->episode_number,
                'created_at' => $showEpisode->created_at,
                'release_year' => $showEpisode->release_year,
                'release_dateTime' => $this->formattedReleaseDateTime,
                'scheduled_release_dateTime' => $this->formattedScheduledDateTime,
                'mist_stream_id' => $showEpisode->mist_stream_id,
                'video' => [
                    'file_name' => $showEpisode->video->file_name ?? '',
                    'cdn_endpoint' => $showEpisode->video->appSetting->cdn_endpoint ?? '',
                    'folder' => $showEpisode->video->folder ?? '',
                    'cloud_folder' => $showEpisode->video->cloud_folder ?? '',
                    'upload_status' => $showEpisode->video->upload_status ?? '',
                    'video_url' => $showEpisode->video->video_url ?? '',
                    'type' => $showEpisode->video->type ?? '',
                    'storage_location' => $showEpisode->video->storage_location ?? '',
                ],
                'youtube_url' => $showEpisode->youtube_url ?? '',
                'video_embed_code' => $showEpisode->video_embed_code,
            ],
//            'video' => [
//                'file_name' => $video->file_name ?? '',
//                'cdn_endpoint' => $video->appSetting->cdn_endpoint ?? '',
//                'folder' => $video->folder ?? '',
//                'cloud_folder' => $video->cloud_folder ?? '',
//                'upload_status' => $video->upload_status ?? '',
//            ],
            'image' => [
                'id' => $showEpisode->image->id,
                'name' => $showEpisode->image->name,
                'folder' => $showEpisode->image->folder,
                'cdn_endpoint' => $showEpisode->appSetting->cdn_endpoint,
                'cloud_folder' => $showEpisode->image->cloud_folder,
            ],
            'creators' => TeamMember::where('team_id', $teamId)
                ->join('users', 'team_members.user_id', '=', 'users.id')
                ->select('users.*', 'team_members.user_id')
                ->latest()
                ->paginate(3)
                ->withQueryString()
                ->through(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_photo_path' => $user->profile_photo_path,
                ]),
            'can' => [
                'manageShow' => Auth::user()->can('manage', $show),
                'editShow' => Auth::user()->can('edit', $show),
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
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



////////////  EDIT
//////////////////

    public function edit(Show $show, ShowEpisode $showEpisode)
    {
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

        $videoForEpisode = Video::where('show_episodes_id', $showEpisode->id)->first();

        // convert release dateTime to user's timezone
        if ($showEpisode->release_dateTime) {
            $this->formattedReleaseDateTime = $this->convertTimeToUserTime($showEpisode->release_dateTime);
        }

        // convert scheduled_release dateTime to user's timezone
        if ($showEpisode->scheduled_release_dateTime) {
            $this->formattedScheduledDateTime = $this->convertTimeToUserTime($showEpisode->scheduled_release_dateTime);
        }

        return Inertia::render('Shows/{$id}/Episodes/{$id}/Edit', [
            'show' => [
                'name' => $show->name,
                'slug' => $show->slug,
                'showRunner' => $show->user->name,
                'image' => [
                    'id' => $show->image->id,
                    'name' => $show->image->name,
                    'folder' => $show->image->folder,
                    'cdn_endpoint' => $show->appSetting->cdn_endpoint,
                    'cloud_folder' => $show->image->cloud_folder,
                ],
                'first_release_year' => $show->first_release_year,
                'last_release_year' => $show->last_release_year,
                'categoryName' => $show->showCategory->name,
                'categorySubName' => $show->showCategorySub->name,
            ],
            'team' => [
                'name' => $show->team->name,
                'slug' => $show->team->slug,
            ],
            'episode' => [
                'id' => $showEpisode->id,
                'name' => $showEpisode->name,
                'slug' => $showEpisode->slug,
                'description' => $showEpisode->description,
                'notes' => $showEpisode->notes,
                'episode_number' => $showEpisode->episode_number,
                'status' => $showEpisode->showEpisodeStatus,
                'release_year' => $showEpisode->release_year ?? null,
                'release_dateTime' => $this->formattedReleaseDateTime ?? null,
                'scheduled_release_dateTime' => $this->formattedScheduledDateTime ?? null,
                'mist_stream_id' => $showEpisode->mist_stream_id,
                'video_id' => $showEpisode->video_id,
                'youtube_url' => $showEpisode->youtube_url,
                'video_embed_code' => $showEpisode->video_embed_code,
                'video' => [
                    'file_name' => $videoForEpisode->file_name ?? '',
                    'cdn_endpoint' => $videoForEpisode->appSetting->cdn_endpoint ?? '',
                    'folder' => $videoForEpisode->folder ?? '',
                    'cloud_folder' => $videoForEpisode->cloud_folder ?? '',
                    'upload_status' => $videoForEpisode->upload_status ?? '',
                    'video_url' => $showEpisode->video->video_url ?? '',
                    'type' => $showEpisode->video->type ?? '',
                    'storage_location' => $showEpisode->video->storage_location ?? '',
                ],
                'image' => [
                    'id' => $showEpisode->image->id,
                    'name' => $showEpisode->image->name,
                    'folder' => $showEpisode->image->folder,
                    'cdn_endpoint' => $showEpisode->appSetting->cdn_endpoint,
                    'cloud_folder' => $showEpisode->image->cloud_folder,
                ],
                'show_id' => $showEpisode->show_id,
            ],
            'image' => [
                'id' => $showEpisode->image->id,
                'name' => $showEpisode->image->name,
                'folder' => $showEpisode->image->folder,
                'cdn_endpoint' => $showEpisode->appSetting->cdn_endpoint,
                'cloud_folder' => $showEpisode->image->cloud_folder,
            ],
            'can' => [
                'manageShow' => Auth::user()->can('manage', $show),
                'editShow' => Auth::user()->can('edit', $show),
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }

////////////  UPLOAD
////////////////////

    public function upload(Show $show, ShowEpisode $showEpisode)
    {
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
            'poster' => $showEpisode->image->name,
            'show' => [
                'name' => $show->name,
                'slug' => $show->slug,
                'showRunner' => $show->user->name,
                'image' => [
                    'id' => $show->image->id,
                    'name' => $show->image->name,
                    'folder' => $show->image->folder,
                    'cdn_endpoint' => $show->appSetting->cdn_endpoint,
                    'cloud_folder' => $show->image->cloud_folder,
                ],
            ],
            'team' => [
                'name' => $show->team->name,
                'slug' => $show->team->slug,
            ],
            'episode' => $showEpisode,
            'can' => [
                'manageShow' => Auth::user()->can('manage', $show),
                'editShow' => Auth::user()->can('edit', $show),
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }


////////////  UPDATE
////////////////////

    public function update(HttpRequest $request, Show $show, ShowEpisode $showEpisode)
    {
        Log::channel('custom_error')->info('release raw date in: '.$request->release_dateTime);
        Log::channel('custom_error')->info('scheduled raw date in: '.$request->scheduled_release_dateTime);

        // validate the request
        $request->validate([
//            'name' => ['required', 'string', 'max:255'],
            'name' => [
                Rule::excludeIf($request->name === $showEpisode->name),
                'required',
                'max:255',
                'accepted_if:show_id,',
                'distinct:ignore_case',
                new UniqueEpisodeName($request->show_id)
            ],
            'show_id' => 'required',
            'episode_number' => 'max:10',
            'description' => 'required',
            'notes' => 'nullable|string',
            'video_url' => 'nullable|active_url',
            'youtube_url' => 'nullable|active_url',
            'video_embed_code' => 'nullable|string',
            'release_dateTime' => 'nullable|date',
            'scheduled_release_dateTime' => 'nullable|date|after:now',
        ]);

        if ($request->video_url) {

            // Create a new Video instance
            $video = new Video();

            // Set the user_id, video_url and storage_location attributes
            $video->user_id = Auth::user()->id;
            $video->name = 'External video';
            $video->file_name = 'external_video_' . Str::uuid();
            $video->video_url = $request->video_url;
            $video->storage_location = 'external';

            // Save the video to the database
            $video->save();

            // Get the ID of the newly created Video model
            $this->videoId = $video->id;

            $jobName = null;

            // get the video information.
            dispatch(new ProcessVideoInfo($this->videoId, $jobName))->onQueue('high');
        }


//        $scheduledDateTime = $request->input('scheduled_release_dateTime');
//        $releaseDateTime = $request->input('release_dateTime');
//
//        // Create a Carbon instance from the datetime string
//        $carbonScheduledDatetime = \Illuminate\Support\Carbon::parse($scheduledDateTime);
//        $carbonReleaseDatetime = \Illuminate\Support\Carbon::parse($releaseDateTime);
//
//        // Convert to UTC
//        $utcScheduledDatetime = $carbonScheduledDatetime->utc();
//        $utcReleaseDatetime = $carbonReleaseDatetime->utc();
//
//        // Format $utcDatetime as a string in ISO 8601 format:
//        $formattedScheduledUtcDatetime = $utcScheduledDatetime->toIso8601String();
//        $formattedReleaseUtcDatetime = $utcReleaseDatetime->toIso8601String();

        $formattedScheduledUtcDatetime = '';
        $formattedReleaseUtcDatetime = '';
        $releaseYear = null;

        Log::channel('custom_error')->info('episode name: '.$request->name);
        Log::channel('custom_error')->info('release date in: '.$request->release_dateTime);
        Log::channel('custom_error')->info('scheduled date in: '.$request->scheduled_release_dateTime);

//        function formatDateForMySQL($dateTimeString) {
//            // Parse the datetime string into a Carbon instance, assuming it's in ISO 8601 format
//            $carbonDateTime = Carbon::parse($dateTimeString);
//            Log::channel('custom_error')->info('carbon parsed date: '.$carbonDateTime);
//
//            // Convert the Carbon instance to the MySQL datetime format
//            $formattedDateTime = $carbonDateTime->format('Y-m-d H:i:s');
//            Log::channel('custom_error')->info('formatted date: '.$formattedDateTime);
//
//            return $formattedDateTime;
//        }

        if ($request->release_dateTime) {
            // Create a Carbon instance from the DateTime string
            $releaseDateTime = Carbon::parse($request->release_dateTime);

            // Get the year from the Carbon instance
            $releaseYear = $releaseDateTime->year;

            // Format $utcDatetime as a string in ISO 8601 format:
            $formattedReleaseUtcDatetime = $this->convertTimeToUserTime($request->release_dateTime);

        }

        if ($request->scheduled_release_dateTime) {
//            $formattedScheduledReleaseDate = formatDateForMySQL($request->scheduled_release_dateTime);
            $showEpisode->show_episode_status_id = 6;
            $releaseYear = null;

            $formattedScheduledUtcDatetime = $this->convertTimeToUserTime($request->scheduled_release_dateTime);

        }

        if ($showEpisode->scheduled_release_dateTime && !$request->scheduled_release_dateTime) {
            $showEpisode->show_episode_status_id = 5;
        }




        $showSlug = Show::query()->where('id', $showEpisode->show_id)->pluck('slug')->first();
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
        $showEpisode->name = $request->name;
        $showEpisode->description = $request->description;
        $showEpisode->episode_number = $request->episode_number;
        $showEpisode->slug = \Str::slug($request->name);
        $showEpisode->notes = $request->notes;
        $showEpisode->video_id = $this->videoId;
        $showEpisode->youtube_url = $request->youtube_url;
        $showEpisode->video_embed_code = $request->video_embed_code;
        $showEpisode->release_dateTime = $formattedReleaseUtcDatetime ?? null;
        $showEpisode->release_year = $releaseYear ?? null;
        $showEpisode->scheduled_release_dateTime = $formattedScheduledUtcDatetime ?? null;
        $showEpisode->save();

        if ($request->video_embed_code !== $showEpisode->video_embed_code && !$request->video_url) {
            // Create and save the notification
            $notification = new Notification;
            $userId = auth()->user()->id;
            $notification->user_id = $userId;

            // make the image the show_episode_poster
            $notification->image_id = $showEpisode->image_id;
            $notification->url = '/shows/'.$showEpisode->show->slug.'/episode/'.$showEpisode->slug;
            $notification->title = $showEpisode->name;
            $notification->message = 'The video url is being generated from the embed code. You will be notified when it is done.';
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
        return redirect(route('shows.showEpisodes.show', [$showSlug, $showEpisode->slug]))
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

    public function updateNotes(HttpRequest $request)
    {
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
        sleep(1);

        return $episode;
    }


////////////  DESTROY
/////////////////////
///
///

    public function destroy(ShowEpisode $showEpisode)
    {
        $showEpisode->delete();
        sleep(1);

        // redirect
        return redirect()->route('showEpisode')->with('message', 'Episode Deleted Successfully');
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
                TRUE);
            curl_setopt($ch, CURLOPT_HEADER,
                FALSE);
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

    public function saveImageFromInternet($imageUrl)
    {
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

    public function convertTimeToUserTime($dateTime): string
    {
        // Get the user's timezone
        $user = Auth::user();
        $userTimezone = $user->timezone;

        // Create a Carbon instance from the DateTime string
        $dateTime = Carbon::parse($dateTime);

        // Convert to UTC
        $convertedDatetime = $dateTime->utc();

        // Format $utcDatetime as a string in ISO 8601 format:
        return $convertedDatetime->toIso8601String();

    }

}
