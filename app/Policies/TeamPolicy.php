<?php

namespace App\Policies;

use App\Models\Show;
use App\Models\User;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Creator;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    use HandlesAuthorization;

    public function viewTeamManagePage(User $user, Team $team)
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

//return $user->id === 1
//? Response::allow()
//: Response::deny('You are not a member of this team.');
//return true;


    public function manage(User $user, Team $team)
    {
        //
    }


    public function createTeam(User $user)
    {
        $userId = $user->id;
        $checkUser = Creator::where('user_id', $userId)->pluck('status_id')->first();

        if($checkUser === 1 || $user->isAdmin){
            return true;
        }
        elseif($checkUser === 2){
            return Response::deny('You\'re creator account has been frozen.');
        }
        elseif($checkUser === 3){
            return Response::deny('You\'re creator account has been suspended.');
        }
        elseif($checkUser === null){
            return Response::deny('Please register as a creator to use this feature.');
        }
        return Response::deny('There\'s been a problem. Please let not.TV know.');
    }


    public function editTeam(User $user, Team $team)
    {
        $userId = $user->id;
        $checkUser = Creator::where('user_id', $userId)->pluck('status_id')->first();

        if($checkUser === 2){
            return Response::deny('You\'re creator account has been frozen.');
        }
        elseif($checkUser === 3){
            return Response::deny('You\'re creator account has been suspended.');
        }
        elseif($checkUser === null){
            return Response::deny('Please register as a creator to use this feature.');
        } elseif($team->user_id === $user->id || $user->isAdmin){
            return true;
        }
        return Response::deny('There\'s been a problem. Please let not.TV know.');
    }

    public function createShow(User $user, Team $team) {

        $userId = $user->id;
        $checkUser = Creator::where('user_id', $userId)->pluck('status_id')->first();

        if ($checkUser === 2) {
            return Response::deny('You\'re creator account has been frozen.');
        } elseif ($checkUser === 3) {
            return Response::deny('You\'re creator account has been suspended.');
        } elseif ($checkUser === null) {
            return Response::deny('Please register as a creator to use this feature.');
        } elseif ($team->user_id === $user->id || $user->isAdmin) {
            return true;
        }

        return Response::deny('There\'s been a problem. Please let not.TV know.');
    }

    /**
     * Determine whether the user can update the team.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
//        if ($team->user_id === $user->id)
//            return $team->user_id === $user->id
//                ? Response::allow()
//                : Response::deny('You do not own this team.');
//
//        elseif($user->isAdmin === 1)
//            return $user->isAdmin === 1
//                ? Response::allow()
//                : Response::deny('You do not own this team.');

        return $user->role_id === 4;
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
