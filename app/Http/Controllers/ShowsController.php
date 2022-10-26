<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Post;
use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\Team;
use App\Models\User;
//use http\QueryString;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ShowsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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

        return Inertia::render('Shows/Index', [
            'shows' => Show::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString()
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'team_id' => $show->team_id,
                    'teamName' => Team::query()->where('id', $show->team_id)->pluck('name')->first(),
                    'showRunnerId' => $show->user_id,
                    'showRunnerName' => User::query()->where('id', $show->user_id)->pluck('name')->first(),
                    'poster' => getPoster($show),
                    'slug' => $show->slug,
//                    'poster' => Image::query()
//                        ->where('show_id', $show->id)
//                        ->pluck('name'),
                    'can' => [
                        'editShow' => Auth::user()->can('edit', $show)
                    ]
                ]),
            'filters' => Request::only(['search']),
            'can' => [
                'viewShows' => Auth::user()->can('view', Show::class),
                'createShow' => Auth::user()->can('create', Show::class),
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HttpRequest $request)
    {
//            // validate the request
//            $attributes = Request::validate([
//                'name' => 'unique:shows|required',
//                'description' => 'required',
//                'user_id' => 'required',
//                'team_id' => 'required',
//                'slug' => 'required',
//            ]);
//            // create the user
//            Show::create($attributes);
//            // redirect
//            return redirect('/shows')->with('message', 'Show Created Successfully');

        $request->validate([
            'name' => 'unique:shows|required|max:255',
//            'name' => ['required', 'string', 'max:255', Rule::unique('shows')->ignore($show->id)],
            'description' => 'required',
            'user_id' => 'required',
            'team_id' => 'required',
        ]);
        Show::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'team_id' => $request->team_id,
            'slug' => \Str::slug($request->name),
            'isBeingEditedByUser_id' => $request->user_id,
            'image_id' => null,
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

    /**
     * Display the specified resource.
     *
     * @param  int  $show
     * @return \Illuminate\Http\Response
     */
    // URL path is currently set to show.id
    // change show($id) to show($slug) to
    // make URL path = slug.
    public function show(Show $show)
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

        return Inertia::render('Shows/{$id}/Index', [
            'show' => $show,
            'poster' => getPoster($show),
            'teamName' => Team::query()->where('id', $show->team_id)->pluck('name')->firstOrFail(),
            'showRunner' => User::query()->where('id', $show->user_id)->pluck('name')->firstOrFail(),
            'can' => [
                'manageShow' => Auth::user()->can('manage', $show),
                'editShow' => Auth::user()->can('edit', $show),
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $show
     * @return \Illuminate\Http\Response
     */
    // URL path is currently set to show.id
    // change show($id) to show($slug) to
    // make URL path = slug.
    public function manage(Show $show)
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
        $team = Team::query()->where('id', $show->team_id)->firstOrFail();
        $showRunner = User::query()->where('id', $show->user_id)->pluck('name')->first();
        return Inertia::render('Shows/{$id}/Manage', [
            'show' => $show,
            'team' => $team,
            'poster' => getPoster($show),
            'showRunnerName' => $showRunner,
            'episodes' => ShowEpisode::query()->where('show_id', $show->id)->get(),
//            'episodes' => Episode::query()
//                ->when(Request::input('search'), function ($query, $search) {
//                    $query->where('name', 'like', "%{$search}%");
//                })
//                ->paginate(10)
//                ->withQueryString()
//                ->through(fn($episode) => [
//                    'id' => $episode->id,
//                    'name' => $episode->name,
//                    'description' => $episode->description,
//                    'notes' => $episode->notes,
//                    'poster' => Image::query()->where('id', $episode->image_id)->pluck('name')->first(),
//                ]),

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $show
     * @return \Illuminate\Http\Response
     */
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

        return Inertia::render('Shows/{$id}/Edit', [
            'show' => $show,
            'team' => Team::query()->where('id', $show->team_id)->firstOrFail(),
            'showRunner' => User::query()->where('id', $show->user_id)->pluck('id','name')->firstOrFail(),
            'poster' => getPoster($show),
//            'can' => [
//                'viewShows' => Auth::user()->can('view', Show::class),
//                'editShow' => Auth::user()->can('edit', Show::class),
//                'viewCreator' => Auth::user()->can('viewCreator', User::class),
//            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HttpRequest $request, Show $show)
    {

        // validate the request
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('shows')->ignore($show->id)],
            'description' => 'required',
        ]);

        // update the show
        $show->name = $request->name;
        $show->description = $request->description;
        $show->save();
        sleep(1);

        // gather the data needed to render the Manage page
        // this is all redundant. It's all contained in the
        // teams.manage route above. But I (tec21) don't know
        // how to simplify this *frustrated*.

        // redirect
        return redirect(route('shows.manage', [$show->slug]))->with('message', 'Show Updated Successfully');;

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


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Show $show)
    {
        $show->delete();
        sleep(1);

        // redirect
        return redirect()->route('shows')->with('message', 'Show Deleted Successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $show
     * @return \Illuminate\Http\Response
     */
    public function createEpisode(Show $show)
    {

        return Inertia::render('Shows/{$id}/Episode/Create', [
            'show' => $show,
            'team' => Team::query()->where('id', $show->team_id)->first(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $show
     * @param  int  $showEpisode
     * @return \Illuminate\Http\Response
     */
    // URL path is currently set to show.id
    // change show($id) to show($slug) to
    // make URL path = slug.
    public function manageEpisode(Show $show, ShowEpisode $showEpisode) {

        function getPoster($showEpisode) {
            $getPoster = Image::query()
                ->where('show_episode_id', $showEpisode->id)
                ->pluck('name')
                ->first();
            if (!empty($getPoster)) {
                $poster = $getPoster;
            } else {
                $poster = 'EBU_Colorbars.svg.png';
            }

            return $poster;
        }

        $team = Team::query()->where('id', $showEpisode->team_id)->firstOrFail();
        $showRunner = User::query()->where('id', $show->user_id)->pluck('name')->first();

        {
            return Inertia::render('Shows/{$id}/Episode/{$id}/Manage', [
                'show'           => $show,
                'episode'        => $showEpisode,
                'team'           => $team,
                'poster'         => getPoster($showEpisode),
                'showRunnerName' => $showRunner,
            ]);
        }
    }


}
