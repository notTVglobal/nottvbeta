<?php

namespace App\Policies;

use App\Models\Creator;
use App\Models\Show;
use App\Models\Team;
use App\Models\TeamManager;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ShowPolicy {
  use HandlesAuthorization;

  public function viewAny(User $user) {
    return true; // all users can view shows
  }

  public function view(User $user, Show $show) {
    // Allow admins to view any show regardless of its status
    if ($user->isAdmin) {
      return Response::allow();
    }

    // Check if the show is in a specific frozen, restricted, or hidden status
    if ($this->isRestrictedShow($show)) {
      return Response::deny($this->restrictionMessage($show));
    }

    // If the show has a status_id of 9
    if ($show->status_id == 9) {
      // Only users who are creators can view it
      return $user->is_creator
          ? Response::allow()
          : Response::deny('You must be a creator to view this show.');
    }

    // If the show has a status of 2, anyone can view it
    if ($show->status_id == 2) {
      return Response::allow();
    }

    // If the show has a status of 1, only show->team->members can view it
    if ($show->status_id == 1) {
      return $show->team->members->contains($user->id)
          ? Response::allow()
          : Response::deny('You must be a team member to view this show.');
    }

    // Optionally handle other cases or provide a default response
    return Response::deny('You do not have permission to view this show.');

  }

  public function viewShowManagePage(User $user, Show $show): bool|Response {
    if ($this->hasAccessToTeam($user, $show)) {
      return true;
    }

    return $this->handleTeamMemberStatus($user, $show->team_id);
  }

  // tec21: this policy isn't working.
//    public function editShowManagePage(User $user, Show $show)
//    {
//        if($user->isAdmin || $show->user_id === $user->id){
//            return true;
//        }
//        return Response::deny('You must be the Show Runner, Show Manager, Team Leader, or a Team Manager to edit this show.');
//    }

  public function create(User $user) {

    $userId = $user->id;
    $checkUser = Creator::where('user_id', $userId)->pluck('status_id')->first();

    if ($checkUser === 1 || Auth::user()->isAdmin) {
      return true;
    } elseif ($checkUser === 2) {
      return Response::deny('You\'re creator account has been frozen.');
    } elseif ($checkUser === 3) {
      return Response::deny('You\'re creator account has been suspended.');
    } elseif ($checkUser === null) {
      return Response::deny('Please register as a creator to use this feature.');
    }

    return Response::deny('There\'s been a problem. Please let not.TV know.');
  }

  public function edit(User $user, Show $show) {
    if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
      return true;
    }

    return $this->handleTeamMemberStatusForEdit($user, $show->team_id);
  }

  public function update(User $user, Show $show) {
    if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
      return true;
    }

    return $this->handleTeamMemberStatusForManage($user, $show->team_id);
  }

  public function goLive(User $user, Show $show) {
    if ($this->handleTeamMemberStatusForGoLive($user, $show->team_id)) {
      return true;
    } elseif ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
      return true;
    }
  }

  public function viewEpisodeManagePage(User $user, Show $show) {
    if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
      return true;
    }

    return $this->handleTeamMemberStatus($user, $show->team_id);
  }

  public function createEpisode(User $user, Show $show) {
    if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
      return true;
    }

    return $this->handleTeamMemberStatusForManage($user, $show->team_id);
  }

  public function editEpisode(User $user, Show $show) {
    if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
      return true;
    }

    return $this->handleTeamMemberStatusForManage($user, $show->team_id);
  }

  // TODO: The other model policies will need a manage() if they don't already
  //  because we use it in our routes\channels.php for broadcasting events.
  public function manage(User $user, Show $show): bool|Response {
    if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
      return true;
    }

    return $this->handleTeamMemberStatusForManage($user, $show->team_id);
  }


  public function delete(User $user, Show $show) {
    if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
      return true;
    }

    return $this->handleTeamMemberStatusForManage($user, $show->team_id);
  }

  public function restore(User $user, Show $show) {
    if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
      return true;
    }

    return $this->handleTeamMemberStatusForManage($user, $show->team_id);
  }

  public function forceDelete(User $user, Show $show) {
    if ($this->hasAccessToTeam($user, $show)) {
      return true;
    }

    return $this->handleTeamMemberStatusForManage($user, $show->team_id);
  }

  private function hasAccessToTeam(User $user, Show $show) {
    $userId = $user->id;
    $team = $show->team;

    $isTeamOwner = $team->user_id === $userId;
    $isTeamLeader = $team->team_leader === $userId;
    $isTeamManager = TeamManager::where('team_id', $team->id)
        ->where('user_id', $userId)
        ->exists();
    // Check if $user->creator is not null before trying to access $user->creator->id
    $isShowRunner = $user->creator && $show->show_runner === $user->creator->id;

    $isAdmin = $user->isAdmin;

    return $isAdmin || $isTeamOwner || $isTeamLeader || $isTeamManager || $isShowRunner;
  }

  private function handleTeamMemberStatus(User $user, $teamId) {
    $isTeamMemberActive = TeamMember::where('team_id', $teamId)
        ->where('user_id', $user->id)
        ->value('active');

    if ($isTeamMemberActive === 1) {
      return true;
    }

    if ($isTeamMemberActive === 0) {
      return Response::deny('You are not active on this team.');
    }

    return Response::deny('You are not a member of this team.');
  }

  private function handleTeamMemberStatusForManage(User $user, $teamId) {
    $isTeamMemberActive = TeamMember::where('team_id', $teamId)
        ->where('user_id', $user->id)
        ->value('active');

    if ($isTeamMemberActive === 1) {
      return Response::deny('You are not the show runner or manager.');
    }

    if ($isTeamMemberActive === 0) {
      return Response::deny('You are not active on this team.');
    }

    if ($isTeamMemberActive === null) {
      return Response::deny('You are not a member of this team.');
    }

    return Response::deny('There\'s been a problem. Please let not.TV know.');
  }

  private function handleTeamMemberStatusForGoLive(User $user, $teamId) {
    $isTeamMemberActive = TeamMember::where('team_id', $teamId)
        ->where('user_id', $user->id)
        ->value('active');

    if ($isTeamMemberActive) {
      return Response::allow();
    }

    if ($isTeamMemberActive === 0) {
      return Response::deny('You are not active on this team.');
    }

    if ($isTeamMemberActive === null) {
      return Response::deny('You are not a member of this team.');
    }

    return Response::deny('There\'s been a problem. Please let not.TV know.');
  }

  private function handleTeamMemberStatusForEdit(User $user, $teamId) {
    $isTeamMemberActive = TeamMember::where('team_id', $teamId)
        ->where('user_id', $user->id)
        ->value('active');

    if ($isTeamMemberActive === 1) {
      return Response::deny('You are not the show runner or a manager.');
    }

    if ($isTeamMemberActive === 0) {
      return Response::deny('You are not active on this team.');
    }

    if ($isTeamMemberActive === null) {
      return Response::deny('You are not a member of this team.');
    }

    return Response::deny('There\'s been a problem. Please let not.TV know.');
  }

  /**
   * Check if the show is in a restricted status and needs special handling.
   *
   * @param \App\Models\Show $show
   * @return bool
   */
  private function isRestrictedShow(Show $show): bool {
    return in_array($show->status_id, [6, 7, 8]);
  }

  /**
   * Return the appropriate message for restricted shows.
   *
   * @param \App\Models\Show $show
   * @return string
   */
  private function restrictionMessage(Show $show): string {
    return match ($show->status_id) {
      6 => 'The show is frozen please contact the notTV support team.',
      7 => 'The show is restricted please contact the notTV support team.',
      8 => 'The show is hidden please contact the notTV support team.',
      default => 'Access is restricted.',
    };
  }
}
