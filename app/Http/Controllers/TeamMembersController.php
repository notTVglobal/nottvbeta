<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use App\Models\TeamMember;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class TeamMembersController extends Controller
{

//    public function toggle(User $user, Team $team) {
//        $user->member->toggle($team);
//    }

    public function attach(Request $request)
    {
        // If you are not signed in, no way.
        if (auth()->guest()) {
            abort(403, 'You are not signed in.');
        }

        $teamSlug = $request->team_slug;
        $teamId = $request->team_id;
        $user = User::findOrFail($request->user_id);
        $team = Team::findOrFail($teamId);

        // If you are not the owner of the team, no way.
        if ($team->user_id != auth()->user()->id) {
            abort(403, 'You are not the owner of this team.');
        }

        // If the team is maxed out, no way.
        if ($team->memberSpots == $team->totalSpots) {
            abort(403, 'Your team is maxed out.');
        }

        $team->members()->attach($request->user_id);
        DB::table('teams')->where('id', $team->id)->increment('memberSpots', 1);
        return redirect(route('teams.manage', [$teamSlug]))->with('message', $user->name . ' has been successfully added to the team.');
//        return inertia('',['message', $user->name . ' has been successfully added to the team.']);

//        $isUserOnTeam = TeamMember::query()
//        ->where('user_id', $user)
//        ->where('team_id', $team);
//        if ($isUserOnTeam) {
//            return ['message', 'This person is already on the team!'];
//        }
//dd($request->team_id);
//        $user->member->attach($request->team_id);
    }

//    public function detach(User $user, Team $team)
//    {
//        if ($user->member->team_id != $team->id) {
//            return message('This person is not on the team!');
//        }
//
//        $user->member->detach($team);
//    }

    public function detach(Request $request)
    {
        $teamSlug = $request->team_slug;
        $user = User::findOrFail($request->user_id);

        $team = Team::findOrFail($request->team_id);
        DB::table('teams')->where('id', $team->id)->decrement('memberSpots', 1);
        $user->teams()->detach($team->id);

//        return Inertia::render('Teams/{$id}/Edit', [
//            'members' => $team->members,
//        ])->with('message', $user->name . ' has been successfully removed from the team.');

        return redirect()->route('teams.manage', $teamSlug)->with('message', $user->name . ' has been successfully removed from the team.');
//        return redirect(route('teams.manage', [$teamSlug]))->with('message', $user->name . ' has been successfully removed from the team.');
    }
}
