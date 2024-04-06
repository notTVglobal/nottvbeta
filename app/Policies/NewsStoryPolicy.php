<?php

namespace App\Policies;

use App\Constants\NewsRoleConstants;
use App\Models\NewsStory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

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
      // Simply check if the user is associated with a NewsPerson
      return $user->newsPerson()->exists();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsStory  $newsPost
     * @return Response|bool
     */
    public function view(User $user, NewsStory $newsStory)
    {
      // Check if the news story is published
      return $newsStory->status === 6;
    }

//    /**
//     * Determine whether the user can create models.
//     *
//     * @param  \App\Models\User  $user
//     * @return bool
//     */
//    public function create(User $user)
//    {
//      return $user->newsPerson  || $user->isAdmin
//          ? Response::allow()
//          : Response::deny('You are not a member of the news team.');
//    }

  public function startStory(User $user)
  {
    // Directly allow admins to start a story
    if ($user->isAdmin) {
      return Response::allow();
    }

    // Check if the user is associated with a NewsPerson and has the required roles
    $userRoleIds = $user->newsPerson?->roles->pluck('id')->all() ?? [];
    if (!empty($userRoleIds) && array_intersect($userRoleIds, [
            NewsRoleConstants::PRODUCER,
            NewsRoleConstants::ASSIGNMENT_EDITOR,
            NewsRoleConstants::WRITER,
            NewsRoleConstants::REPORTER,
            NewsRoleConstants::ANCHOR,
            NewsRoleConstants::PHOTOGRAPHER,
        ])) {
      return Response::allow();
    }

    // If none of the conditions are met, deny permission with a custom message
    return Response::deny('You do not have permission to start a news story.');
  }

  /**
   * Determine whether the user can edit the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\NewsStory  $newsPost
   * @return Response|bool
   */

  public function edit(User $user, NewsStory $newsStory)
  {

    // Deny editing if the news story is already published
    if ($newsStory->status === 6) {
      return Response::deny('Published news stories cannot be edited.');
    }

    // Directly allow admins to edit
    if ($user->isAdmin) {
      return Response::allow();
    }

    // Additionally, allow if the user is the creator of the news story
    if ($newsStory->user_id === $user->id) {
      return Response::allow();
    }

    // Allow if the user is the newsPerson associated with the story
    if ($newsStory->news_person_id && $user->newsPerson && $user->newsPerson->id === $newsStory->news_person_id) {
      return Response::allow();
    }

    // Additionally, check if the user has a role that permits editing
    $userRoleIds = $user->newsPerson?->roles->pluck('id')->all() ?? [];
    if (in_array(NewsRoleConstants::PRODUCER, $userRoleIds) ||
        in_array(NewsRoleConstants::EDITOR, $userRoleIds) ||
        in_array(NewsRoleConstants::ASSIGNMENT_EDITOR, $userRoleIds)) {
      return Response::allow();
    }

    // If none of the above conditions are met, deny permission
    return Response::deny('You are not allowed to edit this news story.');
  }

  public function canPublish(User $user)
  {

    // Directly allow admins to publish if the news story status is 3
    if ($user->isAdmin) {
      return Response::allow();
    }

    // Check if the user has a role that permits publishing
    $userRoleIds = $user->newsPerson?->roles->pluck('id')->all() ?? [];
    if (in_array(NewsRoleConstants::PRODUCER, $userRoleIds) ||
        in_array(NewsRoleConstants::ASSIGNMENT_EDITOR, $userRoleIds)) {
      return Response::allow();
    }

    // If none of the above conditions are met, deny permission
    return Response::deny('You must be a producer or assignment editor to publish news stories.');
  }


  public function publish(User $user, NewsStory $newsStory)
    {
      // Check if the news story is in the correct status to be published
      if ($newsStory->status !== 3) {
        return Response::deny('The news story must be approved before publishing.');
      }

      // Directly allow admins to publish if the news story status is 3
      if ($user->isAdmin) {
        return Response::allow();
      }

      // Check if the user has a role that permits publishing
      $userRoleIds = $user->newsPerson?->roles->pluck('id')->all() ?? [];
      if (in_array(NewsRoleConstants::PRODUCER, $userRoleIds) ||
          in_array(NewsRoleConstants::ASSIGNMENT_EDITOR, $userRoleIds)) {
        return Response::allow();
      }

      // If none of the above conditions are met, deny permission
      return Response::deny('You must be a producer or assignment editor to publish news stories.');
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
     * @param  \App\Models\NewsStory  $newsStory
     * @return Response|bool
     */
    public function restore(User $user, NewsStory $newsStory)
    {
      // Directly allow admins to restore
      if ($user->isAdmin) {
        return Response::allow();
      }

      // Check if the user has a role that permits restoring
      $userRoleIds = $user->newsPerson?->roles->pluck('id')->all() ?? [];
      if (in_array(NewsRoleConstants::PRODUCER, $userRoleIds) ||
          in_array(NewsRoleConstants::ASSIGNMENT_EDITOR, $userRoleIds)) {
        return Response::allow();
      }

      // If none of the above conditions are met, deny permission
      return Response::deny('You must be a producer or assignment editor to restore news stories.');
    }

    public function delete(User $user)
    {

      // Directly allow admins to delete
      if ($user->isAdmin) {
        return true;
      }

      // Additionally, check if the user is a producer or an assignment editor
      $userRoleIds = $user->newsPerson?->roles->pluck('id')->all() ?? [];
      if (in_array(NewsRoleConstants::PRODUCER, $userRoleIds) ||
          in_array(NewsRoleConstants::ASSIGNMENT_EDITOR, $userRoleIds)) {
        return true;
      }

      // If none of the conditions are met, deny permission
      return Response::deny('You must be a producer or assignment editor to delete news stories.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NewsStory  $newsStory
     * @return Response|bool
     */
    public function forceDelete(User $user, NewsStory $newsStory)
    {
        //
    }
}
