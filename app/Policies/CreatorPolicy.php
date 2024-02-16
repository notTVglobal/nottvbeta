<?php

namespace App\Policies;

use App\Models\TeamMember;
use App\Models\User;
use App\Models\Team;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CreatorPolicy
{
    use HandlesAuthorization;


    public function __construct()
    {
        //
    }

    public function viewAny(User $user)
    {
        if ($user->creator && $user->creator->status_id === 1) {
            return true;
        } elseif ($user->creator && $user->creator->status_id === 2) {
            return true;
        }

    }

    public function viewDashboard(User $user)
    {
        if ($user->creator && $user->creator->status_id === 1) {
            return true;
        } elseif ($user->creator && $user->creator->status_id === 2) {
            return true;
        } elseif ($user->isAdmin) {
        return true;
        }
        return Response::deny('You must be a creator to view the Dashboard.');
    }

    public function createTeam(User $user)
    {
        if ($user->creator && $user->creator->status_id === 1) {
            return true;
        }

    }

    public function viewTeam(User $user)
    {
        if ($user->creator && $user->creator->status_id === 1) {
            return true;
        }

    }

    public function editTeam(User $user, Team $team)
    {
        $teamId = $team->id;
        $userId = $user->id;
        $checkUser = TeamMember::where('team_id', '=', $teamId)
            ->where('user_id', '=', $userId)->pluck('active')->first();

        if($checkUser === 1 || $user->isAdmin){
            return true;
        }
        elseif($checkUser === 0){
            return Response::deny('You are not active on this team.');
        }
        elseif($checkUser === null){
            return Response::deny('You are not a member of this team.');
        }
        return Response::deny('There\'s been a problem. Please let not.TV know.');
    }

  public function goLive(User $user)
  {
    // Check if the user is a member of any team and is active
    $isActiveMember = TeamMember::where('user_id', $user->id)
        ->where('active', 1)
        ->exists();

    // If user is active in any team or is an admin
    if ($isActiveMember || $user->isAdmin) {
      return true;
    }

    // If user is not active but is a member of any team
    $isMember = TeamMember::where('user_id', $user->id)->exists();
    if ($isMember && !$isActiveMember) {
      return Response::deny('You are not active on any team.');
    }

    // If user is not a member of any team
    if (!$isMember) {
      return Response::deny('You are not a member of any team.');
    }

    // Default deny if none of the above conditions are met
    return Response::deny('There\'s been a problem. Please let not.TV know.');
  }
}
