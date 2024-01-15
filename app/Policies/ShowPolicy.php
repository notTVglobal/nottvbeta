<?php

namespace App\Policies;

use App\Models\Creator;
use App\Models\Show;
use App\Models\Team;
use App\Models\TeamManager;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ShowPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true; // all users can view shows
    }

    public function view(User $user)
    {
        return true; // all users can view shows
    }

    public function viewShowManagePage(User $user, Show $show)
    {
        if ($this->hasAccessToTeam($user, $show)) {
            return true;
        }

        return $this->handleTeamMemberStatus($user, $show->team_id);
    }

    // tec21: this policy isn't working.
    public function editShowManagePage(User $user, Show $show)
    {
        if($user->isAdmin || $show->user_id === $user->id){
            return true;
        }
        return Response::deny('You must be the Show Runner, Show Manager, Team Leader, or a Team Manager to edit this show.');
    }

    public function create(User $user) {
        $userId = $user->id;
        $checkUser = Creator::where('user_id', $userId)->pluck('status_id')->first();

        if($checkUser === 1 || Auth::user()->isAdmin){
            return true;
        }
        elseif ($checkUser === 2) {
            return Response::deny('You\'re creator account has been frozen.');
        } elseif ($checkUser === 3) {
            return Response::deny('You\'re creator account has been suspended.');
        } elseif ($checkUser === null) {
            return Response::deny('Please register as a creator to use this feature.');
        }

        return Response::deny('There\'s been a problem. Please let not.TV know.');
    }

    public function edit(User $user, Show $show)
    {
        if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
            return true;
        }

        return $this->handleTeamMemberStatusForEdit($user, $show->team_id);
    }

    public function goLive(User $user, Show $show)
    {
        if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
            return true;
        }

        return $this->handleTeamMemberStatusForGoLive($user, $show->team_id);
    }

    public function viewEpisodeManagePage(User $user, Show $show)
    {
        if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
            return true;
        }

        return $this->handleTeamMemberStatus($user, $show->team_id);
    }

    public function createEpisode(User $user, Show $show)
    {
        if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
            return true;
        }

        return $this->handleTeamMemberStatusForManage($user, $show->team_id);
    }

    public function editEpisode(User $user, Show $show)
    {
        if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
            return true;
        }

        return $this->handleTeamMemberStatusForManage($user, $show->team_id);
    }

    public function manage(User $user, Show $show)
    {
        if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
            return true;
        }

        return $this->handleTeamMemberStatusForManage($user, $show->team_id);
    }

    public function update(User $user, Show $show)
    {
        if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
            return true;
        }

        return $this->handleTeamMemberStatusForManage($user, $show->team_id);
    }

    public function delete(User $user, Show $show)
    {
        if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
            return true;
        }

        return $this->handleTeamMemberStatusForManage($user, $show->team_id);
    }

    public function restore(User $user, Show $show)
    {
        if ($this->hasAccessToTeam($user, $show) || $show->user_id === $user->id) {
            return true;
        }

        return $this->handleTeamMemberStatusForManage($user, $show->team_id);
    }

    public function forceDelete(User $user, Show $show)
    {
        if ($this->hasAccessToTeam($user, $show)) {
            return true;
        }

        return $this->handleTeamMemberStatusForManage($user, $show->team_id);
    }

    private function hasAccessToTeam(User $user, Show $show)
    {
        $userId = $user->id;
        $team = $show->team;

        $isTeamOwner = $team->user_id === $userId;
        $isTeamLeader = $team->team_leader === $userId;
        $isTeamManager = TeamManager::where('team_id', $team->id)
            ->where('user_id', $userId)
            ->exists();
        $isAdmin = $user->isAdmin;

        return $isAdmin || $isTeamOwner || $isTeamLeader || $isTeamManager;
    }

    private function handleTeamMemberStatus(User $user, $teamId)
    {
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

    private function handleTeamMemberStatusForManage(User $user, $teamId)
    {
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

    private function handleTeamMemberStatusForGoLive(User $user, $teamId)
    {
        $isTeamMemberActive = TeamMember::where('team_id', $teamId)
            ->where('user_id', $user->id)
            ->value('active');

        if ($isTeamMemberActive === 0) {
            return Response::deny('You are not active on this team.');
        }

        if ($isTeamMemberActive === null) {
            return Response::deny('You are not a member of this team.');
        }

        return Response::deny('There\'s been a problem. Please let not.TV know.');
    }

    private function handleTeamMemberStatusForEdit(User $user, $teamId)
    {
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
}
