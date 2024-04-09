<?php

namespace App\Policies;

use App\Models\ShowEpisode;
use App\Models\TeamManager;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class ShowEpisodePolicy
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
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ShowEpisode  $showEpisode
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function destroy(User $user, ShowEpisode $showEpisode)
    {
        $teamId = $showEpisode->show->team_id;
        $userId = auth()->user()->id;
        $checkUser = TeamMember::where('team_id', '=', $teamId)
            ->where('user_id', '=', $userId)->pluck('active')->first();

        $isManager = TeamManager::where('team_id', '=', $teamId)
            ->where('user_id', '=', $userId)->first();

        if($showEpisode->show->team->user_id === $userId || $showEpisode->show->team->team_leader === $userId || $isManager || Auth::user()->isAdmin || $showEpisode->user_id === $userId){
            return true;
        }
        elseif($checkUser === 1){
            return Response::deny('You are not the show runner or manager.');
        }
        elseif($checkUser === 0){
            return Response::deny('You are not active on this team.');
        }
        elseif($checkUser === null){
            return Response::deny('You are not a member of this team.');
        }
        return Response::deny('There\'s been a problem. Please let not.TV know.');

    }

}
