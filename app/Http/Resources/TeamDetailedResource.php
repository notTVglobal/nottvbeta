<?php

namespace App\Http\Resources;

use App\Traits\GetTeamUserData;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamDetailedResource extends JsonResource {
  use GetTeamUserData;

  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray(Request $request): array {

    // Resolve the image if it's loaded
    $resolvedImage = $this->whenLoaded('image', function () {
      return $this->image ? (new ImageResource($this->image))->resolve() : null;
    });

    // Resolve the team members if they are paginated
    $members = $this->additional['members'] ?? null;

    if ($members) {
      $members = $members->setCollection(
          $members->getCollection()->map(function ($member) {
            return (new TeamMemberResource($member))->resolve();
          })
      );
    }

    // Resolve the team members if they are loaded
//    $members = $this->whenLoaded('members', function () {
//      return $this->members->map(function ($member) {
//        return (new TeamMemberResource($member))->resolve();
//      });
//    });

    // Count the members associated with the team
    $memberCount = $this->members()->count();

    $managers = $this->whenLoaded('managers', function () {
      return $this->managers->map(function ($manager) {
        return (new TeamManagerResource($manager))->resolve();
      });
    });

    // Check if the team is loaded and not null
    $teamOwnerData = $this->user ? $this->getTeamUserData($this->user) : $this->getEmptyTeamUserData();
    $teamLeaderData = $this->teamLeader ? $this->getTeamUserData($this->teamLeader->user) : $this->getEmptyTeamUserData();

    return [
        'id'               => $this->id,
        'name'             => $this->name,
        'description'      => $this->description,
        'slug'             => $this->slug,
        'image'            => $resolvedImage,
        'team_status_id'   => $this->team_status_id,
//        'nextBroadcast'    => $this->nextBroadcast ? $this->nextBroadcast->toArray() : [],
        'nextBroadcast'    => $this->nextBroadcast ?? [],
        'public_message'   => $this->public_message ?? '',
        'socialMediaLinks' => [
            'www_url'        => $this->www_url ?? null,
            'instagram_name' => $this->instagram_name ?? null,
            'telegram_url'   => $this->telegram_url ?? null,
            'twitter_handle' => $this->twitter_handle ?? null,
        ],
        'totalSpots'       => $this->totalSpots,
//        'members'          => $members ?? null,
        'members'          => $members ? $members->toArray() : null, // Pass the paginated members
        'memberCount'      => $memberCount,
        'teamOwner'        => $teamOwnerData,
        'teamLeader'       => $teamLeaderData,
        'managers'         => $managers,
    ];
  }
}
