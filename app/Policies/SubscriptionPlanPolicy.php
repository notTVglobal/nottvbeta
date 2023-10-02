<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPlanPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->isAdmin;
    }

    public function update(User $user)
    {
        return $user->isAdmin;
    }

    public function delete(User $user)
    {
        return $user->isAdmin;
    }
}
