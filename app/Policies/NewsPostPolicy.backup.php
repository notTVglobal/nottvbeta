<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NewsPost;
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
        return true;
    }

    /**
     * Determine whether the user can view the post.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        // any user can view a newsPost if it is published
        // else only newsPersons can view
        return $user->isAdmin;
    }


    /**
     * Determine whether the user can create posts.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->isAdmin;
    }

    /**
     * Determine whether the user can edit posts.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function edit(User $user)
    {
        return $user->isAdmin;
    }


    /**
     * Determine whether the user can update the post.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsPost  $newsPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, NewsPost $newsPost)
    {
//        return $user->isAdmin;
        return true;
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsPost  $newsPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        return $user->isAdmin;
    }

    /**
     * Determine whether the user can restore the post.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsPost  $newsPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, NewsPost $newsPost)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the post.
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
