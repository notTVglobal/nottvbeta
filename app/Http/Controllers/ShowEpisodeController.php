<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\Image;
use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Rules\UniqueEpisodeName;
use Inertia\Inertia;

class ShowEpisodeController extends Controller
{


    public function __construct()
    {

//        $this->middleware('can:viewShowManagePage,show')->only(['manage']);
//        $this->middleware('can:editShow,show')->only(['edit']);
//        $this->middleware('can:createShow,show')->only(['create']);
//        $this->middleware('can:createEpisode,show')->only(['createEpisode']);
//        $this->middleware('can:viewEpisodeManagePage,show')->only(['manageEpisode']);
          $this->middleware('can:editEpisode,show')->only(['editEpisode']);

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
            'video_file_url' => 'nullable|active_url',
            'video_file_embed_code' => 'nullable|string',
        ]);
        $showEpisode = new ShowEpisode();
        $showEpisode->isBeingEditedByUser_id = Auth::user()->id;
        $showEpisode->name = $request->name;
        $showEpisode->description = $request->description;
        $showEpisode->user_id = Auth::user()->id;
        $showEpisode->show_id = $request->show_id;
        $showEpisode->episode_number = $request->episode_number;
        $showEpisode->slug = \Str::slug($request->name);
        $showEpisode->video_url = $request->video_url;
        $showEpisode->video_embed_code = $request->video_embed_code;
        $showEpisode->notes = $request->notes;
        $showEpisode->release_year = Carbon::now()->format('Y');

        $showEpisode->save();

        $showSlug = $request->show_slug;
        $showEpisodeSlug = $showEpisode->slug;

        // Use this route to return
        // the user to the new episode page.

//        $episode = ShowEpisode::query()
//            ->where('name', $request->name)
//            ->pluck('show_id')
//            ->firstOrFail();

        return redirect()
            ->route('shows.showEpisodes.show',
                ['show' => $showSlug, 'showEpisode' => $showEpisodeSlug])
            ->with('message', 'Episode Created Successfully');

    }


////////////  SHOW
//////////////////

    public function show(Show $show, ShowEpisode $showEpisode) {

        $teamId = $show->team_id;
        $video = Video::where('show_episodes_id', $showEpisode->id)->first();

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
                'release_dateTime' => $showEpisode->release_dateTime,
                'mist_stream_id' => $showEpisode->mist_stream_id,
                'video_id' => $showEpisode->video_id,
                'video_url' => $showEpisode->video_url,
                'video_embed_code' => $showEpisode->video_embed_code,
            ],
            'video' => [
                'file_name' => $video->file_name ?? '',
                'cdn_endpoint' => $video->appSetting->cdn_endpoint ?? '',
                'folder' => $video->folder ?? '',
                'cloud_folder' => $video->cloud_folder ?? '',
                'upload_status' => $video->upload_status ?? '',
            ],
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
                'episode_number' => $showEpisode->episode_number,
                'created_at' => $showEpisode->created_at,
                'release_year' => $showEpisode->release_year,
                'release_dateTime' => $showEpisode->release_dateTime,
                'mist_stream_id' => $showEpisode->mist_stream_id,
                'video_id' => $showEpisode->video_id,
                'video_url' => $showEpisode->video_url,
                'video_embed_code' => $showEpisode->video_embed_code,
                'video' => [
                    'file_name' => $videoForEpisode->file_name ?? '',
                    'cdn_endpoint' => $videoForEpisode->appSetting->cdn_endpoint ?? '',
                    'folder' => $videoForEpisode->folder ?? '',
                    'cloud_folder' => $videoForEpisode->cloud_folder ?? '',
                    'upload_status' => $videoForEpisode->upload_status ?? '',
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
            'video_embed_code' => 'nullable|string',
            'release_date' => 'nullable|string',
        ]);

        // update the show
        $showEpisode->name = $request->name;
        $showEpisode->description = $request->description;
        $showEpisode->episode_number = $request->episode_number;
        $showEpisode->slug = \Str::slug($request->name);
        $showEpisode->notes = $request->notes;
        $showEpisode->video_url = $request->video_url;
        $showEpisode->video_embed_code = $request->video_embed_code;
        $showEpisode->save();
        sleep(1);

        $showSlug = Show::query()->where('id', $showEpisode->show_id)->pluck('slug')->first();
        $showEpisodeSlug = $showEpisode->slug;

        // gather the data needed to render the Manage page
        // this is all redundant. It's all contained in the
        // teams.manage route above. But I (tec21) don't know
        // how to simplify this *frustrated*.

        // redirect
        return redirect()
            ->route('shows.showEpisodes.show',
                [$showSlug,$showEpisodeSlug])
            ->with('message', 'Episode Updated Successfully');

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

}
