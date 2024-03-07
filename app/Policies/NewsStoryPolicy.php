<?php

namespace App\Policies;

use App\Models\NewsStory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class NewsStoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
       //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsStory  $newsPost
     * @return Response|bool
     */
    public function view(User $user)
    {
       //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
      return $user->newsPerson  || $user->isAdmin
          ? Response::allow()
          : Response::deny('You are not a member of the news team.');
    }


  /**
   * Determine whether the user can edit the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\NewsStory  $newsPost
   * @return Response|bool
   */

  public function edit(User $user)
  {
    return $user->newsPerson || $user->isAdmin
        ? Response::allow()
        : Response::deny('You are not allowed to edit.');
  }


  /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsStory  $newsPost
     * @return Response|bool
     */

    public function update(User $user)
    {
        return $user->newsPerson || $user->isAdmin
            ? Response::allow()
            : Response::deny('You are not allowed to update.');
    }

    public function publish(User $user)
    {
        return $user->isAdmin
            ? Response::allow()
            : Response::deny('You are not allowed to publish.');
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
     * @param  \App\Models\NewsStory  $newsPost
     * @return Response|bool
     */
    public function restore(User $user, NewsStory $newsPost)
    {
        //
    }

    public function delete(User $user)
    {
//        return false;
        return $user->isAdmin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsStory  $newsPost
     * @return Response|bool
     */
    public function forceDelete(User $user, NewsStory $newsPost)
    {
        //
    }
}
