<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Movie;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class MoviePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if ($user->subscribed('default') || $user->isVip || $user->isAdmin) {
            return true;
        }
        return Response::deny('Please upgrade your account.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Movie $movie)
    {

      // Check if the movie has a status_id of 9
      if ($movie->status_id == 9) {
        // If the user is a creator, allow viewing
        if ($user->creator) {
          return true;
        }

        return Response::deny('You must be a creator to view this movie.');

      }

        if ($user->subscribed('default') || $user->isVip || $user->creator || $user->isAdmin) {
            return true;
        }
        return Response::deny('Please upgrade your account.');

    }

    public function edit(User $user) {

        if ($user->isAdmin) {
            return true;
        }
        return Response::deny('You must be an admin to edit a movie.');
    }

  public function create(User $user) {

    if ($user->isAdmin) {
      return true;
    }
    return Response::deny('You must be an admin to add a movie.');
  }
  public function destroy(User $user) {

    if ($user->isAdmin) {
      return true;
    }
    return Response::deny('You must be an admin to destroy a movie.');
  }


}
