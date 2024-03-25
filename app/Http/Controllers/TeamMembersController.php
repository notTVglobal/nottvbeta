<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class TeamMembersController extends Controller
{

//    public function toggle(User $user, Team $team) {
//        $user->member->toggle($team);
//    }

  public function fetchTeamMembers(Request $request) {
    // Validate the request data
    $validatedData = $request->validate([
        'teamId' => 'required|integer',
    ]);

    // Extract teamId from the validated data
    $teamId = $validatedData['teamId'];

    $team = Team::with(['members' => function ($query) {
      // Apply a constraint to only include members where the creator's status_id is 1
      $query->whereHas('creator', function ($query) {
        $query->where('status_id', 1);
      })
          ->select('id', 'name', 'email', 'phone', 'profile_photo_path'); // Adjust if you need more fields
    },
                        'members.creator' => function ($query) {
                          // Assuming you only want to load certain fields from the creator
                          $query->select('id', 'user_id');
                        }])
        ->findOrFail($teamId);

    // Extract and transform team members from the loaded relationship
    $teamMembers = $team->members->map(function ($member) {
      return [
          'id'                 => $member->id,
          'creator_id'         => $member->creator ? $member->creator->id : null, // Access the creator's ID, if available
          'name'               => $member->name,
          'profile_photo_path' => $member->profile_photo_path,
          'profile_photo_url'  => $member->profile_photo_url, // Assuming this is accessible or you have an accessor for it
      ];
    });

    // Return the transformed team members data as a JSON response
    return response()->json($teamMembers);
  }

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

        // If you are not the creator of the team or the team leader, no way.
        // Use the 'update' policy method
        $this->authorize('update', $team);

        // If the team is maxed out, no way.
        if ($team->memberSpots == $team->totalSpots) {
            abort(403, 'Your team has reached the maximum number of team members.');
        }

        $team->members()->attach($request->user_id);

      // notify new team member
      NotificationService::createAndDispatchNotification(
          $user->id,
          $team->image_id,
          '/teams/'.$team->slug,
          $team->name,
          '<span class="text-green-500">You have been added to the team.</span>'
      );

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

        // Check if the user is a manager of the team
        if ($team->managers()->where('user_id', $user->id)->exists()) {
            // User is a manager, proceed with detach
            $team->managers()->detach($user->id);
            // Additional actions after detaching the user (if needed)
        }

        DB::table('teams')->where('id', $team->id)->decrement('memberSpots', 1);
        $user->teams()->detach($team->id);

//        return Inertia::render('Teams/{$id}/Edit', [
//            'members' => $team->members,
//        ])->with('message', $user->name . ' has been successfully removed from the team.');

        // notify new team member
//        $notification = new Notification;
//        $notification->user_id = $user->id;
//        // make the image the team_poster
//        $notification->image_id = $team->image_id;
//        $notification->url = '';
//        $notification->title = $team->name;
//        $notification->message = '<span class="text-orange-500">You have been removed from the team.</span>';
//        $notification->save();
//        // Trigger the event to broadcast the new notification
//        event(new NewNotificationEvent($notification));

      // notify new team member
      NotificationService::createAndDispatchNotification(
          $user->id,
          $team->image_id,
          '',
          $team->name,
          '<span class="text-orange-500">You have been removed from the team.</span>'
      );

        return redirect()->route('teams.manage', $teamSlug)->with('message', $user->name . ' has been successfully removed from the team.');
//        return redirect(route('teams.manage', [$teamSlug]))->with('message', $user->name . ' has been successfully removed from the team.');
    }
}
