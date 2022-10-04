<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
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
                    'logo' => $team->logo,
                    'user_id' => $team->user_id,
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
            'name' => 'unique:teams|required',
            'description' => 'required',
            'user_id' => 'required',
            'logo',
            'totalSpots',
        ]);
        Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'logo' => $request->logo,
            'totalSpots' => $request->totalSpots,
            'slug' => \Str::slug($request->name),
        ]);

        $teamId = Team::query()->where('name', $request->name)->firstOrFail();
        return redirect()->route('teams.show', $teamId)->with('message', 'Team Created Successfully');

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
            $user = User::query()->where('id', $userId)->first();
            return $user->name;
        }
        return Inertia::render('Teams/{$id}/Index', [
            'team' => $team,
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
                    'poster' => $show->poster
                ]),
            'filters' => Request::only(['team_id']),
            'can' => [
                'editTeam' => Auth::user()->can('edit', Team::class)
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
        return Inertia::render('Teams/{$id}/Edit', [
            'team' => $team,
            'can' => [
                'editTeam' => Auth::user()->can('edit', Team::class)
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
    public function update(Request $request, Team $team)
    {
//        $request->validate([
//            'name' => 'required|string|max:255',
//            'description' => 'required|string|max:5000',
//            'logo',
//            'member_spots'
//        ]);
//        $team->name = $request->name;
//        $team->description = $request->description;
//        $team->logo = $request->logo;
//        $team->save();
//        sleep(1);
        // validate the request
        $attributes = Request::validate([
            'name' => 'required',
            'description' => 'required',
            'logo',
            'totalSpots' => 'integer',
        ]);
        // update the show
        $team->update($attributes);

        // redirect
        return Inertia::render('Teams/{$id}/Index', [
            'team' => $team
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
