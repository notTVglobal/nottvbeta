<?php

namespace App\Policies;

use App\Constants\NewsRoleConstants;
use App\Models\NewsRssFeed;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class NewsRssFeedPolicy
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
      return ($user->newsPerson || $user->isAdmin)
          ? Response::allow()
          : Response::deny('You must be a member of the news team to view this.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsRssFeed  $newsRssFeed
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
      return ($user->newsPerson || $user->isAdmin)
          ? Response::allow()
          : Response::deny('You must be a member of the news team to view this.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
      // Check if the user is a producer or an assignment editor
      $userRoleIds = $user->newsPerson?->roles->pluck('id')->all() ?? [];

      if (in_array(NewsRoleConstants::PRODUCER, $userRoleIds) || in_array(NewsRoleConstants::ASSIGNMENT_EDITOR, $userRoleIds)) {
        return Response::allow();
      }

      return Response::deny('You must be a producer or assignment editor to add news feeds.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsRssFeed  $newsRssFeed
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
      // Check if the user is a producer or an assignment editor
      $userRoleIds = $user->newsPerson?->roles->pluck('id')->all() ?? [];

      if (in_array(NewsRoleConstants::PRODUCER, $userRoleIds) || in_array(NewsRoleConstants::ASSIGNMENT_EDITOR, $userRoleIds)) {
        return Response::allow();
      }

      return Response::deny('You must be a producer or assignment editor to edit news feeds.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsRssFeed  $newsRssFeed
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
      // Check if the user is a producer or an assignment editor
      $userRoleIds = $user->newsPerson?->roles->pluck('id')->all() ?? [];

      if (in_array(NewsRoleConstants::PRODUCER, $userRoleIds) || in_array(NewsRoleConstants::ASSIGNMENT_EDITOR, $userRoleIds)) {
        return Response::allow();
      }

      return Response::deny('You must be a producer or assignment editor to delete news feeds.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsRssFeed  $newsRssFeed
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user)
    {
      {
        // Check if the user is a producer or an assignment editor
        $userRoleIds = $user->newsPerson?->roles->pluck('id')->all() ?? [];

        if (in_array(NewsRoleConstants::PRODUCER, $userRoleIds) || in_array(NewsRoleConstants::ASSIGNMENT_EDITOR, $userRoleIds)) {
          return Response::allow();
        }

        return Response::deny('You must be a producer or assignment editor to restore news feeds.');
      }
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsRssFeed  $newsRssFeed
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user)
    {
      {
        // Check if the user is a producer or an assignment editor
        $userRoleIds = $user->newsPerson?->roles->pluck('id')->all() ?? [];

        if (in_array(NewsRoleConstants::PRODUCER, $userRoleIds) || in_array(NewsRoleConstants::ASSIGNMENT_EDITOR, $userRoleIds)) {
          return Response::allow();
        }

        return Response::deny('You must be a producer or assignment editor to force delete news feeds.');
      }
    }
}
