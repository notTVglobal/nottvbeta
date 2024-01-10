<?php

namespace App\Policies;

use App\Models\Show;
use App\Models\TeamManager;
use App\Models\User;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Creator;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    use HandlesAuthorization;

    public function view(User $user)
    {
        if($user->role_id > 1){
            return true;
        }
    }

    public function manage(User $user, Team $team)
    {
//        $teamId = $team->id;
//        $userId = $user->id;
        $userIsManager = TeamManager::where('team_id', '=', $team->id)
            ->where('user_id', '=', $user->id)->first();

        if($userIsManager || $user->isAdmin || $user->id === $team->team_leader){
            return true;
        } elseif($userIsManager === null){
            return Response::deny('You are not a team manager.');
        } return Response::deny('There\'s been a problem. Please let not.tv know.');
    }


    public function viewTeamManagePage(User $user, Team $team)
    {
//        $teamId = $team->id;
//        $userId = $user->id;
        $userIsActiveTeamMember = TeamMember::where('team_id', '=', $team->id)
                                ->where('user_id', '=', $user->id)->pluck('active')->first();

        if($userIsActiveTeamMember === 1 || $user->isAdmin){
            return true;
        } elseif($userIsActiveTeamMember === 0){
            return Response::deny('You are not active on this team.');
        } elseif($userIsActiveTeamMember === null){
            return Response::deny('You are not a member of this team.');
        } return Response::deny('There\'s been a problem. Please let not.tv know.');
    }

//return $user->id === 1
//? Response::allow()
//: Response::deny('You are not a member of this team.');
//return true;


    public function createTeam(User $user)
    {
        $userIsActiveCreator = Creator::where('user_id', $user->id)->pluck('status_id')->first();

        if($userIsActiveCreator === 1 || $user->isAdmin){
            return true;
        } elseif($userIsActiveCreator === 2){
            return Response::deny('You\'re creator account has been frozen.');
        } elseif($userIsActiveCreator === 3){
            return Response::deny('You\'re creator account has been suspended.');
        } elseif($userIsActiveCreator === null){
            return Response::deny('Please register as a creator to use this feature.');
        } return Response::deny('There\'s been a problem. Please let not.tv know.');
    }

// Formerly Edit.
//    public function update(User $user, Team $team)
//    {
//        $userId = $user->id;
//        $checkUser = Creator::where('user_id', $userId)->pluck('status_id')->first();
//
//        if($checkUser === 2){
//            return Response::deny('You\'re creator account has been frozen.');
//        }
//        elseif($checkUser === 3){
//            return Response::deny('You\'re creator account has been suspended.');
//        }
//        elseif($checkUser === null){
//            return Response::deny('Please register as a creator to use this feature.');
//        } elseif($team->user_id === $user->id || $user->isAdmin){
//            return true;
//        }
//        return Response::deny('There\'s been a problem. Please let not.TV know.');
//    }

    public function createShow(User $user, Team $team) {

        $userIsActiveCreator = Creator::where('user_id', $user->id)->pluck('status_id')->first();

        if ($team->user_id === $user->id || $user->isAdmin) {
            return true;
        } elseif ($userIsActiveCreator === 2) {
            return Response::deny('You\'re creator account has been frozen.');
        } elseif ($userIsActiveCreator === 3) {
            return Response::deny('You\'re creator account has been suspended.');
        } elseif ($userIsActiveCreator === null) {
            return Response::deny('Please register as a creator to use this feature.');
        } return Response::deny('There\'s been a problem. Please let not.tv know.');
    }

    /**
     * Determine whether the user can update the team.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Team $team)
    {
        $userId = $user->id;
        $checkUser = Creator::where('user_id', $userId)->pluck('status_id')->first();

        if($team->user_id === $user->id || $team->teamLeader->user->id === $user->id || $user->isAdmin) {
            return true;
        } elseif($checkUser === 2){
            return Response::deny('You\'re creator account has been frozen.');
        } elseif($checkUser === 3){
            return Response::deny('You\'re creator account has been suspended.');
        } elseif($checkUser === null){
            return Response::deny('Please register as a creator to use this feature.');
        } elseif ($userId != $team->user_id && (!isset($team->teamLeader) || $userId != $team->teamLeader->user->id)) {
            return Response::deny('You\'re not the creator of this team or the team leader.');
        } return Response::deny('There\'s been a problem. Please let not.tv know.');
    }

    /**
     * Determine whether the user can delete the team.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Team $team)
    {
        if ($user->id === $team->user_id)
            return $user->id === $team->user_id;

        elseif($user->isAdmin === 1)
            return $user->isAdmin === 1;
    }

    /**
     * Determine whether the user can restore the team.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Team $team)
    {
        if ($user->id === $team->user_id)
            return $user->id === $team->user_id;

        elseif($user->isAdmin === 1)
            return $user->isAdmin === 1;
    }

    /**
     * Determine whether the user can permanently delete the team.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Team $team)
    {
        if ($user->id === $team->user_id)
            return $user->id === $team->user_id;

        elseif($user->isAdmin === 1)
            return $user->isAdmin === 1;
    }
}
