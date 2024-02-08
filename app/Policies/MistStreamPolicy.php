<?php

namespace App\Policies;

use App\Models\MistStream;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MistStreamPolicy
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
        $isAdmin = $user->isAdmin;
      // If the user is an admin, allow viewing
        if ($isAdmin) {
          return true;
        }

        return Response::deny('You must be an admin to display this.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MistStream  $mistStream
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, MistStream $mistStream)
    {
      $isAdmin = $user->isAdmin;
      // If the user is an admin, allow viewing
      if ($isAdmin) {
        return true;
      }

      return Response::deny('You must be an admin to view this.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
      $isAdmin = $user->isAdmin;
      // If the user is an admin, allow viewing
      if ($isAdmin) {
        return true;
      }

      return Response::deny('You must be an admin to create this.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MistStream  $mistStream
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, MistStream $mistStream)
    {
      $isAdmin = $user->isAdmin;
      // If the user is an admin, allow viewing
      if ($isAdmin) {
        return true;
      }

      return Response::deny('You must be an admin to update this.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MistStream  $mistStream
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, MistStream $mistStream)
    {
      $isAdmin = $user->isAdmin;
      // If the user is an admin, allow viewing
      if ($isAdmin) {
        return true;
      }

      return Response::deny('You must be an admin delete this.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MistStream  $mistStream
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, MistStream $mistStream)
    {
      $isAdmin = $user->isAdmin;
      // If the user is an admin, allow viewing
      if ($isAdmin) {
        return true;
      }

      return Response::deny('You must be an admin to restore this.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MistStream  $mistStream
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, MistStream $mistStream)
    {
      $isAdmin = $user->isAdmin;
      // If the user is an admin, allow viewing
      if ($isAdmin) {
        return true;
      }

      return Response::deny('You must be an admin to force delete this.');
    }
}
