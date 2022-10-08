<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        function teamOwner($userId) {
            $teamOwner = User::query()->where('id', $userId)->first();
            return $teamOwner->name;
        }

        function logo($imageId) {
            $logo = Image::query()->where('id', $imageId)->first();
            return $logo->name;
        }

        return Inertia::render('Teams/Index', [
            'teams' => Team::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString()
                ->through(fn($team) => [
                    'id' => $team->id,
                    'name' => $team->name,
                    'logo' => logo($team->image_id),
                    'teamOwner' => teamOwner($team->user_id),
                    'team_id' => $team->team_id,
                    'memberSpots' => $team->memberSpots,
                    'totalSpots' => $team->totalSpots,
                ]),
            'filters' => Request::only(['search']),
            'can' => [
                'viewTeams' => Auth::user()->can('view', Team::class),
                'createTeam' => Auth::user()->can('create', Team::class),
                'editTeam' => Auth::user()->can('edit', Team::class)
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
        return Inertia::render('Teams/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HttpRequest $request)
    {
//        $attributes = Request::validate([
//            'name' => 'unique:teams|required',
//            'description' => 'required',
//            'logo',
//            'totalSpots',
//            'user_id' => 'required',
//            'slug' => 'required',
//        ]);
//        // create the team
//        Team::create($attributes);
//        // redirect
//        return redirect('/teams')->with('message', 'Team Created Successfully');

        $request->validate([
            'name' => 'unique:teams|required|max:255',
            'description' => 'required|string|max:5000',
            'user_id' => 'required',
            'totalSpots' => 'required|integer|min:1',
        ]);
        Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'totalSpots' => $request->totalSpots,
            'slug' => \Str::slug($request->name),
        ]);

        $teamId = Team::query()->where('name', $request->name)->firstOrFail();
        return redirect()->route('teams.manage', $teamId)->with('message', 'Team Created Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(team $team)
    {
        function showRunner($userId) {
            $showRunner = User::query()->where('id', $userId)->first();
            return $showRunner->name;
        }

        function logo($imageId) {
            $logo = Image::query()->where('id', $imageId)->first();
            return $logo->name;
        }

        function poster($imageId) {
            $poster = Image::query()->where('id', $imageId)->first();
            return $poster->name;
        }

        return Inertia::render('Teams/{$id}/Index', [
            'team' => $team,
            'logo' => logo($team->image_id),
            'shows' => DB::table('shows')->where('team_id', $team->id)
                ->latest()
                ->paginate(5)
                ->withQueryString()
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'description' => $show->description,
                    'showRunner' => showRunner($show->user_id),
                    'team_id' => $show->team_id,
                    'poster' => poster($show->image_id),
                ]),
            'filters' => Request::only(['team_id']),
            'can' => [
                'editTeam' => Auth::user()->can('edit', Team::class),
                'manageTeam' => Auth::user()->can('edit', Team::class)
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // URL path is currently set to show.id
    // change show($id) to show($slug) to
    // make URL path = slug.
    public function manage(team $team)
    {
        function showRunner($userId) {
            $showRunner = User::query()->where('id', $userId)->first();
            return $showRunner->name;
        }

        function logo($imageId) {
            $logo = Image::query()->where('id', $imageId)->first();
            return $logo->name;
        }

        function poster($imageId) {
            $poster = Image::query()->where('id', $imageId)->first();
            return $poster->name;
        }

        return Inertia::render('Teams/{$id}/Manage', [
            'team' => $team,
            'logo' => logo($team->image_id),
            'shows' => DB::table('shows')->where('team_id', $team->id)
                ->latest()
                ->paginate(5)
                ->withQueryString()
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'description' => $show->description,
                    'showRunner' => showRunner($show->user_id),
                    'team_id' => $show->team_id,
                    'poster' => poster($show->image_id),
                ]),
            'filters' => Request::only(['team_id']),
            'can' => [
                'editTeam' => Auth::user()->can('edit', Team::class),
                'manageTeam' => Auth::user()->can('edit', Team::class),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(team $team)
    {
        function showRunner($userId) {
            $showRunner = User::query()->where('id', $userId)->first();
            return $showRunner->name;
        }

        function logo($imageId) {
            $logo = Image::query()->where('id', $imageId)->first();
            return $logo->name;
        }

        return Inertia::render('Teams/{$id}/Edit', [
            'team' => $team,
            'logo' => logo($team->image_id),
            'can' => [
                'editTeam' => Auth::user()->can('edit', Team::class),
                'manageTeam' => Auth::user()->can('edit', Team::class)
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HttpRequest $request, Team $team)
    {
//        $team = Team::query()->where('id', $team->id)->firstOrFail();

        // validate the request
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('teams')->ignore($team->id)],
            'description' => 'required|string|max:5000',
            'totalSpots' => 'required|integer|min:1',
        ]);

        // update the team
        $team->name = $request->name;
        $team->description = $request->description;
        $team->totalSpots = $request->totalSpots;
        $team->save();
        sleep(1);

        // gather the data needed to render the Manage page
        // this is all redundant. It's all contained in the
        // teams.manage route above. But I (tec21) don't know
        // how to simplify this *frustrated*.

        function showRunner($userId) {
            $showRunner = User::query()->where('id', $userId)->first();
            return $showRunner->name;
        }

        function logo($imageId) {
            $logo = Image::query()->where('id', $imageId)->first();
            return $logo->name;
        }

        function poster($imageId) {
            $poster = Image::query()->where('id', $imageId)->first();
            return $poster->name;
        }

        // redirect
        return Inertia::render('Teams/{$id}/Manage', [
            // responses need to be limited to only
            // the information required with ->only()
            // https://inertiajs.com/responses
            'team' => $team,
            'logo' => logo($team->image_id),
            'shows' => DB::table('shows')->where('team_id', $team->id)
                ->latest()
                ->paginate(5)
                ->withQueryString()
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'description' => $show->description,
                    'showRunner' => showRunner($show->user_id),
                    'team_id' => $show->team_id,
                    'poster' => poster($show->image_id),
                ]),
            'filters' => Request::only(['team_id']),
            'can' => [
                'editTeam' => Auth::user()->can('edit', Team::class),
                'manageTeam' => Auth::user()->can('edit', Team::class),
            ]
        ])->with('message', 'Team Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->delete();
        sleep(1);

        return redirect()->route('teams')->with('message', 'Team Deleted Successfully');
    }
}
