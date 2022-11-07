<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\Team;
use App\Models\User;
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
            ->route('shows.showEpisodes.manageEpisode',
                [$showSlug,$showEpisodeSlug])
            ->with('message', 'Episode Created Successfully');

    }


////////////  SHOW
//////////////////

    public function show(Show $show, ShowEpisode $showEpisode) {

        return Inertia::render('Shows/{$id}/Episodes/{$id}/Index', [
            'show' => [
                'name' => $show->name,
                'slug' => $show->slug,
                'showRunner' => $show->user->name,
                'poster' => $show->image->name,
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

        return Inertia::render('Shows/{$id}/Episodes/{$id}/Edit', [
            'poster' => $showEpisode->image->name,
            'show' => [
                'name' => $show->name,
                'slug' => $show->slug,
                'showRunner' => $show->user->name,
                'poster' => $show->image->name,
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
            'video_file_url' => 'nullable|active_url',
            'video_file_embed_code' => 'nullable|string',
        ]);

        // update the show
        $showEpisode->name = $request->name;
        $showEpisode->description = $request->description;
        $showEpisode->episode_number = $request->episode_number;
        $showEpisode->slug = \Str::slug($request->name);
        $showEpisode->notes = $request->notes;
        $showEpisode->video_file_url = $request->video_file_url;
        $showEpisode->video_file_embed_code = $request->video_file_embed_code;
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
            ->route('shows.showEpisodes.manageEpisode',
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
