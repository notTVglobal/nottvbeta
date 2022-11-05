<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Team;
use App\Models\Creator;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the team.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return bool
     */
    public function manage(User $user, Team $team)
    {
       if($user->id === $team->user_id){
           return true;
       } elseif ($user->isAdmin) {
           return true;
       }
       return 404;
    }

    /**
     * Determine whether the user can create teams.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->id === 155;
    }

    /**
     * Determine whether the user can edit teams.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function edit(User $user)
    {
//        if ($team->user_id === $user->id)
//            return $team->user_id === $user->id
//                ? Response::allow()
//                : Response::deny('This is not your team.');
//
//        elseif($user->isAdmin === 1)
//            return $user->isAdmin === 1
//                ? Response::allow()
//                : Response::deny('This is not your team.');

        return $user->role_id === 4;
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
