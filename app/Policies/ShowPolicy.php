<?php

namespace App\Policies;

use App\Models\Creator;
use App\Models\Show;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class ShowPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        if ($user->role_id === 2)
            return $user->role_id === 2;

        elseif($user->role_id === 3)
            return $user->role_id === 3;

        elseif($user->role_id === 4)
            return $user->role_id === 4;
    }

    public function viewShowManagePage(User $user, Show $show)
    {
        $teamId = $show->team_id;
        $userId = $user->id;
        $checkUser = TeamMember::where('team_id', '=', $teamId)
            ->where('user_id', '=', $userId)->pluck('active')->first();

        if($checkUser === 1 || $user->isAdmin || $show->user_id === $user->id){
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


        // This policy needs to receive a $team.id
        // to simplify things for now, all creators
        // are allowed to create shows. But, the
        // create show button only shows up if the
        // user is a Team Leader.
        //
        //
//        $teamId = $show->team_id;
//        $userId = Auth::user()->id;
//
//        $checkUser = TeamMember::where('team_id', '=', $teamId)
//            ->where('user_id', '=', $userId)->pluck('active')->first();
//
//        if($show->team->user_id === $userId || $show->user_id === $userId){
//            return true;
//        }
//        elseif($user->isAdmin) {
//            return true;
//        }
//        elseif($checkUser === 0){
//            return Response::deny('You are not active on this team.');
//        }
//        elseif($checkUser === null){
//            return Response::deny('You are not a member of this team.');
//        }
//        return Response::deny('There\'s been a problem. Please let not.TV know.');
//    }


    public function createShow(User $user) {

        $userId = Auth::user()->id;
        $checkUser = Creator::where('user_id', $userId)->pluck('status_id')->first();

        if ($checkUser === 1 || Auth::user()->isAdmin) {
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

    public function editShow(User $user, Show $show)
    {
        $teamId = $show->team_id;
        $userId = Auth::user()->id;
        $checkUser = TeamMember::where('team_id', '=', $teamId)
            ->where('user_id', '=', $userId)->pluck('active')->first();

        if($show->team->user_id === $userId || Auth::user()->isAdmin || $show->user_id === $userId){
            return true;
        }
        elseif($checkUser === 1){
            return Response::deny('You are not the show runner or manager.');
        }
        elseif($checkUser === 0){
            return Response::deny('You are not active on this team.');
        }
        elseif($checkUser === null){
            return Response::deny('You are not a member of this team.');
        }
        return Response::deny('There\'s been a problem. Please let not.TV know.');
    }

    public function goLive(User $user, Show $show)
    {
        $teamId = $show->team_id;
        $userId = $user->id;
        $checkUser = TeamMember::where('team_id', '=', $teamId)
            ->where('user_id', '=', $userId)->pluck('active')->first();

        if($checkUser === 1 || $user->isAdmin || $show->user_id === $user->id){
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

    public function viewEpisodeManagePage(User $user, Show $show)
    {
        $teamId = $show->team_id;
        $userId = $user->id;
        $checkUser = TeamMember::where('team_id', '=', $teamId)
            ->where('user_id', '=', $userId)->pluck('active')->first();

        if($checkUser === 1 || $user->isAdmin || $show->user_id === $user->id){
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

    public function createEpisode(User $user, Show $show)
    {
        $teamId = $show->team_id;
        $userId = $user->id;
        $checkUser = TeamMember::where('team_id', '=', $teamId)
            ->where('user_id', '=', $userId)->pluck('active')->first();

        if($show->team->user_id === $user->id || $user->isAdmin || $show->user_id === $user->id){
            return true;
        }
        elseif($checkUser === 1){
            return Response::deny('You are not the show runner or manager.');
        }
        elseif($checkUser === 0){
            return Response::deny('You are not active on this team.');
        }
        elseif($checkUser === null){
            return Response::deny('You are not a member of this team.');
        }
        return Response::deny('There\'s been a problem. Please let not.TV know.');
    }

    public function editEpisode(User $user, Show $show)
    {
        $teamId = $show->team_id;
        $userId = $user->id;
        $checkUser = TeamMember::where('team_id', '=', $teamId)
            ->where('user_id', '=', $userId)->pluck('active')->first();

        if($show->team->user_id === $user->id || $user->isAdmin || $show->user_id === $user->id){
            return true;
        }
        elseif($checkUser === 1){
            return Response::deny('You are not the show runner or manager.');
        }
        elseif($checkUser === 0){
            return Response::deny('You are not active on this team.');
        }
        elseif($checkUser === null){
            return Response::deny('You are not a member of this team.');
        }
        return Response::deny('There\'s been a problem. Please let not.TV know.');
    }

    /**
     * Determine whether the user can manage models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function manage(User $user, Show $show)
    {
        if ($user->id === $show->user_id)
            return $user->id === $show->user_id;

        elseif($user->isAdmin === 1)
            return $user->isAdmin === 1;
    }

    /**
     * Determine whether the user can edit models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Show  $show
     * @return \Illuminate\Auth\Access\Response|bool
     */
//    public function edit(User $user, Show $show)
//    {
//        if ($user->id === $show->user_id)
//            return $user->id === $show->user_id;
//
//        elseif($user->isAdmin)
//            return $user->isAdmin;
//    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Show  $show
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Show $show)
    {
        if ($user->id === $show->user_id)
            return $user->id === $show->user_id;

        elseif($user->isAdmin === 1)
            return $user->isAdmin === 1;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Show  $show
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Show $show)
    {
        if ($user->id === $show->user_id)
            return $user->id === $show->user_id;

        elseif($user->isAdmin === 1)
            return $user->isAdmin === 1;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Show  $show
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Show $show)
    {
        if ($user->id === $show->user_id)
            return $user->id === $show->user_id;

        elseif($user->isAdmin === 1)
            return $user->isAdmin === 1;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Show  $show
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Show $show)
    {
        if ($user->id === $show->user_id)
            return $user->id === $show->user_id;

        elseif($user->isAdmin === 1)
            return $user->isAdmin === 1;
    }
}
