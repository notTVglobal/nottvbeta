<?php

namespace Database\Factories;

use App\Models\Creator;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Team;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamMember>
 */
class TeamMemberFactory extends Factory
{
  protected $model = TeamMember::class;

  public function definition()
  {
    return [
        'user_id' => Creator::pluck('user_id')->random(),
        'team_id' => Team::pluck('id')->random(),
        'active' => 1,
    ];
  }
}
