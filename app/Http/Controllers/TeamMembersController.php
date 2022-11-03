<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Inertia\Inertia;

class TeamMembersController extends Controller
{

//    public function toggle(User $user, Team $team) {
//        $user->member->toggle($team);
//    }

    public function attach(User $user, Team $team)
    {
        if ($user->member->team_id === $team->id) {
            return message('This person is already on the team!');
        }

        $user->member->attach($team);
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
        $team = $request->team_id;
        $user->teams()->detach($team);
        return redirect(route('teams.manage', [$teamSlug]))->with('message', $user->name . ' has been successfully removed from the team.');
    }
}
