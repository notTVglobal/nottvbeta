<?php

namespace App\Policies;

use App\Models\Show;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->role_id === 4;
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
    public function edit(User $user, Show $show)
    {
        if ($user->id === $show->user_id)
            return $user->id === $show->user_id;

        elseif($user->isAdmin)
            return $user->isAdmin;
    }

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
