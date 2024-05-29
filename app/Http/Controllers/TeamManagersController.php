<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use App\Models\TeamManager;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class TeamManagersController extends Controller {

//    public function toggle(User $user, Team $team) {
//        $user->member->toggle($team);
//    }

  public function attach(Request $request) {
    // If you are not signed in, no way.
    if (auth()->guest()) {
      abort(403, 'You are not signed in.');
    }

    $teamSlug = $request->team_slug;
    $teamId = $request->team_id;
    $userId = $request->user_id;
    $user = User::findOrFail($userId);
    $team = Team::findOrFail($teamId);

    // If you are not the creator of the team or the team leader, no way.
    // Use the 'update' policy method
    $this->authorize('update', $team);

    $team->managers()->attach($request->user_id, [
        'user_id' => $userId,
        'team_id' => $teamId,
    ]);

    // notify new team member
    $notification = new Notification;
    $notification->user_id = $user->id;
    // make the image the team_poster
    $notification->image_id = $team->image_id;
    $notification->url = '/teams/' . $team->slug . '/manage';
    $notification->title = $team->name;
    $notification->message = '<span class="text-yellow-500">You are now a team manager.</span>';
    $notification->save();
    // Trigger the event to broadcast the new notification
    event(new NewNotificationEvent($notification));

    return response()->json([
        'message' => $user->name . ' is now a team manager.',
        'manager' => $user, // Ensure this contains the necessary manager data
    ]);
  }

  public function detach(Request $request) {
    // Ensure the user is authenticated
    if (auth()->guest()) {
      abort(403, 'You are not signed in.');
    }

    $teamSlug = $request->team_slug;
    $user = User::findOrFail($request->user_id);
    $team = Team::findOrFail($request->team_id);

    $team->managers()->detach($user->id);

//        return Inertia::render('Teams/{$id}/Edit', [
//            'members' => $team->members,
//        ])->with('message', $user->name . ' has been successfully removed from the team.');

    // notify new team member
    $notification = new Notification;
    $notification->user_id = $user->id;
    // make the image the team_poster
    $notification->image_id = $team->image_id;
    $notification->url = '/teams/' . $team->slug;
    $notification->title = $team->name;
    $notification->message = '<span class="text-yellow-500">You have been removed as a team manager.</span>';
    $notification->save();

    // Trigger the event to broadcast the new notification
    event(new NewNotificationEvent($notification));

    // Return a JSON response
    return response()->json([
        'message' => $user->name . ' has been removed as a manager.',
        'manager' => $user, // Return the user data if needed
    ]);
  }
}
