<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Models\InviteCode;
use App\Models\Notification;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use App\Models\TeamMember;
use App\Mail\TeamInviteMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TeamMembersController extends Controller {

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

    $team = Team::with(['members'         => function ($query) {
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

  public function attach(Request $request) {
    // If you are not signed in, no way.
    if (auth()->guest()) {
      abort(403, 'You are not signed in.');
    }

    $teamId = $request->team_id;
    $user = User::findOrFail($request->user_id);
    $team = Team::findOrFail($teamId);

    // If you are not the creator of the team or the team leader, no way.
    // Use the 'update' policy method
    $this->authorize('update', $team);

    // If the team is maxed out, no way.
    if ($team->members()->count() == $team->totalSpots) {
      abort(403, 'Your team has reached the maximum number of team members.');
    }

    $team->members()->attach($request->user_id);

    // notify new team member
    NotificationService::createAndDispatchNotification(
        $user->id,
        $team->image_id,
        '/teams/' . $team->slug,
        $team->name,
        '<span class="text-green-500">You have been added to the team.</span>'
    );

//        DB::table('teams')->where('id', $team->id)->increment('memberSpots', 1);

    return response()->json([
        'member'  => $team->members()->where('user_id', $request->user_id)->first(),
        'message' => $user->name . ' has been successfully added to the team.'
    ]);

  }

//    public function detach(Request $request)
//    {
//        $teamSlug = $request->team_slug;
//        $user = User::findOrFail($request->user_id);
//        $team = Team::findOrFail($request->team_id);
//
//        // Check if the user is a manager of the team
//        if ($team->managers()->where('user_id', $user->id)->exists()) {
//            // User is a manager, proceed with detach
//            $team->managers()->detach($user->id);
//            // Additional actions after detaching the user (if needed)
//        }
//
//        DB::table('teams')->where('id', $team->id)->decrement('memberSpots', 1);
//        $user->teams()->detach($team->id);
//
//      // notify new team member
//      NotificationService::createAndDispatchNotification(
//          $user->id,
//          $team->image_id,
//          '',
//          $team->name,
//          '<span class="text-orange-500">You have been removed from the team.</span>'
//      );
//
//        return redirect()->route('teams.manage', $teamSlug)->with('message', $user->name . ' has been successfully removed from the team.');
//    }
  public function detach(Request $request) {
    $user = User::findOrFail($request->user_id);
    $team = Team::findOrFail($request->team_id);

    // Check if the user is a manager of the team
    if ($team->managers()->where('user_id', $user->id)->exists()) {
      // User is a manager, proceed with detach
      $team->managers()->detach($user->id);
      // Additional actions after detaching the user (if needed)
    }

//    DB::table('teams')->where('id', $team->id)->decrement('memberSpots', 1);
    $user->teams()->detach($team->id);

    // Notify removed team member
    NotificationService::createAndDispatchNotification(
        $user->id,
        $team->image_id,
        '',
        $team->name,
        '<span class="text-orange-500">You have been removed from the team.</span>'
    );

    return response()->json([
        'message' => $user->name . ' has been successfully removed from the team.',
        'user_id' => $user->id
    ]);
  }

  public function inviteMember(Request $request, Team $team): \Illuminate\Http\JsonResponse {
    $request->validate([
        'email' => 'required|email',
    ]);

    try {
      $user = Auth::user();
      $email = $request->input('email');
      // Generate a random invite code
      $faker = Faker::create();
      $randomWords = $faker->word() . $faker->word();
      $randomDigits = mt_rand(100, 999);
      $inviteCode = $randomWords . $randomDigits;

      // Set the expiry date to three months from now
      $expiryDateUtc = Carbon::now()->addMonths(3)->toDateTimeString();

      // Save the invite code to the database with the team ID
      $invite = InviteCode::create([
          'code'         => $inviteCode,
          'user_role_id' => 4,
          'team_id'      => $team->id,
          'volume'       => 1,
          'used_count'   => 0, // Starts unused
          'expiry_date'  => $expiryDateUtc,
          'created_by'   => $user->id,
      ]);

      // Generate the invite URL
      $inviteUrl = config('app.url') . '/invite/' . $invite->ulid;

      // Send the invite email
      Mail::to($request->input('email'))->send(new TeamInviteMail(
          $request->input('email'),
          $user->name,
          $team->name,
          $inviteUrl,
          $inviteCode
      ));

      return response()->json(['message' => 'Invitation sent successfully.']);
    } catch (\Exception $e) {
      // Log the error
      Log::error('Error inviting member: ' . $e->getMessage(), [
          'team_id' => $team->id,
          'user_id' => Auth::user()->id,
      ]);

      // Return a JSON response with an error message
      return response()->json(['message' => 'Failed to send the invitation. Please try again later.'], 500);
    }
  }

}
