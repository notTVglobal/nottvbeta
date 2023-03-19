<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Post;
use App\Models\Show;
use App\Models\ShowCategory;
use App\Models\ShowCategorySub;
use App\Models\ShowEpisode;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
//use http\QueryString;
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

class ShowsController extends Controller
{

    public function __construct()
    {

        $this->middleware('can:viewShowManagePage,show')->only(['manage']);
        $this->middleware('can:edit,show')->only(['edit']);
//        $this->middleware('can:create,show')->only(['store']);
        $this->middleware('can:createEpisode,show')->only(['createEpisode']);
        $this->middleware('can:viewEpisodeManagePage,show')->only(['manageEpisode']);

    }


////////////  INDEX
///////////////////

    public function index()
    {

        return Inertia::render('Shows/Index', [
            'shows' => Show::with('team', 'user', 'image', 'showEpisodes', 'showStatus')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(8)
                ->withQueryString()
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'description' => $show->description,
                    'team_id' => $show->team_id,
                    'teamName' => $show->team->name,
                    'teamSlug' => $show->team->slug,
                    'showRunnerId' => $show->user_id,
                    'showRunnerName' => $show->user->name,
                    'poster' => $show->image->name,
                    'slug' => $show->slug,
                    'totalEpisodes' => $show->showEpisodes->count(),
                    'status' => $show->showStatus->name,
                    'statusId' => $show->showStatus->id,
                    'copyrightYear' => $show->created_at->format('Y'),
                    'first_release_year' => $show->first_release_year,
                    'last_release_year' => $show->last_release_year,
                    'can' => [
                        'editShow' => Auth::user()->can('editShow', $show),
                        'viewShow' => Auth::user()->can('viewShowManagePage', $show)
                    ]
                ]),
            'episodes' => ShowEpisode::with('show', 'image')
                ->latest()
                ->paginate(3)
                ->through(fn($episode) => [
                    'id' => $episode->id,
                    'name' => $episode->name,
                    'description' => $episode->description,
                    'poster' => $episode->image->name,
                    'slug' => $episode->slug,
                    'showName' => $episode->show->name,
                    'showSlug' => $episode->show->slug,
                    'releaseDate' => $episode->created_at->format('M D, Y'),
                ]),
            'showsTrending' => Show::with('image')
                ->paginate(3)
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'description' => $show->description,
                    'poster' => $show->image->name,
                    'slug' => $show->slug,
                    'copyrightYear' => $show->created_at->format('Y'),
                ]),
            'showsComingSoon' => Show::with('image')
                ->latest()
                ->paginate(3)
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'description' => $show->description,
                    'poster' => $show->image->name,
                    'slug' => $show->slug,
                    'copyrightYear' => $show->created_at->format('Y'),
                ]),
            'filters' => Request::only(['search']),
            'can' => [
                'viewShows' => Auth::user()->can('view', Show::class),
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }



////////////  CREATE AND STORE
//////////////////////////////

    public function create()
    {
             return Inertia::render('Shows/Create', [
                 'teams' => Team::query()
                     ->where('user_id', Auth::user()->id)
                     ->paginate(10)
                     ->withQueryString()
                     ->through(fn($team) => [
                         'id' => $team->id,
                         'name' => $team->name,
                         'slug' => $team->slug,
                         'can' => [
                             'manageTeam' => Auth::user()->can('manage', $team)
                         ]
                     ]),
                 'userId' => Auth::user()->id,
        ]);

    }


    public function store(HttpRequest $request)
    {

        $request->validate([
            'name' => 'unique:shows|required|string|max:255',
//            'name' => ['required', 'string', 'max:255', Rule::unique('shows')->ignore($show->id)],
            'description' => 'required|string',
            'user_id' => 'required',
            'team_id' => 'required|integer|min:1',
        ],
            [ 'team_id' => 'A team must be selected.']);
        Show::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'team_id' => $request->team_id,
            'slug' => \Str::slug($request->name),
            'isBeingEditedByUser_id' => $request->user_id,
            'first_release_year' => \Carbon\Carbon::now()->format('Y'),
        ]);

        // Use this route to return
        // the user to the new show page.

        $show = Show::query()->where('name', $request->name)->firstOrFail();
        return redirect()->route('shows.manage', $show)->with('message', 'Show Created Successfully');
