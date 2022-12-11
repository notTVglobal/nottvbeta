<?php

namespace App\Policies;

use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsPostPolicy
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
     * @param  \App\Models\NewsPost  $newsPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
       //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->newsPerson;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsPost  $newsPost
     * @return \Illuminate\Auth\Access\Response|bool
     */

    public function edit(User $user)
    {
        return $user->newsPerson;
    }


    public function update(User $user, NewsPost $newsPost)
    {
        return $user->newsPerson;
    }

    public function publish(User $user)
    {
        return $user->isAdmin;
    }

//    /**
//     * Determine whether the user can delete the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\NewsPost  $newsPost
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function delete(User $user, NewsPost $newsPost)
//    {
//        if ($user->id === $newsPost->user_id)
//            return $user->id === $newsPost->user_id;
//
//        elseif($user->isAdmin === 1)
//            return $user->isAdmin === 1;
//    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsPost  $newsPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, NewsPost $newsPost)
    {
        //
    }

    public function delete(User $user)
    {
        return $user->isAdmin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsPost  $newsPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, NewsPost $newsPost)
    {
        //
    }
}
