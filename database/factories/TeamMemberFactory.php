<?php

namespace Database\Factories;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Team;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamMember>
 */
class TeamMemberFactory extends Factory
{
  protected $model = TeamMember::class;

  // This property will hold all unique combinations of user and team IDs
  protected static $uniqueCombinations = null;

  public function definition()
  {
    // Generate the unique combinations once
    if (self::$uniqueCombinations === null) {
      $userIds = \App\Models\User::pluck('id')->all();
      $teamIds = \App\Models\Team::pluck('id')->all();

      foreach ($userIds as $userId) {
        foreach ($teamIds as $teamId) {
          self::$uniqueCombinations[] = ['user_id' => $userId, 'team_id' => $teamId];
        }
      }

      shuffle(self::$uniqueCombinations); // Shuffle to randomize
    }

    // Take one combination from the list
    $combination = array_pop(self::$uniqueCombinations);

    return [
        'user_id' => $combination['user_id'],
        'team_id' => $combination['team_id'],
        'active' => 1,
    ];
  }
}
