<?php
namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait GetTeamUserData {
  protected function getTeamUserData($user): array {
    if (!$user) {
      return $this->getEmptyTeamUserData();
    }

    return [
        'id'                  => $user->id,
        'name'                => $user->name,
        'creator_status_id'   => $user->creator?->status->id,
        'creator_status_name' => $user->creator?->status->status,
    ];
  }

  protected function getEmptyTeamUserData(): array {
    return [
        'id'                  => null,
        'name'                => null,
        'creator_status_id'   => null,
        'creator_status_name' => null,
    ];
  }

  protected function getUserPermissions($team): array {
    $user = Auth::user();

    if (!$user) {
      // No user is authenticated, return default permissions
      return [
          'viewTeam' => false,
          'manageTeam' => false,
          'editTeam' => false,
      ];
    }

    $isTeamManager = $team->managers->contains('id', $user->id) ?? false;
    $isTeamLeader = optional($team->teamLeader)->user_id === $user->id ?? false;
    $isTeamOwner = $team->user_id === $user->id ?? false;
    $isTeamMember = $team->members->contains('id', $user->id) ?? false;

    // Example logic to determine user permissions
    return [
        'editTeam'             => $user->can('update', $team),
        'manageTeam'           => $user->can('manage', $team),
        'transferTeam'         => $user->can('transfer', $team),
        'isTeamOwner'          => $isTeamOwner,
        'isTeamLeader'         => $isTeamLeader,
        'isTeamManager'        => $isTeamManager,
        'isTeamMember'         => $isTeamMember,
        'hasSpecialPermission' => $isTeamOwner || $isTeamLeader || $isTeamManager || $user->isAdmin,
      // Add more permissions as needed
    ];
  }
}
