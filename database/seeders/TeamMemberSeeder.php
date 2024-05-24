<?php

namespace Database\Seeders;

use App\Models\Creator;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $teams = Team::all();
    $creatorUserIds = Creator::pluck('user_id')->all();

    foreach ($teams as $team) {
      $existingMembers = TeamMember::where('team_id', $team->id)->pluck('user_id')->all();
      $availableUserIds = array_diff($creatorUserIds, $existingMembers);

      // Ensure there are enough available users
      $membersToCreate = min(20, count($availableUserIds));

      // Create members
      foreach (array_slice($availableUserIds, 0, $membersToCreate) as $userId) {
        TeamMember::factory()->create([
            'user_id' => $userId,
            'team_id' => $team->id,
            'active' => 1,
        ]);
      }
    }
  }
}
