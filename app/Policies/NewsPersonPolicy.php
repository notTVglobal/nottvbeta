<?php

namespace App\Policies;

use App\Models\NewsPerson;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class NewsPersonPolicy
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

        return $user->newsPerson;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsPerson  $newsPerson
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
        $user->isAdmin;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsPerson  $newsPerson
     * @return \Illuminate\Auth\Access\Response|bool
     */
  public function update(User $user, NewsPerson $newsPerson)
  {
    // Define your authorization logic here
    return $user->id === $newsPerson->user_id;
  }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsPerson  $newsPerson
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        return $user->isAdmin
            ? Response::allow()
            : Response::deny('You are not allowed to remove someone from the news team.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsPerson  $newsPerson
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, NewsPerson $newsPerson)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsPerson  $newsPerson
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, NewsPerson $newsPerson)
    {
        //
    }
}