//        return Inertia::render('Shows/{$id}/Manage', $show)->with('message', 'Show Created Successfully');

        // Use this route to return the
        // user back to the team page
        // instead of the new show page.

//      return redirect()->route('teams.manage', $request->team_id)->with('message', 'Show Created Successfully');
    }



////////////  SHOW
//////////////////

    public function show(Show $show)
    {

        $teamId = $show->team_id;

        return Inertia::render('Shows/{$id}/Index', [
            'show' => [
                'name' => $show->name,
                'description' => $show->description,
                'showRunner' => $show->user->name,
                'slug' => $show->slug,
                'poster' => $show->image->name,
                'copyrightYear' => $show->created_at->format('Y'),
                'first_release_year' => $show->first_release_year,
                'last_release_year' => $show->last_release_year,
                'www_url' => $show->www_url,
                'instagram_name' => $show->instagram_name,
                'telegram_url' => $show->telegram_url,
                'twitter_handle' => $show->twitter_handle,
            ],

            ////////////////
            // tec21: this returns all columns from the showEpisode and image table
            // find a way to limit the query to only what we need (see my previous
            // attempt below)
            //
//            'episodes' => ShowEpisode::latest()->where('show_id', $show->id)->get(),
//            'episodes' => ShowEpisode::latest()->paginate(5),

//            'episodes' => ShowEpisode::with('show', 'image')
//                ->where('show_id', $show->id)
//                ->when(Request::input('search'), function ($query, $search) {
//                    $query->where('name', 'like', "%{$search}%");
//                })
//                ->latest()
//                ->paginate(5)
//                ->when(Request::input('search'), function ($query, $search) {
//                    $query->where('name', 'like', "%{$search}%");
//                }),

            'episodes' => ShowEpisode::with('image', 'show', 'showEpisodeStatus')
                ->where('show_id', $show->id)
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(5)
                ->withQueryString()
                ->through(fn($showEpisode) => [
                    'id' => $showEpisode->id,
                    'name' => $showEpisode->name,
                    'poster' => $showEpisode->image->name,
                    'slug' => $showEpisode->slug,
                    'created_at' => $showEpisode->created_at,
                ]),
            'creators' => TeamMember::where('team_id', $teamId)
                ->join('users', 'team_members.user_id', '=', 'users.id')
                ->select('users.*', 'team_members.user_id')
                ->latest()
                ->paginate(10)
                ->withQueryString()
                ->through(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_photo_path' => $user->profile_photo_path,
                ]),
            'team' => [
                'name' => $show->team->name,
                'slug' => $show->team->slug,
                'poster' => $show->team->image->name,
            ],
            'can' => [
                'manageShow' => Auth::user()->can('manage', $show),
                'editShow' => Auth::user()->can('edit', $show),
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }


////////////  MANAGE
////////////////////

    public function manage(Show $show)
    {

        return Inertia::render('Shows/{$id}/Manage', [
            'show' => [
                'name' => $show->name,
                'description' => $show->description,
                'showRunner' => $show->user->name,
                'slug' => $show->slug,
                'poster' => $show->image->name,
                'copyrightYear' => $show->created_at->format('Y'),
                'notes' => $show->notes,
            ],
            'team' => [
                'name' => $show->team->name,
                'slug' => $show->team->slug,
            ],
            'episodes' => ShowEpisode::with('image', 'show', 'showEpisodeStatus')
                ->where('show_id', $show->id)
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(5)
                ->withQueryString()
                ->through(fn($showEpisode) => [
                    'id' => $showEpisode->id,
                    'name' => $showEpisode->name,
                    'poster' => $showEpisode->image->name,
                    'slug' => $showEpisode->slug,
                    'episodeNumber' => $showEpisode->episode_number,
                    'notes' => $showEpisode->notes,
                    'episodeStatus' => $showEpisode->showEpisodeStatus->name,
                    'episodeStatusId' => $showEpisode->showEpisodeStatus->id,
                ]),
            'can' => [
                'editShow' => auth()->user()->can('edit', $show),
                'createEpisode' => auth()->user()->can('createEpisode', $show),
                'createAssignment' => auth()->user()->can('editShow', $show),
                'goLive' => auth()->user()->can('goLive', $show),
            ]

        ]);
    }


////////////  EDIT
//////////////////

    public function edit(Show $show)
    {
//        $team = Show::query()->where('id', $show->id)->pluck('team_id')->firstOrFail();

        // Currently this queries all images in the database
        // this needs to be changed to limit the query.
        //
        DB::table('users')->where('id', Auth::user()->id)->update([
           'isEditingShow_id' => $show->id,
        ]);

        DB::table('shows')->where('id', $show->id)->update([
            'isBeingEditedByUser_id' => Auth::user()->id,
        ]);

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

        $categories = ShowCategory::all();
        $sub_categories = ShowCategorySub::all();

        return Inertia::render('Shows/{$id}/Edit', [
            'show' => $show,
            'team' => Team::query()->where('id', $show->team_id)->firstOrFail(),
            'showRunner' => User::query()->where('id', $show->user_id)->pluck('id','name')->firstOrFail(),
            'poster' => getPoster($show),
            'categories' => $categories,
            'showCategoryId' => $show->showCategory->id,
            'showCategoryName' => $show->showCategory->name,
            'showCategoryDescription' => $show->showCategory->description,
            'sub_categories' => $sub_categories,
//            'can' => [
//                'viewShows' => Auth::user()->can('view', Show::class),
//                'editShow' => Auth::user()->can('edit', Show::class),
//                'viewCreator' => Auth::user()->can('viewCreator', User::class),
//            ]
        ]);
    }


////////////  UPDATE
////////////////////


    public function update(HttpRequest $request, Show $show)
    {
         // validate the request
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('shows')->ignore($show->id)],
            'description' => 'required',
            'category' => 'required',
            'sub_category' => 'nullable',
            'www_url' => 'nullable|active_url',
            'instagram_name' => 'nullable|string|max:30',
            'telegram_url' => 'nullable|active_url',
            'twitter_handle' => 'nullable|string|min:4|max:15',
            'notes' => 'nullable|string|max:1024',
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
        $show->notes = $request->notes;
        $show->save();
        sleep(1);



        // gather the data needed to render the Manage page
        // this is all redundant. It's all contained in the
        // teams.manage route above. But I (tec21) don't know
        // how to simplify this *frustrated*.

        // redirect
        return redirect(route('shows.manage', [$show->slug]))->with('message', 'Show Updated Successfully');

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

    public function destroy(Show $show)
    {
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

    public function createEpisode(Show $show)
    {

        return Inertia::render('Shows/{$id}/Episodes/Create', [
            'show' => $show,
            'team' => Team::query()->where('id', $show->team_id)->first(),
        ]);
    }


////////////  MANAGE EPISODE
////////////////////////////
///
/// tec21: this might be better
/// in the ShowEpisodes Controller.

    public function manageEpisode(Show $show, ShowEpisode $showEpisode) {

        return Inertia::render('Shows/{$id}/Episodes/{$id}/Manage', [
            'show' => [
                'name' => $show->name,
                'slug' => $show->slug,
                'showRunner' => $show->user->name,
                'poster' => $show->image->name,
                'copyrightYear' => $show->created_at->format('Y'),
            ],
            'team' => [
                'name' => $show->team->name,
                'slug' => $show->team->slug,
            ],
            'episode' => $showEpisode,
            'can' => [
                'editEpisode' => auth()->user()->can('editEpisode', $show),
                'goLive' => auth()->user()->can('goLive', $show),
            ]

        ]);

    }

}
