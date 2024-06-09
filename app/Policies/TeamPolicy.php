<?php

namespace App\Policies;

use App\Models\Show;
use App\Models\TeamManager;
use App\Models\User;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Creator;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    use HandlesAuthorization;

  public function viewAny(?User $user)
  {
    return true;
  }

    public function view(User $user)
    {
        return true;
    }

    public function manage(User $user, Team $team)
    {
        $isAdmin = $user->isAdmin; // Assuming isAdmin is a property or method on the User model
        $isTeamOwner = $user->id === $team->user_id;
        $isTeamLeader = $user->id === $team->team_leader;
        $isTeamManager = TeamManager::where('team_id', $team->id)
            ->where('user_id', $user->id)
            ->exists(); // Using exists() for better performance

        if ($isAdmin || $isTeamOwner || $isTeamLeader || $isTeamManager) {
            return true;
        }

        return Response::deny('You do not have permission to manage this team.');
    }


    public function viewTeamManagePage(User $user, Team $team)
    {
        $isAdmin = $user->isAdmin; // Assuming isAdmin is a property or method on the User model
        $isTeamOwner = $user->id === $team->user_id;
        $isTeamLeader = $user->id === $team->team_leader;
        $isTeamManager = TeamManager::where('team_id', $team->id)
            ->where('user_id', $user->id)
            ->exists(); // Using exists() for better performance

        $userIsActiveTeamMember = TeamMember::where('team_id', $team->id)
            ->where('user_id', $user->id)
            ->value('active'); // Directly get the 'active' column value

        if ($isAdmin || $isTeamOwner || $isTeamLeader || $isTeamManager || $userIsActiveTeamMember === 1) {
            return true;
        }

        if ($userIsActiveTeamMember === 0) {
            return Response::deny('You are not active on this team.');
        }

        if ($userIsActiveTeamMember === null) {
            return Response::deny('You are not a member of this team.');
        }

        return Response::deny('There\'s been a problem. Please let not.tv know.');
    }

    public function createTeam(User $user)
    {
        $userIsActiveCreator = Creator::where('user_id', $user->id)
            ->value('status_id'); // Directly get the 'status_id' column value

        if ($userIsActiveCreator === 1 || $user->isAdmin) {
            return true;
        }

        return match ($userIsActiveCreator) {
            2 => Response::deny('Your creator account has been frozen.'),
            3 => Response::deny('Your creator account has been suspended.'),
            null => Response::deny('Please register as a creator to use this feature.'),
            default => Response::deny('There\'s been a problem. Please let not.tv know.'),
        };
    }

    public function edit(User $user, Team $team)
    {
      $creatorStatus = Creator::where('user_id', $user->id)->value('status_id');

      // Check if the authenticated user is the team creator, team leader, or an admin
      if ($team->user_id === $user->id || (isset($team->teamLeader) && $team->teamLeader->user->id === $user->id) || $user->isAdmin) {
        return true;
      }

      return match ($creatorStatus) {
        2 => Response::deny('Your creator account has been frozen.'),
        3 => Response::deny('Your creator account has been suspended.'),
        null => Response::deny('Please register as a creator to use this feature.'),
        default => Response::deny('You\'re not the creator of this team, the team leader, nor an admin.'),
      };
    }

    public function createShow(User $user, Team $team)
    {
        $userIsActiveCreator = Creator::where('user_id', $user->id)->value('status_id');

        if ($team->user_id === $user->id || $user->isAdmin) {
            return true;
        }

        return match ($userIsActiveCreator) {
            2 => Response::deny('Your creator account has been frozen.'),
            3 => Response::deny('Your creator account has been suspended.'),
            null => Response::deny('Please register as a creator to use this feature.'),
            default => Response::deny('There\'s been a problem. Please let not.tv know.'),
        };
    }

    // This policy was re-formatted by ChatGPT on January-10-2024 with tec21.
    public function update(User $user, Team $team)
    {
        $userId = $user->id;
        $creatorStatus = Creator::where('user_id', $userId)->value('status_id');

        // Check if the authenticated user is the team creator, team leader, or an admin
        if ($team->user_id === $userId || (isset($team->teamLeader) && $team->teamLeader->user->id === $userId) || $user->isAdmin) {
            return true;
        }

        return match ($creatorStatus) {
            2 => Response::deny('Your creator account has been frozen.'),
            3 => Response::deny('Your creator account has been suspended.'),
            null => Response::deny('Please register as a creator to use this feature.'),
            default => Response::deny('You\'re not the creator of this team, the team leader, nor an admin.'),
        };
    }

    public function transfer(User $user, Team $team)
    {
        $teamIsEligible = !in_array($team->teamStatus->id, [6, 7, 8, 9, 10]);
        $userOwnsTeam = $user->id === $team->user_id;

        if ($user->isAdmin) {
            return true;
        }

        if (!$teamIsEligible) {
            return Response::deny('This team is not eligible for transfer.');
        }

        if (!$userOwnsTeam) {
            return Response::deny('You are not the owner of this team.');
        }

        return true;
    }

    /**
     * Determine whether the user can delete the team.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Team $team)
    {
        if ($user->id === $team->user_id)
            return $user->id === $team->user_id;

        elseif($user->isAdmin === 1)
            return $user->isAdmin === 1;
    }

    /**
     * Determine whether the user can restore the team.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Team $team)
    {
        if ($user->id === $team->user_id)
            return $user->id === $team->user_id;

        elseif($user->isAdmin === 1)
            return $user->isAdmin === 1;
    }

    /**
     * Determine whether the user can permanently delete the team.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Team $team)
    {
        if ($user->id === $team->user_id)
            return $user->id === $team->user_id;

        elseif($user->isAdmin === 1)
            return $user->isAdmin === 1;
    }
}
