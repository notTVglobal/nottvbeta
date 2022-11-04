<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Team;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamMember>
 */
class TeamMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
//        $user = User::factory()
//            ->hasAttached(
//                Team::factory()->count(3),
//                ['active' => true]
//            )
//            ->create();
        return [
            'user_id' => \App\Models\User::all()->random()->id,
            'team_id' => \App\Models\Team::all()->random()->id,
            'active' => 1,
        ];
    }
}
