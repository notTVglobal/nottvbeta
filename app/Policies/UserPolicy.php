<?php

namespace App\Policies;

use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
        return $user->isAdmin;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view Creator content.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewCreator(User $user)
    {
        return $user->creator;
    }

    /**
     * Determine whether the user can view Premium content.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewPremium(User $user)
    {
        if ($user->role_id === 2)
            return $user->role_id === 2;

        elseif($user->role_id === 3)
            return $user->role_id === 3;

        elseif($user->role_id === 4)
            return $user->role_id === 4;
    }

    /**
     * Determine whether the user can view VIP content.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewVip(User $user)
    {

//        return $user->vip;
        if($user->role_id === 3)
            return $user->role_id === 3;

        elseif($user->role_id === 4)
            return $user->role_id === 4;
    }

    /**
     * Determine whether the user can view all users.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAdmin(User $user)
    {
        return $user->isAdmin;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->isAdmin === 1;
    }

    /**
     * Determine whether the user can edit models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function edit(User $user)
    {
        return $user->isAdmin === 1;
    }


    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        return $user->isAdmin === 1;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        // tec21: I entered a bad value on purpose so no one can delete a user right now
        return $user->isAdmin === 1;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        return $user->isAdmin === 1;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        // tec21: I entered a bad value on purpose so no one can delete a user right now
        return $user->isAdmin === 2;
    }
}
