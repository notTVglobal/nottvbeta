<?php

namespace App\Policies;

use App\Models\NewsPersonMessage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsPersonMessagePolicy {

  use HandlesAuthorization;

  public function viewAny(User $user)
  {
    return $user->newsPerson()->exists();
  }

  public function view(User $user, NewsPersonMessage $newsPersonMessage)
  {
    return $user->newsPerson && $user->newsPerson->id === $newsPersonMessage->recipient_id;
  }

  public function create(User $user) {
    return true; // Anyone can create a message
  }

  public function update(User $user, NewsPersonMessage $newsPersonMessage) {
    return $user->newsPerson && $user->newsPerson->id === $newsPersonMessage->recipient_id;
  }

  public function delete(User $user, NewsPersonMessage $newsPersonMessage) {
    return $user->newsPerson && $user->newsPerson->id === $newsPersonMessage->recipient_id;
  }

  public function deleteAll(User $user, NewsPersonMessage $newsPersonMessage) {
    return $user->newsPerson && $user->newsPerson->id === $newsPersonMessage->recipient_id;
  }

  public function restore(User $user, NewsPersonMessage $newsPersonMessage)
  {
    return $user->newsPerson && $user->newsPerson->id === $newsPersonMessage->recipient_id;
  }

  public function forceDelete(User $user, NewsPersonMessage $newsPersonMessage)
  {
    return $user->newsPerson && $user->newsPerson->id === $newsPersonMessage->recipient_id;
  }

  public function fetch(User $user)
  {
    // Authorization logic for fetching messages
    return $user->newsPerson()->exists();
  }
}
