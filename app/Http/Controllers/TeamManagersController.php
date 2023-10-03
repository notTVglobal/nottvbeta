<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use App\Models\TeamManager;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class TeamManagersController extends Controller
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
        $userId = $request->user_id;
        $user = User::findOrFail($userId);
        $team = Team::findOrFail($teamId);

        // If you are not the owner of the team, no way.
        if ($team->user_id !== auth()->user()->id && !auth()->user()->isAdmin) {
            abort(403, 'You must be the owner of this team to add new managers.');
        }

        $team->managers()->attach($request->user_id, [
            'user_id' => $userId,
            'team_id' => $teamId,
            // Add any other additional attributes here
        ]);


        // notify new team member
        $notification = new Notification;
        $notification->user_id = $user->id;
        // make the image the team_poster
        $notification->image_id = $team->image_id;
        $notification->url = '/teams/'.$team->slug;
        $notification->title = $team->name;
        $notification->message = 'You are now a team manager.';
        $notification->save();
        // Trigger the event to broadcast the new notification
        event(new NewNotificationEvent($notification));

        return redirect(route('teams.manage', [$teamSlug]))->with('message', $user->name . ' is now a team manager.');
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

        $team->managers()->detach($user->id);

//        return Inertia::render('Teams/{$id}/Edit', [
//            'members' => $team->members,
//        ])->with('message', $user->name . ' has been successfully removed from the team.');

        return redirect()->route('teams.manage', $teamSlug)->with('message', $user->name . ' has been removed as a manager.');
//        return redirect(route('teams.manage', [$teamSlug]))->with('message', $user->name . ' has been successfully removed from the team.');
    }
}
